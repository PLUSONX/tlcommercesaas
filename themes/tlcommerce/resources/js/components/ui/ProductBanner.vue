<template>
  <div class="product-banner-item d-flex align-items-center justify-content-center">
    <!-- Single viewport image — avoids downloading both desktop and mobile assets -->
    <div class="slide-img" :class="isMobile ? 'mobile' : 'desktop'">
      <a :href="content.url">
        <img
          :src="activeImage"
          alt="image"
          :width="imageWidth"
          :height="imageHeight"
          :fetchpriority="priority ? 'high' : 'auto'"
          :loading="priority ? 'eager' : 'lazy'"
          decoding="async"
        />
        <!-- <img :src="content.desktop" alt="image" /> -->
        <!-- <img :src="content.mobile" alt="image" /> -->
      </a>
    </div>
    <!-- End Image -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "ProductBanner",
  props: {
    content: {
      type: Object,
      required: true,
    },
    priority: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    ...mapGetters("layout", ["isMobile"]),
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
    activeImage() {
      if (this.isMobile) {
        return this.cleanMobileImage || this.cleanDesktopImage;
      }
      return this.cleanDesktopImage || this.cleanMobileImage;
    },
    // Intrinsic size hints for CLS; CSS keeps width:100% / height:auto (no crop)
    imageWidth() {
      return this.isMobile ? 800 : 1600;
    },
    imageHeight() {
      return this.isMobile ? 500 : 500;
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

// .product-banner-item {
//   box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
// }

.slide-img {
  border-radius: 12px;
  overflow: hidden;
  width: 100%;

  img {
    width: 100%;
    height: auto;
    display: block;
  }
}
</style>
