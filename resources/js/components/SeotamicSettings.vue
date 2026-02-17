<script setup>
  import { ref, useTemplateRef } from 'vue'
  import { Button, Header, PublishContainer } from '@statamic/cms/ui'
  import {
    AfterSaveHooks,
    BeforeSaveHooks,
    Pipeline,
    Request,
  } from '@statamic/cms/save-pipeline'

  const props = defineProps({
    blueprint: Object,
    initialValues: Object,
    initialMeta: Object,
  })

  const errors = ref({})
  const saving = ref(false)
  const values = ref(props.initialValues)
  const meta = ref(props.initialMeta)
  const container = useTemplateRef('container')

  function save() {
    new Pipeline()
      .provide({ container, errors, saving })
      .through([
        new BeforeSaveHooks('seotamic'),
        new Request('/cp/cnj/seotamic', 'POST'),
        new AfterSaveHooks('seotamic'),
      ])
      .then(location.reload.bind(location))
  }
</script>

<template>
  <Header
    icon="search-magnifying-glass"
    :title="__('seotamic::general.seotamic')"
  >
    <Button text="Save" variant="primary" :disabled="saving" @click="save" />
  </Header>

  <PublishContainer
    ref="container"
    v-model="values"
    :blueprint="blueprint"
    :meta="meta"
    :errors="errors"
  />
</template>
