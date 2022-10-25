<template>
  <div>
    <div>
      <Heading :title="meta.t.title_title">
        {{ meta.t.title_instructions }}
      </Heading>

      <ButtonGroup :options="titleOptions" v-model="valueData.title.type" />

      <div class="seotamic-mt-2">
        <text-input
          ref="title"
          :value="valueData.title.value"
          type="text"
          :isReadOnly="valueData.title.type !== 'custom'"
          :limit="meta.config.meta_title_length"
          name="meta_title"
          id="meta_title"
          @input="updateTitleDebounced"
        />
      </div>

      <div class="seotamic-mt-2">
        <div class="toggle-fieldtype-wrapper">
          <toggle-input
            :value="valueData.title.prepend"
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
            :value="valueData.title.append"
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
        v-model="valueData.description.type"
      />

      <div class="seotamic-mt-2">
        <textarea-input
          ref="description"
          :value="valueData.description.value"
          type="text"
          :isReadOnly="valueData.description.type !== 'custom'"
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
      :url="meta.seotamic.preview_url"
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

      // Default values…
      valueData: {
        title: {
          value: "",
          custom_value: "",
          type: "title", // title, custom
          prepend: true,
          append: true,
        },
        description: {
          value: "",
          custom_value: "",
          type: "empty", // empty, custom
        },
      },
    };
  },

  mounted() {
    this.valueData = this.value;
  },

  updated() {
    this.valueData = this.value;
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
        this.prependExists && this.valueData.title.prepend
          ? this.meta.seotamic.title_prepend + " "
          : "";
      const append =
        this.appendExists && this.valueData.title.append
          ? " " + this.meta.seotamic.title_append
          : "";

      return `${prepend}${this.valueData.title.value}${append}`;
    },

    previewDescription() {
      return this.valueData.description.value === "" ||
        !this.valueData.description.value
        ? this.description
        : this.valueData.description.value;
    },
  },

  watch: {
    "valueData.title.type": function (newVal) {
      if (newVal === "title") {
        this.valueData.title.custom_value = this.valueData.title.value;
        this.valueData.title.value = this.meta.title;
      } else {
        this.valueData.title.value = this.valueData.title.custom_value;
      }
    },

    "valueData.description.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.valueData.description.custom_value =
          this.valueData.description.value;
      }

      if (newVal === "custom") {
        this.valueData.description.value =
          this.valueData.description.custom_value;
      } else {
        this.valueData.description.value = "";
      }
    },
  },

  methods: {
    updatePrepend(value) {
      this.valueData.title.prepend = value;
    },

    updateAppend(value) {
      this.valueData.title.append = value;
    },
  },
};
</script>
