<template>
  <div class="feedback-form" :class="{
    'force-mobile-layout': forcedMobile,
    'mobile-content-wrapper': forcedMobile,
  }">
    <!-- <div class="shipping-info light-bg pt-60 pb-60"> -->
    <div class="shipping-info light-bg pt-20 pb-60">
      <div class="custom-container2">
        <!-- <div class="shipping-info light-bg pt-60 pb-60"> -->
        <div class="shipping-info light-bg pt-20 pb-60">
          <div class="row">
            <h2 class="mb-4">{{ $t("Leave Feedback") }}</h2>

            <div class="col-lg-12">
              <div class="form-group mb-20">
                <label class="font-weight-bold fz-12 mb-2">
                  {{ $t("Rating") }}
                  <span class="text-danger">*</span>
                </label>
                <div class="emoji-rating" role="radiogroup" :aria-label="$t('Rating')">
                  <button
                    v-for="option in ratingOptions"
                    :key="option.value"
                    type="button"
                    role="radio"
                    class="emoji-rating__option"
                    :class="{ 'emoji-rating__option--active': form.satisfaction_rating === option.value }"
                    :aria-checked="form.satisfaction_rating === option.value"
                    @click="setRating(option.value)"
                  >
                    <span class="emoji-rating__emoji" aria-hidden="true">{{ option.emoji }}</span>
                    <span class="emoji-rating__label">{{ $t(option.label) }}</span>
                  </button>
                </div>
                <p v-if="ratingError" class="fz-12 text-danger mt-1">
                  {{ ratingError }}
                </p>
                <template v-if="errors.satisfaction_rating">
                  <p v-for="(error, index) in errors.satisfaction_rating" :key="index" class="fz-12 text-danger mt-1">
                    {{ error }}
                  </p>
                </template>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group mb-20">
                <label class="font-weight-bold fz-12 mb-2">
                  {{ $t("Name") }}
                </label>
                <input v-model="form.name" type="text" class="theme-input-style" :placeholder="$t('Name')" />
                <template v-if="errors.name">
                  <p v-for="(error, index) in errors.name" :key="index" class="fz-12 text-danger mt-1">
                    {{ error }}
                  </p>
                </template>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group mb-20">
                <label class="font-weight-bold fz-12 mb-2">
                  {{ $t("Phone") }}
                </label>
                <input v-model="form.phone" type="tel" class="theme-input-style" :placeholder="$t('Phone Number')" />
                <template v-if="errors.phone">
                  <p v-for="(error, index) in errors.phone" :key="index" class="fz-12 text-danger mt-1">
                    {{ error }}
                  </p>
                </template>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group mb-20">
                <label class="font-weight-bold fz-12 mb-2">
                  {{ $t("Comment") }}
                </label>
                <textarea v-model="form.comment" class="theme-input-style" rows="4"
                  :placeholder="$t('Write a review')" />
                <template v-if="errors.comment">
                  <p v-for="(error, index) in errors.comment" :key="index" class="fz-12 text-danger mt-1">
                    {{ error }}
                  </p>
                </template>
              </div>
            </div>

            <div class="col-12 mt-3">
              <button type="button" style="border-radius: 8px;" class="btn btn-fill" :disabled="formSubmitting"
                @click.prevent="submitFeedback">
                <span v-if="formSubmitting">
                  <CSpinner component="span" size="sm" aria-hidden="true" />
                  {{ $t("Please wait") }}
                </span>
                <span v-else>
                  {{ $t("Submit Feedback") }}
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { CSpinner } from "@coreui/vue";
import axios from "axios";

export default {
  name: "FeedbackForm",
  components: {
    CSpinner,
  },
  data() {
    return {
      ratingOptions: [
        { value: 1, emoji: "😡", label: "Very Dissatisfied" },
        { value: 2, emoji: "😞", label: "Dissatisfied" },
        { value: 3, emoji: "😐", label: "Neutral" },
        { value: 4, emoji: "🙂", label: "Satisfied" },
        { value: 5, emoji: "😄", label: "Very Satisfied" },
      ],
      form: {
        name: "",
        phone: "",
        satisfaction_rating: null,
        comment: "",
      },
      errors: {},
      ratingError: "",
      formSubmitting: false,
    };
  },
  computed: {
    ...mapState({
      customerInfo: (state) => state.customerInfo,
      isCustomerLogin: (state) => state.isCustomerLogin,
    }),
    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),
    forcedMobile() {
      if (this.isSplitScreen && !this.isMobile) {
        return true;
      }
      return this.isMobile;
    },
  },
  mounted() {
    if (this.isCustomerLogin && this.customerInfo) {
      if (this.customerInfo.name) {
        this.form.name = this.customerInfo.name;
      }
      if (this.customerInfo.phone) {
        this.form.phone = this.customerInfo.phone;
      }
    }
  },
  methods: {
    setRating(star) {
      this.form.satisfaction_rating = star;
      this.ratingError = "";
    },
    submitFeedback() {
      this.errors = {};
      this.ratingError = "";

      if (!this.form.satisfaction_rating) {
        this.ratingError = this.$t("Rating is required");
        return;
      }

      this.formSubmitting = true;

      axios
        .post("/api/v1/ecommerce-core/customer-feedback", {
          name: this.form.name || null,
          phone: this.form.phone || null,
          satisfaction_rating: this.form.satisfaction_rating,
          comment: this.form.comment || null,
        })
        .then((response) => {
          if (response.data.success) {
            this.$toast.success(this.$t("Thank you for your feedback"));
            this.$router.push({ name: "storeInfo" });
          } else {
            this.$toast.error(this.$t("Something went wrong"));
          }
          this.formSubmitting = false;
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          } else {
            this.$toast.error(this.$t("Something went wrong"));
          }
          this.formSubmitting = false;
        });
    },
  },
};
</script>

<style scoped>
.emoji-rating {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.emoji-rating__option {
  flex: 1 1 calc(20% - 8px);
  min-width: 56px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 10px 4px;
  background: #fff;
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  cursor: pointer;
  transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
}

.emoji-rating__option:hover {
  border-color: #ccc;
  background: #fafafa;
}

.emoji-rating__option--active {
  border-color: #333;
  background: #f8f9fa;
  box-shadow: 0 0 0 1px #333;
}

.emoji-rating__emoji {
  font-size: 28px;
  line-height: 1;
}

.emoji-rating__label {
  font-size: 10px;
  line-height: 1.2;
  text-align: center;
  color: #555;
}

.emoji-rating__option--active .emoji-rating__label {
  color: #222;
  font-weight: 600;
}

@media (max-width: 575px) {
  .emoji-rating__option {
    flex: 1 1 calc(33.333% - 8px);
  }
}

.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}
</style>
