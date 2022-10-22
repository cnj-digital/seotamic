<template>
  <div>
    <div>
      <Heading title="Title">
        It can be used to determine the title used on search engine results
        pages. Defaults to `title` which sets the page title as the Entry title.
        For custom entries, select `Custom` and enter your own value.
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
            Prepend to the title the text set in General SEO settings
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
            Append to the title the text set in General SEO settings
          </label>
        </div>
      </div>
    </div>

    <div class="seotamic-mt-8">
      <Heading title="Description">
        It can be used to determine the text used under the title on search
        engine results pages. If empty, search engines will automatically
        generate this text.
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
      :url="meta.config.preview_url"
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
      // Move to translations…
      description:
        "This description will be prefilled by the search engine depending on your content. You can change it manually by selecting custom and typing in your own.",

      titleOptions: [
        { label: "Title", value: "title" },
        { label: "Custom", value: "custom" },
      ],

      descriptionOptions: [
        { label: "Empty", value: "empty" },
        { label: "Custom", value: "custom" },
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
    // TODO Translations

    if (!this.value) {
      this.value = this.valueData;
    }

    if (!this.value.title) {
      this.value.title = this.valueData.title;
    }

    if (!this.value.description) {
      this.value.description = this.valueData.description;
    }

    this.valueData = this.value;

    // Re set values on mounted from the latest data
    if (this.valueData.title.type === "title") {
      this.valueData.title.value = this.meta.title;
    }
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

    valueData: function (newVal) {
      this.update(newVal);
    },
  },

  methods: {
    updateTitleDebounced: _.debounce((value) => {
      this.valueData.title.value = value;
    }, 50),

    updateDescriptionDebounced: _.debounce((value) => {
      this.valueData.description.value = value;
    }, 50),

    updatePrepend(value) {
      this.valueData.title.prepend = value;
    },

    updateAppend(value) {
      this.valueData.title.append = value;
    },
  },
};
</script>
