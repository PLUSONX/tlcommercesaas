<template>
  <template v-if="isSplitScreen && !isMobile">
    <!-- Categories — split-screen layout -->
    <section class="category-section category-section--split home-page-section">
      <div class="px-3 px-sm-0">
        <swiper
          v-if="properties.categories.length && properties.style == 'slider'"
          :modules="modules"
          :loop="splitScreenSwiperLoop"
          :centeredSlides="false"
          :watch-overflow="true"
          :autoplay="false"
          :pagination="splitScreenSwiperPagination"
          class="category-slider category-full-width theme-slider-dots"
          :breakpoints="splitScreenSwiperBreakpoints"
        >
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
  computed: {
    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    splitScreenCategoryCount() {
      return this.properties?.categories?.length || 0;
    },

    splitScreenSwiperLoop() {
      return this.splitScreenCategoryCount > 6;
    },

    splitScreenSwiperPagination() {
      if (this.splitScreenCategoryCount <= 4) {
        return false;
      }
      return { clickable: true };
    },

    splitScreenSwiperBreakpoints() {
      const count = this.splitScreenCategoryCount;
      const cap = (max) => (count ? Math.min(max, count) : max);

      return {
        "0": { slidesPerView: cap(2), spaceBetween: 5 },
        "768": { slidesPerView: cap(3), spaceBetween: 2 },
        "1024": { slidesPerView: cap(4), spaceBetween: 2 },
        "1440": { slidesPerView: cap(5), spaceBetween: 2 },
      };
    },
  },
};
</script>

<style scoped>
/* split-screen layout */
.category-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.category-section--split :deep(.category-slider.swiper-watch-overflow .swiper-wrapper) {
  justify-content: flex-start;
}

.category-section--split :deep(.category-full-width .swiper-slide),
.category-section--split :deep(.category-slider .swiper-slide) {
  width: auto !important;
  max-width: none !important;
  flex-shrink: 0;
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  padding: 0 !important;
  margin: 0 !important;
  display: flex;
  justify-content: center;
}

.category-section--split :deep(.category-card),
.category-section--split :deep(.category-card--split) {
  width: auto !important;
  min-width: 0 !important;
  padding: 0 !important;
  margin: 0 !important;
  background: transparent !important;
  background-color: transparent !important;
  box-shadow: none !important;
  border: none !important;
  border-radius: 0 !important;
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