<template>
  <div class="">
    <div
      v-if="previewTitle"
      class="seotamic-text-xs seotamic-uppercase seotamic-font-bold seotamic-tracking-wider"
    >
      {{ previewTitle }}
    </div>

    <div v-if="permalink" class="seotamic-mt-2">
      <a
        :href="`https://developers.facebook.com/tools/debug/?q=` + permalink"
        class="text-sm underline text-blue hover:text-blue-dark"
        target="_blank"
        rel="noopener noreferrer"
      >
        Facebook Debugger
      </a>
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
          ref="title"
          class="seotamic-text-[#1d2129] seotamic-font-semibold seotamic-text-base seotamic-leading-[20px] seotamic-line-clamp-2 seotamic-mt-[5px]"
        >
          {{ title }}
        </div>
        <div
          v-show="showDescription"
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
      required: false,
      default: "",
    },
    permalink: {
      type: String,
      required: false,
      default: "",
    },
    domain: {
      type: String,
      required: true,
      default: "",
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

  data() {
    return {
      showDescription: true,
    };
  },

  // watch title changes
  watch: {
    title() {
      this.$nextTick(() => {
        // if $refs.title is in 2 lines, hide the descritpion
        if (this.$refs.title.clientHeight >= 40) {
          this.showDescription = false;
        } else {
          this.showDescription = true;
        }
      });
    },
  },
};
</script>
