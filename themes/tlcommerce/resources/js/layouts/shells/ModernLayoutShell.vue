<template>
  <div class="modern-layout-container split-screen-container" :style="containerStyle">
    <preloader :loading="preloaderLoading"></preloader>
    <div class="split-screen-content" :class="[contentSideClass, 'force-mobile-view']" :style="contentStyle">
      <div
        class="modern-header-host"
        :class="{
          'modern-header-host--home': isHomePage,
          'modern-header-host--page': !isHomePage,
        }"
      >
        <div class="split-screen-search-inline-host modern-search-inline-host"></div>
        <modern-header :key="$route.fullPath" :site-properties="siteProperties" :mode="mode"
          :header-logo-style="headerLogoStyle" :cart-item="cartItem" :header-style="headerStyle"
          :header-menu-style="headerMenuStyle" :currencies="currencies" :languages="languages"
          :data-loading="menuItemsLoading" :page-header-image="modernPageHeaderImage"
          @change-language-currency="forwardChangeLanguageCurrency" />
      </div>

      <div ref="splitContentScroll" class="content-split-nudge">
        <router-view v-slot="{ Component, route }">
          <component v-if="showRouteOutlet && Component" :is="Component"
            :key="`${route.name}-${outletKey}`" />
        </router-view>
      </div>

      <company-footer v-if="showSplitScreenCompanyFooter" class="split-screen-company-footer" />

      <gdpr v-if="isMobile && gdprProperties != null && gdprProperties.gdpr_status == 1"
        class="split-screen-gdpr" :properties="gdprProperties"></gdpr>

    </div>

    <div class="split-screen-feature" :class="featureSideClass" :style="featureStyle">
      <BannerFeature v-if="!isMobile" :settings="splitScreenSettings" :imagePath="featureImagePath"
        :cart-item="cartItem" :header-style="headerStyle" :header-menu-style="headerMenuStyle"
        :currencies="currencies" :languages="languages" :data-loading="menuItemsLoading"
        @change-language-currency="forwardChangeLanguageCurrency" />

      <!-- Dark Light Switcher -->
      <div class="floating-mode-switcher-wrap" v-if="darkLightStatus == '1'">
        <label class="dl-switch">
          <input class="dark-looks-mode-changer" @change="$emit('toggle-dark', $event)" :checked="mode == 'dark'"
            type="checkbox" />
          <span class="dl-slider"></span>
          <span class="dl-light">Light</span>
          <span class="dl-dark">Dark</span>
        </label>
      </div>
      <!-- End Dark Light Switcher -->

    </div>
  </div>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { mapGetters } from "vuex";

const Preloader = defineAsyncComponent(() =>
  import("@/components/ui/Preloader.vue")
);
const Gdpr = defineAsyncComponent(() => import("@/components/ui/Gdpr.vue"));
const BannerFeature = defineAsyncComponent(() =>
  import("@/components/features/BannerFeature.vue")
);
const ModernHeader = defineAsyncComponent(() =>
  import("@/components/layout/modern/ModernHeader.vue")
);
const CompanyFooter = defineAsyncComponent(() =>
  import("@/components/ui/CompanyFooter.vue")
);

export default {
  name: "ModernLayoutShell",
  inheritAttrs: false,
  components: {
    Preloader,
    Gdpr,
    BannerFeature,
    ModernHeader,
    CompanyFooter,
  },
  emits: ["change-language-currency", "toggle-dark"],
  props: {
    preloaderLoading: { type: Boolean, default: false },
    siteProperties: { type: Object, default: () => ({}) },
    mode: { type: String, default: null },
    headerLogoStyle: { type: Object, default: () => ({}) },
    cartItem: { type: Number, default: 0 },
    headerStyle: { type: Object, default: () => ({}) },
    headerMenuStyle: { type: Object, default: () => ({}) },
    currencies: { type: Array, default: () => [] },
    languages: { type: Array, default: () => [] },
    menuItemsLoading: { type: Boolean, default: true },
    showRouteOutlet: { type: Boolean, default: true },
    outletKey: { type: Number, default: 0 },
    showSplitScreenCompanyFooter: { type: Boolean, default: true },
    gdprProperties: { type: Object, default: null },
    darkLightStatus: { type: String, default: "0" },
  },

  provide() {
    return {
      setModernPageHeaderImage: (image) => {
        this.modernPageHeaderImage = image || null;
      },
    };
  },
  data() {
    return {
      modernPageHeaderImage: null,
    };
  },
  computed: {
    ...mapGetters("layout", [
      "splitScreenSettings",
      "effectiveContentPosition",
      "featureImagePath",
      "splitRatio",
      "isMobile",
    ]),

    isHomePage() {
      return this.$route.name === "home";
    },

    containerStyle() {
      // This shell only mounts for layoutType === 'modern'
      if (this.isMobile) return {};

      const { content, feature } = this.splitRatio;
      const columns =
        this.effectiveContentPosition === "left"
          ? `${content}fr ${feature}fr`
          : `${feature}fr ${content}fr`;

      return {
        display: "grid",
        gridTemplateColumns: columns,
        minHeight: "100vh",
      };
    },

    contentSideClass() {
      return this.effectiveContentPosition === "left" ? "order-1" : "order-2";
    },

    featureSideClass() {
      return this.effectiveContentPosition === "left" ? "order-2" : "order-1";
    },

    contentStyle() {
      return {
        backgroundColor: "#ffffff",
      };
    },

    featureStyle() {
      if (!this.splitScreenSettings) return {};
      return {
        backgroundColor: this.splitScreenSettings.background_color || "#ffffff",
        backgroundImage: this.splitScreenSettings.feature_image
          ? `url(${this.splitScreenSettings.feature_image})`
          : "none",
        backgroundSize: "cover",
        backgroundPosition: "center",
      };
    },

    featureComponent() {
      if (!this.splitScreenSettings) return null;
      const componentMap = {
        banner: "BannerFeature",
        product: "ProductFeature",
        video: "VideoFeature",
      };
      return componentMap[this.splitScreenSettings.feature_type] || "BannerFeature";
    },
  },
  watch: {
    $route() {
      // Clear the previous product hero immediately on navigation. The product
      // page will push the new product thumbnail back in after its API response.
      this.modernPageHeaderImage = null;

      this.$nextTick(() => {
        const scrollEl = this.$refs.splitContentScroll;
        if (scrollEl) {
          scrollEl.scrollTop = 0;
        }
      });
    },
  },
  methods: {
    forwardChangeLanguageCurrency(lang, currency) {
      this.$emit("change-language-currency", lang, currency);
    },
  },
};
</script>

