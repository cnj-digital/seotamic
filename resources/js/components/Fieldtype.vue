<script>
export default {
  props: {
    value: {
      required: true,
    },
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
    config: {
      type: Object,
      default: () => {
        return {};
      },
    },
    handle: {
      type: String,
      required: true,
    },
    meta: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },

  methods: {
    update(value) {
      this.$emit("input", value);
    },

    updateDebounced: _.debounce(function (value) {
      this.update(value);
    }, 50),

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

    updateMeta(value) {
      this.$emit("meta-updated", value);
    },
  },

  computed: {
    name() {
      if (this.namePrefix) {
        return `${this.namePrefix}[${this.handle}]`;
      }

      return this.handle;
    },

    replicatorPreview() {
      return "";
    },

    fieldId() {
      return "field_" + this.config.handle;
    },
  },

  watch: {
    replicatorPreview: {
      immediate: true,
      handler(text) {
        this.$emit("replicator-preview-updated", text);
      },
    },

    value: function (newVal) {
      this.update(newVal);
    },
  },
};
</script>
