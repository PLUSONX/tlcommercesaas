<template>
  <!-- Categories -->
  <!-- <h1>Test</h1> -->
  <section class="pt-15 pb-15 category-section home-page-section" :style="styleObject" :class="{
    'force-mobile-layout': forcedMobile,
    'mobile-content-wrapper': forcedMobile
  }">
    <div class="px-3 px-sm-0">
      <!-- <swiper v-if="properties.categories.data.length" :modules="modules" :loop="true" :centeredSlides="true" 
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
      }"> -->

      <swiper v-if="properties.categories.data.length" :modules="modules" :loop="true" :centeredSlides="true" :autoplay="{
        delay: 2500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      }" :pagination="{
        clickable: true,
      }" class="category-slider category-full-width theme-slider-dots" :breakpoints="swiperBreakpoints">
        <swiper-slide v-for="(cat, index) in properties.categories.data" :key="`category-${index}`">
          <category-card :cat="cat" />
        </swiper-slide>
      </swiper>
    </div>
  </section>
  <!-- End Categories -->
</template>
<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
const CategoryCard = defineAsyncComponent(() =>
  import("../ui/CategoryCard.vue")
);

import { Autoplay, Pagination } from "swiper";
const axios = require("axios").default;
import { mapState, mapGetters } from "vuex";
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
        "--section-padding": `${this.properties.padding_top +
          "px " +
          this.properties.padding_right +
          "px " +
          this.properties.padding_bottom +
          "px " +
          this.properties.padding_left +
          "px"
          }`,
        //Margin
        "--section-margin": `${this.properties.margin_top +
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

    ...mapGetters('layout', ['isSplitScreen', 'isMobile']),

    forcedMobile() {
      // identical intent to MainLayout.vue
      if (this.isSplitScreen) {
        return true;
      }
      return this.isMobile;
    },

    swiperBreakpoints() {
      if (this.forcedMobile) {
        // Force 2 slides regardless of actual screen width
        return {
          '0': { slidesPerView: 2, spaceBetween: 16 },
        };
      }
      return {
        '0': { slidesPerView: 2, spaceBetween: 16 },
        '768': { slidesPerView: 3, spaceBetween: 20 },
        '1024': { slidesPerView: 4, spaceBetween: 20 },
        '1440': { slidesPerView: 5, spaceBetween: 20 },
      };
    },

  },
};
</script>
<style scoped>
.category-section {
  background-image: var(--section-bg-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}


.force-mobile-layout .row>[class*="col-"] {
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