<style scoped lang="scss">
.split-screen-container {
  width: 100%;
  align-items: stretch;
  height: 100vh;
  height: 100dvh;
  overflow: hidden;
  direction: ltr;
}

.split-screen-content {
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 0 !important;
  margin: 0 !important;
  height: 100vh;
  height: 100dvh;
  max-height: 100vh;
  max-height: 100dvh;
  overflow: hidden;
}

.content-split-nudge {
  flex: 1 1 auto;
  min-height: 0;
  width: 100%;
  overflow-y: auto;
  overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
  overscroll-behavior: contain;
  background-color: inherit;
}

.modern-header-host {
  flex-shrink: 0;
  z-index: 20;
}

.modern-header-host--home {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  z-index: 30;
  pointer-events: none;
}

.modern-header-host--home :deep(.modern-header) {
  pointer-events: auto;
}

.modern-header-host--page {
  position: relative;
  flex-shrink: 0;
  width: 100%;
  margin: 0;
  padding: 0;
}

.split-screen-company-footer {
  flex-shrink: 0;
  width: 100%;
}

.split-screen-search-inline-host {
  max-height: 0;
  overflow: hidden;
  flex-shrink: 0;
  transition: max-height 0.25s ease-out;

  &.is-open {
    max-height: 72px;
  }

  :deep(.mobile-search-form) {
    position: relative !important;
    transform: none !important;
    visibility: visible;
  }
}

.split-screen-feature {
  position: sticky;
  top: 0;
  height: 100vh;
  max-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

// .split-screen-feature {
//   position: absolute;
//   display: flex;
//   align-items: center;
//   justify-content: center;
//   position: sticky;
//   top: 0;
//   height: 100vh;
//   // margin-top: 50px;
// }

/* Mobile split-screen: fixed app shell, single inner scroll pane */
@media (max-width: 768px) {
  .split-screen-container {
    display: block !important;
    grid-template-columns: none !important;
    height: 100%;
    overflow: hidden;
  }

  .split-screen-content {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    order: initial !important;
    height: 100% !important;
    max-height: 100% !important;
    overflow: hidden !important;
  }

  .split-screen-feature {
    display: none !important;
  }

  .split-screen-header-sticky--compact {
    position: static;
    flex-shrink: 0;
    z-index: 999;
    background: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  }

  .content-split-nudge {
    flex: 1 1 auto;
    min-height: 0;
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    background-color: inherit;
  }

  .split-screen-gdpr {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
  }
}

.split-screen-content.force-mobile-view {
  /* Remove max-width constraint */
  width: 100%;
  // overflow-y: auto;
  // padding: 0 20px; /* Optional: add some padding */
}

/* Hide desktop elements in split screen */
.force-mobile-view .d-none.d-lg-block {
  display: none !important;
}

.force-show {
  display: block !important;
  visibility: visible !important;
  opacity: 1 !important;
}

.mobile-content-wrapper {
  /* Simulate mobile viewport */
  max-width: 100%;
  width: 100%;
}

/* Hide desktop-only elements in slot content */
.mobile-content-wrapper .d-none.d-lg-block,
.mobile-content-wrapper .d-lg-block {
  display: none !important;
}

/* Force mobile elements to show */
.mobile-content-wrapper .d-block.d-lg-none,
.mobile-content-wrapper .d-lg-none {
  display: block !important;
}

/* Override any media queries that might show desktop elements */
@media (min-width: 992px) {

  .mobile-content-wrapper .d-none.d-lg-block,
  .mobile-content-wrapper .d-lg-block {
    display: none !important;
  }

  .mobile-content-wrapper .d-block.d-lg-none,
  .mobile-content-wrapper .d-lg-none {
    display: block !important;
  }
}

.forced-mobile-context {
  /* 1. Force the container to a mobile width if necessary */
  max-width: 100%;
  /* Or 100% depending on your split design */
  margin: 0 auto;
}

/* 2. Nuclear option: Force hide desktop elements and show mobile elements */
.forced-mobile-context :deep(.d-lg-block),
.forced-mobile-context :deep(.d-xl-block),
.forced-mobile-context :deep(.hide-on-mobile) {
  display: none !important;
}

.forced-mobile-context :deep(.d-none),
.forced-mobile-context :deep(.show-on-mobile) {
  /* Only show it if it's meant to be visible on mobile */
  display: block !important;
}
</style>
