<template>
  <quiz-intro-stack v-if="isCustomStack" :quiz="quiz" :intro="intro" @start="$emit('start')" />
  <!-- <div v-else class="quiz-intro mb-4" :class="[layoutClass, alignmentClass]" :style="introStyles"> -->
  <div v-else class="quiz-intro" :class="[layoutClass, alignmentClass]" :style="introStyles">
    <div class="quiz-intro__hero" :style="heroStyles">
      <div v-if="showOverlay" class="quiz-intro__overlay"></div>
      <div v-if="isHeroImageLeft && heroImageUrl" class="quiz-intro__side-image" :style="heroSideImageStyle"></div>
      <div v-if="isHeroSquareTop && heroImageUrl" class="quiz-intro__square-image quiz-intro__square-image--top">
        <img :src="heroImageUrl" :alt="quiz.title" />
      </div>
      <div class="quiz-intro__content" :class="alignmentClass">
        <h2 class="quiz-intro__title mb-2">{{ quiz.title }}</h2>
        <p v-if="intro.subtitle" class="quiz-intro__subtitle mb-3">{{ intro.subtitle }}</p>
        <div v-if="quiz.description" class="quiz-intro__description mb-4" v-html="quiz.description"></div>
        <p v-if="metaLine" class="quiz-intro__meta small mb-4">{{ metaLine }}</p>
        <button type="button" class="btn quiz-intro__cta" @click="$emit('start')">
          {{ ctaText }}
        </button>
        <div v-if="secondaryLink" class="mt-3">
          <router-link v-if="isInternalLink" :to="secondaryLink.url" class="quiz-intro__secondary">
            {{ secondaryLink.text }}
          </router-link>
          <a v-else :href="secondaryLink.url" class="quiz-intro__secondary">
            {{ secondaryLink.text }}
          </a>
        </div>
      </div>
      <div v-if="isHeroSquareBottom && heroImageUrl" class="quiz-intro__square-image quiz-intro__square-image--bottom">
        <img :src="heroImageUrl" :alt="quiz.title" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { cleanMediaPath } from "@/utils/sectionProps";
import QuizIntroStack from "@/components/quiz/QuizIntroStack.vue";

export default {
  name: "QuizIntro",
  components: {
    QuizIntroStack,
  },
  props: {
    quiz: {
      type: Object,
      required: true,
    },
  },
  emits: ["start"],
  computed: {
    ...mapGetters("layout", ["isMobile"]),
    intro() {
      return this.quiz?.layout_config?.intro || {};
    },
    layout() {
      return this.intro.layout || "minimal";
    },
    isCustomStack() {
      return this.layout === "custom_stack";
    },
    layoutClass() {
      return `quiz-intro--${this.layout}`;
    },
    alignmentClass() {
      const align = this.intro.alignment || "center";
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
    heroBackgroundCss() {
      return this.heroImageUrl ? `url("${this.heroImageUrl}")` : null;
    },
    heroSideImageStyle() {
      return this.heroBackgroundCss ? { backgroundImage: this.heroBackgroundCss } : {};
    },
    isHeroImageLeft() {
      return this.layout === "hero_image_left";
    },
    isHeroSquareTop() {
      return this.layout === "hero_square_top";
    },
    isHeroSquareBottom() {
      return this.layout === "hero_square_bottom";
    },
    showOverlay() {
      return this.layout === "full_bleed" && !!this.heroImageUrl;
    },
    introStyles() {
      const opacity = (this.intro.overlay_opacity ?? 40) / 100;
      return {
        "--quiz-bg": this.intro.background_color || "#ffffff",
        "--quiz-text": this.intro.text_color || "#111111",
        "--quiz-btn": this.intro.button_color || "#ff5a1f",
        "--quiz-btn-text": this.intro.button_text_color || "#ffffff",
        "--quiz-overlay": this.intro.overlay_color || "#000000",
        "--quiz-overlay-opacity": opacity,
      };
    },
    heroStyles() {
      if (
        this.layout === "minimal" ||
        !this.heroImageUrl ||
        this.isHeroImageLeft ||
        this.isHeroSquareTop ||
        this.isHeroSquareBottom
      ) {
        return {};
      }
      return {
        backgroundImage: this.heroBackgroundCss,
      };
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
        const minutes = this.intro.estimated_minutes || 2;
        parts.push(`~${minutes} ${this.$t("min")}`);
      }
      return parts.length ? parts.join(" · ") : "";
    },
    ctaText() {
      return this.intro.cta_text || this.$t("Start Quiz");
    },
    secondaryLink() {
      const text = this.intro.secondary_link_text;
      const url = this.intro.secondary_link_url;
      if (!text || !url) {
        return null;
      }
      return { text, url };
    },
    isInternalLink() {
      const url = this.secondaryLink?.url || "";
      return url.startsWith("/") && !url.startsWith("//");
    },
  },
};
</script>

