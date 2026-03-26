<template>
  <div>
    <Label class="capitalize" :text="previewTitle" />

    <div v-if="permalink">
      <a
        :href="`https://pagespeed.web.dev/report?url=` + permalink"
        class="text-sm underline text-blue hover:text-blue-dark"
        target="_blank"
        rel="noopener noreferrer"
      >
        Pagespeed Insights
      </a>
    </div>

    <div class="mt-2 border shadow-xs rounded-lg p-3 flex flex-col max-w-150">
      <div class="text-sm text-[#202124] dark:text-gray-300 mb-0.5">
        {{ url }}
      </div>
      <div
        class="text-[#1a0dab] dark:text-gray-300 text-xl leading-6.5 truncate mt-1.25"
      >
        {{ title }}
      </div>
      <div class="text-sm text-[#4d5156] dark:text-gray-300">
        {{ truncate(description) }}
      </div>
    </div>
  </div>
</template>

<script setup>
  import { Label } from '@statamic/cms/ui'
  import { computed } from 'vue'

  const props = defineProps({
    previewTitle: {
      type: String,
      required: true,
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

  const url = computed(() => {
    if (
      props.domain &&
      (props.domain.startsWith('https://') ||
        props.domain.startsWith('http://'))
    ) {
      return props.domain
    }

    return 'https://' + props.domain
  })

  function truncate(str, num = 160) {
    if (!str) return str

    if (str.length <= num) {
      return str
    }

    return str.slice(0, num) + ' …'
  }
</script>
