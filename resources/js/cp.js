import SeotamicSearchPreviewFieldtype from './components/SeotamicSearchPreviewFieldtype.vue'
import SeotamicSocialPreviewFieldtype from './components/SeotamicSocialPreviewFieldtype.vue'
import SeotamicMetaFieldtype from './components/SeotamicMetaFieldtype.vue'
import SeotamicSocialFieldtype from './components/SeotamicSocialFieldtype.vue'
import SeotamicSettings from './components/SeotamicSettings.vue'

Statamic.booting(() => {
  Statamic.$components.register(
    'seotamic_search_preview-fieldtype',
    SeotamicSearchPreviewFieldtype,
  )
  Statamic.$components.register(
    'seotamic_social_preview-fieldtype',
    SeotamicSocialPreviewFieldtype,
  )
  Statamic.$components.register(
    'seotamic_meta-fieldtype',
    SeotamicMetaFieldtype,
  )
  Statamic.$components.register(
    'seotamic_social-fieldtype',
    SeotamicSocialFieldtype,
  )

  Statamic.$inertia.register('seotamic::settings/Show', SeotamicSettings)
})
