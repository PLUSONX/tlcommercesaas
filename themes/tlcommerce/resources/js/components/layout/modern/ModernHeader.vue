<template>
  <header class="modern-header" :class="[
    isHomePage ? 'modern-header--home' : 'modern-header--page',
    { 'modern-header--rtl': isRtl },
  ]">
    <div v-if="!isHomePage" class="modern-header__media" :style="pageHeaderStyle" aria-hidden="true">
      <div class="modern-header__overlay"></div>
    </div>

    <!-- Shared: offcanvas | search+suggestions | lang | info | cart (RTL via direction) -->
    <div class="modern-header__toolbar" :class="{ 'modern-header__toolbar--home': isHomePage }">
      <the-offcanvas :user-info="customerInfo" :menu-items="offcanvas.menuItems" :header-menu-style="headerMenuStyle"
        :header-style="headerStyle" class="modern-header__offcanvas" />

      <div class="modern-header__search-wrap" ref="searchWrap">
        <search-form class="modern-header__search" rounded style-two @search-suggestions="getSearchProducts" />
        <div class="modern-header__suggestions search-suggestion box-shadow bg-white" v-if="suggestionsOpen">
          <div v-if="
            (tag_suggestions && tag_suggestions.length) ||
            category_suggestions ||
            products_suggestions
          ">
            <div v-if="tag_suggestions && tag_suggestions.length">
              <div class="modern-header__suggestion-heading px-2 py-1 text-uppercase fs-10 text-muted bg-soft-secondary">
                {{ $t("Popular Suggestions") }}
              </div>
              <ul class="list-unstyled mb-0">
                <li class="d-block modern-header__suggestion-item suggestion_list" v-for="(tag, index) in tag_suggestions"
                  :key="`tag-${index}`" @click="closeSuggestions">
                  <router-link :to="`/product/search?tag=${tag.permalink}`">{{
                    tag.name
                  }}</router-link>
                </li>
              </ul>
            </div>

            <div v-if="products_suggestions">
              <div ref="searchSuggestion"
                class="modern-header__suggestion-heading px-2 py-1 text-uppercase fs-10 text-muted bg-soft-secondary">
                {{ $t("Products Suggestions") }}
              </div>
              <ul class="list-unstyled mb-0">
                <li class="d-block modern-header__suggestion-item suggestion_list" v-for="(product, index) in products_suggestions"
                  :key="`product-${index}`" @click="closeSuggestions">
                  <single-product :item="product" small />
                </li>
              </ul>
            </div>
          </div>

          <div class="p-3 text-center mt-1" v-else>
            {{ $t("Sorry, nothing found for") }}
            <strong>{{ search_key }}</strong>
          </div>
        </div>
      </div>

      <div class="modern-header__langcurrency">
        <language-currency-switcher
          :currencies="currencies"
          :languages="languages"
          :data-loading="dataLoading"
          :use-fixed-dropdown="true"
          @change-language-currency="setCurrencyLanguage"
        />
      </div>

      <router-link
        :to="{ name: 'storeInfo' }"
        class="modern-header__info"
        :aria-label="$t('Store Info')"
      >
        <span class="material-icons">info_outline</span>
      </router-link>

      <router-link to="/cart" class="modern-header__cart" :aria-label="$t('Cart')">
        <base-icon-svg name="cart" :width="14" :height="14" />
        <span v-if="cartItemCount > 0" class="modern-header__cart-count">
          {{ cartCountLabel }}
        </span>
      </router-link>
    </div>

    <!-- Other pages only: back + title -->
    <div v-if="!isHomePage" class="modern-header__nav">
      <button type="button" class="modern-header__back" :aria-label="$t('Back')" @click="goBack">
        <span class="material-icons">{{ isRtl ? "arrow_forward" : "arrow_back" }}</span>
      </button>
      <h1 class="modern-header__title">{{ pageTitle }}</h1>
    </div>
  </header>
</template>

