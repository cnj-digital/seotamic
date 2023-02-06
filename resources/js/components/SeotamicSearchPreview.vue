<template>
  <SearchPreview
    :domain="$attrs.meta.seotamic.preview_domain"
    :title="previewTitle"
    :description="$attrs.meta.t.default_description"
  />
</template>

<script>
import Heading from "./seotamic/Heading.vue";
import SearchPreview from "./seotamic/SearchPreview.vue";

export default {
  components: {
    Heading,
    SearchPreview,
  },

  computed: {
    prependExists() {
      return this.$attrs.meta.seotamic.title_prepend !== null;
    },
    appendExists() {
      return this.$attrs.meta.seotamic.title_append !== null;
    },

    previewTitle() {
      const prepend = this.prependExists
        ? this.$attrs.meta.seotamic.title_prepend + " "
        : "";
      const append = this.appendExists
        ? " " + this.$attrs.meta.seotamic.title_append
        : "";
      return `${prepend}${this.$attrs.meta.t.demo_title}${append}`;
    },
  },
};
</script>
