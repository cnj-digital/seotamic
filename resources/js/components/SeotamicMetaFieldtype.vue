<template>
  <div v-if="value">
    <Field
      class="mt-4"
      id="meta_title"
      :label="__('seotamic::seo.meta_title_title')"
      :instructions="__('seotamic::seo.meta_title_instructions')"
      instructions-below
    >
      <template #actions>
        <ButtonGroup :options="titleOptions" v-model="titleType" />
      </template>

      <Input
        type="text"
        id="meta_title"
        name="meta_title"
        :model-value="value.title.value"
        :read-only="titleType !== 'custom'"
        :limit="meta.config.meta_title_length"
        @update:model-value="updateTitleDebounced"
      />
    </Field>

    <Field class="mt-4 px-0" :label="__('seotamic::seo.meta_prepend_label')">
      <Switch
        :model-value="value.title.prepend"
        :read-only="!prependable"
        @update:model-value="
          update({ ...value, title: { ...value.title, prepend: $event } })
        "
      />
    </Field>

    <Field class="mt-4 px-0" :label="__('seotamic::seo.meta_append_label')">
      <Switch
        :model-value="value.title.append"
        :read-only="!appendable"
        @update:model-value="
          update({ ...value, title: { ...value.title, append: $event } })
        "
      />
    </Field>
  </div>

  <div v-if="value" class="mt-4">
    <Field
      class="mt-2"
      :label="__('seotamic::seo.meta_description_title')"
      :instructions="__('seotamic::seo.meta_description_instructions')"
      instructions-below
    >
      <template #actions>
        <ButtonGroup :options="descriptionOptions" v-model="descriptionType" />
      </template>
      <Textarea
        id="meta_description"
        name="meta_description"
        :model-value="value.description.value"
        :read-only="descriptionType !== 'custom'"
        :limit="meta.config.meta_description_length"
        @update:model-value="updateDescriptionDebounced"
      />
    </Field>
  </div>

  <SearchPreview
    class="mt-8"
    :preview-title="__('seotamic::seo.meta_preview_title')"
    :permalink="meta.permalink"
    :domain="meta.seotamic.preview_domain"
    :title="previewTitle"
    :description="previewDescription"
  />
</template>

<script setup>
  import { Fieldtype } from '@statamic/cms'
  import { Field, Input, Switch, Textarea } from '@statamic/cms/ui'
  import { computed, ref, watch } from 'vue'
  import ButtonGroup from './seotamic/ButtonGroup.vue'
  import SearchPreview from './seotamic/SearchPreview.vue'
  import debounce from '../helpers/debounce'

  const emit = defineEmits(Fieldtype.emits)
  const props = defineProps(Fieldtype.props)
  const { expose, update } = Fieldtype.use(emit, props)

  defineExpose(expose)

  const titleType = ref('title')
  const descriptionType = ref('empty')

  const appendable = computed(() => props.meta.seotamic?.title_append != null)
  const prependable = computed(() => props.meta.seotamic?.title_prepend != null)

  const previewTitle = computed(() => {
    const append =
      appendable &&
      !!props.value?.title?.append &&
      !!props.meta.seotamic?.title_append
        ? ` ${props.meta.seotamic.title_append}`
        : ''

    const prepend =
      prependable &&
      !!props.value?.title?.prepend &&
      !!props.meta.seotamic?.title_prepend
        ? `${props.meta.seotamic?.title_prepend} `
        : ''

    return `${prepend}${props.value.title.value}${append}`.trim()
  })

  const previewDescription = computed(() => {
    return !!props.value.description.value
      ? props.value.description.value
      : __('seotamic::seo.meta_default_description')
  })

  watch(titleType, value => {
    const title = { ...props.value?.title }

    if (value == 'title') {
      title.custom_value = title.value
      title.value = props.meta.title
    } else {
      title.value = title.custom_value
    }

    update({ ...props.value, title })
  })

  watch(descriptionType, value => {
    const description = { ...props.value?.description }

    if (value == 'custom') {
      description.value = description.custom_value
    } else {
      description.custom_value = description.value
      description.value = ''
    }

    update({ ...props.value, description })
  })

  const titleOptions = [
    { label: __('seotamic::seo.meta_label_title'), value: 'title' },
    { label: __('seotamic::seo.meta_label_custom'), value: 'custom' },
  ]

  const descriptionOptions = [
    { label: __('seotamic::seo.meta_label_empty'), value: 'empty' },
    { label: __('seotamic::seo.meta_label_custom'), value: 'custom' },
  ]

  const updateTitleDebounced = debounce(value => {
    if (!props.value?.title) return

    const title = { ...props.value.title, value }

    update({ ...props.value, title })
  }, 50)

  const updateDescriptionDebounced = debounce(value => {
    if (!props.value?.description) return

    const description = { ...props.value.description, value }

    update({ ...props.value, description })
  }, 50)
</script>