<script>
import axios from "axios";
import offcanvas from "@/fakeDB/offcanvas.json";
import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";
import SearchForm from "@/components/ui/SearchForm.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import LanguageCurrencySwitcher from "@/components/pageheader/LanguageCurrencySwitcher.vue";
import { mapState, mapGetters } from "vuex";

const ROUTE_TITLE_KEYS = {
  products: "Products",
  categories: "Categories",
  categoryProducts: "Categories",
  product: "Product",
  searchProduct: "Search",
  storeInfo: "Store Info",
  cart: "Cart",
  Deals: "Deals",
  Collection: "Collection",
  blogs: "Blog",
  blog: "Blog",
  customerFeedback: "Feedback",
  Quiz: "Quiz",
};

export default {
  name: "ModernHeader",
  components: {
    TheOffcanvas,
    SearchForm,
    SingleProduct,
    LanguageCurrencySwitcher,
  },
  emits: ["change-language-currency"],
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
    currencies: {
      type: Array,
      default: () => [],
    },
    languages: {
      type: Array,
      default: () => [],
    },
    dataLoading: {
      type: Boolean,
      default: false,
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
      suggestionsOpen: false,
      products_suggestions: null,
      category_suggestions: null,
      tag_suggestions: null,
      search_key: "",
      loading: false,
    };
  },
  watch: {
    $route() {
      this.closeSuggestions();
    },
  },
  mounted() {
    document.addEventListener("click", this.onDocumentClick);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.onDocumentClick);
  },
  computed: {
    ...mapState({
      customerInfo: (state) => state.customerInfo,
    }),
    ...mapGetters("layout", ["isMobile", "isRtl", "splitScreenSettings"]),
    ...mapGetters(["cartItemCount"]),

    isHomePage() {
      return this.$route.name === "home";
    },

    cartCountLabel() {
      return this.cartItemCount > 99 ? "99+" : String(this.cartItemCount);
    },

    headerBackgroundPath() {
      const path = this.splitScreenSettings?.header_background_image_path;
      if (!path || typeof path !== "string") return null;
      const cleaned = path.replace(/^\/public\//, "").replace(/^\//, "");
      return cleaned ? `/${cleaned}` : null;
    },

    pageHeaderStyle() {
      if (this.isHomePage || !this.headerBackgroundPath) {
        return {};
      }
      return {
        backgroundImage: `url('${this.headerBackgroundPath}')`,
      };
    },

    pageTitle() {
      if (this.$route.meta?.title) {
        return this.$t(this.$route.meta.title);
      }
      const key = ROUTE_TITLE_KEYS[this.$route.name];
      if (key) {
        return this.$t(key);
      }
      const name = this.$route.name;
      if (!name || typeof name !== "string") {
        return "";
      }
      return name
        .replace(/([a-z])([A-Z])/g, "$1 $2")
        .replace(/[-_]/g, " ")
        .replace(/\b\w/g, (c) => c.toUpperCase());
    },
  },
  methods: {
    setCurrencyLanguage(lang, currency) {
      this.$emit("change-language-currency", lang, currency);
    },

    closeSuggestions() {
      this.suggestionsOpen = false;
      this.products_suggestions = null;
      this.category_suggestions = null;
      this.tag_suggestions = null;
    },

    onDocumentClick(e) {
      if (!this.suggestionsOpen) {
        return;
      }

      const wrap = this.$refs.searchWrap;
      const target = e.target;

      if (wrap && (wrap === target || wrap.contains(target))) {
        return;
      }

      this.closeSuggestions();
    },

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
          .catch(() => {
            this.loading = false;
          });
      } else {
        this.closeSuggestions();
      }
    },

    async goBack() {
      this.closeSuggestions();

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

      if (window.history.length <= 1) {
        try {
          await this.$router.replace({ name: "home" });
        } catch {
          // Ignore duplicate navigation
        }
        return;
      }

      this.$router.go(-1);
    },
  },
};
</script>

