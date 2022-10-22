<template>
  <div class="seotamic-mt-8">
    <div
      class="seotamic-text-xs seotamic-uppercase seotamic-font-bold seotamic-tracking-wider"
    >
      {{ previewTitle }}
    </div>
    <div
      class="seotamic-mt-2 seotamic-bg-[#f2f3f5] seotamic-border seotamic-border-[#dadde1] seotamic-shadow-sm seotamic-flex seotamic-flex-col seotamic-max-w-[500px] seotamic-rounded-[3px]"
    >
      <div
        class="seotamic-h-[261px] seotamic-w-full seotamic-bg-blue-300 relative"
      >
        <img
          v-if="image"
          :src="image"
          class="absolute object-cover w-full h-full"
        />
      </div>
      <div class="seotamic-py-2.5 seotamic-px-3">
        <div
          class="seotamic-text-xs seotamic-text-[#606770] seotamic-leading-none seotamic-uppercase"
        >
          {{ domain }}
        </div>
        <div
          class="seotamic-text-[#1d2129] seotamic-font-semibold seotamic-text-base seotamic-leading-[20px] seotamic-truncate seotamic-mt-[5px]"
        >
          {{ title }}
        </div>
        <div
          class="seotamic-text-sm seotamic-leading-[18px] seotamic-text-[#606770] seotamic-truncate seotamic-mt-[3px]"
        >
          {{ description }}
        </div>
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
      default: "Social preview",
    },
    domain: {
      type: String,
      required: true,
      default: "google.com",
    },
    image: {
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
};
</script>
