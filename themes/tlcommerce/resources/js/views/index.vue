<template>


    <!-- <h1 style="color: red; position:fixed;top:0;left:0;z-index:9999;background:yellow;padding:10px;">Test</h1> -->

    <div class="light-bg" v-if="pageLoading">
        <Skeleton class="w-100 pt-20" style="height: 100vh;"></skeleton>
        <!-- <skeleton height="100vh" class="w-100 pt-20"></skeleton> -->
    </div>

    <!-- From Page Builder -->
    <builder-section :page="page" :sections="page_section" :widgets="page_builder_widgets" @section-loaded="loaded"
        v-if="active_pagebuilder && page.page_type == 'builder'" />

    <div class="pt-30 pt-lg-60 pb-60 light-bg" v-else-if="active_pagebuilder && page.page_type == 'default'">
        <div class="custom-container2">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Details -->
                    <article class="post-details">
                        <!-- Page Header -->
                        <header class="entry-header">
                            <div class="entry-thumbnail" v-if="page.page_image != null">
                                <img :src="cleanImage(page.page_image)" alt="" />
                                <!-- <img :src="page.page_image" alt="" /> -->
                            </div>

                            <h1 class="entry-title">
                                {{ page.title }}
                            </h1>
                        </header>
                        <!-- End Page Header -->

                        <!-- Page Content -->
                        <div class="entry-content mb-40" :class="{ 'page-custom-content': hasCustomContentStyles }"
                            v-html="page.content"></div>
                        <!-- End Page Content -->
                    </article>
                </div>
            </div>
        </div>
    </div>

    <div class="home__two" :class="{ 'home__two--rtl': isSplitScreen && isRtl }" v-else>
        <!-- Banner -->
        <!-- <section class="product-banner product-banner-overflow-auto mt-30 mb-30" v-if="dataAvailable"> -->
        <!-- <div class="mt-50"> -->
        <div :class="mtClass">
            <section class="product-banner product-banner-overflow-auto" v-if="dataAvailable && showHomeBanners">


                <div v-if="sliderLoading">
                    <div class="desktop">
                        <div class="d-flex">
                            <skeleton height="450px" class="w-25"></skeleton>
                            <skeleton height="450px" class="slider-container mx-4 skeleton"></skeleton>
                            <skeleton height="450px" class="w-25"></skeleton>
                        </div>
                    </div>
                    <div class="mobile">
                        <skeleton height="120px" class="w-100"></skeleton>
                    </div>
                </div>
                <div class="slider-container" v-else>
                    <swiper v-if="banners && banners.length > 0" :slidesPerView="'auto'" :loop="true" :spaceBetween="20"
                        :centeredSlides="true" :modules="modules" :autoplay="{
                            delay: 4000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        }" :pagination="{
                            clickable: true,
                        }" class="mySwiper theme-slider-dots dots-bottom-30">
                        <swiper-slide v-for="(slide, index) in banners" :key="`slide-${index}`">
                            <product-banner :content="slide" :priority="index === 0" />
                        </swiper-slide>
                    </swiper>
                </div>

            </section>
        </div>
        <!-- End Banner -->

        <!-- <div>

            <HomePageDeliveryShipping v-if="showContactInfo && !isSplitScreen" :enums="enums" :config="configuration"
                :customer-address="customerAddress" :is-customer-login="isCustomerLogin"
                :pickup-points="pickupPoints" />

        </div> -->

        <HomePageDeliveryShipping v-if="showContactInfo && isSplitScreen" :enums="enums" :config="configuration"
            :customer-address="customerAddress" :is-customer-login="isCustomerLogin" :pickup-points="pickupPoints" />

        <!--Dynamic Sections-->
        <template v-if="dataAvailable && homeContentReady">
            <div v-for="(section, index) in sections" :key="index">
                <deal-section v-if="section.layout === 'flashdeal'" :content="section.content"
                    :properties="section.properties"></deal-section>

                <collection-section v-if="section.layout === 'product_collection'" :content="section.content"
                    :properties="section.properties">
                </collection-section>

                <custom-product-section v-if="section.layout === 'custom_product_section'" :content="section.content"
                    :properties="section.properties">
                </custom-product-section>

                <category-section v-if="section.layout === 'category_slider'" :content="section.content"
                    :properties="section.properties"></category-section>

                <custom-category-slider-section v-if="section.layout === 'custom_category_slider'"
                    :content="section.content" :properties="section.properties">
                </custom-category-slider-section>

                <ads-section v-if="section.layout === 'ads'" :content="section.content"
                    :properties="section.properties"></ads-section>

                <cta-section v-if="section.layout === 'featured_product'" :content="section.content"
                    :properties="section.properties"></cta-section>

                <custom-link-section v-if="section.layout === 'custom_link'" :content="section.content"
                    :properties="section.properties"></custom-link-section>

                <blog-section v-if="section.layout === 'blogs'" :content="section.content"
                    :properties="section.properties"></blog-section>

                <top-sellers v-if="section.layout === 'seller_list'" :content="section.content"
                    :properties="section.properties"></top-sellers>
            </div>
        </template>
        <!--End Dynamic Sections-->

        <!-- <HomePageDeliveryShipping v-if="showContactInfo && isSplitScreen" :enums="enums" :config="configuration"
            :customer-address="customerAddress" :is-customer-login="isCustomerLogin" :pickup-points="pickupPoints" /> -->

        <SplitScreenProductList
            v-if="isSplitScreen && homeContentReady && productListViewEnabled"
            :disable-margin="true" />
        <ProductPage v-else-if="isSplitScreen && homeContentReady"
            :disable-margin="true" />

    </div>

