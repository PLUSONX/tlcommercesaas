<template>
  <!-- Widget -->
  <div
    class="widget widget-style-1 widget_top_selling_product"
    :class="{ 'widget_top_selling_product--rtl': isRtl }"
  >
    <h5>
      <span>{{ $t("Top Selling Products") }}</span>
      <span @click="showTopSellingProductWidget = !showTopSellingProductWidget" class="widget-collapse-toggle"><span
          class="material-icons"> expand_more </span>
      </span>
    </h5>
    <div class="top-selling-product light-bg" v-if="showTopSellingProductWidget">
      <swiper
        v-if="products.length"
        :key="`top-selling-${isRtl ? 'rtl' : 'ltr'}-${products.length}`"
        :dir="isRtl ? 'rtl' : 'ltr'"
        :modules="modules"
        :loop="true"
        class="product-grid-slider theme-slider-dots"
        :breakpoints="{
          '0': {
            slidesPerView: 2,
            spaceBetween: 8,
          },
          '768': {
            slidesPerView: 2,
            spaceBetween: 8,
          },
          '1024': {
            slidesPerView: 2,
            spaceBetween: 8,
          },
        }"
      >
        <swiper-slide v-for="(item, index) in products" :key="`slide-${index}`">
          <single-product :item="item" styleEight widgetSlider />
        </swiper-slide>
      </swiper>
    </div>
  </div>
  <!-- Widget -->
</template>

<script>
import SingleProduct from "@/components/product/SingleProduct.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination } from "swiper";
import { mapGetters } from "vuex";

export default {
  components: {
    SingleProduct,
    Swiper,
    SwiperSlide,
    Pagination,
  },
  props: {
    products: {
      type: Array,
      required: false,
    },
  },
  data() {
    return {
      showTopSellingProductWidget: true,
    };
  },
  computed: {
    ...mapGetters("layout", ["isRtl"]),
  },
  setup() {
    return {
      modules: [Pagination],
    };
  },
};
</script>

<style scoped lang="scss">
.widget_top_selling_product :deep(.swiper-slide) {
  height: auto;
}

.widget_top_selling_product :deep(.single-product-item) {
  width: 100%;
  margin-bottom: 0;
}

.widget_top_selling_product :deep(.single-product-item .position-relative) {
  height: auto;
}

.widget_top_selling_product :deep(.single-product-item .position-relative img) {
  width: 100%;
  height: auto;
  aspect-ratio: 1;
  object-fit: cover;
  display: block;
}

.widget_top_selling_product :deep(.button-group) {
  display: none !important;
}

.widget_top_selling_product :deep(.product-title-price-row) {
  flex-direction: column !important;
  align-items: stretch !important;
  margin-inline-start: 5px !important;
}

.widget_top_selling_product :deep(.product-title-col),
.widget_top_selling_product :deep(.product-price-col) {
  width: 100%;
  max-width: 100%;
  padding: 0;
}

.widget_top_selling_product :deep(.product-title) {
  height: auto !important;
  -webkit-line-clamp: 3;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-size: 12px;
  line-height: 1.35;
  margin: 4px 0;
  word-break: break-word;
}

.widget_top_selling_product :deep(.product-summary) {
  min-height: auto !important;
  padding: 6px 8px 8px;
}

.widget_top_selling_product :deep(.product-price) {
  font-size: 13px;
  margin-top: 2px;
}

.widget_top_selling_product :deep(.star-rating) {
  font-size: 14px !important;
  margin-bottom: 2px;
}

.widget_top_selling_product--rtl {
  h5 {
    direction: rtl;
  }
}

.widget_top_selling_product--rtl :deep(.product-title) {
  direction: rtl;
  text-align: right;
}

.widget_top_selling_product--rtl :deep(.product-summary) {
  direction: rtl;
  text-align: right;
}

.widget_top_selling_product--rtl :deep(.product-price) {
  direction: ltr;
  text-align: right;
  justify-content: flex-end;
}

.widget_top_selling_product--rtl :deep(.star-rating) {
  align-self: flex-end;
}
</style>
