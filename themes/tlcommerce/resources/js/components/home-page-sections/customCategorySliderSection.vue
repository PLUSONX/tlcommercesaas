<template>
  <template v-if="isFeaturePaneLayout && !isMobile">
    <section
      v-if="sectionReady"
      class="pt-15 pb-15 custom-category-section custom-category-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      :style="splitScreenStyleObject"
    >
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title
              class="mb-30 section-title"
              :title="sectionTitle"
              :titleColor="sectionStyleProps.title_color"
            />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link
              class="btn btn-sm mb-30 section_btn"
              :style="splitScreenStyleObject"
              to="/categories"
            >
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <swiper
          v-if="useSlider"
          :slidesPerView="6"
          :modules="modules"
          :spaceBetween="1"
          :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :loop="categoryList.length > categorySlotCount"
          :pagination="{ clickable: true }"
          class="product-grid-slider theme-slider-dots"
          :breakpoints="splitScreenSwiperBreakpoints"
        >
          <swiper-slide
            v-for="(cat, index) in categoryList"
            :key="`custom-cat-slide-${index}`"
          >
            <single-category :cat="cat" />
          </swiper-slide>
        </swiper>

        <div
          v-else
          class="custom-category-scale-grid"
          :class="{ 'custom-category-scale-grid--compact': isCompactStatic }"
          :style="staticGridStyle"
        >
          <div
            v-for="(cat, index) in categoryList"
            :key="`custom-cat-static-${index}`"
            class="custom-category-scale-grid__item"
          >
            <single-category :cat="cat" />
          </div>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <section
      v-if="sectionReady"
      class="pt-15 pb-15 custom-category-section home-page-section"
      :style="styleObject"
    >
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title
              class="mb-30 section-title"
              :title="sectionTitle"
              :titleColor="sectionStyleProps.title_color"
            />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link
              class="btn btn-sm mb-30 section_btn"
              :style="styleObject"
              to="/categories"
            >
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <swiper
          v-if="useSlider"
          :slidesPerView="6"
          :modules="modules"
          :spaceBetween="1"
          :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :loop="categoryList.length > categorySlotCount"
          :pagination="{ clickable: true }"
          class="product-grid-slider theme-slider-dots"
          :breakpoints="defaultSwiperBreakpoints"
        >
          <swiper-slide
            v-for="(cat, index) in categoryList"
            :key="`custom-cat-slide-${index}`"
          >
            <single-category :cat="cat" />
          </swiper-slide>
        </swiper>

        <div
          v-else
          class="custom-category-scale-grid"
          :class="{ 'custom-category-scale-grid--compact': isCompactStatic }"
          :style="staticGridStyle"
        >
          <div
            v-for="(cat, index) in categoryList"
            :key="`custom-cat-static-${index}`"
            class="custom-category-scale-grid__item"
          >
            <single-category :cat="cat" />
          </div>
        </div>
      </div>
    </section>
  </template>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper";
import { mapGetters } from "vuex";
import {
  normalizeSectionProps,
  sectionBgImageUrl,
  sectionViewAllLabel,
} from "@/utils/sectionProps";

const SingleCategory = defineAsyncComponent(() =>
  import("../product/SingleCategory.vue")
);

