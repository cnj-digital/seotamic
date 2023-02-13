<template>
  <div v-if="value">
    <div>
      <Heading :title="meta.t.title_title">
        {{ meta.t.title_instructions }}
      </Heading>

      <ButtonGroup :options="titleOptions" v-model="value.title.type" />

      <div class="seotamic-mt-2">
        <text-input
          ref="title"
          :value="value.title.value"
          type="text"
          :isReadOnly="value.title.type !== 'custom'"
          :limit="meta.config.meta_title_length"
          name="meta_title"
          id="meta_title"
          @input="updateTitleDebounced"
        />
      </div>

      <div class="seotamic-mt-2">
        <div class="toggle-fieldtype-wrapper">
          <toggle-input
            :value="value.title.prepend"
            @input="updatePrepend"
            :read-only="!prependExists"
          />
          <label class="inline-label">
            {{ meta.t.prepend_label }}
          </label>
        </div>
      </div>

      <div>
        <div class="toggle-fieldtype-wrapper">
          <toggle-input
            :value="value.title.append"
            @input="updateAppend"
            :read-only="!appendExists"
          />
          <label class="inline-label">
            {{ meta.t.append_label }}
          </label>
        </div>
      </div>
    </div>

    <div class="seotamic-mt-8">
      <Heading :title="meta.t.description_title">
        {{ meta.t.description_instructions }}
      </Heading>

      <ButtonGroup
        :options="descriptionOptions"
        v-model="value.description.type"
      />

      <div class="seotamic-mt-2">
        <textarea-input
          ref="description"
          :value="value.description.value"
          type="text"
          :isReadOnly="value.description.type !== 'custom'"
          :limit="meta.config.meta_description_length"
          name="meta_description"
          id="meta_description"
          @input="updateDescriptionDebounced"
        />
      </div>
    </div>

    <SearchPreview
      class="seotamic-mt-8"
      :preview-title="meta.t.preview_title"
      :permalink="meta.permalink"
      :domain="meta.seotamic.preview_domain"
      :title="previewTitle"
      :description="previewDescription"
    />

    <div class="seotamic-mt-8 seotamic-h-px seotamic-bg-gray-300"></div>
  </div>
</template>

<script>
import Fieldtype from "./Fieldtype.vue";
import Heading from "./seotamic/Heading.vue";
import ButtonGroup from "./seotamic/ButtonGroup.vue";
import SearchPreview from "./seotamic/SearchPreview.vue";

export default {
  components: {
    ButtonGroup,
    Heading,
    SearchPreview,
  },
  mixins: [Fieldtype],
  data() {
    return {
      description: this.meta.t.default_description,

      titleOptions: [
        { label: this.meta.t.label_title, value: "title" },
        { label: this.meta.t.label_custom, value: "custom" },
      ],

      descriptionOptions: [
        { label: this.meta.t.label_empty, value: "empty" },
        { label: this.meta.t.label_custom, value: "custom" },
      ],
    };
  },

  computed: {
    prependExists() {
      return this.meta.seotamic.title_prepend !== null;
    },

    appendExists() {
      return this.meta.seotamic.title_append !== null;
    },

    previewTitle() {
      const prepend =
        this.prependExists && this.value.title.prepend
          ? this.meta.seotamic.title_prepend + " "
          : "";
      const append =
        this.appendExists && this.value.title.append
          ? " " + this.meta.seotamic.title_append
          : "";

      return `${prepend}${this.value.title.value}${append}`;
    },

    previewDescription() {
      return this.value.description.value === "" ||
        !this.value.description.value
        ? this.description
        : this.value.description.value;
    },
  },

  watch: {
    "value.title.type": function (newVal) {
      if (newVal === "title") {
        this.value.title.custom_value = this.value.title.value;
        this.value.title.value = this.meta.title;
      } else {
        this.value.title.value = this.value.title.custom_value;
      }
    },

    "value.description.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.value.description.custom_value = this.value.description.value;
      }

      if (newVal === "custom") {
        this.value.description.value = this.value.description.custom_value;
      } else {
        this.value.description.value = "";
      }
    },
  },

  methods: {
    updatePrepend(value) {
      this.value.title.prepend = value;
    },

    updateAppend(value) {
      this.value.title.append = value;
    },
  },
};
</script>
