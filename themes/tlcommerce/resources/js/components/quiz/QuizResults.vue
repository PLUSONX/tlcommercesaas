<template>
  <div class="quiz-results" :class="rootClasses" :style="pageStyles">
    <div class="quiz-results__inner" :style="innerStyles">
      <div v-if="!results.length" class="quiz-results__empty alert alert-info">
        {{ $t("No matching products found.") }}
      </div>

      <div v-if="showFeaturedCard && results.length" class="quiz-results__featured" :style="cardWrapperStyles">
        <quiz-results-stack :blocks="config.blocks" :theme="config.theme" :results="results"
          :hero-image="heroBlockImage" />
      </div>

      <div v-if="showProductGrid && results.length" class="quiz-results__grid-section"
        :class="{ 'mt-4': showFeaturedCard }">
        <template v-if="gridConfig.show_heading">
          <h3 v-if="resolvedHeading" class="mb-2">{{ resolvedHeading }}</h3>
          <p v-if="resolvedSubheading" class="text-muted mb-4">{{ resolvedSubheading }}</p>
        </template>

        <template v-if="isSplitScreen && !isMobile">
          <div class="row g-0 mobile-gap-10">
            <div v-for="item in results" :key="item.product_id" class="col-6 compact-card mb-3">
              <div class="quiz-result-card">
                <span v-if="gridConfig.show_match_badge" class="quiz-match-badge" :style="matchBadgeStyles">{{
                  item.match_pct }}% {{ $t("match") }}</span>
                <single-product v-if="item.product" :item="item.product" styleEight />
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="row mobile-gap-10">
            <div v-for="item in results" :key="item.product_id" class="col-lg-4 col-md-6 col-6 mb-3">
              <div class="quiz-result-card">
                <span v-if="gridConfig.show_match_badge" class="quiz-match-badge" :style="matchBadgeStyles">{{
                  item.match_pct }}% {{ $t("match") }}</span>
                <single-product v-if="item.product" :item="item.product" styleEight />
              </div>
            </div>
          </div>
        </template>
      </div>

      <div v-if="activeActions.length" class="quiz-results__actions" :style="actionsRowStyles">
        <template v-for="action in activeActions" :key="action.id">
          <button v-if="action.action === 'restart'" type="button" class="quiz-results__action-btn"
            :class="actionClass(action)" :style="actionStyles(action)" @click="$emit('restart')">
            {{ actionLabel(action) }}
          </button>
          <button v-else-if="action.action === 'share'" type="button" class="quiz-results__action-btn"
            :class="actionClass(action)" :style="actionStyles(action)" @click="handleShare">
            {{ actionLabel(action) }}
          </button>
          <a v-else-if="resolvedActionLinks[action.id]?.kind === 'router'" :href="resolvedActionLinks[action.id].href"
            class="quiz-results__action-btn" :class="actionClass(action)" :style="actionStyles(action)"
            @click.prevent="navigateAction(resolvedActionLinks[action.id].to)">
            {{ actionLabel(action) }}
          </a>
          <a v-else-if="resolvedActionLinks[action.id]?.kind === 'external'" :href="resolvedActionLinks[action.id].href"
            target="_blank" rel="noopener noreferrer" class="quiz-results__action-btn" :class="actionClass(action)"
            :style="actionStyles(action)">
            {{ actionLabel(action) }}
          </a>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import SingleProduct from "@/components/product/SingleProduct.vue";
import QuizResultsStack from "@/components/quiz/QuizResultsStack.vue";
import { buildResultContext, resolveTokens, getProductForRank, resolveActionLink } from "@/utils/quizResultTokens";

const LEGACY_RESULTS_CONFIG = {
  show_featured_card: false,
  show_product_grid: true,
  theme: {
    background_type: "solid",
    background_color: "transparent",
    fill_viewport: false,
    content_max_width: 900,
    alignment: "left",
    text_color: "#111111",
    card_enabled: false,
  },
  blocks: [],
  product_grid: {
    show_heading: true,
    heading: "",
    subheading: "",
    show_match_badge: true,
    match_badge_bg: "#ff5a1f",
    match_badge_text: "#ffffff",
  },
  actions: [
    {
      id: "restart",
      enabled: true,
      label: "Retake Quiz",
      style: "outline",
      action: "restart",
      bg_color: "transparent",
      text_color: "#111111",
      border_color: "#111111",
    },
  ],
};