</template>

<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper";
import {
    preparePageContentForRender,
    removePageContentStyles,
} from "@/utils/pageContentStyles";
import CategorySection from "@/components/home-page-sections/categorySection.vue";

const BuilderSection = defineAsyncComponent(() =>
    import("@/components/page-builder/BuilderSection.vue")
);

const ProductBanner = defineAsyncComponent(() =>
    import("@/components/ui/ProductBanner.vue")
);

const DealSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/dealSection.vue")
);

const collectionSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/collectionSection.vue")
);

const CustomCategorySliderSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/customCategorySliderSection.vue")
);

const adsSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/adsSection.vue")
);

const ctaSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/ctaSection.vue")
);

const CustomLinkSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/customLinkSection.vue")
);

const blogSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/blogSection.vue")
);

const CustomProductSection = defineAsyncComponent(() =>
    import("@/components/home-page-sections/CustomProductSection.vue")
);

const TopSellers = defineAsyncComponent(() =>
    import("@/components/home-page-sections/TopSellers.vue")
);

const ProductPage = defineAsyncComponent(() =>
    import(/* webpackChunkName: "ProductPage" */ "@/views/products/index.vue")
);

const SplitScreenProductList = defineAsyncComponent(() =>
    import(/* webpackChunkName: "SplitScreenProductList" */ "@/components/product/SplitScreenProductList.vue")
);

import HomePageDeliveryShipping from '@/components/order-steps/homePageDeliveryShipping.vue';
// import HomePageDeliveryShipping from "../components/order-steps/homePageDeliveryShipping.vue";
import enums from "../enums/enums";
import { mapState, mapGetters } from "vuex";
import { trackSocialPixels } from "@/utils/trackSocialPixels";
// import CompanyFooter from "../components/ui/CompanyFooter.vue";
const axios = require("axios").default;

function readBootstrapProductListViewEnabled() {
    try {
        const settings =
            typeof window !== "undefined"
                ? window.__TLC_BOOTSTRAP__?.productListViewSettings
                : null;

        return !!settings?.is_list_view_enabled;
    } catch (e) {
        return false;
    }
}

