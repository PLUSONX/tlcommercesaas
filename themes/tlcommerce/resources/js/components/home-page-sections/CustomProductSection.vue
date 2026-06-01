<template>
  <section
    v-if="productList.length"
    class="collection-section home-page-section"
    :style="styleObject"
  >
    <div class="custom-container2">
      <div class="row align-items-center">
        <div class="col-md-6">
          <section-title
            class="mb-30 section-title"
            :title="
              sectionBlock?.category_info != null
                ? sectionBlock.category_info.name
                : sectionProps?.title
                  ? $t(sectionProps.title)
                  : ''
            "
            :titleColor="sectionProps?.title_color"
          />
        </div>
        <div class="col-md-6 text-md-end" v-if="sectionProps?.content == 'category'">
          <router-link
            v-if="sectionBlock?.category_info != null"
            class="btn btn-sm rounded-0 mb-30 section_btn"
            :style="styleObject"
            :to="`/products/category/${sectionBlock.category_info.slug}`"
          >
            {{
              sectionProps.btn_title != null
                ? sectionProps.btn_title
                : $t("View All")
            }}
          </router-link>
        </div>
      </div>
      <swiper
        v-if="productList.length"
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
        <swiper-slide v-for="(item, index) in productList" :key="`slide-${index}`">
          <single-product :item="item" />
        </swiper-slide>
      </swiper>
    </div>
  </section>
</template>
<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper";

const SingleProduct = defineAsyncComponent(() =>
  import("../product/SingleProduct.vue")
);

export default {
  name: "CustomProductSection",
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
      type: [Object, Array],
      default: () => ({}),
    },
  },
  computed: {
    sectionBlock() {
      if (Array.isArray(this.properties)) {
        return this.properties[0] || {};
      }
      return this.properties || {};
    },

    productList() {
      return this.sectionBlock?.products?.data ?? [];
    },

    sectionProps() {
      if (Array.isArray(this.properties)) {
        return this.properties[0] || {};
      }
      return this.properties || {};
    },

    styleObject() {
      const props = this.sectionProps;
      const bgImage = props.bg_image;
      return {
        "--section-background-color": props.bg_color,
        "--section-background-image": `url(${
          bgImage?.startsWith("/public") ? bgImage.slice(7) : bgImage
        })`,
        "--section-background-image-position": props.background_position,
        "--section-background-image-size": props.background_size,
        "--section-background-image-repeat": props.background_repeat,
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
        "--button-color": props.btn_color,
        "--button-background-color": props.btn_bg_color,
        "--button-border":
          props.btn_border != null ? props.btn_border + "px solid" : "0px",
        "--button-border-color": props.btn_border_color,
        "--button-hover-border-color": props.btn_border_hover_color,
        "--button-hover-bg-color": props.btn_bg_hover_color,
        "--button-hover-color": props.btn_hover_color,
      };
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
</style>
