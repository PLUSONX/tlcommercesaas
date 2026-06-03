<template>
  <template v-if="isSplitScreen">
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
          v-if="categoryList.length"
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
          :breakpoints="swiperBreakpoints"
          @swiper="onSplitCategorySwiper"
        >
          <swiper-slide
            v-for="(cat, index) in categoryList"
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
          v-if="categoryList.length"
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
            v-for="(cat, index) in categoryList"
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
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      splitCategorySwiper: null,
      splitCategoryResizeTimeout: null,
    };
  },
  computed: {
    categoryList() {
      return this.properties?.categories?.data ?? [];
    },

    sectionProperties() {
      return this.properties || {};
    },

    styleObject() {
      const props = this.sectionProperties;
      return {
        //Section
        "--section-background-color": props.bg_color,
        "--section-bg-image": `url(${props.bg_image})`,
        "--section-background-image-position": props.background_position,
        "--section-background-image-size": props.background_size,
        "--section-background-image-repeat": props.background_repeat,
        //Padding
        "--section-padding": `${
          props.padding_top +
          "px " +
          props.padding_right +
          "px " +
          props.padding_bottom +
          "px " +
          props.padding_left +
          "px"
        }`,
        //Margin
        "--section-margin": `${
          props.margin_top +
          "px " +
          props.margin_right +
          "px " +
          props.margin_bottom +
          "px " +
          props.margin_left +
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
      return this.categoryList.length;
    },

    splitScreenSwiperLoop() {
      // Loop duplicates slides — looks like long empty gaps when few categories
      return this.splitScreenCategoryCount > 8;
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

    swiperBreakpoints() {
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
  box-sizing: border-box;
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  opacity: 1 !important;
  padding: 0 !important;
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
