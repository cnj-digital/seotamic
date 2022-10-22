import SeotamicMetaFieldtype from "./components/SeotamicMetaFieldtype";
import SeotamicSocialFieldtype from "./components/SeotamicSocialFieldtype";

Statamic.booting(() => {
  Statamic.component("seotamic_meta-fieldtype", SeotamicMetaFieldtype);
  Statamic.component("seotamic_social-fieldtype", SeotamicSocialFieldtype);
});
