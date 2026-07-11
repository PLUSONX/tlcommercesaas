<template>
  <div class="quiz-results-stack" :class="alignmentClass" :style="stackInnerStyles">
    <template v-for="block in activeBlocks" :key="block.id">
      <div v-if="block.type === 'hero_image' && heroImageUrl" class="quiz-results-stack__hero" :class="heroClasses"
        :style="heroBlockStyles(block)">
        <img :src="heroImageUrl" alt="" />
      </div>

      <a v-else-if="block.type === 'hero_image' && heroProductImageForBlock(block) && productLinksByBlockId[block.id]"
        :href="productLinksByBlockId[block.id].href"
        class="quiz-results-stack__hero quiz-results-stack__product-link quiz-results-stack__product-image"
        :class="heroClasses" :style="heroProductImageStyles(block)"
        @click.prevent="navigateToProduct(productLinksByBlockId[block.id].to)">
        <img :src="heroProductImageForBlock(block)" :alt="productAltForBlock(block)" />
      </a>

      <div v-else-if="block.type === 'hero_image' && heroProductImageForBlock(block)" class="quiz-results-stack__hero"
        :class="heroClasses" :style="heroProductImageStyles(block)">
        <img :src="heroProductImageForBlock(block)" :alt="productAltForBlock(block)" />
      </div>

      <span v-else-if="block.type === 'product_image' && isSwatchBlock(block)"
        class="quiz-results-stack__product-swatch-wrap"
        :class="{ 'quiz-results-stack__product-swatch-wrap--glow': swatchGlowEnabled }"
        :style="productSwatchWrapStyles(block)">
        <span class="quiz-results-stack__product-swatch-halo" aria-hidden="true"></span>
        <a v-if="productLinksByBlockId[block.id]"
          :href="productLinksByBlockId[block.id].href"
          class="quiz-results-stack__product-image quiz-results-stack__product-link quiz-results-stack__product-swatch"
          :style="productSwatchStyles(block)" :aria-label="productAltForBlock(block)"
          @click.prevent="navigateToProduct(productLinksByBlockId[block.id].to)">
        </a>
        <span v-else
          class="quiz-results-stack__product-image quiz-results-stack__product-swatch"
          :style="productSwatchStyles(block)" :aria-label="productAltForBlock(block)">
        </span>
      </span>

      <a v-else-if="block.type === 'product_image' && !isSwatchBlock(block) && productImageForBlock(block) && productLinksByBlockId[block.id]"
        :href="productLinksByBlockId[block.id].href" class="quiz-results-stack__product-image quiz-results-stack__product-link"
        :style="productImageStyles(block)" @click.prevent="navigateToProduct(productLinksByBlockId[block.id].to)">
        <img :src="productImageForBlock(block)" :alt="productAltForBlock(block)" />
      </a>

      <div v-else-if="block.type === 'product_image' && !isSwatchBlock(block) && productImageForBlock(block)"
        class="quiz-results-stack__product-image" :style="productImageStyles(block)">
        <img :src="productImageForBlock(block)" :alt="productAltForBlock(block)" />
      </div>

      <a v-else-if="block.type === 'match_badge' && productLinksByBlockId[block.id]"
        :href="productLinksByBlockId[block.id].href" class="quiz-results-stack__match-badge quiz-results-stack__product-link"
        :style="matchBadgeStyles(block)" @click.prevent="navigateToProduct(productLinksByBlockId[block.id].to)">
        {{ matchPctForBlock(block) }}% {{ $t("match") }}
      </a>

      <span v-else-if="block.type === 'match_badge'" class="quiz-results-stack__match-badge"
        :style="matchBadgeStyles(block)">
        {{ matchPctForBlock(block) }}% {{ $t("match") }}
      </span>

      <div v-else-if="block.type === 'title' && titleHtml(block)" class="quiz-results-stack__title"
        :style="blockWrapperStyles(block)" v-html="titleHtml(block)"></div>

      <div v-else-if="block.type === 'subtitle' && resolvedBlockHasText(subtitleHtml(block))" class="quiz-results-stack__subtitle"
        :style="blockWrapperStyles(block)" v-html="subtitleHtml(block)"></div>

      <div v-else-if="block.type === 'tagline' && resolvedBlockHasText(taglineHtml(block))" class="quiz-results-stack__tagline"
        :class="taglineFontClass" :style="blockWrapperStyles(block)" v-html="taglineHtml(block)"></div>

      <div v-else-if="block.type === 'body' && resolvedBlockHasText(bodyHtml(block))" class="quiz-results-stack__body" :class="bodyFontClass"
        :style="blockWrapperStyles(block)" v-html="bodyHtml(block)"></div>

      <div v-else-if="block.type === 'separator' && block.style === 'mixture'"
        class="quiz-results-stack__separator quiz-results-stack__mixture"
        :style="{ ...blockSpacingStyles(block), ...mixtureContainerStyles(block) }">
        <template v-for="(part, partIndex) in mixtureParts(block)" :key="partIndex">
          <span v-if="part.type === 'gap'" :style="{ width: (part.size || 12) + 'px', flexShrink: 0 }"></span>
          <span v-else-if="part.type === 'dots'" class="quiz-results-stack__sep-dots-inline">
            <span v-for="n in 3" :key="n" :style="mixturePartDotStyle(block)"></span>
          </span>
          <span v-else :style="mixturePartStyles(part, block)"></span>
        </template>
      </div>

      <div v-else-if="block.type === 'separator'" class="quiz-results-stack__separator" :class="separatorClass(block)"
        :style="{ ...blockSpacingStyles(block), ...separatorStyles(block) }">
        <span v-if="block.style === 'dots'" class="quiz-results-stack__sep-dots">
          <span v-for="n in 3" :key="n" :style="mixturePartDotStyle(block)"></span>
        </span>
      </div>

      <div v-else-if="block.type === 'meta' && resolvedBlockHasText(metaHtml(block))" class="quiz-results-stack__meta small"
        :style="blockWrapperStyles(block)" v-html="metaHtml(block)"></div>
    </template>
  </div>