export default {
  name: "CustomCategorySliderSection",
  components: {
    Swiper,
    SwiperSlide,
    SingleCategory,
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
      type: [Object, Array],
      required: false,
    },
  },
  computed: {
    sectionStyleProps() {
      return normalizeSectionProps(this.properties);
    },

    sectionTitle() {
      const title = this.sectionStyleProps?.title;
      return title ? this.$t(title) : "Categories";
    },

    viewAllLabel() {
      return sectionViewAllLabel(this.properties, this.$t);
    },

    categoryList() {
      return this.sectionStyleProps?.categories?.data ?? [];
    },

    sectionReady() {
      return this.categoryList.length > 0;
    },

    isSplitDesktop() {
      return this.isFeaturePaneLayout && !this.isMobile;
    },

    useSlider() {
      const count = this.categoryList.length;
      if (this.isMobile || this.isSplitDesktop) {
        return count > 2;
      }
      return count > 6;
    },

    categorySlotCount() {
      if (this.isMobile || this.isSplitDesktop) {
        return 2;
      }
      return 6;
    },

    isCompactStatic() {
      return !this.useSlider && this.categoryList.length < 4;
    },

    staticGridStyle() {
      return {
        "--category-slot-count": this.categorySlotCount,
        "--category-count": this.categoryList.length,
      };
    },

    styleObject() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": sectionBgImageUrl(p.bg_image),
        "--section-background-image-position": p.background_position,
        "--section-background-image-size": p.background_size,
        "--section-background-image-repeat": p.background_repeat,
        "--section-padding": `${
          p.padding_top +
          "px " +
          p.padding_right +
          "px " +
          p.padding_bottom +
          "px " +
          p.padding_left +
          "px"
        }`,
        "--section-margin": `${
          p.margin_top +
          "px " +
          p.margin_right +
          "px " +
          p.margin_bottom +
          "px " +
          p.margin_left +
          "px"
        }`,
        "--button-color": p.btn_color,
        "--button-background-color": p.btn_bg_color,
        "--button-border":
          p.btn_border != null ? p.btn_border + "px solid" : 0 + "px",
        "--button-border-color": p.btn_border_color,
        "--button-hover-border-color": p.btn_border_hover_color,
        "--button-hover-bg-color": p.btn_bg_hover_color,
        "--button-hover-color": p.btn_hover_color,
      };
    },

    ...mapGetters("layout", ["isFeaturePaneLayout", "isMobile"]),

    splitScreenStyleObject() {
      return {
        ...this.styleObject,
        "--section-background-color": "transparent",
        "--section-background-image": "none",
      };
    },

    splitScreenSwiperBreakpoints() {
      return {
        "0": { slidesPerView: 2 },
        "480": { slidesPerView: 2 },
        "768": { slidesPerView: 2 },
        "1024": { slidesPerView: 2 },
      };
    },

    defaultSwiperBreakpoints() {
      return {
        "0": { slidesPerView: 2 },
        "480": { slidesPerView: 2 },
        "768": { slidesPerView: 3 },
        "1024": { slidesPerView: 6 },
      };
    },
  },
};
</script>

<style scoped>
.section_btn {
  color: var(--button-color, #fff);
  background-color: var(--button-background-color, var(--c1, #e62d04));
  border: var(--button-border, 0);
  border-color: var(--button-border-color, transparent);
  min-height: 32px;
  min-width: 80px;
}

.section_btn:hover {
  color: var(--button-hover-color, #fff);
  background-color: var(--button-hover-bg-color, var(--c1, #e62d04));
  border-color: var(--button-hover-border-color, transparent);
}

.custom-category-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.custom-category-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.custom-category-scale-grid {
  display: flex;
  flex-wrap: nowrap;
  justify-content: center;
  align-items: stretch;
  width: 100%;
  gap: 1px;
}

.custom-category-scale-grid__item {
  flex: 0 0 calc(100% / var(--category-slot-count));
  max-width: calc(100% / var(--category-slot-count));
  min-width: 0;
}

.custom-category-scale-grid--compact {
  --category-thumb-max-height: clamp(
    120px,
    calc(96px + (4 - var(--category-count)) * 28px),
    200px
  );
}

.custom-category-scale-grid--compact :deep(.position-relative) {
  max-height: var(--category-thumb-max-height);
  overflow: hidden;
}

.custom-category-scale-grid--compact :deep(.v-lazy-image),
.custom-category-scale-grid--compact :deep(img) {
  width: 100%;
  height: 100%;
  max-height: var(--category-thumb-max-height);
  object-fit: cover;
}

@media (max-width: 767px) {
  .custom-category-scale-grid--compact {
    --category-thumb-max-height: clamp(
      100px,
      calc(80px + (4 - var(--category-count)) * 20px),
      160px
    );
  }
}

.product-grid-slider :deep(.swiper-slide) {
  height: auto;
}
</style>
