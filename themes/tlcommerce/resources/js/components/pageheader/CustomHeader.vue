<template>
  <div class="custom-header">
    <div class="custom-header-card" :class="{ 'custom-header-card--compact': showCompactToolbar }">
      <div class="custom-header-card__left">
        <button v-if="!isHomePage" type="button" class="custom-header-icon-btn custom-header-back-btn"
          :aria-label="$t('Back')" @click="goBack">
          <span class="material-icons">arrow_back</span>
        </button>
        <template v-else-if="showCompactToolbar && isHomePage">
          <the-offcanvas :user-info="customerInfo" :menu-items="offcanvas.menuItems"
            :header-menu-style="headerMenuStyle" :header-style="headerStyle" class="custom-header__offcanvas" />
          <the-logo v-if="logo" :logo="logo" :title="storeTitle" :header-logo-style="headerLogoStyle"
            class="custom-header-card__logo" />
        </template>
        <template v-else-if="isHomePage">
          <the-logo v-if="logo" :logo="logo" :title="storeTitle" :header-logo-style="headerLogoStyle"
            class="custom-header-card__logo" />
          <div class="custom-header-card__text">
            <h1 class="custom-header-card__name">{{ storeName }}</h1>
            <p v-if="storeMotto" class="custom-header-card__motto">{{ storeMotto }}</p>
          </div>
        </template>
      </div>

      <div class="custom-header-card__right">
        <div v-if="showCompactToolbar" class="custom-header-toolbar">
          <router-link to="/cart" class="custom-header-icon-btn btn-circle custom-header__cart"
            :aria-label="$t('Cart')">
            <base-icon-svg name="cart" class="material-icons" :width="12" :height="12" />
            <span
              v-if="cartItemCount > 0"
              class="custom-header__cart-count count position-absolute d-flex align-items-center justify-content-center"
            >
              {{ cartCountLabel }}
            </span>
          </router-link>
          <search-form class="custom-header__search" style-two mobile-style
            overlay-teleport=".split-screen-search-inline-host" />
          <router-link :to="{ name: 'storeInfo' }" class="custom-header-icon-btn custom-header-info-btn"
            :aria-label="$t('Store Info')">
            <span class="material-icons">info_outline</span>
          </router-link>
        </div>
        <router-link v-else-if="isHomePage" :to="{ name: 'storeInfo' }"
          class="custom-header-info-btn custom-header-info-btn--default" :aria-label="$t('Store Info')">
          <span class="material-icons">info_outline</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import offcanvas from "@/fakeDB/offcanvas.json";
import TheLogo from "@/components/global/TheLogo.vue";
import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";
import SearchForm from "@/components/ui/SearchForm.vue";
import { mapState, mapGetters } from "vuex";

