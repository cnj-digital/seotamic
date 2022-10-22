<template>
  <div>
    <div class="seotamic-text-sm seotamic-font-bold seotamic-text-[#1c2e36]">
      {{ title }}
    </div>
    <div class="seotamic-text-[13px] seotamic-text-[#737f8c] seotamic-mb-2">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
      default: "",
    },
  },
};
</script>
