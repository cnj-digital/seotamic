<template>
  <div class="button-group-fieldtype-wrapper inline-mode">
    <div class="btn-group">
      <button
        class="btn !seotamic-py-1 seotamic-text-sm seotamic-h-auto !seotamic-px-3"
        v-for="(option, $index) in options"
        :key="$index"
        ref="button"
        :name="option.label"
        :value="option.value"
        :class="{ active: value === option.value }"
        @click="$emit('input', option.value)"
        v-text="option.label || option.value"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
};
</script>
