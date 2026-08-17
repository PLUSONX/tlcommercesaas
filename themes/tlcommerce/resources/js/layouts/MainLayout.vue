<template>
  <div :class="layoutClass">
    <preloader v-if="!layoutReady" :loading="true" />
    <component
      v-else
      :is="activeShell"
      :preloader-loading="preloaderLoading"
      :top-bar-banner-properties="top_bar_banner_properties"
      :site-properties="data.site_properties"
      :mode="mode"
      :cart-item="cartItem"
      :wishlist-item="wishlistItem"
      :compare-item="compareItem"
      :header-logo-style="headerLogoStyle"
      :header-menu-style="headerMenuStyle"
      :header-style="headerStyle"
      :menu-items-loading="MenuItemsLoading"
      :currencies="data.currencies"
      :languages="data.languages"
      :mega-categories="data.megaCategories"
      :header-bottom-menu="headerBottomMenu"
      :show-route-outlet="showRouteOutlet"
      :outlet-key="outletKey"
      :is-single-product="isSingleProduct"
      :footer-style="footerStyle"
      :footer-bg-img="footerBgImg"
      :widget-html="widget_html"
      :widget-options="widget_options"
      :social-style="socialStyle"
      :subscription-form-style="subscriptionFormStyle"
      :gdpr-properties="gdpr_properties"
      :dark-light-status="dark_light_status"
      :show-split-screen-company-footer="showSplitScreenCompanyFooter"
      @change-language-currency="setCurrencyLanguage"
      @logout-customer="logoutCustomer"
      @toggle-dark="toggleDark"
    />

    <!--Cookie Consent (default layout + split-screen desktop)-->
    <gdpr v-if="gdpr_properties != null && gdpr_properties.gdpr_status == 1 && !(isFeaturePaneLayout && isMobile)"
      :properties="gdpr_properties"></gdpr>

    <!-- Website popup (shared — teleported to body for correct stacking on all layouts) -->
    <teleport to="body">
      <CModal :visible="visibleWebsitePopup" alignment="center" class="website-popup-modal"
        @close="visibleWebsitePopup = false">
        <CModalBody class="modal-body p-0 position-relative website-popup-modal-body rounded-0">
          <button class="btn-circle custom-modal-btn position-absolute size-35" @click="closePopupModal()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
          <div class="m-0" v-html="website_popup_properties?.website_popup_content"></div>
          <subscribe-form class="mt-20" v-if="website_popup_properties?.website_popup_subscribe_status == 1" />
        </CModalBody>
      </CModal>
    </teleport>
  </div>
</template>



<script>
import { mapState, mapGetters, mapActions } from "vuex";
import config from "../config.js";
const axios = require("axios").default;
import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import { safeGetItem } from "@/utils/safeStorage";
import { scheduleBackgroundRefresh } from "@/utils/scheduleBackgroundRefresh";
import { resolveLayoutShell } from "./shells";
import {
  CModal,
  CButton,
  CModalHeader,
  CModalTitle,
  CModalBody,
} from "@coreui/vue";

const Gdpr = defineAsyncComponent(() => import("@/components/ui/Gdpr.vue"));
const Preloader = defineAsyncComponent(() =>
  import("@/components/ui/Preloader.vue")
);
const SubscribeForm = defineAsyncComponent(() =>
  import("@/components/widget/SubscribeForm.vue")
);

// import ProductPage from '@/views/products/index.vue';

function readBootstrapSiteProperties() {
  try {
    return window.__TLC_BOOTSTRAP__?.siteProperties ?? null;
  } catch (e) {
    return null;
  }
}

function readStorefrontBootstrap() {
  try {
    return window.__TLC_BOOTSTRAP__ ?? null;
  } catch (e) {
    return null;
  }
}

function hasUsableBootstrapSiteData(boot) {
  return !!(
    boot?.siteProperties &&
    typeof boot.siteProperties === "object" &&
    Object.keys(boot.siteProperties).length
  );
}

