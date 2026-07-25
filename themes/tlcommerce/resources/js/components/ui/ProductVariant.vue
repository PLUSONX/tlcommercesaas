<template>
  <component v-bind:is="tag">{{ formattedString }}</component>
</template>
<script>
import { formatVariantDisplay } from "@/utils/formatVariantDisplay";

export default {
  name: "ProductVariant",
  props: {
    variant: {
      type: [String, Number],
      required: false,
      default: "",
    },
    tag: {
      type: String,
      required: false,
      default: "span",
    },
  },
  computed: {
    formattedString() {
      return formatVariantDisplay(this.variant);
    },
  },
};
</script>
