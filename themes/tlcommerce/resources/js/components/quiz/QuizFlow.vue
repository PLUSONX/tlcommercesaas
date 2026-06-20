<template>

  <div class="quiz-flow" :class="{ 'light-bg': useLightBg }">

    <div class="">

      <div v-if="loading" class="text-center py-5">

        <p>{{ $t("Loading") }}...</p>

      </div>



      <div v-else-if="loadError" class="alert alert-danger">

        {{ loadError }}

      </div>



      <template v-else-if="quiz">

        <quiz-intro v-if="step === 'intro'" :quiz="quiz" @start="startQuiz" />



        <quiz-questions-step

          v-else-if="step === 'questions'"

          :quiz="quiz"

          :step-style="stepStyle"

          :current-index="currentIndex"

          :selections="selections"

          :validation-error="validationError"

          :submitting="submitting"

          @prev="prevQuestion"

          @next="nextQuestion"

          @submit="submitQuiz"

          @update:selection="onSelectionUpdate"

        />



        <quiz-results
          v-else-if="step === 'results'"
          :quiz="quiz"
          :results="results"
          @restart="retakeQuiz"
        />

      </template>

    </div>

  </div>

</template>



<script>

import axios from "axios";

import { mapState } from "vuex";

import QuizIntro from "@/components/quiz/QuizIntro.vue";

import QuizQuestionsStep from "@/components/quiz/QuizQuestionsStep.vue";

import QuizResults from "@/components/quiz/QuizResults.vue";



const SESSION_KEY = "quiz_session_token";



