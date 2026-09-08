import assert from 'node:assert/strict'
import { existsSync, readFileSync, readdirSync } from 'node:fs'
import vm from 'node:vm'
import { test } from 'node:test'
import * as Vue from 'vue'

// Exercise the distributed bundle: PHP tests and source-only tests miss stale builds.
const components = {}
// Use Statamic's actual update functions so inheritance checks follow the CP path.
function statamicSource(path, bundlePrefix) {
  const source = new URL(`../../vendor/statamic/cms/resources/js/${path}`, import.meta.url)
  if (existsSync(source)) return readFileSync(source, 'utf8')
  // Recent Composer releases ship readable development bundles instead of Vue source.
  const directory = new URL('../../vendor/statamic/cms/resources/dist-dev/build/assets/', import.meta.url)
  return readdirSync(directory)
    .filter(name => name.startsWith(bundlePrefix) && name.endsWith('.js'))
    .map(name => readFileSync(new URL(name, directory), 'utf8')).join('\n')
}
const fieldtypeSource = statamicSource('components/fieldtypes/fieldtype.js', 'index-')
const updateSource = fieldtypeSource.match(/const update = \(value\) => \{[\s\S]*?\n\s*\};/)[0]
const makeUpdate = vm.runInNewContext(`(emit) => { ${updateSource}; return update }`)
const publishSource = statamicSource('components/ui/Publish/Field.vue', 'ui-')
const valueUpdatedSource = publishSource.match(/function valueUpdated\(value\) \{[\s\S]*?\n\s*\}/)[0]

const manifest = JSON.parse(readFileSync(new URL('../../resources/dist/build/manifest.json', import.meta.url)))
const bundle = readFileSync(new URL(`../../resources/dist/build/${manifest['resources/js/cp.js'].file}`, import.meta.url), 'utf8')
const ui = new Proxy({}, { get: (_, name) => ({ name }) })
vm.runInNewContext(bundle, {
  window: { Vue },
  __STATAMIC__: {
    core: { Fieldtype: { props: {}, emits: [], use: emit => ({ expose: {}, update: makeUpdate(emit) }) } },
    ui,
    savePipeline: {},
  },
  Statamic: {
    booting: callback => callback(),
    $components: { register: (name, component) => { components[name] = component } },
    $inertia: { register() {} },
  },
  __: value => value,
  setTimeout,
  clearTimeout,
})

function open(value, type = 'social') {
  const props = Vue.reactive({
    value,
    meta: {
      config: {},
      meta: { title: { type: 'title' }, description: { type: 'empty' } },
      title: 'Entry title',
      seotamic: { social_title: 'Global title', social_description: 'Global description' },
    },
  })
  const localizedFields = []
  const handle = `seotamic_${type}`
  const valueUpdated = vm.runInNewContext(`(${valueUpdatedSource})`, {
    data_get: () => props.value,
    containerValues: { value: {} },
    fullPath: { value: handle },
    setFieldValue: (_, value) => { props.value = value },
    desync: () => { if (!localizedFields.includes(handle)) localizedFields.push(handle) },
  })
  const scope = Vue.effectScope()
  const render = scope.run(() => components[`seotamic_${type}-fieldtype`].setup(props, { expose() {}, emit: (event, value) => { assert.equal(event, 'update:value'); valueUpdated(value) } }))
  const field = index => {
    const root = render({ ...props, __: value => value }, [])
    return type === 'social' ? root.children[index] : root.children[index].children[0]
  }
  return {
    props, scope, localizedFields,
    mode: index => field(index).children.actions()[0].props,
    input: index => field(index).children.default()[0].props,
  }
}

for (const [index, name] of ['title', 'description'].entries()) {
  test(`distributed social ${name} preserves custom selection and text on reload`, async () => {
    const page = open({ title: { type: 'title', value: 'Entry title', custom_value: '' }, description: { type: 'meta', value: '', custom_value: '' } })
    let reloaded
    try {
      page.mode(index)['onUpdate:modelValue']('custom')
      await Vue.nextTick()
      page.input(index)['onUpdate:modelValue'](`Custom ${name}`)
      await new Promise(resolve => setTimeout(resolve, 80))
      assert.equal(page.props.value[name].type, 'custom')
      assert.equal(page.props.value[name].value, `Custom ${name}`)
      reloaded = open(JSON.parse(JSON.stringify(page.props.value)))
      assert.equal(reloaded.mode(index).modelValue, 'custom')
      assert.equal(reloaded.input(index)['model-value'], `Custom ${name}`)
    } finally {
      page.scope.stop()
      reloaded?.scope.stop()
    }
  })
}

for (const type of ['meta', 'social']) {
  test(`${type} modes follow site changes and restored origin values without editing them`, async () => {
    const custom = text => ({ type: 'custom', value: text, custom_value: text })
    const page = open({ title: custom('English title'), description: custom('English description') }, type)
    try {
      const translated = {
        title: { type: 'title', value: 'Slovenian title', custom_value: '' },
        description: { type: type === 'social' ? 'meta' : 'empty', value: '', custom_value: '' },
      }
      page.props.value = structuredClone(translated)
      await Vue.nextTick()
      assert.equal(page.mode(0).modelValue, 'title')
      assert.equal(page.mode(1).modelValue, translated.description.type)
      assert.equal(page.input(0)['read-only'], true)
      assert.deepEqual(JSON.parse(JSON.stringify(page.props.value)), translated)
      assert.deepEqual(page.localizedFields, [])

      const origin = { title: custom('Origin title'), description: custom('Origin description') }
      page.props.value = structuredClone(origin)
      await Vue.nextTick()
      assert.equal(page.mode(0).modelValue, 'custom')
      assert.equal(page.mode(1).modelValue, 'custom')
      assert.equal(page.input(0)['model-value'], 'Origin title')
      assert.equal(page.input(0)['read-only'], false)
      assert.deepEqual(JSON.parse(JSON.stringify(page.props.value)), origin)
    } finally {
      page.scope.stop()
    }
  })
}

for (const type of ['meta', 'social']) {
  for (const [index, name] of ['title', 'description'].entries()) {
    test(`${type} ${name} edit breaks inheritance without mutating the origin`, async () => {
      const origin = { title: { type: 'custom', value: 'Origin title' }, description: { type: 'custom', value: 'Origin description' } }
      const page = open(origin, type)
      try {
        const input = page.input(index)
        const update = input['onUpdate:modelValue'] || input['onUpdate:model-value']
        update('Translated text')
        await new Promise(resolve => setTimeout(resolve, 80))
        assert.deepEqual(page.localizedFields, [`seotamic_${type}`])
        assert.equal(page.props.value[name].value, 'Translated text')
        assert.equal(origin[name].value, `Origin ${name}`)
      } finally {
        page.scope.stop()
      }
    })
  }
}
