<template>
  <div class="seotamic-mt-8">
    <div
      class="seotamic-text-xs seotamic-uppercase seotamic-font-bold seotamic-tracking-wider"
    >
      {{ previewTitle }}
    </div>
    <div
      class="seotamic-mt-2 seotamic-bg-white seotamic-border seotamic-shadow-sm seotamic-p-3 seotamic-flex seotamic-flex-col seotamic-max-w-[600px] seotamic-rounded-[3px]"
    >
      <div class="seotamic-text-sm seotamic-text-[#202124] seotamic-mb-0.5">
        {{ url }}
      </div>
      <div
        class="seotamic-text-[#1a0dab] seotamic-text-xl seotamic-leading-[26px] seotamic-truncate seotamic-mt-[5px]"
      >
        {{ title }}
      </div>
      <div class="seotamic-text-sm seotamic-text-[#4d5156]">
        {{ truncate(description) }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    previewTitle: {
      type: String,
      required: true,
      default: "Search preview",
    },
    url: {
      type: String,
      required: true,
      default: "",
    },
    title: {
      type: String,
      required: true,
      default: "",
    },
    description: {
      type: String,
      required: true,
      default: "",
    },
  },

  methods: {
    truncate(str, num = 160) {
      if (!str) return str;

      if (str.length <= num) {
        return str;
      }

      return str.slice(0, num) + " …";
    },
  },
};
</script>