export default {
  name: "CustomHeader",
  components: {
    TheLogo,
    TheOffcanvas,
    SearchForm,
  },
  props: {
    siteProperties: {
      type: Object,
      default: () => ({}),
    },
    mode: {
      type: String,
      default: "",
    },
    headerLogoStyle: {
      type: Object,
      default: () => ({}),
    },
    cartItem: {
      type: Number,
      default: 0,
    },
    headerStyle: {
      type: Object,
      default: () => ({}),
    },
    headerMenuStyle: {
      type: Object,
      default: () => ({}),
    },
  },
  inject: {
    resetRouteOutlet: {
      from: "resetRouteOutlet",
      default: null,
    },
  },
  data() {
    return {
      offcanvas,
    };
  },
  computed: {
    ...mapState({
      customerInfo: (state) => state.customerInfo,
    }),
    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),
    ...mapGetters(["cartItemCount"]),
    showCompactToolbar() {
      return this.isSplitScreen && this.isMobile;
    },
    isHomePage() {
      return this.$route.name === "home";
    },
    storeName() {
      return this.siteProperties.site_name || this.siteProperties.site_title || "";
    },
    storeTitle() {
      return this.siteProperties.site_title || this.storeName;
    },
    storeMotto() {
      return this.siteProperties.site_motto || "";
    },
    logo() {
      if (this.mode === "dark") {
        return this.siteProperties.mobile_dark_logo || this.siteProperties.mobile_logo || "";
      }
      return this.siteProperties.mobile_logo || "";
    },
    cartCountLabel() {
      return this.cartItemCount > 99 ? "99+" : String(this.cartItemCount);
    },
  },
  methods: {
    async goBack() {
      this.closeHeaderOverlays();

      if (this.$route.name === "product") {
        const referrer = document.referrer || "";
        const fromInstagram = /instagram\.com/i.test(referrer);
        // Instagram WebView often reports history.length > 1 even on landing URLs.
        // Prefer an in-SPA home navigation so the outlet cannot stay blank.
        const preferHome = fromInstagram || window.history.length <= 1;

        try {
          if (preferHome) {
            await this.$router.replace({ name: "home" });
          } else {
            if (this.resetRouteOutlet) {
              await this.resetRouteOutlet();
            }
            this.$router.go(-1);
          }
        } catch {
          try {
            await this.$router.replace({ name: "home" });
          } catch {
            // Ignore duplicate navigation to home
          }
        }
        return;
      }

      this.$router.go(-1);
    },
    closeHeaderOverlays() {
      document
        .querySelector(".split-screen-search-inline-host")
        ?.classList.remove("is-open");
    },
  },
};
</script>

<style scoped>
.custom-header-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  background: #f8f9fa;
  border: 1px solid #e2e2e2;
  padding: 12px 16px;
  width: 100%;
}

.custom-header-card__left {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
  flex: 1;
}

.custom-header-card__logo {
  --header-logo-max-height: 40px;
}

.custom-header-card--compact .custom-header-card__logo {
  --header-logo-max-height: 32px;
}

.custom-header-card__text {
  min-width: 0;
}

.custom-header-card__name {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  line-height: 1.2;
  color: #2d3748;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.custom-header-card__motto {
  margin: 2px 0 0;
  font-size: 0.75rem;
  color: #718096;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.custom-header-card__right {
  flex-shrink: 0;
}

.custom-header-toolbar {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Circular icon buttons — white background on hover, white icon via primary overlay ring */
.custom-header-icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  min-width: 40px;
  padding: 0;
  flex-shrink: 0;
  background-color: #fff !important;
  border: 1px solid #e2e8f0;
  border-radius: 50%;
  color: #141414;
  text-decoration: none;
  transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease, color 0.2s ease;
  position: relative;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  isolation: isolate;
}

.custom-header-icon-btn::after {
  content: "";
  position: absolute;
  inset: 3px;
  border-radius: 50%;
  background-color: var(--color-primary);
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: 0;
}

.custom-header-icon-btn:hover {
  background-color: #fff !important;
  border-color: var(--color-primary) !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
  transform: translateY(-1px);
  /* color: #fff !important; */
}

.custom-header-icon-btn:hover::after {
  opacity: 1;
}

.custom-header-icon-btn>* {
  position: relative;
  z-index: 1;
}

/* .custom-header-icon-btn:hover .material-icons {
  color: #fff !important;
} */

.custom-header-back-btn .material-icons {
  font-size: 22px;
}

