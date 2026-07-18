<template>
  <div
    class="langcurrency-wrap"
    ref="dropdownMenu"
    v-if="!dataLoading"
  >
    <button
      type="button"
      ref="triggerBtn"
      class="btn-circle custom-icon-btn langcurrency-trigger"
      @click.prevent="toggleDropdown"
    >
      <span class="material-icons">language</span>
    </button>

    <div
      v-if="showLanguageCurrency && !useFixedDropdown"
      class="my-account-dropdown langcurrency-dropdown"
    >
      <ul class="list-unstyled mb-0">
        <li class="langcurrency-section-label">{{ $t("Language") }}</li>
        <li v-for="(lang, index) in languages" :key="`lang-${index}`">
          <a
            href="#"
            class="custom-menu"
            :class="{ active: selected_lang === lang.code }"
            @click.prevent="selectLanguage(lang.code)"
          >{{ lang.title }}</a>
        </li>
        <li class="langcurrency-section-label">{{ $t("Currency") }}</li>
        <li v-for="(currency, index) in currencies" :key="`currency-${index}`">
          <a
            href="#"
            class="custom-menu"
            :class="{ active: selected_currency?.code === currency.code }"
            @click.prevent="selectCurrency(currency)"
          >{{ currency.code }}</a>
        </li>
        <!-- <li>
          <a
            href="#"
            class="custom-menu langcurrency-save"
            @click.prevent="setCurrencyLanguage"
          >{{ $t("Save Changes") }}</a>
        </li> -->
      </ul>
    </div>

    <Teleport to="body">
      <div
        v-if="showLanguageCurrency && useFixedDropdown"
        ref="fixedDropdown"
        class="my-account-dropdown langcurrency-dropdown langcurrency-dropdown--fixed"
        :style="fixedDropdownStyle"
      >
        <ul class="list-unstyled mb-0">
          <li class="langcurrency-section-label">{{ $t("Language") }}</li>
          <li v-for="(lang, index) in languages" :key="`lang-fixed-${index}`">
            <a
              href="#"
              class="custom-menu"
              :class="{ active: selected_lang === lang.code }"
              @click.prevent="selectLanguage(lang.code)"
            >{{ lang.title }}</a>
          </li>
          <li class="langcurrency-section-label">{{ $t("Currency") }}</li>
          <li v-for="(currency, index) in currencies" :key="`currency-fixed-${index}`">
            <a
              href="#"
              class="custom-menu"
              :class="{ active: selected_currency?.code === currency.code }"
              @click.prevent="selectCurrency(currency)"
            >{{ currency.code }}</a>
          </li>
          <!-- <li>
            <a
              href="#"
              class="custom-menu langcurrency-save"
              @click.prevent="setCurrencyLanguage"
            >{{ $t("Save Changes") }}</a>
          </li> -->
        </ul>
      </div>
    </Teleport>
  </div>

  <div class="langcurrency-wrap" ref="dropdownMenu" v-if="dataLoading">
    <skeleton
      width="45px"
      height="45px"
      border-radius="50%"
    ></skeleton>
  </div>
</template>

<script>
import { safeGetItem, safeJsonParse } from "../../utils/safeStorage";

export default {
  name: "LanguageCurrencySwitcher",
  emits: ["change-language-currency"],
  props: {
    currencies: {
      type: Array,
      required: false,
      default: () => [],
    },
    languages: {
      type: Array,
      required: false,
      default: () => [],
    },
    dataLoading: {
      type: Boolean,
      required: true,
      default: false,
    },
    useFixedDropdown: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selected_lang: safeGetItem("locale") || "en",
      selected_currency: safeJsonParse("currency", null),
      showLanguageCurrency: false,
      fixedDropdownStyle: {},
    };
  },
  watch: {
    showLanguageCurrency(isOpen) {
      if (isOpen && this.useFixedDropdown) {
        this.$nextTick(() => {
          this.updateFixedDropdownPosition();
        });
      }
    },
  },
  mounted() {
    document.addEventListener("click", this.close);
    window.addEventListener("scroll", this.onViewportChange, true);
    window.addEventListener("resize", this.onViewportChange);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.close);
    window.removeEventListener("scroll", this.onViewportChange, true);
    window.removeEventListener("resize", this.onViewportChange);
  },
  methods: {
    toggleDropdown() {
      this.showLanguageCurrency = !this.showLanguageCurrency;
    },
    updateFixedDropdownPosition() {
      const trigger = this.$refs.triggerBtn;
      if (!trigger) return;

      const rect = trigger.getBoundingClientRect();
      const isRtl = document.documentElement.dir === "rtl";

      if (isRtl) {
        this.fixedDropdownStyle = {
          top: `${rect.bottom + 10}px`,
          right: `${window.innerWidth - rect.right}px`,
          left: "auto",
        };
      } else {
        this.fixedDropdownStyle = {
          top: `${rect.bottom + 10}px`,
          right: `${window.innerWidth - rect.right}px`,
          left: "auto",
        };
      }
    },
    onViewportChange() {
      if (this.showLanguageCurrency && this.useFixedDropdown) {
        this.updateFixedDropdownPosition();
      }
    },
    selectLanguage(code) {
      if (this.selected_lang === code) return;
      this.selected_lang = code;
      this.applySelection();
    },
    selectCurrency(currency) {
      if (this.selected_currency?.code === currency.code) return;
      this.selected_currency = currency;
      this.applySelection();
    },
    applySelection() {
      this.showLanguageCurrency = false;
      this.setCurrencyLanguage();
    },
    setCurrencyLanguage() {
      this.$emit(
        "change-language-currency",
        this.selected_lang,
        this.selected_currency
      );
    },
    close(e) {
      const wrap = this.$refs.dropdownMenu;
      const fixedDropdown = this.$refs.fixedDropdown;
      const target = e.target;

      const insideWrap = wrap && (wrap === target || wrap.contains(target));
      const insideFixed =
        fixedDropdown &&
        (fixedDropdown === target || fixedDropdown.contains(target));

      if (!insideWrap && !insideFixed) {
        this.showLanguageCurrency = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.langcurrency-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.langcurrency-trigger {
  .material-icons {
    font-size: 20px;
  }
}

.my-account-dropdown {
  position: absolute;
  top: calc(100% + 12px);
  left: auto;
  right: auto;
  inset-inline-end: 0;
  z-index: 9999;
  min-width: 190px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(0, 0, 0, 0.08);
  overflow: hidden;
  animation: dropdownFade 0.2s ease-out;

  ul {
    margin: 0 !important;
    padding: 8px 0 !important;
    list-style: none !important;
    display: block !important;

    li {
      margin: 0 !important;
      padding: 0 !important;
      display: block !important;
      width: 100%;

      a {
        display: block;
        padding: 12px 20px;
        color: #333333 !important;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        text-align: left;

        &:hover,
        &.active {
          background-color: #f4f6f9;
          color: var(--c1, #000) !important;
          padding-left: 25px;
        }
      }

      &.langcurrency-section-label {
        padding: 8px 20px 4px !important;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #a8a6a6 !important;
        letter-spacing: 0.05em;
        pointer-events: none;
      }
    }
  }
}

.langcurrency-dropdown--fixed {
  position: fixed;
  z-index: 10000;
}

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
</style>
