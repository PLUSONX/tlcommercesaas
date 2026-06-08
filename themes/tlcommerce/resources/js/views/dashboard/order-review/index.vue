<template>
  <Dashboard>
    <div class="order_review" :class="{
      'force-mobile-layout': forcedMobile,
      'mobile-content-wrapper': forcedMobile,
    }">
      <page-header class="py-3" whiteBg :items="bItems" />
      <template v-if="!loading">
        <div v-if="orderDetails != null">
          <div class="shadow-card mb-30 p-3">
            <h6>{{ $t("Order ID") }} : {{ orderDetails.order_code }}</h6>
            <p class="mb-0">{{ $t("Order Placed on") }} {{ orderDetails.order_date }}</p>
            <p class="mb-0">
              {{ $t("Total") }} :
              <the-currency :amount="orderDetails.total_payable_amount"></the-currency>
            </p>
          </div>

          <div class="shadow-card p-3">
            <h5 class="mb-4">{{ $t("Write a review") }}</h5>

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
                  :class="{ 'emoji-rating__option--active': form.rating === option.value }"
                  :aria-checked="form.rating === option.value"
                  @click="setRating(option.value)"
                >
                  <span class="emoji-rating__emoji" aria-hidden="true">{{ option.emoji }}</span>
                  <span class="emoji-rating__label">{{ $t(option.label) }}</span>
                </button>
              </div>
              <p v-if="ratingError" class="fz-12 text-danger mt-1">
                {{ ratingError }}
              </p>
              <template v-if="errors.rating">
                <p v-for="(error, index) in errors.rating" :key="index" class="fz-12 text-danger mt-1">
                  {{ error }}
                </p>
              </template>
            </div>

            <div class="form-group mb-20">
              <label class="font-weight-bold fz-12 mb-2">
                {{ $t("Write a review") }}
              </label>
              <textarea
                v-model="form.review"
                class="theme-input-style"
                rows="4"
                :placeholder="$t('Write a review')"
              />
              <template v-if="errors.review">
                <p v-for="(error, index) in errors.review" :key="index" class="fz-12 text-danger mt-1">
                  {{ error }}
                </p>
              </template>
            </div>

            <div class="form-group mb-20">
              <label class="font-weight-bold fz-12 mb-2">
                {{ $t("Images") }}
              </label>
              <base-file-input id="reviewImages" name="reviewImages" v-on:getFileInput="reviewImages($event)" />
              <template v-if="errors.review_images">
                <p v-for="(error, index) in errors.review_images" :key="index" class="fz-12 text-danger mt-1">
                  {{ error }}
                </p>
              </template>
            </div>

            <button
              type="button"
              class="btn btn-fill"
              :disabled="formSubmitting"
              @click.prevent="submitReview"
            >
              <span v-if="formSubmitting">
                <CSpinner component="span" size="sm" aria-hidden="true" />
                {{ $t("Please wait") }}
              </span>
              <span v-else>
                {{ $t("Submit") }}
              </span>
            </button>
          </div>
        </div>
      </template>
      <template v-if="loading">
        <skeleton class="w-100 mb-20" height="70px"></skeleton>
        <skeleton class="w-100" height="500px"></skeleton>
      </template>
    </div>
  </Dashboard>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import Dashboard from "@/views/dashboard.vue";
import axios from "axios";
import enums from "@/enums/enums";
import { mapState, mapGetters } from "vuex";
import { CSpinner } from "@coreui/vue";

export default {
  name: "OrderReview",
  components: {
    PageHeader,
    Dashboard,
    CSpinner,
  },
  data() {
    return {
      enums: enums,
      loading: false,
      orderDetails: null,
      formSubmitting: false,
      ratingError: "",
      errors: {},
      review_images: [],
      form: {
        rating: null,
        review: "",
      },
      ratingOptions: [
        { value: 1, emoji: "😡", label: "Very Dissatisfied" },
        { value: 2, emoji: "😞", label: "Dissatisfied" },
        { value: 3, emoji: "😐", label: "Neutral" },
        { value: 4, emoji: "🙂", label: "Satisfied" },
        { value: 5, emoji: "😄", label: "Very Satisfied" },
      ],
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Dashboard"),
          href: "/dashboard",
        },
        {
          text: this.$t("Purchase History"),
          href: "/dashboard/purchase-history",
        },
        {
          text: this.$t("Review"),
          active: true,
        },
      ],
    };
  },
  computed: {
    ...mapState({
      customerToken: (state) => state.customerToken,
      siteSettings: (state) => state.siteSettings,
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
    document.title = this.$t("Dashboard") + " | " + this.$t("Review");
    if (this.siteSettings?.enable_product_reviews != this.enums.status.ACTIVE) {
      this.$toast.error(this.$t("Reviews are not enabled"));
      this.$router.push("/dashboard/purchase-history");
      return;
    }
    this.getCustomerOrderDetails();
  },
  methods: {
    setRating(star) {
      this.form.rating = star;
      this.ratingError = "";
    },
    reviewImages(e) {
      this.review_images = e;
    },
    isOrderEligible(order) {
      return (
        order.payment_status == this.enums.order_payment_status.PAID &&
        order.delivery_status == this.enums.order_delivery_status.DELIVERED
      );
    },
    getCustomerOrderDetails() {
      this.loading = true;
      axios
        .post(
          "/api/v1/ecommerce-core/customer/order/details",
          {
            order_id: this.$route.params.id,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.orderDetails = response.data.data;
            if (!this.isOrderEligible(this.orderDetails)) {
              this.$toast.error(this.$t("This order is not eligible for review"));
              this.$router.push("/dashboard/purchase-history");
              return;
            }
          } else {
            this.$toast.error(this.$t("Order not found"));
            this.$router.push("/dashboard/purchase-history");
          }
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
          this.$toast.error(this.$t("Order not found"));
          this.$router.push("/dashboard/purchase-history");
        });
    },
    submitReview() {
      this.errors = {};
      this.ratingError = "";

      if (!this.form.rating) {
        this.ratingError = this.$t("Rating is required");
        return;
      }

      this.formSubmitting = true;

      const formData = new FormData();
      formData.append("order_id", this.orderDetails.id);
      formData.append("rating", this.form.rating);
      formData.append("review", this.form.review || "");
      for (let z = 0; z < this.review_images.length; z++) {
        formData.append("review_images[" + z + "]", this.review_images[z]);
      }

      axios
        .post("/api/v1/ecommerce-core/customer/submit-customer-review", formData, {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.$toast.success(this.$t("Thank you for your review"));
            this.$router.push("/dashboard/purchase-history");
          } else {
            this.$toast.error(response.data.message || this.$t("Something went wrong"));
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

.force-mobile-layout .row > [class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}
</style>
