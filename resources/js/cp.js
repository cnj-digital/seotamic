import SeotamicSearchPreview from "./components/SeotamicSearchPreview.vue";
import SeotamicSocialPreview from "./components/SeotamicSocialPreview.vue";
import SeotamicMetaFieldtype from "./components/SeotamicMetaFieldtype.vue";
import SeotamicSocialFieldtype from "./components/SeotamicSocialFieldtype.vue";

Statamic.booting(() => {
  Statamic.component(
    "seotamic_search_preview-fieldtype",
    SeotamicSearchPreview
  );
  Statamic.component(
    "seotamic_social_preview-fieldtype",
    SeotamicSocialPreview
  );
  Statamic.component("seotamic_meta-fieldtype", SeotamicMetaFieldtype);
  Statamic.component("seotamic_social-fieldtype", SeotamicSocialFieldtype);
});