/* Default mode info (desktop home, non-compact) */
.custom-header-info-btn--default {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  color: #2d3748;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  text-decoration: none;
  transition: box-shadow 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

.custom-header-info-btn--default:hover {
  background: #ffffff;
  border-color: var(--color-primary);
  color: var(--color-primary);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
  transform: translateY(-1px);
}

.custom-header-info-btn--default .material-icons {
  font-size: 22px;
}

/* Compact toolbar: cart */
.custom-header-toolbar :deep(.custom-header__cart.btn-circle) {
  position: relative;
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  background-color: #fff !important;
  border: 1px solid #e2e8f0 !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: visible;
  isolation: isolate;
  transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.custom-header-toolbar :deep(.custom-header__cart.btn-circle::after) {
  content: "";
  position: absolute;
  inset: 3px;
  border-radius: 50%;
  background-color: var(--color-primary);
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: 0;
}

.custom-header-toolbar :deep(.custom-header__cart.btn-circle:hover) {
  background-color: #fff !important;
  border-color: var(--color-primary) !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
  transform: translateY(-1px);
}

.custom-header-toolbar :deep(.custom-header__cart.btn-circle:hover::after) {
  opacity: 1;
}

.custom-header-toolbar :deep(.custom-header__cart svg) {
  position: relative;
  z-index: 1;
  width: 12px !important;
  height: 12px !important;
}

/* .custom-header-toolbar :deep(.custom-header__cart:hover svg) {
  color: #fff !important;
} */

.custom-header-toolbar :deep(.custom-header__cart .custom-header__cart-count) {
  z-index: 3;
  min-width: 18px;
  height: 18px;
  padding: 0 4px;
  font-size: 10px;
  font-weight: 600;
  line-height: 1;
  color: #fff;
  background-color: var(--color-primary, #e53e3e);
  border: 2px solid #fff;
  border-radius: 999px;
  right: -4px;
  top: -4px;
  box-sizing: border-box;
  pointer-events: none;
}

/* Offcanvas hamburger */
.custom-header-card__left :deep(.custom-header__offcanvas .hamburger) {
  position: relative;
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  border-radius: 50%;
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 0;
  background-color: #fff !important;
  border: 1px solid #e2e8f0 !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
  isolation: isolate;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger::after) {
  content: "";
  position: absolute;
  inset: 3px;
  border-radius: 50%;
  background-color: var(--color-primary);
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: 0;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger span) {
  position: relative;
  z-index: 1;
  display: block;
  flex-shrink: 0;
  height: 2px;
  margin-top: 2px;
  margin-bottom: 2px;
  background-color: #141414 !important;
  transition: background-color 0.2s ease;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger span:first-child) {
  margin-top: 0;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger span:nth-child(1)) {
  width: 8px;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger span:nth-child(2)) {
  width: 14px;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger span:nth-child(3)) {
  width: 8px;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger:hover) {
  background-color: #fff !important;
  border-color: var(--color-primary) !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
  transform: translateY(-1px);
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger:hover::after) {
  opacity: 0;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger:hover span) {
  background-color: #141414 !important;
}

.custom-header-card__left :deep(.custom-header__offcanvas .hamburger.active span) {
  transform: none !important;
  opacity: 1 !important;
}

/* Search toggle */
.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button.bg-transparent) {
  background-color: #fff !important;
  background-image: none !important;
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button) {
  position: relative;
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  border-radius: 50%;
  background-color: #fff !important;
  border: 1px solid #e2e8f0 !important;
  color: #141414 !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  isolation: isolate;
  transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button::after) {
  content: "";
  position: absolute;
  inset: 3px;
  border-radius: 50%;
  background-color: var(--color-primary);
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: 0;
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button:hover) {
  background-color: #fff !important;
  border: 1px solid var(--color-primary) !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
  transform: translateY(-1px);
  /* color: #fff !important; */
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button:hover::after) {
  opacity: 1;
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button svg),
.custom-header-toolbar :deep(.custom-header__search .icon-wrapper) {
  position: relative;
  z-index: 1;
}

.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button svg) {
  width: 12px !important;
  height: 12px !important;
}

.custom-header-toolbar :deep(.custom-header__search.custom-search-btn-mobile .icon-wrapper),
.custom-header-toolbar :deep(.custom-header__search.custom-search-btn-mobile .icon-wrapper svg),
.custom-header-toolbar :deep(.custom-header__search.custom-search-btn-mobile svg) {
  color: #141414 !important;
  fill: #141414 !important;
  transition: color 0.2s ease, fill 0.2s ease;
}

/* .custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button:hover svg),
.custom-header-toolbar :deep(.custom-header__search.search-form-wrapper > button:hover .icon-wrapper svg) {
  color: #fff !important;
  fill: #fff !important;
} */

/* Compact info uses .custom-header-icon-btn hover rules above */
</style>
