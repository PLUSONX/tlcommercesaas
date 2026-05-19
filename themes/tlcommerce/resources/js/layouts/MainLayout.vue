<template>
  <div :class="layoutClass">
    <template v-if="isSplitScreen && !isMobile">
      <div class="split-screen-container" :style="containerStyle">
        <div class="split-screen-content" :class="[contentSideClass, 'force-mobile-view']" :style="contentStyle">
          <header class="header__two love-sticky" style="margin-bottom: 30px;">

            <header-middle :site-properties="data.site_properties" :mode="mode" :cart-item="cartItem"
              :wishlist-item="wishlistItem" :compare-item="compareItem" :header-logo-style="headerLogoStyle"
              :header-menu-style="headerMenuStyle" :menu-items="rightMenuItems" :data-loading="MenuItemsLoading"
              :right-menu-items="rightMenuItems" :left-menu-items="leftMenuItems"
              @logout-customer="logoutCustomer"></header-middle>

          </header>

          <div class="content-split-nudge">
            <slot></slot>
          </div>

        </div>

        <div class="split-screen-feature" :class="featureSideClass" :style="featureStyle">
          <BannerFeature :settings="splitScreenSettings" :imagePath="featureImagePath" v-if="isSplitScreen" />


          <!--End Cookie Consent-->

          <!-- Dark Light Switcher -->
          <div class="floating-mode-switcher-wrap" v-if="dark_light_status == '1'">
            <label class="dl-switch">
              <input class="dark-looks-mode-changer" @change="toggleDark" :checked="mode == 'dark'" type="checkbox" />
              <span class="dl-slider"></span>
              <span class="dl-light">Light</span>
              <span class="dl-dark">Dark</span>
            </label>
          </div>
          <!-- End Dark Light Switcher -->
          <!--Website popup-->
          <CModal :visible="visibleWebsitePopup" alignment="center" class="website-popup-modal" @close="
            () => {
              visibleWebsitePopup = false;
            }
          ">
            <CModalBody class="modal-body p-0 position-relative website-popup-modal-body rounded-0">
              <button class="btn-circle custom-modal-btn position-absolute size-35" @click="closePopupModal()">
                <base-icon-svg name="close" :width="10" :height="10" />
              </button>
              <div class="m-0" v-html="website_popup_properties.website_popup_content"></div>
              <subscribe-form class="mt-20"
                v-if="website_popup_properties.website_popup_subscribe_status == 1"></subscribe-form>
            </CModalBody>
          </CModal>
          <!--End website popup-->

        </div>
      </div>
    </template>

    <!-- Default Layout (Always on Mobile) -->
    <template v-else>

      <div class="layout__two">
        <preloader :loading="preloaderLoading"></preloader>

        <top-bar-banner :properties="top_bar_banner_properties" v-if="
          top_bar_banner_properties != null &&
          top_bar_banner_properties.topbar_banner_status == 1
        "></top-bar-banner>
        <!-- Header -->
        <header class="header__two love-sticky">

          <header-middle :site-properties="data.site_properties" :mode="mode" :cart-item="cartItem"
            :wishlist-item="wishlistItem" :compare-item="compareItem" :header-logo-style="headerLogoStyle"
            :header-menu-style="headerMenuStyle" class="d-none d-lg-block"></header-middle>

        </header>

        <mobile-header :site-properties="data.site_properties" :mode="mode" :cart-item="cartItem"
          :header-style="headerStyle" :header-menu-style="headerMenuStyle"
          :header-logo-style="headerLogoStyle"></mobile-header>
        <!-- End Header -->

        <div class="main_content light-bg">
          <slot />
        </div>



        <BackToTop />
      </div>

    </template>

    <!--Cookie Consent-->
    <gdpr v-if="gdpr_properties != null && gdpr_properties.gdpr_status == 1" :properties="gdpr_properties"></gdpr>
  </div>
</template>



<script>
import { mapState, mapGetters, mapActions } from "vuex";
import config from "../config.js";
const axios = require("axios").default;
import VRuntimeTemplate from "vue3-runtime-template";

const address_widget = defineAsyncComponent(() =>
  import("@/components/widget/address_widget.vue")
);

const newsletter_widget = defineAsyncComponent(() =>
  import("@/components/widget/newsletter_widget.vue")
);
const social_links = defineAsyncComponent(() =>
  import("@/components/widget/social_links.vue")
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

const HeaderTop = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderTop.vue")
);

const HeaderMiddle = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderMiddle.vue")
);
const HeaderBottom = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderBottom.vue")
);

const MobileHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/MobileHeader.vue")
);

const BackToTop = defineAsyncComponent(() =>
  import("@/components/ui/BackToTop.vue")
);
const Preloader = defineAsyncComponent(() =>
  import("@/components/ui/Preloader.vue")
);

const footer_left_menu = defineAsyncComponent(() =>
  import("@/components/widget/footer_left_menu.vue")
);

const footer_right_menu = defineAsyncComponent(() =>
  import("@/components/widget/footer_right_menu.vue")
);
const SubscribeForm = defineAsyncComponent(() =>
  import("@/components/widget/SubscribeForm.vue")
);

const featured_blog_widget = defineAsyncComponent(() =>
  import("@/components/widget/featured_blog_widget.vue")
);

const recent_blog_widget = defineAsyncComponent(() =>
  import("@/components/widget/recent_blog_widget.vue")
);

const BannerFeature = defineAsyncComponent(() =>
  import("@/components/features/BannerFeature.vue")
);

// import ProductPage from '@/views/products/index.vue';


import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import {
  CModal,
  CButton,
  CModalHeader,
  CModalTitle,
  CModalBody,
} from "@coreui/vue";
import CompanyFooter from "../components/ui/CompanyFooter.vue";

export default {
  name: "MainLayout",
  components: {
    CModal,
    CButton,
    CModalHeader,
    CModalTitle,
    CModalBody,
    HeaderTop,
    HeaderMiddle,
    HeaderBottom,
    MobileHeader,
    BackToTop,
    Copyright,
    StickyFooter,
    Gdpr,
    TopBarBanner,
    SubscribeForm,
    address_widget,
    footer_left_menu,
    footer_right_menu,
    newsletter_widget,
    social_links,
    featured_blog_widget,
    recent_blog_widget,
    VRuntimeTemplate,
    Preloader,
    BannerFeature,
    CompanyFooter
  },
  setup() {
    const data = reactive({
      site_properties: {},
      languages: [],
      currencies: [],
      megaCategories: [],
    });

    const store = useStore();
    getSiteProperties();
    getMegacategories();

    /**
     * Get site properties
     */
    function getSiteProperties() {
      // console.log("-------getSiteProperties method called-----")
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };
      axios
        .post("/api/v1/ecommerce-core/site-properties", null, {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            // console.log("response site_properties: ", response.data);
            data.site_properties = response.data.siteProperties;
            data.languages = response.data.languages;
            data.currencies = response.data.currencies;
            store.dispatch("siteSettings", response.data.site_settings);
            store.dispatch("siteProperties", response.data.siteProperties);
          }
        })
        .catch((error) => { });
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
        "Accept-Language": localStorage.getItem("locale") || "en",
      };

      axios
        .get("/api/v1/ecommerce-core/mega-categories", {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
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

    isMobile() {
      // Get split screen state from store
      const splitScreen = this.$store.getters['layout/isSplitScreen'];
      const actualMobile = this.$store.getters['layout/isMobile'];

      // If split screen is active, always treat as mobile
      if (splitScreen) {
        return true;
      }

      // Otherwise use the actual mobile detection
      return actualMobile;
    },
    ...mapState({
      preloaderLoading: (state) => state.preloaderLoading,
      mode: (state) => state.mode,
      customerToken: (state) => {
        // console.log("state:", state);
        // console.log("state.customerToken:", state.customerToken)
        return state.customerToken;
      },
      // customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
      wishlistItem: (state) =>
        state.customerDashboardInfo != null
          ? state.customerDashboardInfo.total_wishlisted_product
          : 0,
      cartItem: (state) =>
        state.cart.length ? state.cart.reduce((a, b) => a + b.quantity, 0) : 0,
      compareItem: (state) =>
        state.compareItems.length ? state.compareItems.length : 0,
    }),

    // NEW: Layout computed properties
    ...mapGetters('layout', [
      'isSplitScreen',
      'splitScreenSettings',
      'contentPosition',
      'featureImagePath',
      'isMobile'
    ]),

    featureComponent() {
      // console.log("feature Component method called!!")
      // Return the appropriate component based on feature_type
      // const type = this.featureType;
      const type = 'banner';

      if (type == 'banner') {
        return () => import('@/components/features/BannerFeature.vue');
      }
      // else if (type === 'product') {
      //   return () => import('./components/ProductFeature.vue');
      // } else if (type === 'video') {
      //   return () => import('./components/VideoFeature.vue');
      // }

      return null;
    },

    featureSideClass() {
      return this.contentPosition === 'left' ? 'order-2' : 'order-1';
    },

    featureStyle() {
      // You can add dynamic styling based on splitScreenSettings
      return {
        backgroundColor: this.splitScreenSettings?.background_color || '#f8f9fa'
      };
    },

    layoutClass() {
      if (this.isMobile) {
        return 'layout__two';
      }
      return this.isSplitScreen ? 'layout-split-screen' : 'layout__two';
    },

    containerStyle() {
      if (!this.isSplitScreen || this.isMobile) return {};

      return {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        minHeight: '100vh'
      };
    },

    contentSideClass() {
      return this.contentPosition === 'left' ? 'order-1' : 'order-2';
    },

    featureSideClass() {
      return this.contentPosition === 'left' ? 'order-2' : 'order-1';
    },

    contentStyle() {
      return {
        backgroundColor: '#ffffff',
        overflowY: 'auto'
      };
    },

    featureStyle() {
      if (!this.splitScreenSettings) return {};

      return {
        backgroundColor: this.splitScreenSettings.background_color || '#ffffff',
        backgroundImage: this.splitScreenSettings.feature_image
          ? `url(${this.splitScreenSettings.feature_image})`
          : 'none',
        backgroundSize: 'cover',
        backgroundPosition: 'center'
      };
    },

    featureComponent() {
      if (!this.splitScreenSettings) return null;

      const componentMap = {
        'banner': 'BannerFeature',
        'product': 'ProductFeature',
        'video': 'VideoFeature'
      };

      return componentMap[this.splitScreenSettings.feature_type] || 'BannerFeature';
    }

  },
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
    this.getThemeStyle();
    this.getAllMenusForEcommerceHome();
    this.getFooterWidget();

    if (this.isCustomerLogin) {
      this.checkCustomerAuthentication();
      setInterval(this.checkCustomerAuthentication, 1000 * 60);
    }
    this.$store.state.$t = this.translateLanguage;

    // NEW: Initialize layout detection
    this.fetchActiveLayout();
    this.checkMobileView();
    window.addEventListener('resize', this.handleResize);
    this.initResizeObserver();
  },

  beforeUnmount() {
    // NEW: Clean up listeners
    window.removeEventListener('resize', this.handleResize);

    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
    }

    if (this.resizeTimeout) {
      clearTimeout(this.resizeTimeout);
    }
  },

  methods: {

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
      }, 150);
    },

    initResizeObserver() {
      if ('ResizeObserver' in window) {
        this.resizeObserver = new ResizeObserver(() => {
          this.checkMobileView();
        });
        this.resizeObserver.observe(document.body);
      }
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
            this.rightMenuItems = response.data.header_top_right_menus.menus;
            this.leftMenuItems = response.data.header_top_left_menus.menus;
            this.headerBottomMenu =
              response.data.header_bottom_middle_menus.menus;

            this.footerLeftMenus = response.data.footer_widget_left_menus.menus;
            this.footerLeftTitle =
              response.data.footer_widget_left_menus.widget_title;

            this.footerRightMenus =
              response.data.footer_widget_right_menus.menus;
            this.footerRightTitle =
              response.data.footer_widget_right_menus.widget_title;
            this.MenuItemsLoading = false;
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
    $route(to, from) {
      if (this.$route.name === "product") {
        this.isSingleProduct = true;
      } else {
        this.isSingleProduct = false;
      }
    },
  },
  beforeDestroy() {
    document.removeEventListener("click", this.close);
  },
};
</script>

<style scoped lang="scss">
.modal-dialog.modal-dialog-centered {
  background-color: red;
}

.modal.website-popup-modal .modal-content {
  border-radius: 0px !important;
}

.custom-modal-btn {
  top: -15px;
  right: -15px;
}

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

.split-screen-container {
  width: 100%;
  align-items: flex-start;
  height: fit-content;
  min-height: unset;
}

.split-screen-content {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  padding: 0 !important;
  margin: 0 !important;
  height: fit-content;
  min-height: unset;
}

.split-screen-feature {
  position: sticky;
  /* Adjust this value to match your header's actual height */
  top: 80px;

  /* The height should be the full viewport MINUS the header height */
  height: calc(100vh - 80px);

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

/* Mobile: Force default layout */
@media (max-width: 768px) {
  .layout-split-screen {
    display: block !important;
  }

  .split-screen-container {
    display: block !important;
    grid-template-columns: none !important;
  }

  .split-screen-content,
  .split-screen-feature {
    width: 100% !important;
    order: initial !important;
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

.company-footer {
  // position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100;
}
</style>
