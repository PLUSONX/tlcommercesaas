<template>
  <div class="customer-review">
    <template v-if="loading">
      <p class="customer-review__loading mb-0">{{ $t("Please wait") }}...</p>
    </template>

    <template v-else-if="reviews.length === 0">
      <p class="customer-review__empty mb-0">{{ $t("No Review Yet") }}</p>
    </template>

    <template v-else>
      <swiper v-if="useSlider" :slides-per-view="1" :space-between="16" :modules="modules" :loop="reviews.length > 1"
        :pagination="{ clickable: true }" :autoplay="{
          delay: 4000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }" class="customer-review-slider theme-slider-dots">
        <swiper-slide v-for="(review, index) in reviews" :key="`customer-review-${review.id ?? index}`">
          <div class="customer-review-card">
            <p class="customer-review-card__name font-weight-bold mb-2">
              {{ reviewCustomerName(review) }}
            </p>
            <div class="product-rating-wrapper mb-2">
              <i :data-star="review.rating" :title="review.rating"></i>
            </div>
            <p v-if="review.review" class="customer-review-card__comment mb-0">
              {{ review.review }}
            </p>
          </div>
        </swiper-slide>
      </swiper>

      <div v-else class="customer-review-card">
        <p class="customer-review-card__name font-weight-bold mb-2">
          {{ reviewCustomerName(reviews[0]) }}
        </p>
        <div class="product-rating-wrapper mb-2">
          <i :data-star="reviews[0].rating" :title="reviews[0].rating"></i>
        </div>
        <p v-if="reviews[0].review" class="customer-review-card__comment mb-0">
          {{ reviews[0].review }}
        </p>
      </div>
    </template>
  </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper";
import enums from "@/enums/enums";

export default {
  name: "CustomerReview",
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [Autoplay, Pagination],
    };
  },
  data() {
    return {
      enums,
      loading: true,
      reviews: [],
    };
  },
  computed: {
    ...mapState({
      siteSettings: (state) => state.siteSettings,
    }),
    useSlider() {
      return this.reviews.length > 1;
    },
  },
  mounted() {
    this.fetchReviews();
  },
  methods: {
    reviewCustomerName(review) {
      return review?.customer?.name || this.$t("Customer");
    },
    fetchReviews() {
      this.loading = true;
      console.log("fetchReviews method called!!!");
      axios
        .post("/api/v1/ecommerce-core/storefront-customer-reviews")
        .then((response) => {
          console.log("storefront-customer-reviews response:", response.data);
          if (response.data.success) {
            this.reviews = response.data.data || [];
          } else {
            this.reviews = [];
          }
        })
        .catch((error) => {
          console.error("Failed to load storefront reviews", error?.response?.status, error);
          this.reviews = [];
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
.customer-review {
  width: 100%;
}

.customer-review__loading,
.customer-review__empty {
  text-align: center;
  color: #666;
  font-size: 14px;
}

.customer-review-card {
  text-align: center;
  padding: 4px 8px;
}

.customer-review-card__name {
  font-size: 14px;
  margin: 0;
}

.customer-review-card__comment {
  font-size: 13px;
  color: #444;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.customer-review-slider {
  width: 100%;
  padding-bottom: 28px;
}

.customer-review-slider :deep(.swiper-pagination) {
  bottom: 0;
}
</style>
