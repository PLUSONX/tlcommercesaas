<template>

  <div class="quiz-questions-step" :style="rootStyles">

    <div class="quiz-questions-step__inner" :style="innerStyles">

      <div

        class="quiz-questions-step__card"

        :class="{ 'quiz-questions-step__card--plain': !cardEnabled }"

        :style="cardStyles"

      >

        <div v-if="showProgress && (stepStyle === 'wizard' || stepStyle === 'cards')" class="quiz-questions-step__progress mb-3">

          <small class="quiz-questions-step__progress-label d-block mb-1">

            {{ $t("Question") }} {{ currentIndex + 1 }} / {{ quiz.questions.length }}

          </small>

          <div class="quiz-questions-step__progress-track">

            <div class="quiz-questions-step__progress-bar" :style="{ width: progressPercent + '%' }"></div>

          </div>

        </div>



        <template v-if="stepStyle === 'scroll'">

          <quiz-question

            v-for="question in quiz.questions"

            :key="question.id"

            :question="question"

            :model-value="selections[question.id]"

            @update:model-value="updateSelection(question.id, $event)"

          />

        </template>



        <template v-else>

          <quiz-question

            v-if="currentQuestion"

            :key="currentQuestion.id"

            :question="currentQuestion"

            :model-value="selections[currentQuestion.id]"

            @update:model-value="updateSelection(currentQuestion.id, $event)"

          />

        </template>



        <div v-if="showNavRow" class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-10">

          <button

            v-if="showBackControl"

            type="button"

            class="btn quiz-questions-step__btn-back"

            :style="backBtnStyles"

            @click="$emit('prev')"

          >

            {{ backLabel }}

          </button>

          <div v-else-if="showNextControl"></div>



          <button

            v-if="stepStyle === 'scroll' && showNextControl"

            type="button"

            class="btn quiz-questions-step__btn-next"

            :style="nextBtnStyles"

            :disabled="submitting"

            @click="$emit('submit')"

          >

            {{ submitting ? $t("Submitting") + "..." : submitLabel }}

          </button>

          <button

            v-else-if="!isLastQuestion && showNextControl"

            type="button"

            class="btn quiz-questions-step__btn-next"

            :style="nextBtnStyles"

            @click="$emit('next')"

          >

            {{ nextLabel }}

          </button>

          <button

            v-else-if="showNextControl"

            type="button"

            class="btn quiz-questions-step__btn-next"

            :style="nextBtnStyles"

            :disabled="submitting"

            @click="$emit('submit')"

          >

            {{ submitting ? $t("Submitting") + "..." : submitLabel }}

          </button>

        </div>



        <p v-if="validationError" class="quiz-questions-step__error mt-3 mb-0">{{ validationError }}</p>

      </div>

    </div>

  </div>

</template>



<script>

import QuizQuestion from "@/components/quiz/QuizQuestion.vue";



const DEFAULT_THEME = {

  background_type: "solid",

  background_color: "#f7f8fa",

  background_gradient_center: "#ffffff",

  background_gradient_edge: "#050a07",

  fill_viewport: true,

  content_max_width: 640,

  alignment: "left",

  card_enabled: true,

  card_background: "#ffffff",

  card_border_color: "#e5e7eb",

  card_border_radius: 12,

  card_padding: 24,

  card_shadow: true,

  question_color: "#111111",

  required_color: "#dc3545",

  progress_track_color: "#e9ecef",

  progress_bar_color: "#ff5a1f",

  progress_height: 6,

  progress_label_color: "#6b7280",

  show_progress: true,

  show_back_button: true,

  show_next_button: true,

  btn_back_label: "Back",

  btn_next_label: "Next",

  btn_submit_label: "See Results",

  btn_back_color: "#111111",

  btn_back_text_color: "#111111",

  btn_next_color: "#ff5a1f",

  btn_next_text_color: "#ffffff",

  btn_border_radius: 4,

  error_color: "#dc3545",

};