<style scoped lang="scss">
.modern-header {
  position: relative;
  width: 100%;
  box-sizing: border-box;
  background-size: cover;
  background-position: center;
}

.modern-header--home {
  background: transparent;
  padding: 5px 5px 0;
  direction: ltr;
}

.modern-header--page {
  min-height: 148px;
  width: 100%;
  margin: 0 0 12px;
  overflow: visible;
  padding: 5px 5px 18px;
  background: transparent;
  box-shadow: none;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 28px;
  direction: ltr;
}

.modern-header--page::before,
.modern-header--page::after {
  content: "";
  position: absolute;
  bottom: 0;
  width: 28px;
  height: 28px;
  z-index: 1; /* above media, below toolbar */
  pointer-events: none;
}

.modern-header--page::before {
  left: 0;
  background: radial-gradient(circle at 100% 0, transparent 28px, #fff 28.5px);
}

.modern-header--page::after {
  right: 0;
  background: radial-gradient(circle at 0 0, transparent 28px, #fff 28.5px);
}

.modern-header__media {
  position: absolute;
  inset: 0;
  z-index: 0;
  border-radius: 0 0 28px 28px;
  overflow: hidden;
  clip-path: inset(0 round 0 0 28px 28px);
  background-color: #3a2a22;
  background-size: cover;
  background-position: center;
  pointer-events: none;
}

.modern-header__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg,
      rgba(30, 20, 14, 0.35) 0%,
      rgba(30, 20, 14, 0.55) 100%);
  pointer-events: none;
}

.modern-header__toolbar,
.modern-header__nav {
  position: relative;
}

.modern-header__toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  direction: ltr;
  z-index: 2;
}

.modern-header__nav {
  z-index: 1;
}

.modern-header__toolbar--home {
  direction: ltr;
}

.modern-header__search-wrap {
  position: relative;
  flex: 1 1 auto;
  min-width: 0;
}

.modern-header__search {
  width: 100%;
}

.modern-header__search :deep(.search-form-wrapper) {
  width: 100%;
}

.modern-header__search :deep(.search-form) {
  width: 100%;
}

.modern-header__search :deep(.input-group) {
  width: 100%;
  flex-wrap: nowrap;
  background: rgba(255, 255, 255, 0.42);
  border-radius: 999px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.35);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

.modern-header__search :deep(.form-control) {
  background: transparent !important;
  border: 0 !important;
  box-shadow: none !important;
  color: #fff !important;
  height: 44px;
  padding-left: 18px;
  padding-right: 8px;
}

.modern-header__suggestion-heading {
  text-align: right;
}

.modern-header__suggestion-item {
  text-align: left;
}

.modern-header__search :deep(.form-control::placeholder) {
  color: rgba(255, 255, 255, 0.85);
}

