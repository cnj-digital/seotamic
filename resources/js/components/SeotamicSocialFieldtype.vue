<template>
  <div v-if="value">
    <Field
      id="og_title"
      :label="__('seotamic::social.social_field_title_title')"
      :instructions="__('seotamic::social.social_field_title_instructions')"
      instructions-below
    >
      <template #actions>
        <ButtonGroup :options="titleOptions" v-model="titleType" />
      </template>

      <Input
        name="og_title"
        id="og_title"
        :read-only="titleType !== 'custom'"
        :limit="meta.config.social_title_length"
        :model-value="value.title.value"
        @update:modelValue="updateTitleDebounced"
      />
    </Field>

    <Field
      class="mt-4"
      id="description"
      :label="__('seotamic::social.social_field_description_title')"
      :instructions="
        __('seotamic::social.social_field_description_instructions')
      "
      instructions-below
    >
      <template #actions>
        <ButtonGroup :options="descriptionOptions" v-model="descriptionType" />
      </template>

      <Input
        id="description"
        name="description"
        type="text"
        :model-value="value.description.value"
        :read-only="descriptionType !== 'custom'"
        :limit="meta.config.social_description_length"
        @update:modelValue="updateDescriptionDebounced"
      />
    </Field>

    <SocialPreview
      class="mt-8"
      :preview-title="__('seotamic::social.social_field_preview_title')"
      :permalink="meta.permalink"
      :domain="meta.seotamic.preview_domain"
      :title="value.title.value"
      :image="meta.social_image"
      :description="value.description.value"
    />
  </div>
</template>

<script setup>
  import { Fieldtype } from '@statamic/cms'
  import { Field, Input, Switch, Textarea } from '@statamic/cms/ui'
  import { computed, ref, watch } from 'vue'
  import ButtonGroup from './seotamic/ButtonGroup.vue'
  import SocialPreview from './seotamic/SocialPreview.vue'
  import debounce from '../helpers/debounce'

  const emit = defineEmits(Fieldtype.emits)
  const props = defineProps(Fieldtype.props)
  const { expose, update } = Fieldtype.use(emit, props)

  defineExpose(expose)

  const titleType = ref('title')
  const descriptionType = ref('meta')

  watch(titleType, (value, old) => {
    const title = { ...props.value?.title }

    if (old == 'custom') {
      title.custom_value = title.value
    }

    if (value == 'title') {
      if (props.meta.meta.title.type == 'custom') {
        title.value = props.meta.meta.title.value
      } else {
        title.value = props.meta.title
      }
    } else if (value == 'general') {
      title.value = props.meta.seotamic.social_title
    } else {
      title.value = title.custom_value
    }

    update({ ...props.value, title })
  })

  watch(descriptionType, (value, old) => {
    const description = { ...props.value?.description }

    if (old == 'custom') {
      description.custom_value = description.value
    }

    // Meta is "Automatic"
    if (value == 'meta') {
      if (props.meta.meta.description.type == 'custom') {
        description.value = props.meta.meta.description.value
      } else {
        description.value = props.meta.seotamic.social_description
      }
    } else if (value == 'general') {
      description.value = props.meta.seotamic.social_description
    } else {
      description.value = description.custom_value
    }

    update({ ...props.value, description })
  })

  const titleOptions = [
    {
      label: __('seotamic::social.social_field_label_title'),
      value: 'title',
    },
    {
      label: __('seotamic::social.social_field_label_general'),
      value: 'general',
    },
    {
      label: __('seotamic::social.social_field_label_custom'),
      value: 'custom',
    },
  ]

  const descriptionOptions = [
    {
      label: __('seotamic::social.social_field_label_meta'),
      value: 'meta',
    },
    {
      label: __('seotamic::social.social_field_label_general'),
      value: 'general',
    },
    {
      label: __('seotamic::social.social_field_label_custom'),
      value: 'custom',
    },
  ]

  const updateTitleDebounced = debounce(value => {
    if (!props.value?.title) return

    const title = { ...props.value.title, value }

    if (title.type == 'custom') {
      title.custom_value = value
    }

    update({ ...props.value, title })
  }, 50)

  const updateDescriptionDebounced = debounce(value => {
    if (!props.value?.description) return

    const description = { ...props.value.description, value }

    if (description.type == 'custom') {
      description.custom_value = value
    }

    update({ ...props.value, description })
  }, 50)
</script>
