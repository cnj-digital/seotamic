import assert from 'node:assert/strict'
import { readFileSync } from 'node:fs'
import vm from 'node:vm'
import { test } from 'node:test'
import * as Vue from 'vue'

// Exercise the distributed bundle: PHP tests and source-only tests miss stale builds.
const components = {}
const manifest = JSON.parse(readFileSync(new URL('../../resources/dist/build/manifest.json', import.meta.url)))
const bundle = readFileSync(new URL(`../../resources/dist/build/${manifest['resources/js/cp.js'].file}`, import.meta.url), 'utf8')
const ui = new Proxy({}, { get: (_, name) => ({ name }) })
vm.runInNewContext(bundle, {
  window: { Vue },
  __STATAMIC__: {
    core: { Fieldtype: { props: {}, emits: [], use: (_, props) => ({ expose: {}, update: value => { props.value = value } }) } },
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

function open(value) {
  const props = Vue.reactive({
    value,
    meta: {
      config: {},
      meta: { title: { type: 'title' }, description: { type: 'empty' } },
      title: 'Entry title',
      seotamic: { social_title: 'Global title', social_description: 'Global description' },
    },
  })
  const scope = Vue.effectScope()
  const render = scope.run(() => components['seotamic_social-fieldtype'].setup(props, { expose() {}, emit() {} }))
  const field = index => render({ ...props, __: value => value }, []).children[index]
  return {
    props, scope,
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
