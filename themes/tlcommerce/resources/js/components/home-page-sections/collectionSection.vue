<template>
  <template v-if="isModernLayout || (isFeaturePaneLayout && !isMobile)">
    <section v-if="collectionReady"
      class="pt-15 pb-15 collection-section collection-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      :class="{ 'collection-section--modern': isModernLayout, 'collection-section--rtl': isRtl }"
      :style="splitScreenStyleObject">
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title class="mb-30 section-title" :title="collectionDetails.name"
              :titleColor="sectionStyleProps.title_color" />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link class="btn btn-sm mb-30 section_btn" :style="splitScreenStyleObject"
              :to="`/collection/${collectionDetails.id}?collection=${collectionDetails.permalink}`">
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <swiper
          v-if="collectionProducts.length"
          :key="`collection-swiper-split-${content}-${collectionProducts.length}-${isModernLayout ? 'modern' : 'split'}`"
          :slidesPerView="featurePaneSlidesPerView"
          :modules="modules"
          :spaceBetween="featurePaneSpaceBetween"
          :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :loop="true"
          :pagination="{
            clickable: true,
          }"
          :dir="isRtl ? 'rtl' : 'ltr'"
          class="product-grid-slider theme-slider-dots"
          :breakpoints="featurePaneSwiperBreakpoints"
        >
          <swiper-slide v-for="(item, index) in collectionProducts" :key="`slide-${index}`">
            <single-product :item="item" :modern-horizontal="isModernLayout" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
  </template>

  <template v-else>
    <!-- Top Pick Up -->
    <section v-if="collectionReady" class="collection-section home-page-section" :style="styleObject">
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title class="mb-30 section-title" :title="collectionDetails.name"
              :titleColor="sectionStyleProps.title_color" />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link class="btn btn-sm mb-30 section_btn" :style="styleObject"
              :to="`/collection/${collectionDetails.id}?collection=${collectionDetails.permalink}`">
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <swiper v-if="collectionProducts.length" :slidesPerView="6" :modules="modules" :spaceBetween="1" :autoplay="{
          delay: 4000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }" :loop="true" :pagination="{
            clickable: true,
          }" class="product-grid-slider theme-slider-dots" :breakpoints="{
            '0': {
              slidesPerView: 2,
            },
            '480': {
              slidesPerView: 2,
            },
            '768': {
              slidesPerView: 3,
            },
            '1024': {
              slidesPerView: 6,
            },
          }">
          <swiper-slide v-for="(item, index) in collectionProducts" :key="`slide-${index}`">
            <single-product :item="item" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
    <!-- End Top Pick Up -->
  </template>
</template>
<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { normalizeSectionProps, sectionBgImageUrl, sectionViewAllLabel } from "@/utils/sectionProps";
import { Autoplay, Pagination } from "swiper";
import { mapGetters } from "vuex";
const SingleProduct = defineAsyncComponent(() =>
  import("../product/SingleProduct.vue")
);
const axios = require("axios").default;

export default {
  name: "CollectionSection",
  components: {
    Swiper,
    SwiperSlide,
    SingleProduct,
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
  data() {
    return {
      collectionDetails: {},
      collectionProducts: [],
      collectionReady: false,
      bgImage: null,
    };
  },
  computed: {
    sectionStyleProps() {
      return normalizeSectionProps(this.properties);
    },

    viewAllLabel() {
      return sectionViewAllLabel(this.properties, this.$t);
    },

    styleObject() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": sectionBgImageUrl(p.bg_image),
        "--section-background-image-position": p.background_position,
        "--section-background-image-size": p.background_size,
        "--section-background-image-repeat": p.background_repeat,
        "--section-padding": `${p.padding_top +
          "px " +
          p.padding_right +
          "px " +
          p.padding_bottom +
          "px " +
          p.padding_left +
          "px"
          }`,
        "--section-margin": `${p.margin_top +
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

    ...mapGetters("layout", ["isFeaturePaneLayout", "isMobile", "layoutType", "isRtl"]),

    isModernLayout() {
      return this.layoutType === "modern";
    },

    featurePaneSlidesPerView() {
      return 2;
    },

    featurePaneSpaceBetween() {
      return 1;
    },

    splitScreenStyleObject() {
      return {
        ...this.styleObject,
        "--section-background-color": "transparent",
        "--section-background-image": "none",
      };
    },

    featurePaneSwiperBreakpoints() {
      return {
        "0": { slidesPerView: 2 },
        "480": { slidesPerView: 2 },
        "768": { slidesPerView: 2 },
        "1024": { slidesPerView: 2 },
      };
    },

    splitScreenSwiperBreakpoints() {
      return this.featurePaneSwiperBreakpoints;
    },
  },
  mounted() {
    this.getCollectionSectionContent();
  },
  methods: {
    getCollectionSectionContent() {
      axios
        .post("/api/theme/tlcommerce/v1/collection-details", {
          id: this.content,
        })
        .then((response) => {
          if (response.data.success) {
            this.collectionDetails = response.data.details ?? {};
            this.collectionProducts =
              response.data?.collection_products?.data ?? [];
            this.collectionReady = true;
          }
        })
        .catch((error) => { });
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

.collection-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.collection-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.collection-section--modern :deep(.swiper),
.collection-section--modern :deep(.swiper-slide) {
  overflow: visible;
}

.collection-section--modern :deep(.swiper-slide) {
  padding-top: 8px;
  padding-bottom: 8px;
}

.collection-section--rtl.force-mobile-layout .section-header-row {
  flex-direction: row-reverse;
  direction: ltr;
}
.collection-section--rtl.force-mobile-layout .section-header-row > .text-md-end {
  text-align: left !important;
}
.collection-section--rtl :deep(.section-title),
.collection-section--rtl :deep(.section-title h2) {
  direction: rtl;
  text-align: right;
}
</style>
