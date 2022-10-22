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
          :limit="meta.config.social_title_length"
          name="og_title"
          id="og_title"
          @input="updateTitleDebounced"
        />
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
        <text-input
          ref="description"
          :value="valueData.description.value"
          type="text"
          :isReadOnly="valueData.description.type !== 'custom'"
          :limit="meta.config.social_description_length"
          name="description"
          id="description"
          @input="updateDescriptionDebounced"
        />
      </div>
    </div>

    <SocialPreview
      class="seotamic-mt-8"
      :preview-title="meta.t.preview_title"
      :name="meta.config.preview_domain"
      :title="valueData.title.value"
      :image="socialPreviewImage"
      :description="valueData.description.value"
    />

    <div class="seotamic-mt-8 seotamic-h-px seotamic-bg-gray-300"></div>
  </div>
</template>

<script>
import Fieldtype from "./Fieldtype.vue";
import Heading from "./seotamic/Heading.vue";
import ButtonGroup from "./seotamic/ButtonGroup.vue";
import SocialPreview from "./seotamic/SocialPreview.vue";

export default {
  components: {
    ButtonGroup,
    Heading,
    SocialPreview,
  },

  mixins: [Fieldtype],

  data() {
    return {
      titleOptions: [
        { label: this.meta.t.label_title, value: "title" },
        { label: this.meta.t.label_general, value: "general" },
        { label: this.meta.t.label_custom, value: "custom" },
      ],

      descriptionOptions: [
        { label: this.meta.t.label_general, value: "general" },
        { label: this.meta.t.label_meta, value: "meta" },
        { label: this.meta.t.label_custom, value: "custom" },
      ],

      // Default values…
      valueData: {
        title: {
          value: "",
          custom_value: "",
          type: "title", // title, general, custom,
        },
        description: {
          value: "",
          custom_value: "",
          type: "general", // general, meta, custom
        },
      },
    };
  },

  mounted() {
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
    socialPreviewImage() {
      if (this.meta.social_image) {
        return `/${this.meta.config.container}/${this.meta.social_image}`;
      }

      return `/${this.meta.config.container}/${this.meta.seotamic.social_image}`;
    },
  },

  watch: {
    "valueData.title.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.valueData.title.custom_value = this.valueData.title.value;
      }

      if (newVal === "title") {
        this.valueData.title.value = this.meta.title;
      } else if (newVal === "general") {
        this.valueData.title.value = this.meta.seotamic.social_title;
      } else {
        this.valueData.title.value = this.valueData.title.custom_value;
      }
    },

    "valueData.description.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.valueData.description.custom_value =
          this.valueData.description.value;
      }

      if (newVal === "meta") {
        this.valueData.description.value = this.meta.meta.description.value;
      } else if (newVal === "general") {
        this.valueData.description.value =
          this.meta.seotamic.social_description;
      } else {
        this.valueData.description.value =
          this.valueData.description.custom_value;
      }
    },

    valueData: function (newVal) {
      this.update(newVal);
    },
  },

  methods: {
    updateTitleDebounced: _.debounce(function (value) {
      this.valueData.title.value = value;
    }, 50),

    updateDescriptionDebounced: _.debounce(function (value) {
      this.valueData.description.value = value;
    }, 50),
  },
};
</script>
