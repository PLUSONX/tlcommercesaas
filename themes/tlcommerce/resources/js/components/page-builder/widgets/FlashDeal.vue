<template>
    <section class="deals-section home-page-section">
        <div class="row align-items-center my-3">
            <div class="col-md-6 mb-2 mb-md-0">
                <section-title class="section-title" :title="title" />
            </div>
            <div class="col-md-6">
                <countdown
                    class="justify-content-md-end"
                    :deadline="dealDetails.deadline"
                />
            </div>
        </div>
        <!-- <swiper
            v-if="dealProducts.length && properties.style == 'slider'"
            direction="vertical"
            :style="{ 
                height: `${verticalSliderHeight}px`,
                maxHeight: '90vh' 
            }"
            :slidesPerView="slider_items.desktop || 3"
            :modules="modules"
            :spaceBetween="20"
            :autoplay="{
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            }"
            :loop="true"
            :pagination="{
                clickable: true,
            }"
            :navigation="true"
            class="product-grid-slider theme-slider-dots vertical-slider"
            :breakpoints="{
                '0': {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                '768': {
                    slidesPerView: slider_items.tab || 2,
                    spaceBetween: 15,
                },
                '1024': {
                    slidesPerView: slider_items.desktop || 3,
                    spaceBetween: 20,
                },
            }"
        > -->
        <swiper
            v-if="dealProducts.length && properties.style == 'slider'"
            :slidesPerView="slider_items.desktop"
            :modules="modules"
            :spaceBetween="1"
            :autoplay="{
                delay: 2500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            }"
            :loop="true"
            :pagination="pagination"
            class="product-grid-slider theme-slider-dots"
            :breakpoints="{
                '0': {
                    slidesPerView: slider_items.mobile,
                },
                '768': {
                    slidesPerView: slider_items.tab,
                },
                '1024': {
                    slidesPerView: slider_items.desktop,
                },
            }"
        > 
            <swiper-slide
                v-for="(item, index) in dealProducts"
                :key="`slide-${index}`"
            >
                <single-product :item="item" />
            </swiper-slide>
        </swiper>
        <div class="row mx-0" v-else>
            <div
                v-for="(item, index) in dealProducts"
                :key="`product-${index}`"
                :class="product_column + ' ' + 'px-1'"
            >
                <single-product :item="item" />
            </div>
        </div>
    </section>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination, Navigation } from "swiper";
const SingleProduct = defineAsyncComponent(() =>
    import("../../product/SingleProduct.vue")
);
const Countdown = defineAsyncComponent(() => import("../../ui/Countdown.vue"));

export default {
    name: "FlashDeal",
    components: {
        Swiper,
        SwiperSlide,
        SingleProduct,
        Countdown,
    },

    props: {
        properties: {
            type: Object,
            default: {},
        },
    },

    setup() {
        return {
            modules: [Autoplay, Pagination, Navigation],
        };
    },

    data() {
        return {
            dealDetails: {},
            dealProducts: [],
            title: "",
            slider_items: {
                mobile: 2,
                tab: 3,
                desktop: 3
            },
            product_column: "col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2",
            pagination: { clickable: true }
        };
    },

    computed: {

        verticalSliderHeight() {
        // Approximate product card height (adjust based on your design)
        const cardHeight = 200; // adjust this value
        const spaceBetween = 20;
        const visibleSlides = this.slider_items?.desktop || 3;
        
        return (cardHeight * visibleSlides) + (spaceBetween * (visibleSlides - 1));
    }

    },

    mounted() {
        this.dealDetails = this.properties["deal-details"] ?? {};
        this.title = this.properties["title_t_"] ?? "";
        this.dealProducts =
            this.properties["products"] && this.properties["products"]["data"]
                ? this.properties["products"]["data"]
                : [];
        
        this.product_column = this.properties["column"] ?? this.product_column;
        this.pagination = this.properties["pagination"] ? this.pagination : false;

        this.slider_items.mobile = this.properties['slide_item_sm'] ? parseInt(this.properties['slide_item_sm']) : this.slider_items.mobile;
        this.slider_items.tab = this.properties['slide_item_md'] ? parseInt(this.properties['slide_item_md']) : this.slider_items.tab;
        this.slider_items.desktop = this.properties['slide_item_lg'] ? parseInt(this.properties['slide_item_lg']) : this.slider_items.desktop;

        console.log("slider_items.desktop: ", this.slider_items.desktop);
        console.log("slide_item_lg: ", parseInt(this.properties['slide_item_lg']));
    },

    methods: {},
};
</script>


