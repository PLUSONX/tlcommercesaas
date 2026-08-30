<template>
  <div class="product-banner-item d-flex align-items-center justify-content-center">
    <div class="slide-img">
      <img
        class="banner-media"
        :src="activeImage"
        :alt="content.title || 'Banner image'"
        :width="imageWidth"
        :height="imageHeight"
        :fetchpriority="priority ? 'high' : 'auto'"
        :loading="priority ? 'eager' : 'lazy'"
        decoding="async"
      />
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "ProductBanner",
  props: {
    content: { type: Object, required: true },
    priority: { type: Boolean, default: false },
  },
  computed: {
    ...mapGetters("layout", ["isMobile"]),
    desktopImage() {
      return this.cleanImagePath(this.content?.desktop);
    },
    mobileImage() {
      return this.cleanImagePath(this.content?.mobile);
    },
    activeImage() {
      return this.isMobile
        ? this.mobileImage || this.desktopImage
        : this.desktopImage || this.mobileImage;
    },
    imageWidth() {
      return this.isMobile ? 800 : 1600;
    },
    imageHeight() {
      return this.isMobile ? 300 : 450;
    },
  },
  methods: {
    cleanImagePath(image) {
      return image ? String(image).replace(/^\/public/, "") : "";
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.product-banner-item,
.slide-img {
  width: 100%;
  height: 100%;
}

.slide-img {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
}

.banner-media {
  display: block;
  width: 100%;
  height: 450px;
  object-fit: cover;
}

@media (max-width: 767px) {
  .banner-media {
    height: 300px;
  }
}
</style>