</template>

<script>
import { cleanMediaPath } from "@/utils/sectionProps";
import { buildResultContext, resolveTokens, getProductForRank, resolveActionLink } from "@/utils/quizResultTokens";
import { readableColor } from "@/utils/readableColor";

const FONT_LINK_ID = "quiz-results-stack-fonts";
const DEFAULT_SPACING = {
  margin_top: 0,
  margin_bottom: 16,
  padding_top: 0,
  padding_bottom: 0,
  padding_x: 0,
};
const DEFAULT_APPEARANCE = {
  text_color: "inherit",
  text_color_custom: "#c9a84c",
  background_color: "transparent",
  background_color_custom: "#1a3d2a",
  padding_x: 0,
  padding_y: 0,
  border_radius: 0,
};

export default {
  name: "QuizResultsStack",
  props: {
    blocks: { type: Array, default: () => [] },
    theme: { type: Object, default: () => ({}) },
    results: { type: Array, default: () => [] },
    productProfiles: { type: Array, default: () => [] },
    heroImage: { type: String, default: null },
  },
  computed: {
    tokenContext() {
      return buildResultContext(this.results, this.productProfiles);
    },
    activeBlocks() {
      return (this.blocks || []).filter((block) => block.enabled !== false);
    },
    alignmentClass() {
      const align = this.theme.alignment || "center";
      return `text-${align}`;
    },
    heroImageUrl() {
      if (this.heroImage) return cleanMediaPath(this.heroImage);
      return null;
    },
    stackInnerStyles() {
      const align = this.theme.alignment || "center";
      const alignItems = align === "left" ? "flex-start" : align === "right" ? "flex-end" : "center";
      const textFallback = this.theme.text_color || this.theme.accent_color || "#c9a84c";
      const taglineColor = this.theme.tagline_color || this.theme.text_color || "#ffffff";
      const descriptionColor = this.theme.description_color || this.theme.text_color || "#ffffff";
      const cardBg = this.theme.card_background || "#ffffff";
      const onCard = !!this.theme.card_enabled;
      return {
        "--quiz-accent": this.theme.accent_color || "#c9a84c",
        "--quiz-text": this.theme.text_color || "#ffffff",
        "--quiz-hero-size": `${this.theme.hero_size || 80}px`,
        "--quiz-tagline-color": onCard
          ? readableColor(taglineColor, cardBg, textFallback)
          : taglineColor,
        "--quiz-tagline-size": `${this.theme.tagline_font_size || 11}px`,
        "--quiz-tagline-letter-spacing": `${this.theme.tagline_letter_spacing ?? 0.15}em`,
        "--quiz-description-color": onCard
          ? readableColor(descriptionColor, cardBg, textFallback)
          : descriptionColor,
        "--quiz-description-size": `${this.theme.description_font_size || 15}px`,
        alignItems,
        width: "100%",
      };
    },
    heroClasses() {
      return {};
    },
    taglineFontClass() {
      return this.theme.tagline_font === "serif_caps" ? "quiz-results-stack__font-serif-caps" : "";
    },
    bodyFontClass() {
      return this.theme.body_font === "script" ? "quiz-results-stack__font-script" : "";
    },
    swatchGlowEnabled() {
      return this.theme.hero_glow !== false;
    },
    productLinksByBlockId() {
      const map = {};
      for (const block of this.activeBlocks) {
        if (block.type !== "product_image" && block.type !== "match_badge" && block.type !== "hero_image") continue;
        const rank = block.type === "hero_image" ? 1 : (block.product_rank || 1);
        const url = getProductForRank(this.tokenContext, rank).url;
        const link = resolveActionLink(url);
        if (link?.kind === "router") {
          const resolved = this.$router.resolve(link.to);
          map[block.id] = { to: link.to, href: resolved.href };
        }
      }
      return map;
    },
  },
  mounted() {
    this.ensureFonts();
  },
  beforeUnmount() {
    const link = document.getElementById(FONT_LINK_ID);
    if (link) link.remove();
  },
  methods: {
    navigateToProduct(to) {
      this.$router.push(to);
    },
    resolveHtml(html) {
      return resolveTokens(html, this.tokenContext);
    },
    resolvedBlockHasText(html) {
      if (!html || !String(html).trim()) return false;
      const tmp = document.createElement("div");
      tmp.innerHTML = String(html);
      const text = (tmp.textContent || tmp.innerText || "").replace(/\s+/g, " ").trim();
      return text.length > 0;
    },
    getBlockSpacing(block) {
      const sp = { ...DEFAULT_SPACING, ...(block.spacing || {}) };
      if (block.spacing_top != null && block.spacing.margin_top == null) sp.margin_top = block.spacing_top;
      if (block.spacing_bottom != null && block.spacing.margin_bottom == null) sp.margin_bottom = block.spacing_bottom;
      return sp;
    },
    getBlockAppearance(block) {
      return { ...DEFAULT_APPEARANCE, ...(block.appearance || {}) };
    },
    resolveColorMode(mode, custom, fallbackKey) {
      if (mode === "custom") return custom || "#c9a84c";
      if (mode === "accent") return this.theme.accent_color || "#c9a84c";
      if (mode === "text") return this.theme.text_color || "#ffffff";
      if (mode === "transparent") return "transparent";
      return this.theme[fallbackKey] || this.theme.text_color || "#ffffff";
    },
    blockSpacingStyles(block, options = {}) {
      const { fullWidth = true } = options;
      const sp = this.getBlockSpacing(block);
      const css = {
        marginTop: `${sp.margin_top}px`,
        marginBottom: `${sp.margin_bottom}px`,
        paddingTop: `${sp.padding_top}px`,
        paddingBottom: `${sp.padding_bottom}px`,
        paddingLeft: `${sp.padding_x}px`,
        paddingRight: `${sp.padding_x}px`,
        boxSizing: "border-box",
      };
      if (fullWidth) css.width = "100%";
      return css;
    },
    blockAppearanceStyles(block, blockType) {
      const app = this.getBlockAppearance(block);
      const css = {};
      if (app.text_color !== "inherit" || blockType === "meta") {
        css.color = this.resolveColorMode(app.text_color, app.text_color_custom, "text_color");
      }
      if (app.background_color !== "transparent") {
        css.backgroundColor = this.resolveColorMode(app.background_color, app.background_color_custom, "card_background");
      }
      if (app.padding_x || app.padding_y) {
        css.paddingLeft = `${app.padding_x || 0}px`;
        css.paddingRight = `${app.padding_x || 0}px`;
        css.paddingTop = `${app.padding_y || 0}px`;
        css.paddingBottom = `${app.padding_y || 0}px`;
      }
      if (app.border_radius) css.borderRadius = `${app.border_radius}px`;
      return css;
    },
    blockWrapperStyles(block) {
      return {
        ...this.blockSpacingStyles(block),
        ...this.blockAppearanceStyles(block, block.type),
      };
    },
    heroBlockStyles(block) {
      return {
        ...this.blockSpacingStyles(block, { fullWidth: false }),
        width: "var(--quiz-hero-size)",
        height: "var(--quiz-hero-size)",
      };
    },
    heroProductImageForBlock(block) {
      if (this.heroImageUrl) return null;
      return this.productImageForBlock({ ...block, product_rank: block.product_rank || 1 });
    },
    heroProductImageStyles(block) {
      const size = parseInt(this.theme.hero_size, 10) || 80;
      return {
        ...this.blockSpacingStyles(block, { fullWidth: false }),
        width: `${size}px`,
        height: `${size}px`,
        borderRadius: `${this.theme.card_border_radius || 0}px`,
        overflow: "hidden",
      };
    },
    productImageStyles(block) {
      const size = block.size || 120;
      const isSwatch = block.display_mode === "swatch";
      return {
        ...this.blockSpacingStyles(block, { fullWidth: false }),
        width: `${isSwatch ? Math.min(size, 80) : size}px`,
        height: `${isSwatch ? Math.min(size, 80) : size}px`,
        borderRadius: isSwatch ? "50%" : `${this.theme.card_border_radius || 0}px`,
        overflow: "hidden",
      };
    },
    isSwatchBlock(block) {
      return block.type === "product_image" && block.display_mode === "swatch";
    },
    productSwatchColor(block) {
      const p = getProductForRank(this.tokenContext, block.product_rank || 1);
      return p.color || this.theme.accent_color || "#c9a84c";
    },
    productSwatchWrapStyles(block) {
      const size = block.size || 120;
      const swatchSize = Math.min(size, 80);
      return {
        ...this.blockSpacingStyles(block, { fullWidth: false }),
        width: `${swatchSize}px`,
        height: `${swatchSize}px`,
      };
    },
    productSwatchStyles(block) {
      return {
        width: "100%",
        height: "100%",
        borderRadius: "50%",
        background: this.productSwatchColor(block),
        border: "2px solid color-mix(in srgb, #ffffff 45%, transparent)",
        boxSizing: "border-box",
        display: "block",
        flexShrink: 0,
      };
    },
    matchBadgeStyles(block) {
      return this.blockWrapperStyles(block);
    },
    productImageForBlock(block) {
      const p = getProductForRank(this.tokenContext, block.product_rank || 1);
      return p.image ? cleanMediaPath(p.image) : null;
    },
    productAltForBlock(block) {
      return getProductForRank(this.tokenContext, block.product_rank || 1).name;
    },
    matchPctForBlock(block) {
      return getProductForRank(this.tokenContext, block.product_rank || 1).match_pct;
    },
    blockHtml(block) {
      if (block.html && String(block.html).trim()) return this.resolveHtml(String(block.html));
      return "";
    },
    titleHtml(block) {
      return this.blockHtml(block);
    },
    subtitleHtml(block) {
      return this.blockHtml(block);
    },
    taglineHtml(block) {
      if (block.html && String(block.html).trim()) return this.resolveHtml(String(block.html));
      const text = block.text ? this.resolveHtml(String(block.text)) : "";
      return text ? `<span>${text}</span>` : "";
    },
    bodyHtml(block) {
      return this.blockHtml(block);
    },
    metaHtml(block) {
      return this.blockHtml(block);
    },
    separatorClass(block) {
      return `quiz-results-stack__separator--${block.style || "dot"}`;
    },
    separatorColor(block) {
      if (block.color === "text") return this.theme.text_color || "#ffffff";
      if (block.color === "custom") return block.color_custom || "#c9a84c";
      return this.theme.accent_color || "#c9a84c";
    },
    separatorWidth(blockOrPart) {
      const map = { short: "40px", medium: "80px", wide: "50%", full: "100%" };
      return map[blockOrPart.width] || map.medium;
    },
    separatorStyles(block) {
      const color = this.separatorColor(block);
      const style = block.style || "dot";
      const thickness = Math.min(4, Math.max(1, parseInt(block.thickness, 10) || 1));
      if (style === "dot") {
        return { width: "6px", height: "6px", borderRadius: "50%", background: color };
      }
      if (style === "diamond") {
        return { width: "8px", height: "8px", background: color, transform: "rotate(45deg)" };
      }
      if (style === "dots") {
        return { width: this.separatorWidth(block), height: "auto", background: "transparent" };
      }
      if (style === "double") {
        return {
          width: this.separatorWidth(block),
          height: `${thickness * 2 + 2}px`,
          borderTop: `${thickness}px solid ${color}`,
          borderBottom: `${thickness}px solid ${color}`,
          background: "transparent",
          opacity: 0.7,
        };
      }
      if (style === "dashed") {
        return {
          width: this.separatorWidth(block),
          height: "0",
          borderTop: `${thickness}px dashed ${color}`,
          background: "transparent",
          opacity: 0.7,
        };
      }
      return {
        width: this.separatorWidth(block),
        height: `${thickness}px`,
        background: color,
        opacity: 0.5,
      };
    },
    mixtureParts(block) {
      if (Array.isArray(block.mixture_parts) && block.mixture_parts.length) return block.mixture_parts;
      return [
        { type: "line", width: "wide" },
        { type: "gap", size: 12 },
        { type: "diamond" },
        { type: "gap", size: 12 },
        { type: "line", width: "wide" },
      ];
    },
    mixtureContainerStyles(block) {
      const align = this.theme.alignment || "center";
      return {
        display: "flex",
        alignItems: "center",
        justifyContent: align === "right" ? "flex-end" : align === "left" ? "flex-start" : "center",
        gap: "0",
        flexShrink: 0,
      };
    },
    mixturePartDotStyle(block) {
      return {
        width: "5px",
        height: "5px",
        borderRadius: "50%",
        background: this.separatorColor(block),
        display: "inline-block",
      };
    },
    mixturePartStyles(part, block) {
      const color = this.separatorColor(block);
      const thickness = Math.min(4, Math.max(1, parseInt(block.thickness, 10) || 1));
      const pType = part.type || "line";
      if (pType === "dot") {
        return { width: "6px", height: "6px", borderRadius: "50%", background: color, display: "inline-block", flexShrink: 0 };
      }
      if (pType === "diamond") {
        return { width: "8px", height: "8px", background: color, transform: "rotate(45deg)", display: "inline-block", flexShrink: 0 };
      }
      const w = this.separatorWidth(part);
      if (pType === "double") {
        return {
          width: w,
          height: `${thickness * 2 + 2}px`,
          borderTop: `${thickness}px solid ${color}`,
          borderBottom: `${thickness}px solid ${color}`,
          display: "inline-block",
          opacity: 0.7,
          flexShrink: 0,
        };
      }
      if (pType === "dashed") {
        return {
          width: w,
          height: "0",
          borderTop: `${thickness}px dashed ${color}`,
          display: "inline-block",
          opacity: 0.7,
          flexShrink: 0,
        };
      }
      return {
        width: w,
        height: `${thickness}px`,
        background: color,
        display: "inline-block",
        opacity: 0.5,
        flexShrink: 0,
      };
    },
    ensureFonts() {
      const needsSerif = this.theme.tagline_font === "serif_caps";
      const needsScript = this.theme.body_font === "script";
      if (!needsSerif && !needsScript) return;
      if (document.getElementById(FONT_LINK_ID)) return;
      const families = [];
      if (needsSerif) families.push("family=Playfair+Display:ital,wght@0,400;0,600;1,400");
      if (needsScript) families.push("family=Great+Vibes");
      const link = document.createElement("link");
      link.id = FONT_LINK_ID;
      link.rel = "stylesheet";
      link.href = `https://fonts.googleapis.com/css2?${families.join("&")}&display=swap`;
      document.head.appendChild(link);
    },
  },
};
</script>

