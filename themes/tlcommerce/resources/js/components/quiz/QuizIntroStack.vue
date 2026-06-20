<template>
  <div class="quiz-intro quiz-intro--custom_stack" :class="alignmentClass" :style="stackRootStyles">
    <div class="quiz-intro__stack" :style="stackContainerStyles">
      <template v-for="block in activeBlocks" :key="block.id">
        <div v-if="block.type === 'hero_image' && heroImageUrl" class="quiz-intro__stack-hero" :class="heroClasses"
          :style="heroBlockStyles(block)">
          <div v-if="theme.hero_frame === 'corners'" class="quiz-intro__hero-frame quiz-intro__hero-frame--corners">
            <span class="quiz-intro__corner quiz-intro__corner--tl"></span>
            <span class="quiz-intro__corner quiz-intro__corner--tr"></span>
            <span class="quiz-intro__corner quiz-intro__corner--bl"></span>
            <span class="quiz-intro__corner quiz-intro__corner--br"></span>
            <img :src="heroImageUrl" :alt="quiz.title" />
          </div>
          <img v-else :src="heroImageUrl" :alt="quiz.title" />
        </div>

        <div v-else-if="block.type === 'title' && titleHtml(block)" class="quiz-intro__stack-title"
          :style="blockWrapperStyles(block)" v-html="titleHtml(block)"></div>

        <div v-else-if="block.type === 'subtitle' && subtitleHtml(block)" class="quiz-intro__stack-subtitle"
          :style="blockWrapperStyles(block)" v-html="subtitleHtml(block)"></div>

        <div v-else-if="block.type === 'tagline' && taglineHtml(block)" class="quiz-intro__stack-tagline"
          :class="taglineFontClass" :style="blockWrapperStyles(block)" v-html="taglineHtml(block)"></div>

        <div v-else-if="block.type === 'body' && bodyHtml(block)" class="quiz-intro__stack-body" :class="bodyFontClass"
          :style="blockWrapperStyles(block)" v-html="bodyHtml(block)"></div>

        <div v-else-if="block.type === 'separator' && block.style === 'mixture'"
          class="quiz-intro__stack-separator quiz-intro__stack-mixture"
          :style="{ ...blockSpacingStyles(block), ...mixtureContainerStyles(block) }">
          <template v-for="(part, partIndex) in mixtureParts(block)" :key="partIndex">
            <span v-if="part.type === 'gap'" :style="{ width: (part.size || 12) + 'px', flexShrink: 0 }"></span>
            <span v-else-if="part.type === 'dots'" class="quiz-intro__sep-dots-inline">
              <span v-for="n in 3" :key="n" :style="mixturePartDotStyle(block)"></span>
            </span>
            <span v-else :style="mixturePartStyles(part, block)"></span>
          </template>
        </div>

        <div v-else-if="block.type === 'separator'" class="quiz-intro__stack-separator" :class="separatorClass(block)"
          :style="{ ...blockSpacingStyles(block), ...separatorStyles(block) }">
          <span v-if="block.style === 'dots'" class="quiz-intro__sep-dots">
            <span v-for="n in 3" :key="n" :style="mixturePartDotStyle(block)"></span>
          </span>
        </div>

        <p v-else-if="block.type === 'meta' && metaLine" class="quiz-intro__stack-meta small"
          :style="blockWrapperStyles(block)">
          {{ metaLine }}
        </p>

        <div v-else-if="block.type === 'cta'" :style="blockSpacingStyles(block)">
          <button type="button" class="btn quiz-intro__stack-cta" :class="ctaClasses"
            :style="ctaAppearanceStyles(block)" @click="$emit('start')">
            <span v-html="ctaHtml(block)"></span>
          </button>
        </div>

        <div v-else-if="block.type === 'secondary_link' && secondaryLinkForBlock(block)"
          class="quiz-intro__stack-secondary" :style="blockWrapperStyles(block)">
          <router-link v-if="isInternalUrl(secondaryLinkForBlock(block).url)" :to="secondaryLinkForBlock(block).url"
            class="quiz-intro__secondary">
            <span v-html="secondaryTextHtml(block)"></span>
          </router-link>
          <a v-else :href="secondaryLinkForBlock(block).url" class="quiz-intro__secondary">
            <span v-html="secondaryTextHtml(block)"></span>
          </a>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { cleanMediaPath } from "@/utils/sectionProps";

