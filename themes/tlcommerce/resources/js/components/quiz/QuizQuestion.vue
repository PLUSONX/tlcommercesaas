<template>

  <div class="quiz-question mb-4" :style="answerCssVars">

    <div class="quiz-question__title-wrap" :style="questionTitleWrapStyles">

      <h5 class="quiz-question__title mb-0">

        {{ question.question_text }}

        <span v-if="question.is_required" class="quiz-question__required">*</span>

      </h5>

    </div>



    <div

      v-if="usesAnswerLayout"

      class="quiz-answers"

      :class="answersContainerClasses"

      :style="answersContainerStyle"

    >

      <label

        v-for="answer in question.answers"

        :key="answer.id"

        class="quiz-answer-option"

        :class="[

          answerOptionClasses,

          { active: isSelected(answer.id) },

          answerTextGradientClass(answer.id),

        ]"

        @click="!showNativeInput ? handleLabelClick(answer.id) : null"

      >

        <input

          v-if="showNativeInput"

          :type="inputType"

          :name="inputType === 'radio' ? 'question-' + question.id : undefined"

          :value="answer.id"

          :checked="isSelected(answer.id)"

          @change="handleInputChange(answer.id, $event)"

        />

        <img

          v-if="answer.answer_image"

          :src="answerImageSrc(answer.answer_image)"

          class="quiz-answer-image"

          alt=""

        />

        <span class="quiz-answer-content">

          <span class="quiz-answer-label">{{ answer.answer_text }}</span>

          <span v-if="answer.answer_description" class="quiz-answer-description" v-html="formatAnswerDescription(answer.answer_description)"></span>

        </span>

      </label>

    </div>



    <div v-else-if="question.question_type === 'dropdown'">

      <select class="quiz-answer-dropdown theme-input-style w-100" :value="singleValue" @change="selectSingle(Number($event.target.value))">

        <option value="">{{ $t("Select an option") }}</option>

        <option v-for="answer in question.answers" :key="answer.id" :value="answer.id">

          {{ answer.answer_text }}

        </option>

      </select>

    </div>

  </div>

</template>



<script>

import { formatAnswerDescription } from "../../utils/multilineText";

const FILL_KEYS = [

  "background",

  "border_color",

  "text_color",

  "hover_background",

  "hover_border_color",

  "active_background",

  "active_border_color",

  "active_text_color",

];



const DEFAULT_ANSWER_STYLE = {

  gap: 8,

  border_width: 1,

  padding: 12,

  radius: 8,

  show_native_input: true,

  background: "#ffffff",

  border_color: "#e2e2e2",

  text_color: "#111111",

  hover_background: "#f3f4f6",

  hover_border_color: "#d1d5db",

  active_background: "#f8f9fa",

  active_border_color: "#333333",

  active_text_color: "#111111",

  grid_columns: 2,

  image_size: 80,

  image_position: "top",

  hover_scale: 1,

};



FILL_KEYS.forEach((key) => {

  DEFAULT_ANSWER_STYLE[`${key}_type`] = "solid";

  DEFAULT_ANSWER_STYLE[`${key}_gradient_center`] = DEFAULT_ANSWER_STYLE[key];

  DEFAULT_ANSWER_STYLE[`${key}_gradient_edge`] = "#050a07";

});