export default {

  name: "QuizFlow",

  components: {

    QuizIntro,

    QuizQuestionsStep,

    QuizResults,

  },

  data() {

    return {

      loading: true,

      loadError: null,

      quiz: null,

      step: "intro",

      currentIndex: 0,

      selections: {},

      results: [],

      submitting: false,

      validationError: "",

      sessionToken: "",

    };

  },

  computed: {

    ...mapState(["isCustomerLogin", "customerToken", "guestCustomerInfo"]),

    slug() {

      return this.$route.params.slug;

    },

    stepStyle() {

      return this.quiz?.layout_config?.step_style || "wizard";

    },

    isCustomStackIntro() {

      return this.step === "intro"

        && this.quiz?.layout_config?.intro?.layout === "custom_stack";

    },

    isCustomResults() {

      return this.step === "results" && !!this.quiz?.layout_config?.results;

    },

    useLightBg() {

      if (this.step === "questions") return false;

      if (this.step === "intro" && this.isCustomStackIntro) return false;

      if (this.step === "results" && this.isCustomResults) return false;

      return this.step === "results" || this.step === "intro";

    },

    apiHeaders() {

      const headers = {

        "Accept-Language": localStorage.getItem("locale") || "",

      };

      if (this.isCustomerLogin && this.customerToken) {

        headers.Authorization = `Bearer ${this.customerToken}`;

      }

      return headers;

    },

    questionsTheme() {

      return this.quiz?.layout_config?.questions?.theme || {};

    },

    showNextButton() {

      return this.questionsTheme.show_next_button !== false;

    },

    showBackButton() {

      return this.questionsTheme.show_back_button !== false;

    },

  },

  watch: {

    slug: {

      immediate: true,

      handler() {

        this.loadQuiz();

      },

    },

    selections: {

      deep: true,

      handler() {

        if (this.step === "questions" && this.stepStyle === "scroll" && !this.showNextButton) {

          this.$nextTick(() => this.tryAutoSubmitScroll());

        }

      },

    },

  },

  mounted() {

    this.sessionToken = this.getOrCreateSessionToken();

  },

  methods: {

    getOrCreateSessionToken() {

      let token = localStorage.getItem(SESSION_KEY);

      if (!token) {

        token = typeof crypto !== "undefined" && crypto.randomUUID

          ? crypto.randomUUID().replace(/-/g, "")

          : `quiz${Date.now()}${Math.random().toString(36).slice(2, 12)}`;

        localStorage.setItem(SESSION_KEY, token);

      }

      return token.slice(0, 64);

    },

    initSelections() {

      const map = {};

      (this.quiz?.questions || []).forEach((q) => {

        map[q.id] = [];

      });

      this.selections = map;

    },

    onSelectionUpdate({ questionId, value }) {

      this.selections = { ...this.selections, [questionId]: value };

      if (!this.showNextButton) {

        this.$nextTick(() => {

          if (this.stepStyle === "scroll") {

            this.tryAutoSubmitScroll();

          } else {

            this.tryAutoAdvance(questionId);

          }

        });

      }

    },

    loadQuiz() {

      this.loading = true;

      this.loadError = null;

      this.step = "intro";

      this.currentIndex = 0;

      this.results = [];



      axios

        .get(`/api/theme/tlcommerce/v1/quiz/${this.slug}`, { headers: this.apiHeaders })

        .then((response) => {

          const payload = response.data?.data || response.data;

          if (!payload || response.data.success === false) {

            this.loadError = this.$t("Quiz not found");

            this.quiz = null;

          } else {

            this.quiz = payload;

            document.title = payload.title || this.$t("Quiz");

            this.initSelections();

            if (payload.layout_config?.skip_intro) {

              this.startQuiz();

            }

          }

          this.loading = false;

        })

        .catch(() => {

          this.loadError = this.$t("Quiz not found");

          this.loading = false;

        });

    },

    startQuiz() {

      this.validationError = "";

      this.currentIndex = 0;

      this.step = "questions";

    },

    prevQuestion() {

      if (!this.showBackButton) {

        return;

      }

      if (this.currentIndex > 0) {

        this.currentIndex -= 1;

      }

    },

    nextQuestion() {

      this.validationError = "";

      const question = this.quiz?.questions?.[this.currentIndex];

      if (question?.is_required && !(this.selections[question.id]?.length)) {

        this.validationError = this.$t("Please answer this question before continuing");

        return false;

      }

      if (this.currentIndex < (this.quiz?.questions?.length || 1) - 1) {

        this.currentIndex += 1;

      }

      return true;

    },

    isLastQuestionIndex() {

      return this.currentIndex >= (this.quiz?.questions?.length || 1) - 1;

    },

    tryAutoAdvance(questionId) {

      if (this.step !== "questions" || this.submitting || this.showNextButton) {

        return;

      }

      const question = this.quiz?.questions?.[this.currentIndex];

      if (!question || question.id !== questionId) {

        return;

      }

      const value = this.selections[questionId] || [];

      if (!value.length) {

        return;

      }

      if (this.isLastQuestionIndex()) {

        this.submitQuiz();

        return;

      }

      this.nextQuestion();

    },

    tryAutoSubmitScroll() {

      if (this.step !== "questions" || this.submitting || this.showNextButton || this.stepStyle !== "scroll") {

        return;

      }

      const answers = this.buildAnswersPayload();

      if (!answers.length) {

        return;

      }

      for (const question of this.quiz.questions) {

        if (question.is_required && !(this.selections[question.id]?.length)) {

          return;

        }

      }

      this.submitQuiz();

    },

    buildAnswersPayload() {

      const answers = [];

      Object.keys(this.selections).forEach((questionId) => {

        (this.selections[questionId] || []).forEach((answerId) => {

          answers.push({

            question_id: Number(questionId),

            answer_id: Number(answerId),

          });

        });

      });

      return answers;

    },

    submitQuiz() {

      this.validationError = "";

      const answers = this.buildAnswersPayload();



      if (!answers.length) {

        this.validationError = this.$t("Please select at least one answer");

        return;

      }



      for (const question of this.quiz.questions) {

        if (question.is_required && !(this.selections[question.id]?.length)) {

          this.validationError = this.$t("Please answer all required questions");

          return;

        }

      }



      this.submitting = true;



      const payload = {

        session_token: this.sessionToken,

        answers,

      };



      if (this.guestCustomerInfo?.name) {

        payload.guest = {

          name: this.guestCustomerInfo.name,

          email: this.guestCustomerInfo.email || null,

        };

      }



      axios

        .post(`/api/theme/tlcommerce/v1/quiz/${this.slug}/submit`, payload, {

          headers: this.apiHeaders,

        })

        .then((response) => {

          if (response.data.success) {

            this.results = response.data.results || [];

            this.step = "results";

          } else {

            this.validationError = response.data.message || this.$t("Something went wrong");

          }

          this.submitting = false;

        })

        .catch((error) => {

          this.validationError =

            error.response?.data?.message || this.$t("Something went wrong");

          this.submitting = false;

        });

    },

    retakeQuiz() {

      this.initSelections();

      this.currentIndex = 0;

      this.results = [];

      this.validationError = "";

      this.step = "intro";

    },

  },

};

</script>