.modern-header__search :deep(.search-icon-btn),
.modern-header__search :deep(.custom-search-btn) {
  background: transparent !important;
  border: 0 !important;
  color: #fff !important;
  box-shadow: none !important;
  min-width: 44px;
  height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.modern-header__suggestions {
  position: absolute;
  top: calc(100% + 6px);
  left: 0;
  right: 0;
  z-index: 50;
  max-height: 60vh;
  overflow-y: auto;
  border-radius: 12px;
}

/* Theme-colored circular offcanvas + cart */
.modern-header__offcanvas {
  flex-shrink: 0;
}

.modern-header__offcanvas :deep(.hamburger) {
  position: relative;
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  border-radius: 50%;
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0;
  background-color: var(--mainC) !important;
  border: 1px solid var(--mainC) !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
  overflow: hidden;
}

.modern-header__offcanvas :deep(.hamburger span) {
  display: block;
  flex-shrink: 0;
  height: 2px;
  margin-top: 2px;
  margin-bottom: 2px;
  background-color: #fff !important;
}

.modern-header__offcanvas :deep(.hamburger span:first-child) {
  margin-top: 0;
}

.modern-header__offcanvas :deep(.hamburger span:nth-child(1)) {
  width: 8px;
}

.modern-header__offcanvas :deep(.hamburger span:nth-child(2)) {
  width: 14px;
}

.modern-header__offcanvas :deep(.hamburger span:nth-child(3)) {
  width: 8px;
}

.modern-header__offcanvas :deep(.hamburger.active span) {
  transform: none !important;
  opacity: 1 !important;
}

.modern-header__langcurrency {
  flex-shrink: 0;
}

.modern-header__langcurrency :deep(.langcurrency-trigger.btn-circle) {
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  padding: 0 !important;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: var(--mainC) !important;
  border: 1px solid var(--mainC) !important;
  color: #fff !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
}

.modern-header__langcurrency :deep(.langcurrency-trigger .material-icons) {
  font-size: 18px !important;
  color: #fff !important;
}

.modern-header__langcurrency :deep(.langcurrency-trigger.btn-circle:hover) {
  background-color: var(--mainC) !important;
  border-color: var(--mainC) !important;
  color: #fff !important;
}

.modern-header__info {
  position: relative;
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: var(--mainC) !important;
  border: 1px solid var(--mainC) !important;
  color: #fff;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
}

.modern-header__info .material-icons {
  font-size: 18px;
  color: #fff;
}

.modern-header__cart {
  position: relative;
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: var(--mainC) !important;
  border: 1px solid var(--mainC) !important;
  color: #fff;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
}

.modern-header__cart :deep(svg) {
  color: #fff;
  fill: #fff;
}

.modern-header__cart-count {
  position: absolute;
  top: -4px;
  right: -4px;
  z-index: 3;
  min-width: 18px;
  height: 18px;
  padding: 0 4px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  background: #fff;
  color: var(--mainC);
  font-size: 10px;
  font-weight: 600;
  line-height: 1;
  pointer-events: none;
}

.modern-header__nav {
  display: flex;
  align-items: center;
  gap: 8px;
  min-height: 36px;
}

.modern-header__back {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  padding: 0;
  border: 0;
  background: transparent;
  color: #fff;
  cursor: pointer;
}

.modern-header__back .material-icons {
  font-size: 22px;
}

.modern-header__title {
  margin: 0;
  font-size: 1.35rem;
  font-weight: 600;
  line-height: 1.2;
  color: #fff;
  letter-spacing: 0.01em;
}

.modern-header--rtl,
.modern-header--rtl .modern-header__toolbar,
.modern-header--rtl .modern-header__toolbar--home,
.modern-header--rtl .modern-header__nav {
  direction: rtl;
}

.modern-header--rtl .modern-header__title {
  text-align: right;
}

.modern-header--rtl .modern-header__search :deep(.form-control) {
  padding-right: 18px;
  padding-left: 8px;
}

.modern-header--rtl .modern-header__suggestion-heading,
.modern-header--rtl .modern-header__suggestion-item {
  text-align: right;
}

.modern-header--rtl .modern-header__suggestions {
  direction: rtl;
}

.modern-header--rtl .modern-header__suggestions :deep(.single-product-item .position-relative.d-flex) {
  flex-direction: row; /* inherit rtl; do not row-reverse again */
}

.modern-header--rtl .modern-header__suggestions :deep(.d-block.pr-10) {
  padding-right: 0;
  padding-left: 10px;
}

.modern-header--rtl .modern-header__suggestions :deep(.product-title),
.modern-header--rtl .modern-header__suggestions :deep(.product-price) {
  text-align: right;
}

.modern-header--rtl .modern-header__cart-count {
  left: -4px;
  right: auto;
}

.modern-header--home .modern-header__search :deep(.input-group) {
  background: rgba(255, 255, 255, 0.38);
}

.modern-header--page:not([style*="background-image"]) {
  background-image: linear-gradient(135deg, #5a4030 0%, #2c1c14 100%);
}
</style>