export default {

  name: "QuizQuestion",

  props: {

    question: {

      type: Object,

      required: true,

    },

    modelValue: {

      type: Array,

      default: () => [],

    },

  },

  emits: ["update:modelValue"],

  inject: {

    quizQuestionsTheme: { default: null },

  },

  computed: {

    pageTheme() {

      return this.quizQuestionsTheme || {};

    },

    resolvedQuestionAlignment() {

      const t = this.pageTheme;

      return t.question_alignment || t.alignment || "left";

    },

    questionTitleWrapStyles() {

      const t = this.pageTheme;

      return {

        background: t.question_background || "transparent",

        textAlign: this.resolvedQuestionAlignment,

        padding: `${t.question_padding || 0}px`,

        margin: `${t.question_margin != null ? t.question_margin : 16}px 0`,

      };

    },

    answersConfig() {

      return this.question?.layout_config?.answers || {};

    },

    resolvedStyle() {

      return {

        ...DEFAULT_ANSWER_STYLE,

        ...(this.answersConfig.resolved || {}),

      };

    },

    layout() {

      return this.answersConfig.layout || "list";

    },

    usesAnswerLayout() {

      return ["radio", "checkbox", "image_select"].includes(this.question.question_type);

    },

    showNativeInput() {

      return this.resolvedStyle.show_native_input !== false;

    },

    inputType() {

      return this.question.question_type === "checkbox" ? "checkbox" : "radio";

    },

    answersContainerClasses() {

      const classes = [`quiz-answers--${this.layout}`];

      if (this.layout === "grid") {

        classes.push(`quiz-answers--image-${this.resolvedStyle.image_position || "top"}`);

      }

      return classes;

    },

    answerOptionClasses() {

      const classes = ["d-flex", "align-items-center"];

      if (this.layout === "grid" && (this.resolvedStyle.image_position || "top") === "top") {

        classes.push("flex-column");

      }

      return classes;

    },

    answersContainerStyle() {

      if (this.layout === "grid") {

        const cols = this.resolvedStyle.grid_columns || 2;

        return { gridTemplateColumns: `repeat(${cols}, 1fr)` };

      }

      return {};

    },

    answerCssVars() {

      const s = this.resolvedStyle;

      const layer = (key) => this.resolveFillLayer(s, key);

      const vars = {

        "--qq-question-color": this.pageTheme.question_color || "#111111",

        "--qq-required-color": this.pageTheme.required_color || "#dc3545",

        "--qq-answer-bg-layer": layer("background"),

        "--qq-answer-border-layer": layer("border_color"),

        "--qq-answer-hover-bg-layer": layer("hover_background"),

        "--qq-answer-hover-border-layer": layer("hover_border_color"),

        "--qq-answer-active-bg-layer": layer("active_background"),

        "--qq-answer-active-border-layer": layer("active_border_color"),

        "--qq-answer-text": this.resolveFill(s, "text_color"),

        "--qq-answer-active-text": this.resolveFill(s, "active_text_color"),

        "--qq-answer-text-solid": this.isGradientFill(s, "text_color") ? "transparent" : this.resolveFill(s, "text_color"),

        "--qq-answer-active-text-solid": this.isGradientFill(s, "active_text_color") ? "transparent" : this.resolveFill(s, "active_text_color"),

        "--qq-answer-radius": `${s.radius}px`,

        "--qq-answer-padding": `${s.padding}px`,

        "--qq-answer-gap": `${s.gap}px`,

        "--qq-answer-border-width": `${s.border_width}px`,

        "--qq-image-size": `${s.image_size}px`,

      };

      if (this.layout === "grid" && (s.hover_scale || 1) > 1) {

        vars["--qq-answer-hover-scale"] = String(s.hover_scale);

      }

      return vars;

    },

    singleValue() {

      return this.modelValue[0] || "";

    },

  },

  methods: {

    formatAnswerDescription,

    isGradientFill(style, baseKey) {

      return (style[`${baseKey}_type`] || "solid") === "radial_gradient";

    },

    resolveFill(style, baseKey) {

      const fallback = DEFAULT_ANSWER_STYLE[baseKey] || "#111111";

      const edgeFallback = DEFAULT_ANSWER_STYLE[`${baseKey}_gradient_edge`] || "#050a07";



      if (this.isGradientFill(style, baseKey)) {

        const center = style[`${baseKey}_gradient_center`] || style[baseKey] || fallback;

        const edge = style[`${baseKey}_gradient_edge`] || edgeFallback;

        return `radial-gradient(circle at center, ${center} 0%, ${edge} 100%)`;

      }



      return style[baseKey] || fallback;

    },

    resolveFillLayer(style, baseKey) {

      const css = this.resolveFill(style, baseKey);

      if (this.isGradientFill(style, baseKey)) {

        return css;

      }

      return `linear-gradient(${css}, ${css})`;

    },

    answerTextGradientClass(answerId) {

      const s = this.resolvedStyle;

      const active = this.isSelected(answerId);

      const textKey = active ? "active_text_color" : "text_color";

      return this.isGradientFill(s, textKey) ? "quiz-answer-option--text-gradient" : "";

    },

    answerImageSrc(image) {

      if (!image) return "";

      if (String(image).startsWith("http")) return image;

      return String(image).replace(/^\/public/, "");

    },

    isSelected(answerId) {

      return this.modelValue.includes(answerId);

    },

    selectSingle(answerId) {

      this.$emit("update:modelValue", answerId ? [answerId] : []);

    },

    toggleMultiple(answerId, checked) {

      const next = [...this.modelValue];

      const index = next.indexOf(answerId);

      if (checked && index === -1) {

        next.push(answerId);

      } else if (!checked && index !== -1) {

        next.splice(index, 1);

      }

      this.$emit("update:modelValue", next);

    },

    handleLabelClick(answerId) {

      if (this.inputType === "checkbox") {

        this.toggleMultiple(answerId, !this.isSelected(answerId));

      } else {

        this.selectSingle(answerId);

      }

    },

    handleInputChange(answerId, event) {

      if (this.inputType === "checkbox") {

        this.toggleMultiple(answerId, event.target.checked);

      } else {

        this.selectSingle(answerId);

      }

    },

  },

};

