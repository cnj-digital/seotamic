import SeotamicSearchPreview from "./components/SeotamicSearchPreview";
import SeotamicSocialPreview from "./components/SeotamicSocialPreview";
import SeotamicMetaFieldtype from "./components/SeotamicMetaFieldtype";
import SeotamicSocialFieldtype from "./components/SeotamicSocialFieldtype";

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
