<template>
  <template v-if="isSplitScreen && !isMobile">
    <!-- Ad Section — split-screen -->
    <section
      class="mb-15 mt-15 ads-section ads-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      :style="splitScreenCssVars"
    >
      <div class="custom-container2">
        <div class="row">
          <div v-for="(ad, index) in cleanAds" :key="`ad-${index}`" :class="`col-lg-${ad.column}`">
            <a :href="ad.url" target="_blank" class="d-block mb-30 mh-294">
              <v-lazy-image class="w-100 lmh-294" :src="ad.image" :alt="index" />
            </a>
          </div>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <!-- Ad Section -->
    <section class="mb-15 mt-15 ads-section home-page-section" :style="cssVars">
      <div class="custom-container2">
        <div class="row">
          <div v-for="(ad, index) in cleanAds" :key="`ad-${index}`" :class="`col-lg-${ad.column}`">
            <a :href="ad.url" target="_blank" class="d-block mb-30 mh-294">
              <v-lazy-image class="w-100 lmh-294" :src="ad.image" :alt="index" />
            </a>
          </div>
        </div>
      </div>
    </section>
  </template>
</template>
<script>
import VLazyImage from "v-lazy-image";
import { cleanMediaPath, normalizeSectionProps, sectionBgImageUrl } from "@/utils/sectionProps";
import { mapGetters } from "vuex";
export default {
  name: "AdsSection",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    content: {
      type: String,
      required: false,
    },
    properties: {
      type: Array,
      required: false,
    },
  },
  computed: {
    sectionStyleProps() {
      return normalizeSectionProps(this.properties);
    },

    cssVars() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": sectionBgImageUrl(p.bg_image),
        "--section-background-image-position": p.background_position,
        "--section-background-image-size": p.background_size,
        "--section-background-image-repeat": p.background_repeat,
        "--section-padding": `${p.padding_top +
          "px " +
          p.padding_right +
          "px " +
          p.padding_bottom +
          "px " +
          p.padding_left +
          "px"
          }`,
        "--section-margin": `${p.margin_top +
          "px " +
          p.margin_right +
          "px " +
          p.margin_bottom +
          "px " +
          p.margin_left +
          "px"
          }`,
        "--button-color": p.btn_color,
        "--button-background-color": p.btn_bg_color,
        "--button-border":
          p.btn_border != null ? p.btn_border + "px solid" : 0 + "px",
        "--button-border-color": p.btn_border_color,
        "--button-hover-border-color": p.btn_border_hover_color,
        "--button-hover-bg-color": p.btn_bg_hover_color,
        "--button-hover-color": p.btn_hover_color,
      };
    },

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    splitScreenCssVars() {
      return {
        ...this.cssVars,
        "--section-background-color": "transparent",
        "--section-background-image": "none",
      };
    },

    cleanAds() {
      const p = this.sectionStyleProps;
      return (p.ads ?? []).map(ad => ({
        ...ad,
        image: cleanMediaPath(ad.image),
      }));
    },
  },
};
</script>
<style scoped>
.ads-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.ads-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.force-mobile-layout .row > [class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.mh-294 {
  height: 294px;
  max-height: 294px;
  overflow: hidden;
}

.mh-294 :deep(.lmh-294),
.mh-294 :deep(.v-lazy-image) {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
</style>
