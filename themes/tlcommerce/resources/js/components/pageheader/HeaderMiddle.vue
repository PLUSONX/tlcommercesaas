<template>
  <!-- Header Top -->
  <!-- <div
    class="custom-header-mid header-top py-20 c1-bg"
    @scroll="scrollHandler"
    ref="fooHeader"
  > -->
  <div :class="headerClasses" @scroll="scrollHandler" ref="fooHeader">
    <div class="custom-container2">
      <div class="row align-items-center">

        <div class="col-lg-3">
          <div class="logo-wrapper d-flex align-items-center">

            <template v-if="!isSticky">
              <template v-if="mode == 'dark'">
                <the-logo v-if="siteProperties.logo_dark" :header-logo-style="headerLogoStyle"
                  :logo="siteProperties.logo_dark" :title="siteProperties.site_name" />
                <h2 class="site-title" v-else>
                  <a href="/">{{ siteProperties.site_name }}</a>
                </h2>
              </template>
              <template v-else>
                <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo"
                  :title="siteProperties.site_name" />
              </template>
            </template>

            <template v-else>
              <template v-if="mode == 'dark'">
                <the-logo v-if="siteProperties.sticky_black_logo" :header-logo-style="headerLogoStyle"
                  :logo="siteProperties.sticky_black_logo" :title="siteProperties.site_name" />
                <h2 class="site-title" v-else>
                  <a href="/">{{ siteProperties.site_name }}</a>
                </h2>
              </template>
              <template v-else>
                <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.sticky_logo"
                  :title="siteProperties.site_name" />
              </template>
            </template>

          </div>
        </div>

        <!-- <div class="col-lg-3" v-if="!this.isSticky">
        <div class="col-lg-3" v-if="!this.isSticky">
          <template v-if="mode == 'dark'">
            <the-logo
              :header-logo-style="headerLogoStyle"
              :logo="siteProperties.logo_dark"
              :title="siteProperties.site_name"
              v-if="siteProperties.logo_dark"
            />
            <h2 class="site-title" v-else>
              <a href="/">{{ siteProperties.site_name }}</a>
            </h2>
          </template>
          <template v-else>
            <the-logo
              :header-logo-style="headerLogoStyle"
              :logo="siteProperties.logo"
              :title="siteProperties.site_name"
              v-if="siteProperties.logo"
            />
            <h2 class="site-title" v-else>
              <a href="/">{{ siteProperties.site_name }}</a>
            </h2>
          </template>
        </div>
        <div class="col-lg-3" v-else>
          <template v-if="mode == 'dark'">
            <div v-if="siteProperties.sticky_black_logo">
              <the-logo
                :header-logo-style="headerLogoStyle"
                :logo="siteProperties.sticky_black_logo"
                :title="siteProperties.site_name"
              />
            </div>
            <div v-else>
              <h2 class="site-title">
                <a href="/">{{ siteProperties.site_name }}</a>
              </h2>
            </div>
          </template>
          <template v-else>
            <div v-if="siteProperties.sticky_logo">

              <the-logo
                :header-logo-style="headerLogoStyle"
                :logo="siteProperties.sticky_logo"
                :title="siteProperties.site_name"
              />
            </div>
            <div v-else>
              <h2 class="site-title">
                <a href="/">{{ siteProperties.site_name }}</a>
              </h2>
            </div>
          </template>
        </div> -->
        <div class="col-lg-6 position-relative">
          <!-- Search Form -->
          <search-form @search-suggestions="getSearchProducts" rounded style-two />
          <!-- End Search Form -->
          <div class="search-suggestion box-shadow bg-white" v-if="suggestionsOpen">
            <div v-if="
              tag_suggestions.length ||
              category_suggestions ||
              products_suggestions
            ">
              <!--Tags suggestions List-->
              <div v-if="tag_suggestions.length">
                <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Popular Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <!-- <li class="d-block text-left suggestion_list" v-for="(tag, index) in tag_suggestions" :key="index"> -->
                  <li class="d-block text-left suggestion_list" v-for="(tag, index) in tag_suggestions" :key="index"
                    @click="closeSuggestions">
                    <router-link :to="`/product/search?tag=${tag.permalink}`">{{
                      tag.name
                      }}</router-link>
                  </li>
                </ul>
              </div>
              <!--End Tags suggestion List--->

              <!--Categories suggestions List-->
              <!-- <div v-if="category_suggestions">
                <div
                  class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary"
                >
                  {{ $t("Category Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <li
                    class="d-block text-left suggestion_list"
                    v-for="(category, index) in category_suggestions"
                    :key="index"
                  >
                    <router-link :to="`/products/category/${category.slug}`">{{
                      category.name
                    }}</router-link>
                  </li>
                </ul>
              </div> -->
              <!--End Categories suggestion List--->
              <div v-if="products_suggestions">
                <div ref="searchSuggestion"
                  class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Products Suggestions") }}
                </div>
                <!--Product List-->
                <ul class="list-unstyled mb-0">
                  <!-- <li
                    class="d-block text-left suggestion_list"
                    v-for="(product, index) in products_suggestions"
                    :key="index"
                  > -->
                  <li class="d-block text-left suggestion_list" v-for="(product, index) in products_suggestions"
                    :key="index" @click="closeSuggestions">
                    <single-product :item="product" small />
                  </li>
                </ul>
                <!--End Product List--->
              </div>
            </div>

            <div class="p-3 text-center mt-1" v-else>
              {{ $t("Sorry, nothing found for") }}
              <strong>{{ search_key }}</strong>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <!-- Header Buttons -->




          <ul class="header-btn-group nav justify-content-end">

            <!-- <li v-if="enable_product_compare == 1">
              <router-link to="/compare" class="btn-circle custom-icon-btn">
                <span class="material-icons">compare_arrows</span>
                <span
                  class="count position-absolute d-flex align-items-center justify-content-center"
                  >{{ compareItem }}</span
                >
              </router-link>
            </li> -->
            <li>
              <router-link to="/dashboard/wishlist" class="btn-circle custom-icon-btn">
                <base-icon-svg name="wishlist" class="material-icons" :width="16.5" :height="16.5" />
                <span class="count position-absolute d-flex align-items-center justify-content-center">{{ wishlistItem
                  }}</span>
              </router-link>
            </li>

            <li>
              <router-link to="/cart" class="btn-circle custom-icon-btn">
                <span class="material-icons">shopping_cart</span>
                <span class="count position-absolute d-flex align-items-center justify-content-center">{{ cartItem
                  }}</span>
              </router-link>
            </li>

            <li ref="dropdownMenu2" v-if="!dataLoading" :class="{ 'active': showMyAccount }">
              <span class="my-account-btn custom-menu" @click.prevent="showMyAccount = !showMyAccount">
                {{ $t("My Account") }}
                <span class="material-icons ms-1">expand_more</span>
              </span>
              <div class="my-account-dropdown" v-if="showMyAccount">
                <!-- <span>Items count: {{ offcanvas.menuItems.length }}</span> -->
                <ul class="list-unstyled mb-0" v-if="isCustomerLogin">
                  <template v-for="(item, index) in offcanvas.menuItems">
                    <menu-item v-if="!item.submenu" :key="`item-${index}`" :item="item" off-canvas
                      :header-menu-style="headerMenuStyle" />

                    <menu-dropdown v-else :key="`group-${index}`" :item="item" off-canvas :open-group="isGroupActive(item, $route.fullPath)
                      " :header-menu-style="headerMenuStyle" />

                  </template>

                  <li>
                    <router-link to="#" @click.prevent="customerLogout()" class="custom-menu">
                      {{ $t("Logout") }}
                    </router-link>
                  </li>
                </ul>
                <!-- <ul class="list-unstyled" v-if="isCustomerLogin">
                    <li>
                      <router-link to="/dashboard" class="custom-menu">{{
                        $t("Dashboard")
                      }}</router-link>
                    </li>
                    <li>
                      <router-link
                        to="#"
                        @click.prevent="customerLogout()"
                        class="custom-menu"
                      >
                        {{ $t("Logout") }}
                      </router-link>
                    </li>
                  </ul> -->
                <ul class="list-unstyled" v-else>
                  <li>
                    <router-link to="/login" class="custom-menu">{{
                      $t("Login")
                    }}</router-link>
                  </li>
                  <li>
                    <router-link to="/register" class="custom-menu">{{
                      $t("Registration")
                    }}</router-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
          <!-- End Header Buttons -->
        </div>
      </div>
    </div>
  </div>

  <!-- End Header Top -->
</template>
<script>
import { mapState, mapGetters } from "vuex";
import SearchForm from "@/components/ui/SearchForm.vue";
import SingleProduct from "../product/SingleProduct.vue";
import MenuItem from "../../components/menu/MenuItem.vue";
import MenuDropdown from "../../components/menu/MenuDropdown.vue";
import axios from "axios";
import offcanvas from "@/fakeDB/offcanvas.json";
import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";

export default {
  name: "HeaderMiddle",
  components: {
    SearchForm,
    SingleProduct,
    MenuItem,
    MenuDropdown,
    TheOffcanvas,
  },
  emits: ["logout-customer"],
  props: {
    siteProperties: {
      type: Object,
      required: false,
    },
    mode: {
      type: String,
      required: false,
    },
    cartItem: {
      type: Number,
      required: false,
      default: 0,
    },
    wishlistItem: {
      type: Number,
      required: false,
      default: 0,
    },
    compareItem: {
      type: Number,
      required: false,
      default: 0,
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    leftMenuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    rightMenuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    dataLoading: {
      type: Boolean,
      required: true,
      default: false,
    },
    userInfo: {
      type: Object,
      default: null,
    },
    menuItems: {
      type: Array,
      default: () => []
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      loading: false,
      search_key: "",
      suggestionsOpen: false,
      products_suggestions: [],
      category_suggestions: [],
      tag_suggestions: [],
      active: 1,
      isSticky: false,
      showMyAccount: false,
      offcanvas,
    };
  },
  computed: {
    ...mapState({
      enable_product_compare: (state) =>
        state.siteSettings != null
          ? state.siteSettings.enable_product_compare
          : 0,

      isCustomerLogin: (state) => state.isCustomerLogin,
      customerToken: (state) => state.customerToken,
      notifications: (state) => state.notifications,
      totalNotifications: (state) =>
        state.notifications.length ? state.notifications.length : 0,
      isGroupActive() {
        return (item, path) => {
          let openGroup = false;
          const func = (item) => {
            if (item.submenu) {
              item.submenu.forEach((item) => {
                if (path === item.url) {
                  openGroup = true;
                } else if (item.submenu) {
                  func(item);
                }
              });
            }
          };
          func(item, path);
          return openGroup;
        };
      },


    }),

    ...mapGetters('layout', [
      'isSplitScreen',
      'isMobile'
    ]),

    headerClasses() {
      return {
        'custom-header-mid': true,
        'header-top': true,
        'py-20': true,
        'c1-bg': true,
        'sticky': (this.isSplitScreen && !this.isMobile) || this.isSticky,
        'fadeInDowns': (this.isSplitScreen && !this.isMobile) || this.isSticky,
      };
    }

    // headerClasses() {
    //   console.log("isSplitScreen: ", this.isSplitScreen);
    //   console.log("isMobile: ", this.isMobile);
    //   // 1. The "Force" condition: both are true
    //   if (this.isSplitScreen && !this.isMobile) {
    //     return 'custom-header-mid header-top py-20 c1-bg sticky fadeInDowns';
    //   }

    //   // 2. The default/fallback logic (your original ternary)
    //   return "custom-header-mid header-top py-20 c1-bg";
    // }

  },
  // computed: mapState({
  //   enable_product_compare: (state) =>
  //     state.siteSettings != null
  //       ? state.siteSettings.enable_product_compare
  //       : 0,
  // }),
  mounted() {
    // console.log("Middle Header mounted!!!");
    // window.addEventListener("scroll", this.scrollHandler);
    // document.addEventListener("click", this.close);

    window.addEventListener("scroll", this.scrollHandler);
    window.addEventListener("resize", this.resizeHandler);  // ADD THIS
    document.addEventListener("click", this.close);

    // console.log("isSticky: ", this.isSticky);

    // console.log("site properties: ", this.siteProperties);

  },
  beforeUnmount() {
    window.removeEventListener("scroll", this.scrollHandler);
    window.removeEventListener("resize", this.resizeHandler);  // CLEAN UP
    document.removeEventListener("click", this.close);
  },
  methods: {
    /**
     * Merged close handler for all dropdowns (Search, Account, Language, Notifications)
     */
    close(e) {
      let target = e.target;

      // 1. Handle Search Suggestions
      let searchEl = this.$refs.searchSuggestion;
      // We check if the click is NOT on the search box
      if (searchEl && searchEl !== target && !searchEl.contains(target)) {
        this.closeSuggestions();
      }

      // 2. Handle Language/Currency Dropdown
      let el = this.$refs.dropdownMenu;
      if (el && el !== target && !el?.contains(target)) {
        this.showLanguageCurrency = false;
      }

      // 3. Handle My Account Dropdown
      let el2 = this.$refs.dropdownMenu2;
      if (el2 && el2 !== target && !el2?.contains(target)) {
        this.showMyAccount = false;
      }

      // 4. Handle Notifications
      if (this.isCustomerLogin) {
        let el3 = this.$refs.notificationDropdownMenu;
        if (el3 && el3 !== target && !el3?.contains(target)) {
          this.showNotification = false;
        }
      }
    },

    /**
     * Helper to reset search state
     */
    closeSuggestions() {
      this.suggestionsOpen = false;
      this.products_suggestions = null; // This ensures the v-if triggers
      this.category_suggestions = null;
      this.tag_suggestions = null;
    },

    /**
     * Get suggestions products
     */
    getSearchProducts(search_key) {
      this.search_key = search_key;
      this.loading = true;
      if (search_key) {
        axios
          .post("/api/v1/ecommerce-core/search-suggestions", {
            search_key: search_key,
          })
          .then((response) => {
            this.loading = false;
            if (response.data.success) {
              this.suggestionsOpen = true;
              this.category_suggestions = response.data.categories.data;
              this.products_suggestions = response.data.products.data;
              this.tag_suggestions = response.data.tags;
            }
          })
          .catch((error) => {
            this.loading = false;
          });
      } else {
        this.closeSuggestions();
      }
    },

    resizeHandler() {
      if (!this.isSplitScreen || this.isMobile) {
        this.isSticky = window.pageYOffset > 100;
      }
    },

    scrollHandler() {
      if (this.isSplitScreen && !this.isMobile) return;
      this.isSticky = window.pageYOffset > 100;
    },

    customerLogout() {
      this.$emit("logout-customer");
    },
  },
  // methods: {
  //   /**
  //    * Will close dropdown area
  //    *
  //    * @param {*} e
  //    */
  //   close(e) {
  //     let el1 = this.$refs.searchSuggestion;
  //     let target = e.target;
  //     if (el1 !== target && !el1?.contains(target)) {
  //       this.suggestionsOpen = false;
  //     }
  //   },
  //   /**
  //    * Get suggestions products
  //    *
  //    */
  //   getSearchProducts(search_key) {
  //     this.search_key = search_key;
  //     this.loading = true;
  //     if (search_key) {
  //       axios
  //         .post("/api/v1/ecommerce-core/search-suggestions", {
  //           search_key: search_key,
  //         })
  //         .then((response) => {
  //           this.loading = false;
  //           if (response.data.success) {
  //             this.suggestionsOpen = true;
  //             this.category_suggestions = response.data.categories.data;
  //             this.products_suggestions = response.data.products.data;
  //             this.tag_suggestions = response.data.tags;
  //           }
  //         })
  //         .catch((error) => { });
  //     } else {
  //       this.suggestionsOpen = false;
  //     }
  //   },
  //   resizeHandler() {
  //     // If no longer in splitscreen+desktop mode, reset sticky state
  //     if (!this.isSplitScreen || this.isMobile) {
  //       this.isSticky = window.pageYOffset > 100;
  //     }
  //   },
  //   scrollHandler() {
  //     if (this.isSplitScreen && !this.isMobile) return;

  //     this.isSticky = window.pageYOffset > 100;
  //   },

  //   /**
  //    * Will close dropdown area
  //    *
  //    * @param {*} e
  //    */
  //   close(e) {
  //     let el = this.$refs.dropdownMenu;
  //     let el2 = this.$refs.dropdownMenu2;
  //     let el3 = this.$refs.notificationDropdownMenu;
  //     let target = e.target;

  //     if (el !== target && !el?.contains(target)) {
  //       this.showLanguageCurrency = false;
  //     }
  //     if (el2 !== target && !el2?.contains(target)) {
  //       this.showMyAccount = false;
  //     }
  //     if (this.isCustomerLogin) {
  //       if (el3 !== target && !el3?.contains(target)) {
  //         this.showNotification = false;
  //       }
  //     }
  //   },

  //   /**
  //    * Will logout customer
  //    *
  //    */
  //   customerLogout() {
  //     this.$emit("logout-customer");
  //   },
  //   // scrollHandler() {

  //   //   if (this.isSplitScreen && !this.isMobile) {
  //   //     return; 
  //   //   }

  //   //   const fooHeader = this.$refs.fooHeader;
  //   //   if (window.pageYOffset > 100) {
  //   //     this.isSticky = true;
  //   //     fooHeader?.classList.add("sticky", "fadeInDowns");
  //   //   } else {
  //   //     this.isSticky = false;
  //   //     fooHeader?.classList.remove("sticky", "fadeInDowns");
  //   //   }
  //   // },
  // },
};
</script>

<style lang="scss" scoped>
.logo-wrapper {
  height: 60px;
  /* Set this to your desired logo height */
  overflow: hidden;
  display: flex;
  align-items: center;
}

/* Ensure the component inside doesn't override the height */
.logo-wrapper :deep(img),
.logo-wrapper :deep(.site-logo) {
  max-height: 100%;
  width: auto;
  display: block;
}

.search-suggestion {
  z-index: 9;
  position: absolute;
  top: 99%;
  width: 90%;
  left: 50%;
  max-height: 500px;
  min-height: 100px;
  overflow-y: scroll;
  overflow-x: hidden;
  transform: translateX(-50%);
  border-radius: 0px;
  scrollbar-width: none !important;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%) !important;
}

.search-suggestion::-webkit-scrollbar {
  display: none;
}

.color-gray {
  color: #0a0e33b8;
}

.font-weight-custom {
  font-weight: 600;
}

.suggestion_list {
  padding: 10px 5px;
}

.suggestion_list:not(:last-child) {
  border-bottom: 1px solid rgba(51, 51, 51, 0.1);
}

.suggestion_list:hover {
  background-color: #dcdcdc36;
}

.bg-soft-secondary {
  background-color: rgb(238 240 242) !important;
}

.fs-10 {
  font-size: 0.625rem !important;
}

.text-muted {
  color: #6c757d !important;
}

/* --- My Account Section Container --- */
.header-btn-group li {
  position: relative;
  /* Anchor for the absolute dropdown */
  display: flex;
  align-items: center;
  list-style: none;
}

/* --- The Pill Button --- */
.my-account-btn {
  /* Visibility & Contrast */
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 30px;

  /* Text & Color */
  color: #fff !important;
  font-weight: 600;
  font-size: 14px;
  text-decoration: none;

  /* Layout */
  padding: 8px 18px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  user-select: none;
  white-space: nowrap;

  &:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.4);
  }

  /* The Arrow Icon */
  .material-icons {
    font-size: 18px;
    transition: transform 0.3s ease;
    margin: 0 !important;
    /* Reset any global margins */
  }
}

/* Rotate arrow when menu is open */
li.active .my-account-btn .material-icons {
  transform: rotate(180deg);
}

/* --- The Dropdown Menu --- */
.my-account-dropdown {
  position: absolute;
  top: calc(100% + 12px);
  /* Spacing below the pill */
  right: 0;
  z-index: 9999;
  min-width: 190px;

  /* Visual Style */
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(0, 0, 0, 0.08);
  overflow: hidden;
  /* Ensures hover states don't bleed out of corners */

  /* Animation */
  animation: dropdownFade 0.2s ease-out;

  ul {
    margin: 0 !important;
    padding: 8px 0 !important;
    list-style: none !important;
    display: block !important;
    /* Override flex if parent is flex */

    li {
      margin: 0 !important;
      /* Reset global li margins */
      padding: 0 !important;
      display: block !important;
      width: 100%;

      a {
        display: block;
        padding: 12px 20px;
        color: #333333 !important;
        /* Always dark on white bg */
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        text-align: left;

        &:hover {
          background-color: #f4f6f9;
          color: var(--c1, #000) !important;
          /* Highlights with your theme color */
          padding-left: 25px;
          /* Subtle slide effect */
        }
      }
    }
  }
}


.my-account-dropdown ul {
  ::v-deep(li > a) {
    display: block;
    padding: 12px 20px;
    color: #333 !important;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    text-align: left;

    &:hover {
      background-color: #f4f6f9;
      color: var(--c1, #000) !important;
      padding-left: 25px;
    }
  }
}

/* --- Dropdown Arrow Tip (Optional) --- */
.my-account-dropdown::before {
  content: "";
  position: absolute;
  top: -6px;
  right: 25px;
  width: 12px;
  height: 12px;
  background: #fff;
  transform: rotate(45deg);
  border-left: 1px solid rgba(0, 0, 0, 0.08);
  border-top: 1px solid rgba(0, 0, 0, 0.08);
}

/* --- Animation --- */
@keyframes dropdownFade {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* --- Responsive Fix --- */
@media (max-width: 991px) {
  .my-account-btn {
    padding: 6px 12px;
    font-size: 12px;
  }

  .my-account-dropdown {
    right: -10px;
    /* Adjust for mobile edges */
  }
}
</style>