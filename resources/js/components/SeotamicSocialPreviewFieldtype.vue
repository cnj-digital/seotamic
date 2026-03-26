<template>
  <SocialPreview
    :domain="meta.seotamic.preview_domain"
    :title="meta.seotamic.social_title"
    :image="meta.image"
    :description="meta.seotamic.social_description"
  />
</template>

<script setup>
  import { Fieldtype } from '@statamic/cms'
  import SocialPreview from './seotamic/SocialPreview.vue'

  const emit = defineEmits(Fieldtype.emits)
  const props = defineProps(Fieldtype.props)
  const { expose } = Fieldtype.use(emit, props)

  defineExpose(expose)
</script>
