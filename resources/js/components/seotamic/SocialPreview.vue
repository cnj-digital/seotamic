<template>
  <div>
    <Label class="capitalize" :text="previewTitle" />

    <div v-if="permalink">
      <a
        :href="`https://developers.facebook.com/tools/debug/?q=` + permalink"
        class="text-sm underline text-blue hover:text-blue-dark"
        target="_blank"
        rel="noopener noreferrer"
      >
        Facebook Debugger
      </a>
    </div>

    <div class="mt-2 border shadow-xs flex flex-col max-w-125 rounded-lg">
      <div class="h-65.25 w-full relative">
        <img
          v-if="image"
          :src="image"
          class="absolute object-cover w-full h-full"
        />
      </div>
      <div class="py-2.5 px-3">
        <div class="text-xs leading-none uppercase">
          {{ domain }}
        </div>
        <div
          ref="title"
          class="font-semibold text-base leading-5 line-clamp-2 mt-1.25"
        >
          {{ title }}
        </div>
        <div v-show="showDescription" class="text-sm truncate mt-0.75">
          {{ description }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { Label } from '@statamic/cms/ui'
  import { nextTick, ref, useTemplateRef, watch } from 'vue'

  const props = defineProps({
    previewTitle: {
      type: String,
      required: false,
      default: '',
    },
    permalink: {
      type: String,
      required: false,
      default: '',
    },
    domain: {
      type: String,
      required: true,
      default: '',
    },
    image: {
      type: String,
      required: true,
      default: '',
    },
    title: {
      type: String,
      required: true,
      default: '',
    },
    description: {
      type: String,
      required: true,
      default: '',
    },
  })

  const showDescription = ref(true)
  const titleRef = useTemplateRef('title')

  watch(
    () => props.title,
    () => {
      nextTick(() => {
        showDescription.value = titleRef.clientHeight < 40
      })
    },
  )
</script>
