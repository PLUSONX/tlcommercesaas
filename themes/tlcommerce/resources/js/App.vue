<template>
  <div>
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
  computed: {
    layout() {
      return this.$route.meta.layout || MainLayout;
    },
  },
  created() {
    // Non-blocking: Blade already injects --mainC. Refresh from API when available.
    this.refreshThemeColor();
  },
  methods: {
    async refreshThemeColor() {
      try {
        const response = await axios.get(
          "/api/theme/tlcommerce/v1/get-theme-color"
        );
        const themeColor = response?.data?.themeColor;

        if (themeColor?.theme_primary_color) {
          document.documentElement.style.setProperty(
            "--mainC",
            themeColor.theme_primary_color
          );
          document.documentElement.style.setProperty(
            "--color-primary",
            themeColor.theme_primary_color
          );
        }
      } catch (e) {
        // Blade already injects --mainC before Vue mounts.
      }
    },
  },
};
</script>