<style scoped>
.quiz-results-stack {
  display: flex;
  flex-direction: column;
  color: var(--quiz-text, #fff);
}

.quiz-results-stack :deep(h1),
.quiz-results-stack :deep(h2),
.quiz-results-stack :deep(h3),
.quiz-results-stack :deep(p),
.quiz-results-stack :deep(span),
.quiz-results-stack :deep(em) {
  color: inherit;
}

.quiz-results-stack__title,
.quiz-results-stack__subtitle,
.quiz-results-stack__tagline,
.quiz-results-stack__body,
.quiz-results-stack__meta {
  color: var(--quiz-text, #fff);
}

.quiz-results-stack__hero {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.quiz-results-stack__hero img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.quiz-results-stack__hero--glow {
  filter: drop-shadow(0 0 12px color-mix(in srgb, var(--quiz-accent) 60%, transparent));
}

.quiz-results-stack__product-image {
  flex-shrink: 0;
  margin-left: auto;
  margin-right: auto;
}

.quiz-results-stack__product-swatch {
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.quiz-results-stack__product-swatch-wrap {
  display: block;
  position: relative;
  margin-left: auto;
  margin-right: auto;
  flex-shrink: 0;
}

.quiz-results-stack__product-swatch-wrap .quiz-results-stack__product-swatch {
  position: relative;
  z-index: 1;
  margin: 0;
}

.quiz-results-stack__product-swatch-halo {
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  pointer-events: none;
  opacity: 0.85;
}

.quiz-results-stack__product-swatch-wrap--glow .quiz-results-stack__product-swatch-halo {
  animation: quiz-results-swatch-halo-blink 2s ease-in-out infinite;
  box-shadow: 0 0 12px 4px color-mix(in srgb, var(--quiz-accent) 70%, #ffffff);
}

@keyframes quiz-results-swatch-halo-blink {
  0%,
  100% {
    opacity: 0.45;
    transform: scale(1);
    box-shadow: 0 0 8px 2px color-mix(in srgb, var(--quiz-accent) 50%, #ffffff);
  }

  50% {
    opacity: 1;
    transform: scale(1.08);
    box-shadow: 0 0 20px 6px color-mix(in srgb, var(--quiz-accent) 80%, #ffffff);
  }
}

.quiz-results-stack__product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.quiz-results-stack__product-link {
  text-decoration: none;
  color: inherit;
  cursor: pointer;
}

.quiz-results-stack__product-link:hover {
  opacity: 0.92;
}

.quiz-results-stack__match-badge {
  display: inline-block;
  background: #ff5a1f;
  color: #fff;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 999px;
  align-self: center;
}

.quiz-results-stack__tagline {
  color: var(--quiz-tagline-color, var(--quiz-text, #fff));
  font-size: var(--quiz-tagline-size, 0.7rem);
  letter-spacing: var(--quiz-tagline-letter-spacing, 0.1em);
  width: 100%;
}

.quiz-results-stack__tagline :deep(*) {
  font-size: inherit;
  letter-spacing: inherit;
  text-transform: inherit;
  font-family: inherit;
  color: inherit;
}

.quiz-results-stack__font-serif-caps {
  font-family: "Playfair Display", Georgia, serif;
  text-transform: uppercase;
}

.quiz-results-stack__font-script {
  font-family: "Great Vibes", cursive;
  font-size: var(--quiz-description-size, 0.95rem);
}

.quiz-results-stack__body {
  width: 100%;
  color: var(--quiz-description-color, var(--quiz-text, #fff));
  font-size: var(--quiz-description-size, 0.95rem);
}

.quiz-results-stack__body :deep(*) {
  font-size: inherit;
  font-family: inherit;
  color: inherit;
}

.quiz-results-stack__separator {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.quiz-results-stack__sep-dots {
  display: flex;
  gap: 6px;
  align-items: center;
  justify-content: center;
}

.quiz-results-stack__sep-dots-inline {
  display: inline-flex;
  gap: 5px;
  align-items: center;
}

.quiz-results-stack__meta {
  width: 100%;
  opacity: 0.85;
}
</style>
