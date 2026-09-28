<template>
  <div class="quiz-page-wrap">
    <div class="quiz-lang-toggle">
      <button
        type="button"
        class="quiz-lang-toggle__btn"
        :class="{ 'is-active': isActive('en') }"
        @click="switchLanguage('en')"
      >
        EN
      </button>
      <button
        type="button"
        class="quiz-lang-toggle__btn"
        :class="{ 'is-active': isActive('ar') }"
        @click="switchLanguage('ar')"
      >
        AR
      </button>
    </div>

    <quiz-flow />
  </div>
</template>

<script>
import axios from "axios";
import QuizFlow from "@/components/quiz/QuizFlow.vue";
import { safeGetItem, safeJsonParse } from "@/utils/safeStorage";

export default {
  name: "QuizPage",
  components: {
    QuizFlow,
  },
  data() {
    return {
      currentLang: safeGetItem("locale") || "en",
      languages: [],
      switching: false,
    };
  },
  computed: {
    // Resolves the *actual* stored code for "en" / "ar" from the real
    // language list (e.g. Arabic might be "ar", "sa", etc. per store).
    codeMap() {
      const map = { en: "en", ar: "ar" };
      this.languages.forEach((lang) => {
        const rawTitle = lang.title || lang.name || "";
        const title = rawTitle.toLowerCase();
        const code = (lang.code || "").toLowerCase();
        const isArabicScript = /[\u0600-\u06FF]/.test(rawTitle);

        if (title.includes("english") || code === "en") {
          map.en = lang.code || map.en;
        }
        if (
          isArabicScript ||
          title.includes("arab") ||
          code === "ar" ||
          code === "sa"
        ) {
          map.ar = lang.code || map.ar;
        }
      });
      return map;
    },
  },
  mounted() {
    // Pre-fetch so the correct button shows as active immediately on load,
    // not only after the user clicks a toggle.
    this.ensureLanguages();
  },
  methods: {
    isActive(key) {
      const code = this.codeMap[key];
      return (
        this.currentLang &&
        code &&
        this.currentLang.toLowerCase() === code.toLowerCase()
      );
    },
    // Same endpoint MainLayout.vue calls to get its language list, so this
    // works correctly on any route, not just the home page.
    async ensureLanguages() {
      if (this.languages.length) return this.languages;
      try {
        const headers = {
          "Content-Type": "application/json",
          "Accept-Language": safeGetItem("locale") || "en",
        };
        const response = await axios.post(
          "/api/v1/ecommerce-core/site-properties",
          null,
          { headers }
        );
        if (response.data && response.data.success) {
          this.languages = response.data.languages || [];
        }
      } catch (e) {
        // fall through with defaults already in codeMap
      }
      return this.languages;
    },
    async switchLanguage(key) {
      if (this.switching) return;
      this.switching = true;

      await this.ensureLanguages();
      const code = this.codeMap[key];

      if (!code || this.isActive(key)) {
        this.switching = false;
        return;
      }

      // Same storage keys + reload used everywhere else in the app
      // (see MainLayout.vue -> setCurrencyLanguage), so the rest of the
      // site (RTL, translations, currency) stays perfectly in sync.
      const currency = safeJsonParse("currency", null);
      localStorage.setItem("locale", code);
      localStorage.setItem("currency", JSON.stringify(currency));
      location.reload();
    },
  },
};
</script>

<style scoped lang="scss">
.quiz-page-wrap {
  position: relative;
  min-height: 100%;
}

.quiz-lang-toggle {
  position: absolute;
  top: 16px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 40;
  display: inline-flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.35);
  border-radius: 999px;
  padding: 3px;
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}

.quiz-lang-toggle__btn {
  border: 0;
  background: transparent;
  color: rgba(255, 255, 255, 0.85);
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.03em;
  padding: 6px 16px;
  border-radius: 999px;
  cursor: pointer;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.quiz-lang-toggle__btn.is-active {
  background: #f2c14e;
  color: #16321f;
}
</style>