export default {
    components: {
        Swiper,
        SwiperSlide,
        ProductBanner,
        DealSection,
        collectionSection,
        CategorySection,
        CustomCategorySliderSection,
        adsSection,
        ctaSection,
        CustomLinkSection,
        blogSection,
        CustomProductSection,
        TopSellers,
        BuilderSection,
        ProductPage,
        SplitScreenProductList,
        HomePageDeliveryShipping
    },
    setup() {
        return {
            modules: [Autoplay, Pagination],
        };
    },
    name: "HomeView",
    data() {
        return {
            pageLoading: true,
            banners: [],
            sections: [],
            dataAvailable: false,
            homeContentReady: false,
            sliderLoading: true,
            page: {},
            page_section: {},
            active_pagebuilder: false,
            page_builder_widgets: {},
            contentStyleId: "",
            hasCustomContentStyles: false,

            // HomePageDeliveryShipping
            showContactInfo: true,
            enums: enums,
            customerAddress: [],
            pickupPoints: [],

            productListViewEnabled: readBootstrapProductListViewEnabled(),
        };
    },
    computed: {

        ...mapState({
            customerToken: (state) => state.customerToken,
            isCustomerLogin: (state) => state.isCustomerLogin,
            configuration: (state) => state.siteSettings,
        }),

        ...mapGetters('layout', ['isSplitScreen', 'isMobile', 'isRtl']),

        showHomeBanners() {
            return !this.isSplitScreen || this.isMobile;
        },

        forcedMobile() {
            if (this.isSplitScreen && !this.isMobile) {
                return true;
            }
            return this.isMobile;
        },

        mtClass() {
            if (this.isSplitScreen && !this.isMobile) {
                // return 'mt-50';
                return 'mt-0';
            }
            return 'mt-1';
        }

    },
    mounted() {
        this.homeContentReady = false;
        document.title = localStorage.getItem("site_title");
        this.getSections();

        // console.log("isCustomerLogin: ", this.isCustomerLogin);

        if (this.isCustomerLogin) {
            this.getCustomerAddress();
        }

        const siteName = this.$store.state.siteSettings?.site_name ?? 'Store';

        trackSocialPixels('ViewContent', {
            content_name: siteName,
            content_category: 'Store',
            currency: 'KWD',
        });

        // console.log('🟡 index.vue mounted!');

    },

    beforeUnmount() {
        removePageContentStyles(this.contentStyleId);
    },

    async created() {
        try {
            const response = await axios.get(
                "/api/theme/tlcommerce/v1/active-sliders"
            );
            if (response.status === 200) {
                this.banners = response.data?.data ?? [];
                this.sliderLoading = false;
            }
        } catch (error) {
            this.sliderLoading = false;
        }
    },

    methods: {

        cleanImage(img) {
            if (!img) return '';

            // Only remove '/public' prefix, keep the leading '/'
            return img.replace(/^\/public/, '');
        },
        setPageContent(rawContent, styleKey) {
            if (this.contentStyleId) {
                removePageContentStyles(this.contentStyleId);
            }
            this.contentStyleId = `page-content-styles-home-${styleKey || "default"}`;
            const prepared = preparePageContentForRender(rawContent, this.contentStyleId);
            this.page.content = prepared.htmlWithoutStyles;
            this.hasCustomContentStyles = prepared.hasCustomStyles;
        },
        /**
         * Get active sections
         */
        getSections() {
            axios
                .get("/api/theme/tlcommerce/v1/active-home-page-sections")
                .then((response) => {
                    if (response.data.success) {
                        this.dataAvailable = true;
                        this.sections = response.data.data ?? [];
                        this.page = response.data.page ?? {};
                        this.page_section = response.data.page_sections ?? {};
                        this.active_pagebuilder =
                            response.data.active_pagebuilder ?? false;
                        this.page_builder_widgets =
                            response.data.page_builder_widgets ?? {};
                        if (this.page?.content) {
                            const styleKey = this.page.permalink || this.page.id || "default";
                            this.setPageContent(this.page.content, styleKey);
                        }
                        if (!this.active_pagebuilder) {
                            this.loaded();
                        }
                    } else {
                        this.loaded();
                    }
                })
                .catch((error) => {
                    this.sections = [];
                    this.loaded();
                });
        },
        /**
         * Make Preloader False
         */
        loaded() {
            this.pageLoading = false;
            this.$nextTick(() => {
                this.homeContentReady = true;
            });
        },

        //-------- HomePageDeliveryShipping --------

        /**
        * Will get customer address
        */
        getCustomerAddress() {
            this.dataLoading = true;
            // console.log("cusomter token: ", this.customerToken);
            axios
                // .get("/api/v1/ecommerce-core/customer/get-customer-all-address", {
                .post("/api/v1/ecommerce-core/customer/get-customer-all-address", null, {
                    headers: {
                        Authorization: `Bearer ${this.customerToken}`,
                    },
                })
                .then((response) => {
                    if (response.data.success) {
                        this.customerAddress = response.data.data;
                    } else {
                        this.customerAddress = [];
                    }
                    this.dataLoading = false;
                })
                .catch((error) => {
                    this.dataLoading = false;
                    this.customerAddress = [];
                });
        },
    },
};
</script>
<style lang="scss">
.home__two .product-banner-overflow-auto {
    overflow: hidden;

    .swiper {
        overflow: initial;
    }
}

.home__two .product-banner-overflow-auto .swiper-slide {
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}
</style>
