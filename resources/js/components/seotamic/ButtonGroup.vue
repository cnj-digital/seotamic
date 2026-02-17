<template>
  <ToggleGroup v-model="modelValue" variant="ghost" :size="size">
    <ToggleItem
      v-for="(option, $index) in options"
      :key="$index"
      :label="option.label || option.value"
      :value="option.value"
    />
  </ToggleGroup>
</template>

<script setup>
  import { ToggleGroup, ToggleItem } from '@statamic/cms/ui'

  const props = defineProps({
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
    size: {
      type: String,
      default: 'sm',
    },
  })

  const modelValue = defineModel({ type: String, required: true })
</script>
