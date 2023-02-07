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
        v-model="value.description.type"
      />

      <div class="seotamic-mt-2">
        <text-input
          ref="description"
          :value="value.description.value"
          type="text"
          :isReadOnly="value.description.type !== 'custom'"
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
      :permalink="meta.permalink"
      :domain="meta.seotamic.preview_domain"
      :title="value.title.value"
      :image="socialPreviewImage"
      :description="value.description.value"
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
      value: false,
    };
  },

  mounted() {
    this.value = this.value;
  },

  updated() {
    this.value = this.value;
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
    "value.title.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.value.title.custom_value = this.value.title.value;
      }

      if (newVal === "title") {
        this.value.title.value = this.meta.title;
      } else if (newVal === "general") {
        this.value.title.value = this.meta.seotamic.social_title;
      } else {
        this.value.title.value = this.value.title.custom_value;
      }
    },

    "value.description.type": function (newVal, oldVal) {
      if (oldVal === "custom") {
        this.value.description.custom_value = this.value.description.value;
      }

      if (newVal === "meta") {
        this.value.description.value = this.meta.meta.description.value;
      } else if (newVal === "general") {
        this.value.description.value = this.meta.seotamic.social_description;
      } else {
        this.value.description.value = this.value.description.custom_value;
      }
    },
  },

  methods: {
    updateTitleDebounced: _.debounce(function (value) {
      this.value.title.value = value;

      if (this.value.title.type === "custom") {
        this.value.title.custom_value = value;
      }
    }, 50),

    updateDescriptionDebounced: _.debounce(function (value) {
      this.value.description.value = value;

      if (this.value.description.type === "custom") {
        this.value.description.custom_value = value;
      }
    }, 50),
  },
};
</script>
