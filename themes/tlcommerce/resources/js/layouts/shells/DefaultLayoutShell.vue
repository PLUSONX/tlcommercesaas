<template>
  <div class="layout__two">
    <preloader :loading="preloaderLoading"></preloader>
    <top-bar-banner :properties="topBarBannerProperties" v-if="
      topBarBannerProperties != null &&
      topBarBannerProperties.topbar_banner_status == 1
    "></top-bar-banner>
    <!-- Header -->
    <header class="header__two love-sticky">
      <header-middle :site-properties="siteProperties" :mode="mode" :cart-item="cartItem"
        :wishlist-item="wishlistItem" :compare-item="compareItem" :header-logo-style="headerLogoStyle"
        :header-menu-style="headerMenuStyle" :data-loading="menuItemsLoading" :currencies="currencies"
        :languages="languages" @change-language-currency="forwardChangeLanguageCurrency"
        @logout-customer="$emit('logout-customer')" class="d-none d-lg-block"></header-middle>
      <header-bottom :data-loading="menuItemsLoading" :mega-categories="megaCategories"
        :menu-items="headerBottomMenu" :header-style="headerStyle" :header-menu-style="headerMenuStyle"
        class="d-none d-lg-block"></header-bottom>
    </header>

    <mobile-header :site-properties="siteProperties" :mode="mode" :cart-item="cartItem"
      :header-style="headerStyle" :header-menu-style="headerMenuStyle" :header-logo-style="headerLogoStyle"
      :currencies="currencies" :languages="languages" :data-loading="menuItemsLoading"
      @change-language-currency="forwardChangeLanguageCurrency"></mobile-header>
    <!-- End Header -->

    <div class="main_content light-bg">
      <router-view v-slot="{ Component, route }">
        <component v-if="showRouteOutlet && Component" :is="Component"
          :key="`${route.name}-${outletKey}`" />
      </router-view>
    </div>

    <StickyFooter v-if="!isSingleProduct" />

    <!-- Footer -->
    <footer :class="footerStyle.custom_footer == 1
      ? 'custom-footer footer footer__two c1-bg'
      : 'footer footer__two c1-bg'
      " :style="{ backgroundImage: `url('${footerBgImg}')` }">
      <!-- Footer Top -->
      <div class="footer-top">
        <div class="custom-container2">
          <div class="row justify-content-between">
            <v-runtime-template :template="widgetHtml"></v-runtime-template>
          </div>
        </div>
      </div>
      <!-- End Footer Top -->
      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <div class="custom-container2">
          <div class="border-top text-center py-4">
            <copyright :site-properties="siteProperties" />
          </div>
        </div>
      </div>
      <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->

    <!--Cookie Consent-->
    <gdpr v-if="gdprProperties != null && gdprProperties.gdpr_status == 1" :properties="gdprProperties"></gdpr>
    <!--End Cookie Consent-->

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

    <BackToTop />
  </div>
</template>

<script>
import { defineAsyncComponent } from "vue";
import VRuntimeTemplate from "vue3-runtime-template";

const HeaderMiddle = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderMiddle.vue")
);
const HeaderBottom = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderBottom.vue")
);
const MobileHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/MobileHeader.vue")
);
const Copyright = defineAsyncComponent(() =>
  import("@/components/ui/Copyright.vue")
);
const StickyFooter = defineAsyncComponent(() =>
  import("@/components/ui/StickyFooter.vue")
);
const Gdpr = defineAsyncComponent(() => import("@/components/ui/Gdpr.vue"));
const TopBarBanner = defineAsyncComponent(() =>
  import("@/components/ui/TopBarBanner.vue")
);
const BackToTop = defineAsyncComponent(() =>
  import("@/components/ui/BackToTop.vue")
);
const Preloader = defineAsyncComponent(() =>
  import("@/components/ui/Preloader.vue")
);
const address_widget = defineAsyncComponent(() =>
  import("@/components/widget/address_widget.vue")
);
const newsletter_widget = defineAsyncComponent(() =>
  import("@/components/widget/newsletter_widget.vue")
);
const social_links = defineAsyncComponent(() =>
  import("@/components/widget/social_links.vue")
);
const footer_left_menu = defineAsyncComponent(() =>
  import("@/components/widget/footer_left_menu.vue")
);
const footer_right_menu = defineAsyncComponent(() =>
  import("@/components/widget/footer_right_menu.vue")
);
const featured_blog_widget = defineAsyncComponent(() =>
  import("@/components/widget/featured_blog_widget.vue")
);
const recent_blog_widget = defineAsyncComponent(() =>
  import("@/components/widget/recent_blog_widget.vue")
);

export default {
  name: "DefaultLayoutShell",
  inheritAttrs: false,
  components: {
    HeaderMiddle,
    HeaderBottom,
    MobileHeader,
    BackToTop,
    Copyright,
    StickyFooter,
    Gdpr,
    TopBarBanner,
    address_widget,
    footer_left_menu,
    footer_right_menu,
    newsletter_widget,
    social_links,
    featured_blog_widget,
    recent_blog_widget,
    VRuntimeTemplate,
    Preloader,
  },
  emits: ["change-language-currency", "logout-customer", "toggle-dark"],
  props: {
    preloaderLoading: { type: Boolean, default: false },
    topBarBannerProperties: { type: Object, default: null },
    siteProperties: { type: Object, default: () => ({}) },
    mode: { type: String, default: null },
    cartItem: { type: Number, default: 0 },
    wishlistItem: { type: Number, default: 0 },
    compareItem: { type: Number, default: 0 },
    headerLogoStyle: { type: Object, default: () => ({}) },
    headerMenuStyle: { type: Object, default: () => ({}) },
    headerStyle: { type: Object, default: () => ({}) },
    menuItemsLoading: { type: Boolean, default: true },
    currencies: { type: Array, default: () => [] },
    languages: { type: Array, default: () => [] },
    megaCategories: { type: Array, default: () => [] },
    headerBottomMenu: { type: Array, default: () => [] },
    showRouteOutlet: { type: Boolean, default: true },
    outletKey: { type: Number, default: 0 },
    isSingleProduct: { type: Boolean, default: false },
    footerStyle: { type: Object, default: () => ({}) },
    footerBgImg: { type: String, default: "#" },
    widgetHtml: { type: String, default: "" },
    /** Exposed as `widget_options` for v-runtime-template footer HTML strings. */
    widgetOptions: { type: [Object, Array], default: () => ({}) },
    socialStyle: { type: Object, default: () => ({}) },
    subscriptionFormStyle: { type: Object, default: () => ({}) },
    gdprProperties: { type: Object, default: null },
    darkLightStatus: { type: String, default: "0" },
  },
  computed: {
    // Names must match strings built in MainLayout.getFooterWidget()
    widget_options() {
      return this.widgetOptions;
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
.footer {
  display: block;
  padding: 0;
}

.main_content {
  min-height: 100vh;
}

.header-btn-group {
  .btn-circle {
    .material-icons {
      font-size: 22px;
    }

    &:hover {

      .icon-wrapper svg,
      .icon-wrapper .icon {
        color: #fff;
      }
    }
  }
}
</style>
