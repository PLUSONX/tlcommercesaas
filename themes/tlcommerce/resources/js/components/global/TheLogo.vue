<template>
  <!-- Logo -->
  <router-link to="/" class="logo">
    <img
      v-if="logo"
      :src="cleanLogo"
      :alt="title"
      :class="this.headerLogoStyle.custom_header_logo == 1 ? 'custom-logo' : ''"
    />
    <!-- <img
      v-if="logo"
      :src="logo"
      :alt="title"
      :class="this.headerLogoStyle.custom_header_logo == 1 ? 'custom-logo' : ''"
    /> -->
    <template v-else>
      {{ title.toUpperCase() }}
    </template>
  </router-link>
  <!-- End Logo -->
</template>

<script>
export default {
  name: "TheLogo",
  props: {
    logo: {
      type: String,
      default: "",
    },
    title: {
      type: String,
      default: "",
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  computed: {
    cleanLogo() {
      return this.logo?.replace('/public', '');
    },
  },
  mounted() {
    console.log('TheLogo mounted');
    console.log('Original logo:', this.logo);
    console.log('Clean logo:', this.cleanLogo);
  },
};
</script>
<!-- <script>
export default {
  name: "TheLogo",
  props: {
    logo: {
      type: String,
      default: "",
    },
    title: {
      type: String,
      default: "",
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
     computed: {
    cleanLogo() {
      return this.logo.replace('/public', '');
    },
  },
  mounted() {
  console.log('TheLogo mounted');
}
  },
};
</script> -->
<style scoped>
</style>