export default {
  name: "MainLayout",
  components: {
    CModal,
    CButton,
    CModalHeader,
    CModalTitle,
    CModalBody,
    Gdpr,
    SubscribeForm,
    Preloader,
  },
  setup() {
    const store = useStore();
    const boot = readStorefrontBootstrap();
    const hasBootstrapSite = hasUsableBootstrapSiteData(boot);

    const data = reactive({
      site_properties:
        store.state.siteProperties ||
        readBootstrapSiteProperties() ||
        {},
      languages: boot?.languages || [],
      currencies: boot?.currencies || [],
      megaCategories: [],
    });

    if (boot?.siteSettings && !store.state.siteSettings) {
      store.dispatch("siteSettings", boot.siteSettings);
    }
    if (hasBootstrapSite && !store.state.siteProperties) {
      store.dispatch("siteProperties", boot.siteProperties);
    }

    /**
     * Get site properties
     */
    function getSiteProperties() {
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": safeGetItem("locale") || "en",
      };
      axios
        .post("/api/v1/ecommerce-core/site-properties", null, {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            data.site_properties = response.data.siteProperties;
            data.languages = response.data.languages;
            data.currencies = response.data.currencies;
            store.dispatch("siteSettings", response.data.site_settings);
            store.dispatch("siteProperties", response.data.siteProperties);
          }
        })
        .catch((error) => { });
    }

    if (hasBootstrapSite) {
      scheduleBackgroundRefresh(getSiteProperties);
    } else {
      getSiteProperties();
    }
    // function getSiteProperties() {
    //   console.log("-------getSiteProperties method called-----")
    //   const headers = {
    //     "Content-Type": "application/json",
    //     "Accept-Language": localStorage.getItem("locale") || "en",
    //   };
    //   axios
    //     .get("/api/v1/ecommerce-core/site-properties", {
    //       headers: headers,
    //     })
    //     .then((response) => {
    //       if (response.data.success) {
    //         console.log("response site_properties: ", response.data);
    //         data.site_properties = response.data.siteProperties;
    //         data.languages = response.data.languages;
    //         data.currencies = response.data.currencies;
    //         store.dispatch("siteSettings", response.data.site_settings);
    //         store.dispatch("siteProperties", response.data.siteProperties);
    //       }
    //     })
    //     .catch((error) => {});
    // }
    /**
     * Get Mega categories
     */
    function getMegacategories() {
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": safeGetItem("locale") || "en",
      };

      axios
        .post("/api/v1/ecommerce-core/mega-categories", {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {

            // console.log("category in mainlayout.vue: ", response);

            data.megaCategories = response.data.data;
          }
        })
        .catch((error) => {
          data.megaCategories = [];
        });
    }

    return {
      data,
      getSiteProperties,
      getMegacategories,
    };
  },

  provide() {
    return {
      resetRouteOutlet: async () => {
        await this.resetRouteOutlet();
      },
    };
  },

  data() {
    return {
      config: config,
      MenuItemsLoading: true,
      headerBottomMenu: [],
      leftMenuItems: [],
      rightMenuItems: [],

      footerLeftMenus: [],
      footerLeftTitle: "",

      footerRightMenus: [],
      footerRightTitle: "",

      footerRightMenus: [],
      contactInfo: {},
      subscription: {
        widget_title: "",
        newsletter_short_desc: "",
      },

      widget_options: [],
      widget_html: "",
      headerStyle: {},
      headerLogoStyle: {},
      headerMenuStyle: {},
      footerStyle: {},
      socialStyle: {},
      subscriptionFormStyle: {},
      footerBgImg: "#",
      isSingleProduct: true,
      outletKey: 0,
      showRouteOutlet: true,
      load_complete: false,

      visibleWebsitePopup: false,
      top_bar_banner_properties: null,
      gdpr_properties: null,
      website_popup_properties: null,
      dark_light_status: "0",

      resizeObserver: null,
      mobileBreakpoint: 768,  // Customizable breakpoint
      resizeTimeout: null,  // Add this for debounce
    };
  },
  //   watch: {
  //   isSplitScreen: {
  //     immediate: true,
  //     handler(newVal) {
  //       if (newVal) {
  //         // Force mobile view when split screen is active
  //         this.$nextTick(() => {
  //           // Any additional logic to maintain mobile view
  //         });
  //       }
  //     }
  //   },

  //   $route() {
  //     // Ensure mobile view persists on route change
  //     if (this.isSplitScreen) {
  //       this.$nextTick(() => {
  //         // Re-apply mobile view styles if needed
  //       });
  //     }
  //   }
  // },
  computed: {
    ...mapState({
      preloaderLoading: (state) => state.preloaderLoading,
      mode: (state) => state.mode,
      customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
      wishlistItem: (state) =>
        state.customerDashboardInfo != null
          ? state.customerDashboardInfo.total_wishlisted_product : 0,
      cartItem: (state) =>
        Array.isArray(state.cart) && state.cart.length
          ? state.cart.reduce((a, b) => a + (b.quantity || 0), 0)
          : 0,
      compareItem: (state) =>
        Array.isArray(state.compareItems) ? state.compareItems.length : 0,
      productPageSuppressCompanyFooter: (state) =>
        state.productPageSuppressCompanyFooter,
    }),

    ...mapState('layout', {
      layoutLoading: (state) => state.loading,
      hasFetchedLayout: (state) => state.hasFetched,
    }),

    ...mapGetters('layout', [
      'layoutType',
      'isSplitScreen',
      'isFeaturePaneLayout',
      'isMobile',
    ]),

    layoutReady() {
      // Server bootstrap sets hasFetched immediately; API refresh is non-blocking.
      return this.hasFetchedLayout && !this.layoutLoading;
    },

    activeShell() {
      return resolveLayoutShell(this.layoutType);
    },

    layoutClass() {
      if (this.layoutType === 'split_screen') return 'layout-split-screen';
      if (this.layoutType === 'modern') return 'layout-modern';
      return 'layout__two';
    },

    showSplitScreenCompanyFooter() {
      return !this.productPageSuppressCompanyFooter;
    },
  },
  // computed: {

  //   isMobile() {
  //     const splitScreen = this.$store.getters['layout/isSplitScreen'];
  //     const actualMobile = this.$store.getters['layout/isMobile'];

  //     if (splitScreen) {
  //       return true;
  //     }

  //     return actualMobile;
  //   },
  //   ...mapState({
  //     preloaderLoading: (state) => state.preloaderLoading,
  //     mode: (state) => state.mode,
  //     customerToken: (state) => {
  //       // console.log("state:", state);
  //       // console.log("state.customerToken:", state.customerToken)
  //       return state.customerToken;
  //     },
  //     // customerToken: (state) => state.customerToken,
  //     isCustomerLogin: (state) => state.isCustomerLogin,
  //     wishlistItem: (state) =>
  //       state.customerDashboardInfo != null
  //         ? state.customerDashboardInfo.total_wishlisted_product
  //         : 0,
  //     cartItem: (state) =>
  //       state.cart.length ? state.cart.reduce((a, b) => a + b.quantity, 0) : 0,
  //     compareItem: (state) =>
  //       state.compareItems.length ? state.compareItems.length : 0,
  //   }),

  //   // NEW: Layout computed properties
  //   ...mapGetters('layout', [
  //     'isSplitScreen',
  //     'splitScreenSettings',
  //     'contentPosition',
  //     'featureImagePath',
  //     // 'isMobile'
  //   ]),

  //   featureComponent() {
  //     const type = 'banner';

  //     if (type == 'banner') {
  //       return () => import('@/components/features/BannerFeature.vue');
  //     }

  //     return null;
  //   },

  //   featureSideClass() {
  //     return this.contentPosition === 'left' ? 'order-2' : 'order-1';
  //   },

  //   featureStyle() {
  //     // You can add dynamic styling based on splitScreenSettings
  //     return {
  //       backgroundColor: this.splitScreenSettings?.background_color || '#f8f9fa'
  //     };
  //   },

  //   layoutClass() {
  //     // if (this.isMobile) {
  //     //   return 'layout__two';
  //     // }
  //     return this.isSplitScreen ? 'layout-split-screen' : 'layout__two';
  //   },

  //   containerStyle() {
  //     if (!this.isSplitScreen || this.isMobile) return {};

  //     return {
  //       display: 'grid',
  //       gridTemplateColumns: '1fr 1fr',
  //       minHeight: '100vh'
  //     };
  //   },

  //   contentSideClass() {
  //     return this.contentPosition === 'left' ? 'order-1' : 'order-2';
  //   },

  //   featureSideClass() {
  //     return this.contentPosition === 'left' ? 'order-2' : 'order-1';
  //   },

  //   contentStyle() {
  //     return {
  //       backgroundColor: '#ffffff',
  //       overflowY: 'auto'
  //     };
  //   },

  //   featureStyle() {
  //     if (!this.splitScreenSettings) return {};

  //     return {
  //       backgroundColor: this.splitScreenSettings.background_color || '#ffffff',
  //       backgroundImage: this.splitScreenSettings.feature_image
  //         ? `url(${this.splitScreenSettings.feature_image})`
  //         : 'none',
  //       backgroundSize: 'cover',
  //       backgroundPosition: 'center'
  //     };
  //   },

  //   featureComponent() {
  //     if (!this.splitScreenSettings) return null;

  //     const componentMap = {
  //       'banner': 'BannerFeature',
  //       'product': 'ProductFeature',
  //       'video': 'VideoFeature'
  //     };

  //     return componentMap[this.splitScreenSettings.feature_type] || 'BannerFeature';
  //   }

  // },
  // computed: mapState({
  //   preloaderLoading: (state) => state.preloaderLoading,
  //   mode: (state) => state.mode,
  //   customerToken: (state) => state.customerToken,
  //   isCustomerLogin: (state) => state.isCustomerLogin,
  //   wishlistItem: (state) =>
  //     state.customerDashboardInfo != null
  //       ? state.customerDashboardInfo.total_wishlisted_product
  //       : 0,
  //   cartItem: (state) =>
  //     state.cart.length ? state.cart.reduce((a, b) => a + b.quantity, 0) : 0,
  //   compareItem: (state) =>
  //     state.compareItems.length ? state.compareItems.length : 0,
  // }),

  mounted() {
    var body = document.querySelector("body");
    body.className = this.mode == "dark" ? "dark" : "";
    this.initHeaderMenus();
    scheduleBackgroundRefresh(() => this.getThemeStyle());
    scheduleBackgroundRefresh(() => this.getFooterWidget(), 2000);

    if (!(this.isFeaturePaneLayout && this.isMobile)) {
      scheduleBackgroundRefresh(() => this.getMegacategories(), 1500);
    }

    if (this.isCustomerLogin) {
      setTimeout(() => {
        this.checkCustomerAuthentication();
        setInterval(this.checkCustomerAuthentication, 1000 * 60);
      }, 500);
    }
    this.$store.state.$t = this.translateLanguage;

    this.initLayout();
    window.addEventListener('resize', this.handleResize);
    this.initResizeObserver();
    this.updateSplitScreenMobileScrollLock();
  },

  beforeUnmount() {
    window.removeEventListener('resize', this.handleResize);

    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
    }

    if (this.resizeTimeout) {
      clearTimeout(this.resizeTimeout);
    }

    document.documentElement.classList.remove('split-screen-mobile-locked');
  },

  methods: {
    async resetRouteOutlet() {
      this.showRouteOutlet = false;
      await this.$nextTick();
      // Fail-safe: never leave the router outlet blank if history/route
      // does not update (common in Instagram in-app browsers).
      setTimeout(() => {
        this.showRouteOutlet = true;
      }, 400);
    },

    // Your existing methods...
    ...mapActions('layout', ['fetchActiveLayout', 'updateMobileView']),

    // NEW: Mobile detection methods
    checkMobileView() {
      const isMobile = window.innerWidth < this.mobileBreakpoint;
      this.updateMobileView(isMobile);
    },

    handleResize() {
      clearTimeout(this.resizeTimeout);
      this.resizeTimeout = setTimeout(() => {
        this.checkMobileView();
      }, 100);
    },

    async initLayout() {
      this.checkMobileView();
      const hasBootstrapLayout = !!(this.$store.state.layout?.activeLayout?.type);
      if (hasBootstrapLayout) {
        scheduleBackgroundRefresh(() => this.fetchActiveLayout());
      } else {
        await this.fetchActiveLayout();
      }
      this.checkMobileView();
    },

    initResizeObserver() {
      if ('ResizeObserver' in window) {
        this.resizeObserver = new ResizeObserver(() => {
          this.checkMobileView();
        });
        this.resizeObserver.observe(document.body);
      }
    },

    updateSplitScreenMobileScrollLock() {
      const shouldLock = this.isFeaturePaneLayout && this.isMobile;
      document.documentElement.classList.toggle('split-screen-mobile-locked', shouldLock);
    },

    translateLanguage(val) {
      return this.$t(val);
    },



    /**
     * Check customer authentication
     */
    checkCustomerAuthentication() {
      // console.log("checkCustomerAuthentication method called!!!");
      // console.log("customer token: ", this.customerToken);

      const fullUrl = "/api/v1/ecommerce-core/auth/customer-refresh-auth";
      // const fullUrl = "/api/v1/ecommerce-core/auth/test-refresh";
      // console.log("🌐 Full URL:", fullUrl);
      // console.log("🏠 Base URL:", axios.defaults.baseURL);
      // console.log("🔗 Complete URL:", window.location.origin + fullUrl);

      axios
        .post(fullUrl, null, {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          // console.log("✅ Response received:", response);

          if (response.data.success) {
            this.$store.dispatch("customerLogin", response.data);
            this.$store.dispatch("getCustomerCartItems");
          } else {
            console.log(".then triggers customer Logout");
            this.$store.dispatch("customerLogout");
          }
        })
        .catch((error) => {
          console.log("❌ Error caught:", error);
          console.log("Error response:", error.response);
          console.log("Error status:", error.response?.status);
          console.log("Error data:", error.response?.data);
        });
    },
    // checkCustomerAuthentication() {
    //   console.log("checkCustomerAuthentication method called!!!");
    //   console.log("customer token: ", this.customerToken);
    //   axios
    //     // .get("/api/v1/ecommerce-core/auth/customer-refresh-auth", {
    //     //   headers: {
    //     //     Authorization: `Bearer ${this.customerToken}`,
    //     //   },
    //     // })
    //     .get("/api/v1/ecommerce-core/customer-refresh-auth", {
    //       headers: {
    //         Authorization: `Bearer ${this.customerToken}`,
    //       },
    //     })
    //     .then((response) => {
    //       if (response.data.success) {
    //         this.$store.dispatch("customerLogin", response.data);
    //         this.$store.dispatch("getCustomerCartItems");
    //       } else {
    //         console.log(".then triggers customer Logout");
    //         this.$store.dispatch("customerLogout");
    //       }
    //     })
    //     .catch((error) => {
    //         console.log(".catch triggers customer Logout");
    //       this.$store.dispatch("customerLogout");
    //     });
    // },

    initHeaderMenus() {
      const menus = readStorefrontBootstrap()?.menus;
      if (this.applyMenusPayload(menus)) {
        scheduleBackgroundRefresh(() => this.getAllMenusForEcommerceHome());
        return;
      }
      this.getAllMenusForEcommerceHome();
    },
    applyMenusPayload(payload) {
      if (!payload?.success) {
        return false;
      }
      this.rightMenuItems = payload.header_top_right_menus?.menus ?? [];
      this.leftMenuItems = payload.header_top_left_menus?.menus ?? [];
      this.headerBottomMenu = payload.header_bottom_middle_menus?.menus ?? [];
      this.MenuItemsLoading = false;
      return true;
    },
    /**
     * Get all ecommerce menus
     */
    getAllMenusForEcommerceHome() {
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };
      axios
        .get("/api/theme/tlcommerce/v1/get-all-menus-for-ecommerce-home", {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            this.applyMenusPayload(response.data);

            this.footerLeftMenus = response.data.footer_widget_left_menus?.menus;
            this.footerLeftTitle =
              response.data.footer_widget_left_menus?.widget_title;

            this.footerRightMenus =
              response.data.footer_widget_right_menus?.menus;
            this.footerRightTitle =
              response.data.footer_widget_right_menus?.widget_title;
          }
        })
        .catch((error) => {
          this.MenuItemsLoading = false;
        });
    },

    /**
     * Get footer widget right menus
     */
    getFooterWidget() {
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };
      axios

        .get("/api/theme/tlcommerce/v1/get-footer-widgets", {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            this.widget_options = response.data.widget_options;
            for (const [key, value] of Object.entries(this.widget_options)) {
              if (key == "address_widget") {
                this.widget_html =
                  this.widget_html +
                  '<div class="col-lg-3 col-sm-6"><' +
                  key +
                  " :" +
                  key +
                  '="widget_options.' +
                  key +
                  '" :footer-style="footerStyle"/><social_links class="widget" :social_links="widget_options.address_widget.social_links" :social-style="socialStyle"/></div>';
              } else if (key == "newsletter_widget") {
                this.widget_html =
                  this.widget_html +
                  '<div class="col-lg-3 col-sm-6"><' +
                  key +
                  " :" +
                  key +
                  '="widget_options.' +
                  key +
                  '" :subscription-form-style="subscriptionFormStyle" :footer-style="footerStyle"/></div>';
              } else if (key == "footer_left_menu") {
                this.widget_html =
                  this.widget_html +
                  '<div class="col-lg-3 col-sm-6"><' +
                  key +
                  " :" +
                  key +
                  '="widget_options.' +
                  key +
                  '" :footer-style="footerStyle"/></div>';
              } else if (key == "footer_right_menu") {
                this.widget_html =
                  this.widget_html +
                  '<div class="col-lg-3 col-sm-6"><' +
                  key +
                  " :" +
                  key +
                  '="widget_options.' +
                  key +
                  '" :footer-style="footerStyle"/></div>';
              } else {
                this.widget_html =
                  this.widget_html +
                  '<div class="col-lg-3 col-sm-6"><' +
                  key +
                  " :" +
                  key +
                  '="widget_options.' +
                  key +
                  '"/></div>';
              }
            }
          }
        })
        .catch((error) => { });
    },

    /**
     * Get theme style
     */
    getThemeStyle() {

      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };

      axios
        .get("/api/theme/tlcommerce/v1/get-theme-style", {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            this.headerStyle = response.data.headerOptions;
            this.headerLogoStyle = response.data.headerLogoStyles;
            this.headerMenuStyle = response.data.headerMenuStyle;
            this.footerStyle = response.data.footerStyle;
            this.socialStyle = response.data.socialStyle;
            this.subscriptionFormStyle = response.data.subscriptionFormStyle;

            this.top_bar_banner_properties =
              response.data.top_bar_banner_properties;
            this.website_popup_properties =
              response.data.website_popup_properties;
            this.gdpr_properties = response.data.gdpr_properties;
            //Dark light switcher status
            this.dark_light_status = response.data.dark_light_status;
            //website popup
            if (
              this.website_popup_properties != null &&
              this.website_popup_properties.website_popup_status == 1
            ) {
              let cooke_value = null;
              const cookies = document.cookie.split("; ");
              for (const cookie of cookies) {
                const [name, value] = cookie.split("=");
                if (name === "website_popup") {
                  cooke_value = value;
                }
              }
              if (cooke_value != null && cooke_value == 1) {
                this.visibleWebsitePopup = false;
              } else {
                this.visibleWebsitePopup = true;
              }
            }
          }
        })
        .catch((error) => {
          console.error("=== API FAILED ===");
          console.error("Error:", error);
          console.error("Error response:", error.response);
        });

      // console.log("=== getThemeStyle END (async call initiated) ===");

    },
    // getThemeStyle() {
    //   const headers = {
    //     "Content-Type": "application/json",
    //     "Accept-Language": localStorage.getItem("locale") || "en",
    //   };

    //   axios
    //     .get("/api/theme/tlcommerce/v1/get-theme-style", {
    //       headers: headers,
    //     })
    //     .then((response) => {

    //       console.log("=== API Response ===", response.data);
    //       console.log("Banner properties:", response.data.top_bar_banner_properties);

    //       if (response.data.success) {
    //         this.headerStyle = response.data.headerOptions;
    //         this.headerLogoStyle = response.data.headerLogoStyles;
    //         this.headerMenuStyle = response.data.headerMenuStyle;
    //         this.footerStyle = response.data.footerStyle;
    //         this.socialStyle = response.data.socialStyle;
    //         this.subscriptionFormStyle = response.data.subscriptionFormStyle;

    //         this.top_bar_banner_properties =
    //           response.data.top_bar_banner_properties;
    //         this.website_popup_properties =
    //           response.data.website_popup_properties;
    //         this.gdpr_properties = response.data.gdpr_properties;
    //         //Dark light switcher status
    //         this.dark_light_status = response.data.dark_light_status;
    //         //website popup
    //         if (
    //           this.website_popup_properties != null &&
    //           this.website_popup_properties.website_popup_status == 1
    //         ) {
    //           let cooke_value = null;
    //           const cookies = document.cookie.split("; ");
    //           for (const cookie of cookies) {
    //             const [name, value] = cookie.split("=");
    //             if (name === "website_popup") {
    //               cooke_value = value;
    //             }
    //           }
    //           if (cooke_value != null && cooke_value == 1) {
    //             this.visibleWebsitePopup = false;
    //           } else {
    //             this.visibleWebsitePopup = true;
    //           }
    //         }
    //       }
    //     })
    //     .catch((error) => {
    //        console.error("=== API FAILED ===");
    //   console.error("Error:", error);
    //   console.error("Error response:", error.response);
    //     });

    //       console.log("=== getThemeStyle END (async call initiated) ===");

    // },

    /**
     * Set Language
     * Set Currency
     */
    setCurrencyLanguage(lang, currency) {
      localStorage.setItem("locale", lang);
      localStorage.setItem("currency", JSON.stringify(currency));
      location.reload();
    },

    /**
     * Will logout customer
     */
    logoutCustomer() {
      axios
        .get("/api/v1/ecommerce-core/auth/customer-logout", {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.$toast.success(this.$t("Logout successful"));
            this.$store.dispatch("customerLogout").then(() => {
              this.$store.dispatch("flushCartData");
              this.$router.push("/");
            });
          } else {
            this.$store.dispatch("customerLogout").then(() => {
              this.$store.dispatch("flushCartData");
              this.$router.push("/");
            });
          }
        })
        .catch((error) => {
          this.$store.dispatch("customerLogout").then(() => {
            this.$store.dispatch("flushCartData");
            this.$router.push("/");
          });
        });
    },

    /**
     * Toggle dark mood
     */
    toggleDark(e) {
      if (e.target.checked) {
        localStorage.setItem("mode", "dark");
        this.$store.dispatch("changeScreenMode", "dark");
      } else {
        localStorage.removeItem("mode");
        this.$store.dispatch("changeScreenMode", null);
      }
      var body = document.querySelector("body");
      body.className = e.target.checked ? "dark" : "";
    },
    /**
     * Close website popup modal
     */
    closePopupModal() {
      const now = new Date();
      const expires = new Date(now.getTime() + 2 * 60 * 60 * 1000);
      document.cookie = `website_popup=1; expires=${expires.toUTCString()}; path=/`;
      this.visibleWebsitePopup = false;
    },
  },

  watch: {
    isFeaturePaneLayout() {
      this.updateSplitScreenMobileScrollLock();
    },

    isMobile() {
      this.updateSplitScreenMobileScrollLock();
    },

    $route(to, from) {
      if (from?.name === "product" && to.name === "home" && this.showRouteOutlet) {
        this.outletKey += 1;
      }

      if (this.$route.name === "product") {
        this.isSingleProduct = true;
      } else {
        this.isSingleProduct = false;
      }

      this.$nextTick(() => {
        this.showRouteOutlet = true;
      });
    },
  },
  beforeDestroy() {
    document.removeEventListener("click", this.close);
  },
};
</script>

<style scoped lang="scss">
.modal.website-popup-modal .modal-content {
  border-radius: 0px !important;
}

:deep(.website-popup-modal .modal-content) {
  border-radius: 6px !important;
  overflow: visible !important;
}

.custom-modal-btn {
  top: -15px;
  right: -15px;
}

.layout-split-screen,
.layout-modern {
  height: 100vh;
  height: 100dvh;
  overflow: hidden;
}

/* Mobile feature-pane host shell (class lives on MainLayout root) */
@media (max-width: 768px) {
  .layout-split-screen,
  .layout-modern {
    display: block !important;
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    overflow: hidden;
  }
}
</style>

<style lang="scss">
html.split-screen-mobile-locked,
html.split-screen-mobile-locked body {
  overflow: hidden;
  height: 100%;
  overscroll-behavior: none;
}
</style>
