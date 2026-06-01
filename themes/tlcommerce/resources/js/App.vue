<template>
  <div v-if="themeStyle">
    <component :is="layout" />
  </div>
</template>

<script>
import MainLayout from "./layouts/MainLayout.vue";
const axios = require("axios").default;

export default {
  components: {
    MainLayout,
  },
  data() {
    return {
      themeStyle: null,
    };
  },
  computed: {
    layout() {
      return this.$route.meta.layout || MainLayout;
    },
  },
  async created() {
    try {
      this.themeStyle = await axios.get("/api/theme/tlcommerce/v1/get-theme-color");
      const themeColor = this.themeStyle?.data?.themeColor;

      if (themeColor?.theme_primary_color) {
        document.documentElement.style.setProperty(
          "--mainC",
          themeColor.theme_primary_color
        );
      }
    } catch (e) {
      // Blade already injects --mainC before Vue mounts.
    }
  },
};
</script>
