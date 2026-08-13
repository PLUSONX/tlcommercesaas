<template>

  <div class="banner-feature">

    <div v-if="!isModernLayout" class="banner-feature__toolbar" :class="[toolbarAlignClass, { 'banner-feature__toolbar--rtl': isRtl }]">

      <the-offcanvas :user-info="customerInfo" :menu-items="offcanvas.menuItems" :header-menu-style="headerMenuStyle"
        :header-style="headerStyle" class="banner-feature__offcanvas" />

      <router-link to="/cart" class="banner-feature__cart btn-circle custom-icon-btn">

        <base-icon-svg name="cart" class="material-icons" :width="10" :height="10" />

        <span class="count position-absolute d-flex align-items-center justify-content-center">

          {{ cartItem }}

        </span>

      </router-link>

      <search-form class="banner-feature__search" style-two mobile-style
        overlay-teleport=".split-screen-search-inline-host" />

      <language-currency-switcher
        class="banner-feature__langcurrency"
        :currencies="currencies"
        :languages="languages"
        :data-loading="dataLoading"
        :use-fixed-dropdown="true"
        @change-language-currency="setCurrencyLanguage"
      />

    </div>



    <img v-if="imagePath" :src="`/${imagePath}`" :alt="settings?.feature_image_name || 'Feature Banner'"
      class="feature-image" />

    <div v-else class="placeholder">

      No image available

    </div>

  </div>

</template>



<script>

import offcanvas from "@/fakeDB/offcanvas.json";

import SearchForm from "@/components/ui/SearchForm.vue";

import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";

import LanguageCurrencySwitcher from "@/components/pageheader/LanguageCurrencySwitcher.vue";

import { mapState, mapGetters } from "vuex";



export default {

  name: 'BannerFeature',

  components: {

    SearchForm,

    TheOffcanvas,

    LanguageCurrencySwitcher,

  },

  emits: ["change-language-currency"],

  props: {

    settings: {

      type: Object,

      required: true

    },

    imagePath: {

      type: String,

      default: null

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

  data() {

    return {

      offcanvas,

    };

  },

  computed: {

    ...mapState({

      customerInfo: (state) => state.customerInfo,

    }),

    ...mapGetters("layout", ["effectiveContentPosition", "isRtl", "layoutType"]),

    isModernLayout() {
      return this.layoutType === "modern";
    },

    toolbarAlignClass() {
      return this.effectiveContentPosition === "left"
        ? "banner-feature__toolbar--inner-left"
        : "banner-feature__toolbar--inner-right";
    },

  },

  methods: {

    setCurrencyLanguage(lang, currency) {

      this.$emit("change-language-currency", lang, currency);

    },

  },

}

</script>



<style scoped>
.banner-feature {

  position: relative;

  width: 100%;

  height: 100%;

  overflow: hidden;

  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

}



.banner-feature__toolbar {

  position: absolute;

  top: 16px;

  z-index: 10;

  display: flex;

  align-items: center;

  gap: 8px;

  direction: ltr;

}



/* RTL — explicit swap (split-screen feature column uses direction: ltr) */
.banner-feature__toolbar--rtl {
  flex-direction: row-reverse;
  direction: ltr;
}



.banner-feature__toolbar--inner-left {

  left: 16px;

  right: auto;

}



.banner-feature__toolbar--inner-right {

  right: 16px;

  left: auto;

}



.feature-image {

  width: 100%;

  height: 100%;

  object-fit: cover;

}



.placeholder {

  color: #999;

  font-size: 18px;

}



/* 20px circular toolbar buttons (cart, offcanvas, search, langcurrency) */

.banner-feature__toolbar :deep(.banner-feature__cart.btn-circle),

.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger),

.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button),

.banner-feature__toolbar :deep(.banner-feature__langcurrency .langcurrency-trigger) {

  width: 40px !important;

  height: 40px !important;

  min-width: 40px !important;

  border-radius: 50%;

  display: inline-flex;

  align-items: center;

  justify-content: center;

  padding: 0;

  flex-shrink: 0;

  background-color: #fff !important;

  border: 1px solid #fff !important;

  color: #141414;

  transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;

}



.banner-feature__toolbar :deep(.banner-feature__cart.btn-circle:hover),

.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger:hover),

.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button:hover),

.banner-feature__toolbar :deep(.banner-feature__langcurrency .langcurrency-trigger:hover) {

  background-color: var(--mainC) !important;

  border: 1px solid #fff !important;

  color: #fff;

}



.banner-feature__toolbar :deep(.banner-feature__cart.btn-circle svg),

.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button svg) {

  width: 12px !important;

  height: 12px !important;

}



.banner-feature__toolbar :deep(.banner-feature__cart.btn-circle:hover svg) {

  color: #fff;

}



.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.text-white) {

  color: #141414 !important;

  cursor: pointer;

  pointer-events: auto;

}



.banner-feature__toolbar :deep(.banner-feature__search.custom-search-btn-mobile .icon-wrapper),

.banner-feature__toolbar :deep(.banner-feature__search.custom-search-btn-mobile .icon-wrapper svg),

.banner-feature__toolbar :deep(.banner-feature__search.custom-search-btn-mobile svg) {

  color: #141414 !important;

  fill: #141414 !important;

}



.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.text-white:hover) {

  color: #fff !important;

}



.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.text-white:hover .icon-wrapper),

.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.text-white:hover .icon-wrapper svg),

.banner-feature__toolbar :deep(.banner-feature__search.custom-search-btn-mobile > button.text-white:hover svg) {

  color: #fff !important;

  fill: #fff !important;

}



.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper) {

  position: relative;

  z-index: 1;

}



.banner-feature__toolbar :deep(.banner-feature__cart.btn-circle .count) {

  width: 18px;

  height: 18px;

  font-size: 11px;

  line-height: 1;

  inset-inline-end: -6px;

  top: -4px;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger) {

  flex-direction: column;

  overflow: hidden;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger span) {

  display: block;

  flex-shrink: 0;

  height: 2px;

  margin-top: 2px;

  margin-bottom: 2px;

  background-color: #141414 !important;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger span:first-child) {

  margin-top: 0;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger span:nth-child(1)) {

  width: 8px;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger span:nth-child(2)) {

  width: 14px;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger span:nth-child(3)) {

  width: 8px;

}



.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger:hover span) {

  background-color: #fff !important;

}



/* Keep three lines when menu is open — cancel global .hamburger.active animation */

.banner-feature__toolbar :deep(.banner-feature__offcanvas .hamburger.active span) {

  transform: none !important;

  opacity: 1 !important;

}



/* Override Bootstrap bg-transparent + border-0 on search toggle */

.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.bg-transparent.border-0) {

  background-color: #fff !important;

  background-image: none !important;

  border: 1px solid #fff !important;

}



.banner-feature__toolbar :deep(.banner-feature__search.search-form-wrapper > button.bg-transparent.border-0:hover) {

  background-color: var(--mainC) !important;

  border: 1px solid #fff !important;

  color: #fff !important;

}



.banner-feature__toolbar :deep(.banner-feature__langcurrency .langcurrency-trigger .material-icons) {

  font-size: 18px !important;

}



.banner-feature__toolbar :deep(.banner-feature__langcurrency .langcurrency-trigger:hover .material-icons) {

  color: #fff !important;

}



.banner-feature__toolbar :deep(.banner-feature__langcurrency .langcurrency-wrap) {

  display: flex;

  align-items: center;

}
</style>
