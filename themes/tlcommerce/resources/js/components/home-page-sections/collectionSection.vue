<template>
  <template v-if="isSplitScreen && !isMobile">
    <section
      v-if="collectionReady"
      class="collection-section collection-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      :style="splitScreenStyleObject"
    >
      <div class="px-3">
        <div class="row align-items-center">
          <div class="col-12">
            <section-title
              class="mb-30 section-title"
              :title="collectionDetails.name"
              :titleColor="properties.title_color"
            />
          </div>
          <div class="col-12">
            <router-link
              class="btn btn-sm rounded-0 mb-30 section_btn"
              :style="splitScreenStyleObject"
              :to="`/collection/${collectionDetails.id}?collection=${collectionDetails.permalink}`"
            >
              {{
                properties.btn_title != null
                  ? properties.btn_title
                  : $t("View All")
              }}
            </router-link>
          </div>
        </div>

        <swiper
          v-if="collectionProducts.length"
          :slidesPerView="2"
          :modules="modules"
          :spaceBetween="8"
          :autoplay="false"
          :loop="splitScreenSwiperLoop"
          :pagination="splitScreenSwiperPagination"
          class="product-grid-slider product-grid-slider--split theme-slider-dots"
          :breakpoints="splitScreenSwiperBreakpoints"
        >
          <swiper-slide
            v-for="(item, index) in collectionProducts"
            :key="`slide-${index}`"
          >
            <single-product :item="item" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
  </template>

  <template v-else>
    <!-- Top Pick Up -->
    <section v-if="collectionReady" class="collection-section home-page-section" :style="styleObject">
      <div class="custom-container2">
        <div class="row align-items-center">
          <div class="col-md-6">
            <section-title
              class="mb-30 section-title"
              :title="collectionDetails.name"
              :titleColor="properties.title_color"
            />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link
              class="btn btn-sm rounded-0 mb-30 section_btn"
              :style="styleObject"
              :to="`/collection/${collectionDetails.id}?collection=${collectionDetails.permalink}`"
            >
              {{
                properties.btn_title != null
                  ? properties.btn_title
                  : $t("View All")
              }}
            </router-link>
          </div>
        </div>

        <swiper
          v-if="collectionProducts.length"
          :slidesPerView="6"
          :modules="modules"
          :spaceBetween="1"
          :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :loop="true"
          :pagination="{
            clickable: true,
          }"
          class="product-grid-slider theme-slider-dots"
          :breakpoints="{
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
          }"
        >
          <swiper-slide
            v-for="(item, index) in collectionProducts"
            :key="`slide-${index}`"
          >
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
import { normalizeSectionProps } from "@/utils/sectionProps";
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

    styleObject() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": `url(${p.bg_image})`,
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

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    splitScreenStyleObject() {
      return {
        ...this.styleObject,
        "--section-background-color": "transparent",
        "--section-background-image": "none",
      };
    },

    splitScreenSwiperLoop() {
      return this.collectionProducts.length > 4;
    },

    splitScreenSwiperPagination() {
      if (this.collectionProducts.length <= 2) {
        return false;
      }
      return { clickable: true };
    },

    splitScreenSwiperBreakpoints() {
      return {
        "0": { slidesPerView: 2, spaceBetween: 8 },
      };
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
        .catch((error) => {});
    },
  },
};
</script>
<style scoped>
.section_btn {
  color: var(--button-color);
  background-color: var(--button-background-color);
  border: var(--button-border);
  border-color: var(--button-border-color);
}
.section_btn:hover {
  color: var(--button-hover-color);
  background-color: var(--button-hover-bg-color);
  border-color: var(--button-hover-border-color);
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
</style>