export default {
  name: "QuizResults",
  components: {
    SingleProduct,
    QuizResultsStack,
  },
  props: {
    quiz: { type: Object, default: null },
    results: { type: Array, default: () => [] },
  },
  emits: ["restart", "share"],
  computed: {
    ...mapGetters(["isSplitScreen", "isMobile"]),
    config() {
      const cfg = this.quiz?.layout_config?.results;

      // console.log("cfg: ", cfg);
      if (cfg) return cfg;
      return LEGACY_RESULTS_CONFIG;
    },
    isLegacy() {
      return !this.quiz?.layout_config?.results;
    },
    showFeaturedCard() {
      if (this.isLegacy) return false;
      return !!this.config.show_featured_card;
    },
    showProductGrid() {
      return this.config.show_product_grid !== false;
    },
    gridConfig() {
      return this.config.product_grid || {};
    },
    tokenContext() {
      return buildResultContext(this.results);
    },
    resolvedHeading() {
      const h = this.gridConfig.heading;
      if (!h) return this.isLegacy ? this.$t("Your recommendations") : "";
      return resolveTokens(h, this.tokenContext).replace(/<[^>]+>/g, "");
    },
    resolvedSubheading() {
      const s = this.gridConfig.subheading;
      if (!s) {
        return this.isLegacy
          ? this.$t("Based on your answers, these products are the best match for you.")
          : "";
      }
      return resolveTokens(s, this.tokenContext).replace(/<[^>]+>/g, "");
    },
    activeActions() {
      const actions = this.config.actions || [];
      return actions.filter((a) => a.enabled !== false);
    },
    theme() {
      return this.config.theme || {};
    },
    fillsViewport() {
      if (this.isLegacy) return false;
      return this.theme.fill_viewport !== false;
    },
    rootClasses() {
      return {
        "quiz-results--fill": this.fillsViewport,
        "quiz-results--fill-centered": this.fillsViewport && !this.showProductGrid,
        "quiz-results--legacy": this.isLegacy,
      };
    },
    pageStyles() {
      const t = this.theme;
      let background = t.background_color || "transparent";
      if (t.background_type === "radial_gradient") {
        const center = t.background_gradient_center || t.background_color || "#1a3d2a";
        const edge = t.background_gradient_edge || "#050a07";
        background = `radial-gradient(circle at center, ${center} 0%, ${edge} 100%)`;
      }
      const styles = {
        background,
        color: t.text_color || "inherit",
        "--quiz-results-accent": t.accent_color || "#c9a84c",
        width: "100%",
        boxSizing: "border-box",
      };
      if (this.fillsViewport) {
        styles.minHeight = "max(400px, 100vh)";
        styles.padding = "32px 24px";
      }
      return styles;
    },
    innerStyles() {
      const align = this.theme.alignment || "left";
      const maxW = this.theme.content_max_width || 900;
      return {
        maxWidth: `${maxW}px`,
        margin: "0 auto",
        textAlign: align,
        width: "100%",
      };
    },
    cardWrapperStyles() {
      const t = this.theme;
      if (!t.card_enabled) {
        return { maxWidth: `${t.card_max_width || 480}px`, margin: "0 auto", width: "100%" };
      }
      const borderColor = t.card_border_color || "#c9a84c";
      const styles = {
        maxWidth: `${t.card_max_width || 480}px`,
        margin: "0 auto",
        width: "100%",
        background: t.card_background || "#1f4530",
        padding: `${t.card_padding ?? 32}px`,
        borderRadius: `${t.card_border_radius ?? 0}px`,
        boxSizing: "border-box",
      };
      if (t.card_border_style === "double") {
        styles.border = `3px double ${borderColor}`;
      } else if (t.card_border_style === "single") {
        styles.border = `1px solid ${borderColor}`;
      }
      return styles;
    },
    matchBadgeStyles() {
      return {
        background: this.gridConfig.match_badge_bg || "#ff5a1f",
        color: this.gridConfig.match_badge_text || "#fff",
      };
    },
    actionsRowStyles() {
      const align = this.theme.alignment || "center";
      return {
        justifyContent: align === "left" ? "flex-start" : align === "right" ? "flex-end" : "center",
      };
    },
    heroBlockImage() {
      const heroBlock = (this.config.blocks || []).find((b) => b.type === "hero_image" && b.enabled !== false);
      if (!heroBlock?.image) return null;
      return heroBlock.image;
    },
    resolvedActionLinks() {
      const map = {};
      for (const action of this.activeActions) {
        const link = this.actionLink(action);
        if (!link) continue;
        if (link.kind === "router") {
          const resolved = this.$router.resolve(link.to);
          map[action.id] = { kind: "router", to: link.to, href: resolved.href };
        } else {
          map[action.id] = link;
        }
      }
      return map;
    },
  },
  methods: {
    actionLabel(action) {
      if (action.action === "restart" && this.isLegacy) {
        return this.$t("Retake Quiz");
      }
      return action.label;
    },
    actionClass(action) {
      return {
        "quiz-results__action-btn--solid": action.style === "solid",
        "quiz-results__action-btn--outline": action.style === "outline",
        "quiz-results__action-btn--glow": action.style === "gradient_glow",
      };
    },
    actionStyles(action) {
      const bg = action.bg_color || "#c9a84c";
      const text = action.text_color || "#1a3d2a";
      const border = action.border_color || bg;
      const styles = {
        borderColor: border,
        color: text,
      };
      if (action.style === "outline") {
        styles.background = "transparent";
        styles.color = text || border;
      } else {
        styles.background = bg;
        styles.color = text;
      }
      if (action.style === "gradient_glow") {
        styles.boxShadow = `0 0 14px color-mix(in srgb, ${bg} 55%, transparent)`;
      }
      return styles;
    },
    productUrlForAction(action) {
      const p = getProductForRank(this.tokenContext, action.product_rank || 1);
      return p.url || null;
    },
    actionLink(action) {
      if (action.action === "top_product") {
        const customUrl = String(action.url || "").trim();
        return resolveActionLink(customUrl || this.productUrlForAction(action));
      }
      if (action.action === "link") {
        return resolveActionLink(action.url);
      }
      return null;
    },
    navigateAction(to) {
      this.$router.push(to);
    },
    async handleShare() {
      const url = window.location.href;
      if (navigator.share) {
        try {
          await navigator.share({ title: this.quiz?.title || "", url });
          this.$emit("share", { url });
          return;
        } catch (e) {
          // fall through to copy
        }
      }
      try {
        await navigator.clipboard.writeText(url);
      } catch (e) {
        // ignore
      }
      this.$emit("share", { url });
    },
  },
};
</script>

<style scoped>
.quiz-results {
  box-sizing: border-box;
}

.quiz-results--fill {
  display: flex;
  flex-direction: column;
  min-height: max(400px, 100vh);
  overflow: hidden;
}

.quiz-results--fill-centered {
  justify-content: center;
}

.quiz-results--legacy {
  padding: 0;
  background: transparent !important;
}

.quiz-results__inner {
  display: flex;
  flex-direction: column;
  gap: 0;
  flex: 1;
  width: 100%;
}

.quiz-results__featured {
  margin-bottom: 8px;
}

.quiz-results__actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 24px;
  width: 100%;
}

.quiz-results__action-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  text-decoration: none;
  border: 1px solid transparent;
  border-radius: 4px;
  cursor: pointer;
  transition: opacity 0.15s ease;
}

.quiz-results__action-btn:hover {
  opacity: 0.9;
  text-decoration: none;
  color: inherit;
}

.quiz-result-card {
  position: relative;
}

.quiz-match-badge {
  position: absolute;
  top: 8px;
  left: 8px;
  z-index: 2;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 999px;
}
</style>
