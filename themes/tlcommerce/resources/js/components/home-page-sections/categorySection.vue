<template>
  <template v-if="isSplitScreen && !isMobile">
    <!-- Categories -->
    <section
      class="pt-5 pb-5 category-section category-section--split home-page-section"
      :style="splitScreenStyleObject"
      :class="{
        'force-mobile-layout': forcedMobile,
        'mobile-content-wrapper': forcedMobile,
      }"
    >
      <div class="px-3 px-sm-0">
        <swiper
          v-if="properties.categories.data.length"
          :modules="modules"
          :loop="splitScreenSwiperLoop"
          :centeredSlides="false"
          :watch-overflow="true"
          :autoplay="false"
          :pagination="splitScreenSwiperPagination"
          class="category-slider category-full-width theme-slider-dots"
          :breakpoints="swiperBreakpoints"
        >
          <swiper-slide
            v-for="(cat, index) in properties.categories.data"
            :key="`category-${index}`"
          >
            <category-card :cat="cat" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
    <!-- End Categories -->
  </template>

  <template v-else>
    <!-- Categories -->
    <section
      class="pt-15 pb-15 category-section home-page-section"
      :style="styleObject"
    >
      <div class="px-3 px-sm-0">
        <swiper
          v-if="properties.categories.data.length"
          :modules="modules"
          :loop="true"
          :centeredSlides="true"
          :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :pagination="{
            clickable: true,
          }"
          class="category-slider category-full-width theme-slider-dots"
          :breakpoints="{
            '0': {
              slidesPerView: 2,
              spaceBetween: 16,
            },
            '768': {
              slidesPerView: 3,
              spaceBetween: 20,
            },
            '1024': {
              slidesPerView: 4,
              spaceBetween: 20,
            },
            '1440': {
              slidesPerView: 5,
              spaceBetween: 20,
            },
          }"
        >
          <swiper-slide
            v-for="(cat, index) in properties.categories.data"
            :key="`category-${index}`"
          >
            <category-card :cat="cat" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
    <!-- End Categories -->
  </template>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import CategoryCard from "../ui/CategoryCard.vue";

import { Autoplay, Pagination } from "swiper";
import { mapGetters } from "vuex";

export default {
  name: "CategorySection",
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
    content: {
      type: String,
      required: false,
    },
    properties: {
      type: Array,
      required: false,
    },
  },
  computed: {
    styleObject() {
      return {
        //Section
        "--section-background-color": this.properties.bg_color,
        "--section-bg-image": `url(${this.properties.bg_image})`,
        "--section-background-image-position":
          this.properties.background_position,
        "--section-background-image-size": this.properties.background_size,
        "--section-background-image-repeat": this.properties.background_repeat,
        //Padding
        "--section-padding": `${
          this.properties.padding_top +
          "px " +
          this.properties.padding_right +
          "px " +
          this.properties.padding_bottom +
          "px " +
          this.properties.padding_left +
          "px"
        }`,
        //Margin
        "--section-margin": `${
          this.properties.margin_top +
          "px " +
          this.properties.margin_right +
          "px " +
          this.properties.margin_bottom +
          "px " +
          this.properties.margin_left +
          "px"
        }`,
      };
    },

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    forcedMobile() {
      return this.isSplitScreen && !this.isMobile;
    },

    // production split-screen: transparent section, tight swiper gaps
    splitScreenStyleObject() {
      return {
        ...this.styleObject,
        "--section-background-color": "transparent",
        "--section-bg-image": "none",
      };
    },

    splitScreenCategoryCount() {
      return this.properties?.categories?.data?.length || 0;
    },

    splitScreenSwiperLoop() {
      // Loop duplicates slides — looks like long empty gaps when few categories
      return this.splitScreenCategoryCount > 8;
    },

    splitScreenSwiperPagination() {
      if (this.splitScreenCategoryCount <= 4) {
        return false;
      }
      return { clickable: true };
    },

    swiperBreakpoints() {
      const count = this.splitScreenCategoryCount;
      const cap = (max) => (count ? Math.min(max, count) : max);

      if (this.forcedMobile) {
        return {
          "0": { slidesPerView: cap(4), spaceBetween: 2 },
        };
      }
      return {
        "0": { slidesPerView: cap(4), spaceBetween: 2 },
        "768": { slidesPerView: cap(8), spaceBetween: 0 },
        "1024": { slidesPerView: cap(10), spaceBetween: 0 },
        "1440": { slidesPerView: cap(12), spaceBetween: 0 },
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

.category-section--split :deep(.category-slider .swiper-wrapper) {
  align-items: center;
}

/* Keep categories left-aligned when they do not fill the track */
.category-section--split :deep(.category-slider.swiper-watch-overflow .swiper-wrapper) {
  justify-content: flex-start;
}

/* Override theme .category-full-width slide max-width (260px) — causes card-like gaps */
.category-section--split :deep(.category-full-width .swiper-slide),
.category-section--split :deep(.category-slider .swiper-slide) {
  width: auto !important;
  max-width: none !important;
  flex-shrink: 0;
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  opacity: 1 !important;
  padding: 0 !important;
  margin: 0 !important;
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

.force-mobile-layout .row > [class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.force-mobile-layout .product-content .image,
.force-mobile-layout .product-content .product-img {
  min-width: 0 !important;
  max-width: 100px;
}

.force-mobile-layout .cart-image-review {
  max-width: 100% !important;
  height: auto !important;
  flex-shrink: 1 !important;
}

.force-mobile-layout .d-none.d-lg-block,
.force-mobile-layout .d-lg-block {
  display: none !important;
}

.force-mobile-layout .d-block.d-lg-none,
.force-mobile-layout .d-lg-none {
  display: block !important;
}

.force-mobile-layout .d-block.d-lg-none.col-12 {
  width: 100% !important;
  display: flex !important;
  flex-wrap: wrap !important;
  justify-content: space-between !important;
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
