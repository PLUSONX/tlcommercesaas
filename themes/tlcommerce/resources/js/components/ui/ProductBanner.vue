<template>
  <div
    class="product-banner-item d-flex align-items-center justify-content-center"
  >
    <!-- Image -->
    <div class="slide-img desktop">
      <a :href="content.url">
        <img :src="cleanDesktopImage" alt="image" />
        <!-- <img :src="content.desktop" alt="image" /> -->
      </a>
    </div>
    <!-- End Image -->
    <!-- Image -->
    <div class="slide-img mobile">
      <a :href="content.url">
        <img :src="cleanMobileImage" alt="image" />
        <!-- <img :src="content.mobile" alt="image" /> -->
      </a>
    </div>
    <!-- End Image -->
  </div>
</template>

<script>
export default {
  name: "ProductBanner",
  props: {
    content: {
      type: Object,
      required: true,
    },
  },
  computed: {
    cleanDesktopImage() {
      const img = this.content?.desktop;
      if (!img) return '';

      return img.startsWith('/public')
        ? img.slice(7)
        : img;
    },
    cleanMobileImage() {
      const img = this.content?.mobile;
      if (!img) return '';

      
      return img.startsWith('/public')
        ? img.slice(7)
        : img;
    },
  },
};
</script>

<!-- <script>
import VLazyImage from "v-lazy-image";
export default {
  name: "ProductBanner",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    content: {
      type: Object,
      required: true,
    },
  },
};
</script> -->

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";
.slide-img.desktop {
  @media (max-width: 767px) {
    display: none;
  }
}
.slide-img.mobile {
  display: none;
  @media (max-width: 767px) {
    display: block;
  }
}
</style>