<style scoped>
.quiz-intro {
  /* border-radius: 12px; */
  overflow: hidden;
  background: var(--quiz-bg, #ffffff);
  color: var(--quiz-text, #111111);
  height: 100%;
}

.quiz-intro__hero {
  position: relative;
  background-size: cover;
  background-position: center;
  background-color: var(--quiz-bg, #ffffff);
}

.quiz-intro__overlay {
  position: absolute;
  inset: 0;
  background: var(--quiz-overlay, #000000);
  opacity: var(--quiz-overlay-opacity, 0.4);
  z-index: 1;
}

.quiz-intro__content {
  position: relative;
  z-index: 2;
  padding: 32px 24px;
}

.quiz-intro__title {
  color: var(--quiz-text, #111111);
}

.quiz-intro__subtitle {
  color: var(--quiz-text, #111111);
  opacity: 0.85;
}

.quiz-intro__description :deep(p:last-child) {
  margin-bottom: 0;
}

.quiz-intro__meta {
  color: var(--quiz-text, #111111);
  opacity: 0.7;
}

.quiz-intro__cta {
  background: var(--quiz-btn, #ff5a1f);
  color: var(--quiz-btn-text, #ffffff);
  border: none;
  border-radius: 8px;
  padding: 12px 28px;
  font-weight: 600;
}

.quiz-intro__cta:hover {
  opacity: 0.92;
  color: var(--quiz-btn-text, #ffffff);
}

.quiz-intro__secondary {
  color: var(--quiz-text, #111111);
  text-decoration: underline;
  font-size: 14px;
}

.quiz-intro--minimal .quiz-intro__hero {
  background-image: none !important;
}

.quiz-intro--hero_centered .quiz-intro__hero {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 360px;
}

.quiz-intro--hero_centered .quiz-intro__content {
  max-width: 560px;
  margin: 0 auto;
  background: var(--quiz-bg, #ffffff);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.quiz-intro--hero_centered .quiz-intro__hero:not([style*="background-image"]) .quiz-intro__content {
  box-shadow: none;
}

.quiz-intro--hero_image_left .quiz-intro__hero {
  display: flex;
  flex-direction: column;
  min-height: auto;
}

@media (min-width: 768px) {
  .quiz-intro--hero_image_left .quiz-intro__hero {
    flex-direction: row;
    min-height: 320px;
  }

  .quiz-intro--hero_image_left .quiz-intro__side-image {
    flex: 0 0 40%;
    min-height: 320px;
  }
}

.quiz-intro--hero_image_left .quiz-intro__content {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.quiz-intro__side-image {
  width: 100%;
  min-height: 200px;
  background-size: cover;
  background-position: center;
  background-color: #eeeeee;
}

.quiz-intro--hero_square_top .quiz-intro__hero,
.quiz-intro--hero_square_bottom .quiz-intro__hero {
  display: flex;
  flex-direction: column;
  min-height: auto;
  padding: 32px 24px;
}

.quiz-intro--hero_square_top.text-left .quiz-intro__hero,
.quiz-intro--hero_square_bottom.text-left .quiz-intro__hero {
  align-items: flex-start;
}

.quiz-intro--hero_square_top.text-center .quiz-intro__hero,
.quiz-intro--hero_square_bottom.text-center .quiz-intro__hero {
  align-items: center;
}

.quiz-intro--hero_square_top.text-right .quiz-intro__hero,
.quiz-intro--hero_square_bottom.text-right .quiz-intro__hero {
  align-items: flex-end;
}

.quiz-intro--hero_square_top .quiz-intro__content,
.quiz-intro--hero_square_bottom .quiz-intro__content {
  padding: 0;
  width: 100%;
}

.quiz-intro__square-image {
  width: 140px;
  height: 140px;
  flex-shrink: 0;
  border-radius: 12px;
  overflow: hidden;
}

.quiz-intro__square-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.quiz-intro__square-image--top {
  margin-bottom: 20px;
}

.quiz-intro__square-image--bottom {
  margin-top: 20px;
}

.quiz-intro--full_bleed .quiz-intro__hero {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 420px;
}

.quiz-intro--full_bleed .quiz-intro__content {
  max-width: 640px;
  margin: 0 auto;
  color: var(--quiz-text, #ffffff);
}

.quiz-intro--full_bleed .quiz-intro__title,
.quiz-intro--full_bleed .quiz-intro__subtitle,
.quiz-intro--full_bleed .quiz-intro__meta,
.quiz-intro--full_bleed .quiz-intro__secondary {
  color: var(--quiz-text, #ffffff);
}
</style>
