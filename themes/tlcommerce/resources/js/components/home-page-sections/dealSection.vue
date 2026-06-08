<template>
  <template v-if="isSplitScreen && !isMobile">
    <section
      class="pt-15 pb-15 deals-section deals-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      :style="splitScreenStyleObject"
    >
      <div class="custom-container2" v-if="success && !dataLoading">
        <div class="row align-items-center my-3">
          <div class="col-md-6 mb-2 mb-md-0">
            <section-title
              class="section-title"
              :title="dealDetails.title"
              :titleColor="properties.title_color"
            />
          </div>
          <div class="col-md-6">
            <countdown
              class="justify-content-md-end"
              :deadline="dealDetails.deadline"
              :titleColor="properties.title_color"
            />
          </div>
        </div>
        <swiper
          v-if="dealProducts.length"
          :slidesPerView="6"
          :modules="modules"
          :spaceBetween="1"
          :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }"
          :loop="true"
          :pagination="{
            clickable: true,
          }"
          class="product-grid-slider theme-slider-dots"
          :breakpoints="splitScreenSwiperBreakpoints"
        >
          <swiper-slide v-for="(item, index) in dealProducts" :key="`slide-${index}`">
            <single-product :item="item" />
          </swiper-slide>
        </swiper>
        <div class="col-md-12 text-center mt-20">
          <router-link
            class="btn btn-sm rounded-0 mb-30 section_btn"
            :style="splitScreenStyleObject"
            :to="`/deals/${dealDetails.permalink}`"
          >
            {{
              properties.btn_title != null ? properties.btn_title : $t("View All")
            }}
          </router-link>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <section class="pt-15 pb-15 deals-section home-page-section" :style="styleObject">
      <div class="custom-container2" v-if="success && !dataLoading">
        <div class="row align-items-center my-3">
          <div class="col-md-6 mb-2 mb-md-0">
            <section-title
              class="section-title"
              :title="dealDetails.title"
              :titleColor="properties.title_color"
            />
          </div>
          <div class="col-md-6">
            <countdown
              class="justify-content-md-end"
              :deadline="dealDetails.deadline"
              :titleColor="properties.title_color"
            />
          </div>
        </div>
        <swiper
          v-if="dealProducts.length"
          :slidesPerView="6"
          :modules="modules"
          :spaceBetween="1"
          :autoplay="{
            delay: 2500,
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
            '768': {
              slidesPerView: 3,
            },
            '1024': {
              slidesPerView: 6,
            },
          }"
        >
          <swiper-slide v-for="(item, index) in dealProducts" :key="`slide-${index}`">
            <single-product :item="item" />
          </swiper-slide>
        </swiper>
        <div class="col-md-12 text-center mt-20">
          <router-link
            class="btn btn-sm rounded-0 mb-30 section_btn"
            :style="styleObject"
            :to="`/deals/${dealDetails.permalink}`"
          >
            {{
              properties.btn_title != null ? properties.btn_title : $t("View All")
            }}
          </router-link>
        </div>
      </div>
    </section>
  </template>
</template>
<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper";
const SingleProduct = defineAsyncComponent(() =>
  import("../product/SingleProduct.vue")
);
const Countdown = defineAsyncComponent(() => import("../ui/Countdown.vue"));
import sectionPreloader from "./sectionPreloader.vue";
const axios = require("axios").default;
import { mapGetters } from "vuex";
import { normalizeSectionProps } from "@/utils/sectionProps";
export default {
  name: "DealSection",
  components: {
    Swiper,
    SwiperSlide,
    SingleProduct,
    Countdown,
    sectionPreloader,
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
  setup() {
    return {
      modules: [Autoplay, Pagination],
    };
  },
  data() {
    return {
      dealDetails: {},
      dealProducts: [],
      success: false,
      dataLoading: true,
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

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

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
  },
  mounted() {
    this.getDealDetails();
  },
  methods: {
    getDealDetails() {
      axios
        .post("/api/theme/tlcommerce/v1/deal-details", {
          id: this.content,
        })
        .then((response) => {
          if (response.data.success) {
            this.dealDetails = response.data.dealsDetails;
            this.dealProducts = response.data?.products?.data ?? [];
            this.success = true;
            this.dataLoading = false;
          }
        })
        .catch((error) => {
          this.success = false;
          this.dataLoading = false;
        });
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

.deals-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.deals-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.force-mobile-layout .row > [class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}
</style>
