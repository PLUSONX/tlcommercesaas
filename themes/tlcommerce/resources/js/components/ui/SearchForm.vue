<template>
  <div class="search-form-wrapper custom-search-btn-mobile">
    <!-- Search Toggle -->
    <button v-if="mobileStyle" class="p-0 bg-transparent border-0 text-white" @click="toggleSearchForm">
      <base-icon-svg name="search" :height="21" :width="21" />
    </button>
    <!-- End Search Toggle -->

    <teleport :to="teleportTarget" :disabled="teleportDisabled">
      <form v-if="showMobileOverlayForm"
        class="search-form mobile-search-form w-100 d-flex align-items-center"
        :class="[
          { active: searchFormActive },
          mobileOverlayFormClass,
        ]"
        action="#">
        <button type="button"
          class="goback border-0 mr-20 d-inline-flex align-items-center justify-content-center"
          @click="toggleSearchForm">
          <base-icon-svg name="undo" :height="20" :width="20" />
        </button>

        <div class="input-group" :class="[
          { 'style--rounded': rounded },
          { 'style--two': styleTwo },
          { 'style--three': styleThree },
          { 'style--four': styleFour },
        ]">
          <input type="text" v-bind:placeholder="$t('Enter your search key')" class="form-control"
            v-model="searching_Key" v-on:keyup="getSearchSuggestions"
            style="position: relative; z-index: 99999; pointer-events: all;" />
          <button v-if="!styleFour" type="submit" class="btn btn_fill custom-search-btn"
            @click.prevent="searchProducts">
            {{ $t("Search") }}
          </button>
          <button v-else type="submit" class="search-icon-btn" @click.prevent="searchProducts">
            <span class="material-icons"> search </span>
          </button>
        </div>
      </form>
    </teleport>

    <form v-if="!mobileStyle" class="search-form" action="#">
      <div class="input-group" :class="[
        { 'style--rounded': rounded },
        { 'style--two': styleTwo },
        { 'style--three': styleThree },
        { 'style--four': styleFour },
      ]">
        <input type="text" v-bind:placeholder="$t('Enter your search key')" class="form-control" v-model="searching_Key"
          v-on:keyup="getSearchSuggestions" />
        <button v-if="!styleFour" type="submit" class="btn btn_fill custom-search-btn" @click.prevent="searchProducts">
          {{ $t("Search") }}
        </button>
        <button v-else type="submit" class="search-icon-btn" @click.prevent="searchProducts">
          <span class="material-icons"> search </span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>

import { mapGetters } from "vuex";
export default {
  name: "SearchForm",
  emits: ["search-suggestions"],
  props: {
    rounded: {
      type: Boolean,
      default: false,
    },
    styleTwo: {
      type: Boolean,
      default: false,
    },
    styleThree: {
      type: Boolean,
      default: false,
    },
    styleFour: {
      type: Boolean,
      default: false,
    },
    mobileStyle: {
      type: Boolean,
      default: false,
    },
    overlayTeleport: {
      type: String,
      default: "",
    },
    fixedOverlay: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      searchFormActive: false,
      searching_Key: "",
    };
  },
  watch: {
    $route() {
      this.closeSearchForm();
      if (this.$route.name !== "searchProduct") {
        this.searching_Key = "";
      }
    },
    isMobile(isMobile) {
      if (isMobile && this.searchFormActive) {
        this.closeSearchForm();
      }
    },
  },
  computed: {
    ...mapGetters('layout', ['isMobile']),

    usesBodyFixedOverlay() {
      return this.mobileStyle && this.fixedOverlay;
    },

    usesHostTeleport() {
      return this.mobileStyle && !!this.overlayTeleport;
    },

    teleportDisabled() {
      if (this.usesBodyFixedOverlay || this.usesHostTeleport) {
        return !this.searchFormActive;
      }
      return true;
    },

    teleportTarget() {
      if (this.usesBodyFixedOverlay) {
        return "body";
      }
      return this.overlayTeleport || "body";
    },

    showMobileOverlayForm() {
      if (!this.mobileStyle) {
        return false;
      }
      if (this.usesBodyFixedOverlay || this.usesHostTeleport) {
        return this.searchFormActive;
      }
      return true;
    },

    mobileOverlayFormClass() {
      if (this.usesBodyFixedOverlay) {
        return "mobile-search-form--fixed";
      }
      if (this.usesHostTeleport) {
        return "mobile-search-form--split-inline";
      }
      return "position-absolute";
    },
  },
  beforeUnmount() {
    this.syncOverlayHost(false);
  },
  methods: {
    syncOverlayHost(isOpen) {
      if (!this.overlayTeleport) {
        return;
      }

      const host = document.querySelector(this.overlayTeleport);
      if (!host) {
        return;
      }

      host.classList.toggle("is-open", isOpen);
    },
    /**
     * get search suggestions
     */
    getSearchSuggestions(event) {
      if (event.key != "Enter") {
        this.$emit("search-suggestions", this.searching_Key);
      }
    },
    /**
     * Get suggestions products
     *
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
          .catch((error) => { });
      } else {
        this.suggestionsOpen = false;
      }
    },
    /**
     * Search Products
     *
     */
    searchProducts() {
      this.searchFormActive = false;
      this.syncOverlayHost(false);
      this.$router.push("/product/search?search_key=" + this.searching_Key);
    },
    toggleSearchForm() {
      this.searchFormActive = !this.searchFormActive;
      this.syncOverlayHost(this.searchFormActive);
    },
    closeSearchForm() {
      this.searchFormActive = false;
      this.syncOverlayHost(false);
    },
  },
};
</script>

<style scoped>
.search-form-wrapper {
  position: relative;
}

:deep(.mobile-search-form--fixed),
:deep(.mobile-search-form:not(.mobile-search-form--split-inline):not(.mobile-search-form--fixed)) {
  transform: translate3d(0, -100%, 0);
  pointer-events: none;
  visibility: hidden;
}

:deep(.mobile-search-form--fixed.active),
:deep(.mobile-search-form:not(.mobile-search-form--split-inline):not(.mobile-search-form--fixed).active) {
  transform: translate3d(0, 0, 0);
  visibility: visible;
}

:deep(.mobile-search-form--fixed) {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  width: 100%;
  z-index: 10001;
}

:deep(.mobile-search-form--split-inline:not(.active)) {
  display: none;
}

:deep(.mobile-search-form--split-inline.active) {
  display: flex;
}
</style>
