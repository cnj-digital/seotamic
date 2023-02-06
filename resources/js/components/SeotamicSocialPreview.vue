<template>
  <SocialPreview
    :domain="meta.seotamic.preview_domain"
    :title="meta.seotamic.social_title"
    :image="socialPreviewImage"
    :description="meta.seotamic.social_description"
  />
</template>

<script>
import Fieldtype from "./Fieldtype.vue";
import SocialPreview from "./seotamic/SocialPreview.vue";

export default {
  components: {
    SocialPreview,
  },

  mixins: [Fieldtype],

  computed: {
    socialPreviewImage() {
      return `/${this.meta.config.container}/${this.meta.seotamic.social_image}`;
    },
  },
};
</script>
