<template>
  <!-- Mobile Header -->
  <!-- <header
    :class="
      this.headerStyle.custom_header == 1
        ? ' c1-bg custom-mobile-header mobile_header__two d-lg-none'
        : 'mobile_header__two d-lg-none c1-bg'
    "
    @scroll="scrollHandler"
    ref="mobileHeader"
  > -->
  <header :class="headerClasses" @scroll="scrollHandler" ref="mobileHeader">
    <div class="custom-container2">
      <div class="row align-items-center justify-content-between">

        <div class="col-6">
          <div class="logo-wrapper d-flex align-items-center">

            <template v-if="!isSticky">
              <template v-if="mode == 'dark'">
                <the-logo v-if="siteProperties.mobile_dark_logo" :logo="siteProperties.mobile_dark_logo"
                  :title="siteProperties.site_title" :header-logo-style="headerLogoStyle" />
                <h4 class="site-title" v-else>
                  {{ siteProperties.site_title }}
                </h4>
              </template>
              <template v-else>
                <the-logo v-if="siteProperties.mobile_logo" :logo="siteProperties.mobile_logo"
                  :title="siteProperties.site_title" :header-logo-style="headerLogoStyle" />
                <h4 class="site-title" v-else>
                  <router-link to="/">
                    {{ siteProperties.site_title }}
                  </router-link>
                </h4>
              </template>
            </template>

            <template v-else>
              <template v-if="mode == 'dark'">
                <the-logo v-if="siteProperties.sticky_black_mobile_logo" :logo="siteProperties.sticky_black_mobile_logo"
                  :title="siteProperties.site_title" :header-logo-style="headerLogoStyle" />
                <h4 class="site-title" v-else>
                  {{ siteProperties.site_title }}
                </h4>
              </template>
              <template v-else>
                <the-logo v-if="siteProperties.sticky_mobile_logo" :logo="siteProperties.sticky_mobile_logo"
                  :title="siteProperties.site_title" :header-logo-style="headerLogoStyle" />
                <h4 class="site-title" v-else>
                  {{ siteProperties.site_title }}
                </h4>
              </template>
            </template>

          </div>
        </div>

        <!-- <div class="col-6" v-if="!isSticky">
          <template v-if="mode == 'dark'">
            <the-logo
              :logo="siteProperties.mobile_dark_logo"
              :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle"
              v-if="siteProperties.mobile_dark_logo"
            />
            <h4 class="site-title" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
          <template v-else>
            <the-logo
              :logo="siteProperties.mobile_logo"
              :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle"
              v-if="siteProperties.mobile_logo"
            />
            <h4 class="site-title" v-else>
              <router-link to="/">
                {{ siteProperties.site_title }}
              </router-link>
            </h4>
          </template>
        </div>
        <div class="col-6" v-else>
          <template v-if="mode == 'dark'">
            <the-logo
              :logo="siteProperties.sticky_black_mobile_logo"
              :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle"
              v-if="siteProperties.sticky_black_mobile_logo"
            />
            <h4 class="site-title" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
          <template v-else>
            <the-logo
              :logo="siteProperties.sticky_mobile_logo"
              :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle"
              v-if="siteProperties.sticky_mobile_logo"
            />
            <h4 class="site-title" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
        </div> -->

        <div class="
            col-6
            d-flex
            align-items-center
            justify-content-end
            position-static
          ">
          <!-- Offcanvas -->
          <the-offcanvas :user-info="customerInfo" :menu-items="offcanvas.menuItems"
            :header-menu-style="headerMenuStyle" :header-style="headerStyle" class="mr-20" />
          <!-- End The Offcanvas -->

          <!-- Search Form -->
          <!-- <search-form style-two mobile-style class="mr-20" /> -->
          <search-form style-two mobile-style class="mr-20" />
          <!-- End Search Form -->

          <!-- Cart Button -->
          <router-link to="/cart" class="btn-circle custom-icon-btn">
            <base-icon-svg name="cart" class="material-icons" :width="18" :height="15" />
            <span class="
                count
                position-absolute
                d-flex
                align-items-center
                justify-content-center
              ">{{ cartItem }}</span>
          </router-link>
          <!-- End Cart Button -->
        </div>
      </div>
    </div>
  </header>
  <!-- End Mobile Header -->
</template>
<script>
import offcanvas from "@/fakeDB/offcanvas.json";
import SearchForm from "@/components/ui/SearchForm.vue";
import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";
import { mapState, mapGetters } from "vuex";
export default {
  name: "MobileHeader",
  components: {
    SearchForm,
    TheOffcanvas,
  },
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
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      offcanvas,
      wishlistItem: 0,
      compareItem: 0,
      isSticky: false,
    };
  },
  computed: {
    ...mapState({
      customerInfo: (state) => state.customerInfo,
    }),

    ...mapGetters('layout', [
      'isSplitScreen',
      'isMobile'
    ]),

    headerClasses() {
      // console.log("isSplitScreen: ", this.isSplitScreen);
      // console.log("isMobile: ", this.isMobile);
      // 1. The "Force" condition: both are true
      if (this.isSplitScreen && !this.isMobile) {
        return 'c1-bg custom-mobile-header mobile_header__two d-lg-none force-show sticky';
      }

      // 2. The default/fallback logic (your original ternary)
      return this.headerStyle.custom_header == 1
        ? 'c1-bg custom-mobile-header mobile_header__two d-lg-none'
        : 'mobile_header__two d-lg-none c1-bg';
    }
  },
  // computed: mapState({
  //   customerInfo: (state) => state.customerInfo,
  // }),
  mounted() {
    window.addEventListener("scroll", this.scrollHandler);
  },
  methods: {
    scrollHandler() {

      if (this.isSplitScreen && !this.isMobile) {
        return;
      }

      const mobileHeader = this.$refs.mobileHeader;
      if (window.pageYOffset > 100) {
        this.isSticky = true;
        mobileHeader?.classList.add("sticky", "fadeInDowns");
      } else {
        this.isSticky = false;
        mobileHeader?.classList.remove("sticky", "fadeInDowns");
      }
    },
  },
};
</script>

<style scoped>
.logo-wrapper {
  /* Set the exact height you want for your mobile header content */
  height: 40px;
  overflow: hidden;
  display: flex;
  align-items: center;
}

/* Ensure the logo within the wrapper behaves */
.logo-wrapper :deep(img),
.logo-wrapper :deep(.site-logo),
.logo-wrapper :deep(.the-logo) {
  max-height: 100%;
  /* Cannot exceed the 40px height */
  max-width: 100%;
  width: auto;
  display: block;
  object-fit: contain;
}

/* If you have a text title instead of a logo, ensure it doesn't break the line */
.site-title {
  margin: 0;
  font-size: 1.1rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
