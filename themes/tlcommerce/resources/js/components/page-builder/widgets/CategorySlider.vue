<template>
  <template v-if="isFeaturePaneLayout">
    <!-- Categories — split-screen layout -->
    <section class="category-section category-section--split home-page-section">
      <div class="px-3 px-sm-0">
        <swiper
          v-if="properties.categories.length && properties.style == 'slider'"
          :modules="modules"
          :loop="splitScreenSwiperLoop"
          :centeredSlides="false"
          :centerInsufficientSlides="splitScreenCenterSlides"
          :watch-overflow="true"
          :autoplay="false"
          :pagination="splitScreenSwiperPagination"
          :slides-per-view="'auto'"
          :space-between="splitCategoryGap"
          class="category-slider category-full-width theme-slider-dots category-slider--split"
          :breakpoints="splitScreenSwiperBreakpoints"
          @swiper="onSplitCategorySwiper"
        >
          <swiper-slide v-for="(cat, index) in properties.categories" :key="`category-${index}`">
            <category-card :cat="cat" />
          </swiper-slide>
        </swiper>
        <div class="row" :class="{ 'justify-content-center': splitScreenFewCategories }" v-else>
          <div v-for="(cat, index) in properties.categories" :key="`category-${index}`"
            class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <category-card :cat="cat" />
          </div>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <!-- Categories — default layout -->
    <section class="category-section home-page-section">
      <div class="px-3 px-sm-0">
        <swiper v-if="properties.categories.length && properties.style == 'slider'" :modules="modules" :loop="true"
          :centeredSlides="true" :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }" :pagination="{ clickable: true }" class="category-slider category-full-width theme-slider-dots"
          :breakpoints="{
            '0': { slidesPerView: 2, spaceBetween: 16 },
            '768': { slidesPerView: 3, spaceBetween: 20 },
            '1024': { slidesPerView: 4, spaceBetween: 20 },
            '1440': { slidesPerView: 5, spaceBetween: 20 },
          }">
          <swiper-slide v-for="(cat, index) in properties.categories" :key="`category-${index}`">
            <category-card :cat="cat" />
          </swiper-slide>
        </swiper>
        <div class="row" v-else>
          <div v-for="(cat, index) in properties.categories" :key="`category-${index}`"
            class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <category-card :cat="cat" />
          </div>
        </div>
      </div>
    </section>
  </template>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import { mapGetters } from "vuex";
import CategoryCard from "../../ui/CategoryCard.vue";
import { Autoplay, Pagination } from "swiper";

export default {
  name: "CategorySlider",
  components: {
    CategoryCard,
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [Autoplay, Pagination],
    };
  },
  props: {
    properties: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      splitCategorySwiper: null,
      splitCategoryResizeTimeout: null,
    };
  },
  computed: {
    ...mapGetters("layout", ["isFeaturePaneLayout", "isMobile"]),

    splitScreenCategoryCount() {
      return this.properties?.categories?.length || 0;
    },

    splitScreenSwiperLoop() {
      return this.splitScreenCategoryCount > 6;
    },

    splitScreenCenterSlides() {
      return !this.splitScreenSwiperLoop;
    },

    splitScreenSwiperPagination() {
      if (this.splitScreenCategoryCount <= 4) {
        return false;
      }
      return { clickable: true };
    },

    splitCategoryGap() {
      return 8;
    },

    splitScreenFewCategories() {
      return this.splitScreenCategoryCount <= 4;
    },

    splitScreenSwiperBreakpoints() {
      const gap = this.splitCategoryGap;
      return {
        "0": { slidesPerView: "auto", spaceBetween: gap },
        "768": { slidesPerView: "auto", spaceBetween: gap },
        "1024": { slidesPerView: "auto", spaceBetween: gap },
        "1440": { slidesPerView: "auto", spaceBetween: gap },
      };
    },
  },
  mounted() {
    window.addEventListener("resize", this.handleSplitCategoryResize);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.handleSplitCategoryResize);
    if (this.splitCategoryResizeTimeout) {
      clearTimeout(this.splitCategoryResizeTimeout);
    }
  },
  methods: {
    onSplitCategorySwiper(swiper) {
      this.splitCategorySwiper = swiper;
      this.$nextTick(() => this.updateSplitCategorySwiper());
    },
    handleSplitCategoryResize() {
      if (this.splitCategoryResizeTimeout) {
        clearTimeout(this.splitCategoryResizeTimeout);
      }
      this.splitCategoryResizeTimeout = setTimeout(() => {
        this.updateSplitCategorySwiper();
      }, 100);
    },
    updateSplitCategorySwiper() {
      if (this.splitScreenCenterSlides && this.splitCategorySwiper) {
        this.splitCategorySwiper.update();
      }
    },
  },
};
</script>

<style scoped>
/* split-screen layout */
.category-section--split {
  background-color: transparent !important;
  background-image: none !important;
  --category-split-gap: 8px;
}

.category-section--split :deep(.category-slider .swiper-wrapper) {
  align-items: center;
}

.category-section--split :deep(.category-slider--split .swiper-slide) {
  width: auto !important;
  max-width: none !important;
  flex-shrink: 0;
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  padding: 0 !important;
  display: flex;
  justify-content: center;
}

.category-section--split :deep(.category-card),
.category-section--split :deep(.category-card--split) {
  width: max-content !important;
  min-width: 0 !important;
  padding: 0 !important;
  margin: 0 !important;
  background: transparent !important;
  background-color: transparent !important;
  box-shadow: none !important;
  border: none !important;
  border-radius: 0 !important;
}

.category-section--split :deep(.swiper-slide:last-child .category-card),
.category-section--split :deep(.swiper-slide:last-child .category-card--split) {
  margin-right: 0 !important;
}

/* default layout */
.category-section {
  background-image: var(--section-bg-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}
</style>