const FONT_LINK_ID = "quiz-intro-stack-fonts";
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
  name: "QuizIntroStack",
  props: {
    quiz: { type: Object, required: true },
    intro: { type: Object, required: true },
  },
  emits: ["start"],
  computed: {
    ...mapGetters("layout", ["isMobile"]),
    theme() {
      return this.intro.theme || {};
    },
    blocks() {
      return Array.isArray(this.intro.blocks) ? this.intro.blocks : [];
    },
    activeBlocks() {
      return this.blocks.filter((block) => block.enabled !== false);
    },
    alignmentClass() {
      const align = this.theme.alignment || this.intro.alignment || "center";
      return `text-${align}`;
    },
    heroImageUrl() {
      let url = null;
      if (this.isMobile && this.intro.hero_image_mobile) {
        url = this.intro.hero_image_mobile;
      } else {
        url = this.intro.hero_image_desktop || this.intro.hero_image_mobile || null;
      }
      return url ? cleanMediaPath(url) : null;
    },
    stackRootStyles() {
      const t = this.theme;
      const accent = t.accent_color || t.text_color || "#111111";
      const text = t.text_color || "#111111";
      const btn = t.button_color || "#ff5a1f";
      const btnText = t.button_text_color || "#ffffff";
      let background = t.background_color || "#ffffff";
      if (t.background_type === "radial_gradient") {
        const center = t.background_gradient_center || t.background_color || "#ffffff";
        const edge = t.background_gradient_edge || "#050a07";
        background = `radial-gradient(circle at center, ${center} 0%, ${edge} 100%)`;
      }
      const themeMin = t.min_height || 400;
      return {
        background,
        "--quiz-accent": accent,
        "--quiz-text": text,
        "--quiz-btn": btn,
        "--quiz-btn-text": btnText,
        "--quiz-hero-size": `${t.hero_size || 140}px`,
        "--quiz-theme-min-height": `${themeMin}px`,
        minHeight: `max(${themeMin}px, 100vh)`,
      };
    },
    stackContainerStyles() {
      const align = this.theme.alignment || this.intro.alignment || "center";
      const alignItems = align === "left" ? "flex-start" : align === "right" ? "flex-end" : "center";
      return { maxWidth: `${this.theme.content_max_width || 560}px`, alignItems };
    },
    heroWrapStyle() {
      return { width: "var(--quiz-hero-size)", height: "var(--quiz-hero-size)" };
    },
    heroClasses() {
      return { "quiz-intro__stack-hero--glow": !!this.theme.hero_glow };
    },
    taglineFontClass() {
      return this.theme.tagline_font === "serif_caps" ? "quiz-intro__font-serif-caps" : "";
    },
    bodyFontClass() {
      return this.theme.body_font === "script" ? "quiz-intro__font-script" : "";
    },
    ctaClasses() {
      return { "quiz-intro__stack-cta--gradient-glow": this.theme.button_style === "gradient_glow" };
    },
    questionCount() {
      return this.intro.question_count ?? this.quiz.question_count ?? 0;
    },
    metaLine() {
      const parts = [];
      if (this.intro.show_question_count !== false && this.questionCount > 0) {
        parts.push(`${this.questionCount} ${this.$t("questions")}`);
      }
      if (this.intro.show_estimated_time) {
        parts.push(`~${this.intro.estimated_minutes || 2} ${this.$t("min")}`);
      }
      return parts.length ? parts.join(" · ") : "";
    },
    ctaText() {
      return this.intro.cta_text || this.$t("Start Quiz");
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
    getBlockSpacing(block) {
      const sp = { ...DEFAULT_SPACING, ...(block.spacing || {}) };
      if (block.spacing_top != null && block.spacing.margin_top == null) {
        sp.margin_top = block.spacing_top;
      }
      if (block.spacing_bottom != null && block.spacing.margin_bottom == null) {
        sp.margin_bottom = block.spacing_bottom;
      }
      return sp;
    },
    getBlockAppearance(block) {
      return { ...DEFAULT_APPEARANCE, ...(block.appearance || {}) };
    },
    resolveColorMode(mode, custom, fallbackKey) {
      if (mode === "custom") return custom || "#c9a84c";
      if (mode === "accent") return this.theme.accent_color || this.theme.text_color || "#111111";
      if (mode === "text") return this.theme.text_color || "#111111";
      if (mode === "transparent") return "transparent";
      return this.theme[fallbackKey] || this.theme.text_color || "#111111";
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
      if (fullWidth) {
        css.width = "100%";
      }
      return css;
    },
    heroBlockStyles(block) {
      return { ...this.blockSpacingStyles(block, { fullWidth: false }), ...this.heroWrapStyle };
    },
    blockAppearanceStyles(block, blockType) {
      const app = this.getBlockAppearance(block);
      const css = {};
      const fallback = blockType === "cta" ? "button_text_color" : "text_color";
      if (app.text_color !== "inherit" || blockType === "meta") {
        css.color = this.resolveColorMode(app.text_color, app.text_color_custom, fallback);
      }
      if (app.background_color !== "transparent") {
        css.backgroundColor = this.resolveColorMode(
          app.background_color,
          app.background_color_custom,
          "background_color"
        );
      }
      if (app.padding_x || app.padding_y) {
        css.paddingLeft = `${app.padding_x || 0}px`;
        css.paddingRight = `${app.padding_x || 0}px`;
        css.paddingTop = `${app.padding_y || 0}px`;
        css.paddingBottom = `${app.padding_y || 0}px`;
      }
      if (app.border_radius) {
        css.borderRadius = `${app.border_radius}px`;
      }
      return css;
    },
    blockWrapperStyles(block) {
      return { ...this.blockSpacingStyles(block), ...this.blockAppearanceStyles(block, block.type) };
    },
    ctaAppearanceStyles(block) {
      const app = this.getBlockAppearance(block);
      const css = {};
      if (app.text_color !== "inherit") {
        css.color = this.resolveColorMode(app.text_color, app.text_color_custom, "button_text_color");
      }
      if (app.background_color !== "transparent") {
        const bg = this.resolveColorMode(app.background_color, app.background_color_custom, "button_color");
        css.backgroundColor = bg;
        css.borderColor = bg;
      }
      if (app.padding_x || app.padding_y) {
        css.paddingLeft = `${(app.padding_x || 0) + 12}px`;
        css.paddingRight = `${(app.padding_x || 0) + 12}px`;
        css.paddingTop = `${(app.padding_y || 0) + 8}px`;
        css.paddingBottom = `${(app.padding_y || 0) + 8}px`;
      }
      if (app.border_radius) {
        css.borderRadius = `${app.border_radius}px`;
      }
      return css;
    },
    blockHtml(block, fallback) {
      if (block.html && String(block.html).trim()) return block.html;
      return fallback || "";
    },
    titleHtml(block) {
      return this.blockHtml(block, this.quiz.title ? `<p>${this.escapeHtml(this.quiz.title)}</p>` : "");
    },
    subtitleHtml(block) {
      return this.blockHtml(block, this.intro.subtitle ? `<p>${this.escapeHtml(this.intro.subtitle)}</p>` : "");
    },
    taglineHtml(block) {
      if (block.html && String(block.html).trim()) {
        return this.resolveTagline(String(block.html));
      }
      const textFallback = block.text ? this.resolveTagline(String(block.text)) : "";
      return textFallback ? `<span>${this.escapeHtml(textFallback)}</span>` : "";
    },
    bodyHtml(block) {
      return this.blockHtml(block, this.quiz.description || "");
    },
    ctaHtml(block) {
      return this.blockHtml(block, this.escapeHtml(this.ctaText));
    },
    secondaryTextHtml(block) {
      const fallback = this.intro.secondary_link_text ? this.escapeHtml(this.intro.secondary_link_text) : "";
      return this.blockHtml(block, fallback);
    },
    secondaryLinkForBlock(block) {
      const url = block.url || this.intro.secondary_link_url;
      const textHtml = this.secondaryTextHtml(block);
      if (!url || !textHtml) return null;
      return { url, text: textHtml };
    },
    isInternalUrl(url) {
      return url.startsWith("/") && !url.startsWith("//");
    },
    resolveTagline(text) {
      return text.replace(/\{count\}/g, String(this.questionCount));
    },
    escapeHtml(text) {
      const div = document.createElement("div");
      div.textContent = text;
      return div.innerHTML;
    },
    separatorClass(block) {
      return `quiz-intro__stack-separator--${block.style || "dot"}`;
    },
    separatorColor(block) {
      if (block.color === "text") return this.theme.text_color || "#111111";
      if (block.color === "custom") return block.color_custom || "#c9a84c";
      return this.theme.accent_color || this.theme.text_color || "#111111";
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
      if (Array.isArray(block.mixture_parts) && block.mixture_parts.length) {
        return block.mixture_parts;
      }
      return [
        { type: "line", width: "wide" },
        { type: "gap", size: 12 },
        { type: "diamond" },
        { type: "gap", size: 12 },
        { type: "line", width: "wide" },
      ];
    },
    mixtureContainerStyles(block) {
      const align = this.theme.alignment || this.intro.alignment || "center";
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
.quiz-intro--custom_stack {
  overflow: hidden;
  color: var(--quiz-text, #111111);
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: max(var(--quiz-theme-min-height, 400px), 100vh);
  padding: 32px 24px;
}

.quiz-intro__stack {
  width: 100%;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
}

.quiz-intro__stack-hero {
  margin-bottom: 0;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.quiz-intro__stack-hero img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.quiz-intro__stack-hero--glow {
  filter: drop-shadow(0 0 12px color-mix(in srgb, var(--quiz-accent) 60%, transparent));
}

.quiz-intro__hero-frame--corners {
  position: relative;
  width: 100%;
  height: 100%;
  padding: 10px;
  box-sizing: border-box;
}

.quiz-intro__corner {
  position: absolute;
  width: 18px;
  height: 18px;
  border-color: var(--quiz-accent, #c9a84c);
  border-style: solid;
  pointer-events: none;
}

.quiz-intro__corner--tl {
  top: 0;
  left: 0;
  border-width: 2px 0 0 2px;
}

.quiz-intro__corner--tr {
  top: 0;
  right: 0;
  border-width: 2px 2px 0 0;
}

.quiz-intro__corner--bl {
  bottom: 0;
  left: 0;
  border-width: 0 0 2px 2px;
}

.quiz-intro__corner--br {
  bottom: 0;
  right: 0;
  border-width: 0 2px 2px 0;
}

.quiz-intro__hero-frame--corners img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.quiz-intro__stack-title,
.quiz-intro__stack-subtitle,
.quiz-intro__stack-tagline,
.quiz-intro__stack-body,
.quiz-intro__stack-meta,
.quiz-intro__stack-secondary {
  color: var(--quiz-text, #111111);
}

.quiz-intro__stack-title :deep(p:last-child),
.quiz-intro__stack-subtitle :deep(p:last-child),
.quiz-intro__stack-body :deep(p:last-child) {
  margin-bottom: 0;
}

.quiz-intro__stack-title :deep(h1),
.quiz-intro__stack-subtitle :deep(h1),
.quiz-intro__stack-body :deep(h1) {
  font-size: 2rem;
  /* line-height: 1.2; */
  line-height: 0.6;
  margin: 0 0 0.35em;
}

.quiz-intro__stack-title :deep(h2),
.quiz-intro__stack-subtitle :deep(h2),
.quiz-intro__stack-body :deep(h2) {
  font-size: 1.5rem;
  line-height: 1.25;
  margin: 0 0 0.35em;
}

.quiz-intro__stack-title :deep(h3),
.quiz-intro__stack-subtitle :deep(h3),
.quiz-intro__stack-body :deep(h3) {
  font-size: 1.25rem;
  line-height: 1.3;
  margin: 0 0 0.35em;
}

.quiz-intro__stack-title :deep(h4),
.quiz-intro__stack-subtitle :deep(h4),
.quiz-intro__stack-body :deep(h4) {
  font-size: 1.1rem;
  line-height: 1.35;
  margin: 0 0 0.35em;
}

.quiz-intro__stack-title :deep(ul),
.quiz-intro__stack-title :deep(ol),
.quiz-intro__stack-subtitle :deep(ul),
.quiz-intro__stack-subtitle :deep(ol),
.quiz-intro__stack-body :deep(ul),
.quiz-intro__stack-body :deep(ol),
.quiz-intro__stack-tagline :deep(ul),
.quiz-intro__stack-tagline :deep(ol) {
  margin: 0 0 0.5em;
  padding-left: 1.25em;
  text-align: inherit;
}

.quiz-intro__stack-title :deep(blockquote),
.quiz-intro__stack-subtitle :deep(blockquote),
.quiz-intro__stack-tagline :deep(blockquote) {
  margin: 0 0 0.5em;
  padding-left: 0.75em;
  border-left: 3px solid var(--quiz-accent, var(--quiz-text));
  opacity: 0.9;
}

.quiz-intro__stack-body :deep(blockquote) {
  margin: 0 0 0.5em;
  padding-left: 0.75em;
  /* border-left: 3px solid var(--quiz-accent, var(--quiz-text)); */
  opacity: 0.9;
  line-height: 1.15;
  max-width: none;
}

.quiz-intro__stack-body :deep(blockquote p) {
  line-height: 1.35;
  font-size: inherit;
  font-style: inherit;
  text-decoration: none;
  margin-top: 0;
  margin-bottom: 0.35em !important;
}

.quiz-intro__stack-body :deep(blockquote p:last-child) {
  margin-bottom: 0 !important;
}

.quiz-intro__stack-title :deep(a),
.quiz-intro__stack-subtitle :deep(a),
.quiz-intro__stack-body :deep(a),
.quiz-intro__stack-tagline :deep(a),
.quiz-intro__stack-secondary :deep(a) {
  color: inherit;
  text-decoration: underline;
}

.quiz-intro__stack-title :deep(p),
.quiz-intro__stack-subtitle :deep(p),
.quiz-intro__stack-body :deep(p),
.quiz-intro__stack-tagline :deep(p) {
  margin-top: 0;
  margin-bottom: 0.5em;
}

.quiz-intro__stack-tagline {
  color: var(--quiz-accent, var(--quiz-text));
}

.quiz-intro__font-serif-caps {
  font-family: "Playfair Display", Georgia, serif;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  font-size: 0.75rem;
}

.quiz-intro__font-script {
  font-family: "Great Vibes", cursive;
  font-size: 1.75rem;
  line-height: 1.4;
}

.quiz-intro__stack-separator {
  flex-shrink: 0;
}

.quiz-intro__stack-mixture {
  width: 100%;
}

.quiz-intro__sep-dots,
.quiz-intro__sep-dots-inline {
  display: inline-flex;
  gap: 6px;
  align-items: center;
}

.text-center .quiz-intro__stack-separator--dot,
.text-center .quiz-intro__stack-separator--line,
.text-center .quiz-intro__stack-separator--dashed,
.text-center .quiz-intro__stack-separator--double,
.text-center .quiz-intro__stack-separator--diamond,
.text-center .quiz-intro__stack-mixture {
  margin-left: auto;
  margin-right: auto;
}

.text-right .quiz-intro__stack-separator--dot,
.text-right .quiz-intro__stack-separator--line,
.text-right .quiz-intro__stack-separator--dashed,
.text-right .quiz-intro__stack-separator--double,
.text-right .quiz-intro__stack-separator--diamond,
.text-right .quiz-intro__stack-mixture {
  margin-left: auto;
}

.quiz-intro__stack-meta {
  opacity: 0.85;
}

.quiz-intro__stack-cta {
  background: var(--quiz-btn, #ff5a1f);
  color: var(--quiz-btn-text, #ffffff);
  border: 1px solid var(--quiz-btn, #ff5a1f);
  border-radius: 4px;
  padding: 12px 32px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.quiz-intro__stack-cta--gradient-glow {
  background: linear-gradient(180deg, color-mix(in srgb, var(--quiz-btn) 80%, white) 0%, var(--quiz-btn) 100%);
  box-shadow: 0 0 16px color-mix(in srgb, var(--quiz-btn) 50%, transparent);
}

.quiz-intro__stack-cta:hover {
  opacity: 0.92;
  color: var(--quiz-btn-text, #ffffff);
}

.quiz-intro__secondary {
  color: var(--quiz-text, #111111);
  text-decoration: underline;
  font-size: 14px;
}
</style>
