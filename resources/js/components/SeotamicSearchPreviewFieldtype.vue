<template>
  <SearchPreview
    :domain="meta.seotamic.preview_domain"
    :title="previewTitle"
    :description="__('seotamic::general.title_preview_placeholder_description')"
  />
</template>

<script setup>
  import { Fieldtype } from '@statamic/cms'
  import { computed } from 'vue'
  import SearchPreview from './seotamic/SearchPreview.vue'

  const emit = defineEmits(Fieldtype.emits)
  const props = defineProps(Fieldtype.props)
  const { expose } = Fieldtype.use(emit, props)

  defineExpose(expose)

  const appendable = computed(() => props.meta.seotamic?.title_append != null)
  const prependable = computed(() => props.meta.seotamic?.title_prepend != null)

  const previewTitle = computed(() => {
    const append =
      appendable && !!props.meta.seotamic?.title_append
        ? ` ${props.meta.seotamic.title_append}`
        : ''

    const prepend =
      prependable && !!props.meta.seotamic?.title_prepend
        ? `${props.meta.seotamic?.title_prepend} `
        : ''

    return `${prepend}${__('seotamic::general.title_preview_placeholder_title')}${append}`.trim()
  })
</script>