export default {

  name: "QuizQuestionsStep",

  components: { QuizQuestion },

  props: {

    quiz: { type: Object, required: true },

    stepStyle: { type: String, default: "wizard" },

    currentIndex: { type: Number, default: 0 },

    selections: { type: Object, required: true },

    validationError: { type: String, default: "" },

    submitting: { type: Boolean, default: false },

  },

  emits: ["prev", "next", "submit", "update:selection"],

  computed: {

    theme() {

      const t = this.quiz?.layout_config?.questions?.theme || {};

      return { ...DEFAULT_THEME, ...t };

    },

    showBackButton() {

      return this.theme.show_back_button !== false;

    },

    showNextButton() {

      return this.theme.show_next_button !== false;

    },

    backLabel() {

      return this.theme.btn_back_label || this.$t("Back");

    },

    nextLabel() {

      return this.theme.btn_next_label || this.$t("Next");

    },

    submitLabel() {

      return this.theme.btn_submit_label || this.$t("See Results");

    },

    showBackControl() {

      return this.showBackButton && this.stepStyle !== "scroll" && this.currentIndex > 0;

    },

    showNextControl() {

      return this.showNextButton;

    },

    showNavRow() {

      return this.showBackControl || this.showNextControl;

    },

    cardEnabled() {

      return this.theme.card_enabled !== false;

    },

    showProgress() {

      return this.theme.show_progress !== false;

    },

    currentQuestion() {

      return this.quiz?.questions?.[this.currentIndex] || null;

    },

    isLastQuestion() {

      return this.currentIndex >= (this.quiz?.questions?.length || 1) - 1;

    },

    progressPercent() {

      if (!this.quiz?.questions?.length) return 0;

      return Math.round(((this.currentIndex + 1) / this.quiz.questions.length) * 100);

    },

    cssVars() {

      const t = this.theme;

      return {

        "--qq-question-color": t.question_color,

        "--qq-required-color": t.required_color,

        "--qq-progress-track": t.progress_track_color,

        "--qq-progress-bar": t.progress_bar_color,

        "--qq-progress-height": `${t.progress_height}px`,

        "--qq-progress-label": t.progress_label_color,

        "--qq-error-color": t.error_color,

      };

    },

    rootStyles() {

      const t = this.theme;

      let background = t.background_color;

      if (t.background_type === "radial_gradient") {

        const center = t.background_gradient_center || t.background_color;

        const edge = t.background_gradient_edge || "#050a07";

        background = `radial-gradient(circle at center, ${center} 0%, ${edge} 100%)`;

      }

      return {

        ...this.cssVars,

        background,

        minHeight: t.fill_viewport ? "max(400px, 100vh)" : "auto",

        padding: "32px 24px",

      };

    },

    innerStyles() {

      const align = this.theme.alignment || "left";

      const alignItems = align === "center" ? "center" : align === "right" ? "flex-end" : "flex-start";

      return {

        maxWidth: `${this.theme.content_max_width || 640}px`,

        margin: "0 auto",

        width: "100%",

        display: "flex",

        flexDirection: "column",

        alignItems,

      };

    },

    cardStyles() {

      const t = this.theme;

      const base = {

        width: "100%",

        textAlign: t.alignment || "left",

      };

      if (!this.cardEnabled) {

        return base;

      }

      return {

        ...base,

        background: t.card_background,

        border: `1px solid ${t.card_border_color}`,

        borderRadius: `${t.card_border_radius}px`,

        padding: `${t.card_padding}px`,

        boxShadow: t.card_shadow ? "0 4px 24px rgba(0, 0, 0, 0.08)" : "none",

      };

    },

    backBtnStyles() {

      const t = this.theme;

      return {

        color: t.btn_back_text_color,

        borderColor: t.btn_back_color,

        borderRadius: `${t.btn_border_radius}px`,

        background: "transparent",

      };

    },

    nextBtnStyles() {

      const t = this.theme;

      return {

        background: t.btn_next_color,

        color: t.btn_next_text_color,

        borderColor: t.btn_next_color,

        borderRadius: `${t.btn_border_radius}px`,

      };

    },

  },

  provide() {

    return {

      quizQuestionsTheme: this.theme,

    };

  },

  methods: {

    updateSelection(questionId, value) {

      this.$emit("update:selection", { questionId, value });

    },

  },

};

</script>



<style scoped>

.quiz-questions-step {

  width: 100%;

  box-sizing: border-box;

}



.quiz-questions-step__card {

  box-sizing: border-box;

}



.quiz-questions-step__card--plain {

  background: transparent !important;

  border: none !important;

  box-shadow: none !important;

  padding: 0 !important;

}



.quiz-questions-step__progress-label {

  color: var(--qq-progress-label, #6b7280);

  font-size: 0.875rem;

}



.quiz-questions-step__progress-track {

  width: 100%;

  height: var(--qq-progress-height, 6px);

  background: var(--qq-progress-track, #e9ecef);

  border-radius: 999px;

  overflow: hidden;

}



.quiz-questions-step__progress-bar {

  height: 100%;

  background: var(--qq-progress-bar, #ff5a1f);

  transition: width 0.2s ease;

}



.quiz-questions-step__btn-back {

  border-width: 1px;

  border-style: solid;

}



.quiz-questions-step__btn-next {

  border-width: 1px;

  border-style: solid;

  font-weight: 600;

}



.quiz-questions-step__error {

  color: var(--qq-error-color, #dc3545);

}

</style>