</script>



<style scoped>

.quiz-question__title {

  color: var(--qq-question-color, #111111);

}



.quiz-question__required {

  color: var(--qq-required-color, #dc3545);

}



.quiz-answers {

  display: flex;

  flex-direction: column;

  gap: var(--qq-answer-gap, 8px);

}



.quiz-answers--grid {

  display: grid;

}



.quiz-answer-option {

  padding: var(--qq-answer-padding, 12px);

  border: var(--qq-answer-border-width, 1px) solid transparent;

  border-radius: var(--qq-answer-radius, 8px);

  cursor: pointer;

  color: var(--qq-answer-text-solid, #111111);

  transition: border-color 0.15s, background 0.15s, color 0.15s;

  margin-bottom: 0;

  background-image: var(--qq-answer-bg-layer), var(--qq-answer-border-layer);

  background-origin: padding-box, border-box;

  background-clip: padding-box, border-box;

}



.quiz-answer-option:hover:not(.active) {

  background-image: var(--qq-answer-hover-bg-layer), var(--qq-answer-hover-border-layer);

  color: var(--qq-answer-text-solid, #111111);

}



.quiz-answer-option.active {

  background-image: var(--qq-answer-active-bg-layer), var(--qq-answer-active-border-layer);

  color: var(--qq-answer-active-text-solid, #111111);

}



.quiz-answer-option--text-gradient:not(.active) .quiz-answer-label,

.quiz-answer-option--text-gradient:not(.active) .quiz-answer-description {

  background: var(--qq-answer-text);

  background-clip: text;

  -webkit-background-clip: text;

  -webkit-text-fill-color: transparent;

  color: transparent;

}



.quiz-answer-option--text-gradient.active .quiz-answer-label,

.quiz-answer-option--text-gradient.active .quiz-answer-description {

  background: var(--qq-answer-active-text);

  background-clip: text;

  -webkit-background-clip: text;

  -webkit-text-fill-color: transparent;

  color: transparent;

}



.quiz-answers--grid .quiz-answer-option {

  text-align: center;

  transform-origin: center center;

  transition: transform 0.2s ease, border-color 0.15s, background 0.15s, color 0.15s;

}

.quiz-answers--grid .quiz-answer-option:hover,

.quiz-answers--grid .quiz-answer-option.active {

  transform: scale(var(--qq-answer-hover-scale, 1));

  z-index: 1;

}



.quiz-answers--image-left .quiz-answer-option {

  flex-direction: row !important;

  text-align: left;

}



.quiz-answer-image {

  width: var(--qq-image-size, 80px);

  height: var(--qq-image-size, 80px);

  object-fit: cover;

  border-radius: calc(var(--qq-answer-radius, 8px) * 0.75);

  flex-shrink: 0;

}



.quiz-answers--grid:not(.quiz-answers--image-left) .quiz-answer-image {

  margin-bottom: 8px;

}



.quiz-answers--image-left .quiz-answer-image {

  margin-right: 8px;

}



.quiz-answers:not(.quiz-answers--grid) .quiz-answer-option input + .quiz-answer-content,

.quiz-answers:not(.quiz-answers--grid) .quiz-answer-option input + .quiz-answer-image + .quiz-answer-content {

  margin-left: 8px;

}



.quiz-answer-content {

  display: flex;

  flex-direction: column;

  min-width: 0;

}



.quiz-answer-label {

  font-weight: 500;

}



.quiz-answer-description {

  display: block;

  font-size: 0.875rem;

  opacity: 0.85;

  margin-top: 4px;

}



.quiz-answer-option input {

  flex-shrink: 0;

}



.quiz-answer-dropdown {

  border: 1px solid #e2e2e2;

  border-radius: 8px;

  padding: 12px;

  color: #111111;

  background: #fff;

}

</style>

