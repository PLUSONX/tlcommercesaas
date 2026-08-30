<template>


    <!-- <h1 style="color: red; position:fixed;top:0;left:0;z-index:9999;background:yellow;padding:10px;">Test</h1> -->

    <template v-if="pageLoading">
        <div class="light-bg">
            <Skeleton class="w-100 pt-20" style="height: 100vh;"></skeleton>
            <!-- <skeleton height="100vh" class="w-100 pt-20"></skeleton> -->
        </div>
    </template>

    <template v-else-if="active_pagebuilder && page.page_type == 'builder'">
        <!-- From Page Builder -->
        <builder-section :page="page" :sections="page_section" :widgets="page_builder_widgets"
            @section-loaded="loaded" />
    </template>

    <div v-else-if="active_pagebuilder && page.page_type == 'default'" class="pt-30 pt-lg-60 pb-60 light-bg">
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

    <div v-else class="home__two" :class="{
        'home__two--rtl': isFeaturePaneLayout && isRtl,
        'home__two--modern': isModernLayout,
    }">
        <!-- Banner -->
        <!-- <section class="product-banner product-banner-overflow-auto mt-30 mb-30" v-if="dataAvailable"> -->
        <!-- <div class="mt-50"> -->
        <div :class="mtClass">
            <!-- Check the optional Scroll Hero before showing the normal slider. -->
            <div v-if="scrollHeroLoading" class="scroll-hero-checking">
                <skeleton :height="isMobile ? '300px' : '450px'" class="w-100"></skeleton>
            </div>

            <!-- Scroll Hero and the normal slider are mutually exclusive. -->
            <ScrollHero v-else-if="showHomeBanners && hasScrollHero" :config="scrollHero"
                @ready="onScrollHeroReady" @failed="disableScrollHero" />

            <section v-else-if="showHomeBanners && hasHeroContent" class="product-banner product-banner-overflow-auto"
                :class="{ 'product-banner--modern-hero': isModernLayout }"
                @timeupdate.capture="limitHeroVideoToThreeSeconds">


                <div v-if="sliderLoading">
                    <div class="desktop" v-if="!isModernLayout">
                        <div class="d-flex">
                            <skeleton height="450px" class="w-25"></skeleton>
                            <skeleton height="450px" class="slider-container mx-4 skeleton"></skeleton>
                            <skeleton height="450px" class="w-25"></skeleton>
                        </div>
                    </div>
                    <div class="mobile" v-if="!isModernLayout">
                        <skeleton height="120px" class="w-100"></skeleton>
                    </div>
                    <div v-if="isModernLayout" class="modern-hero-skeleton">
                        <skeleton height="300px" class="w-100"></skeleton>
                    </div>
                </div>
                <div class="slider-container" v-else>
                    <swiper v-if="hasHeroContent" :key="`home-banner-${isRtl ? 'rtl' : 'ltr'}-${heroSlideCount}`"
                        :dir="isRtl ? 'rtl' : 'ltr'" :slidesPerView="isModernLayout ? 1 : 'auto'"
                        :loop="heroSlideCount > 1" :spaceBetween="isModernLayout ? 0 : 20"
                        :centeredSlides="!isModernLayout" :modules="modules" :autoplay="heroSlideCount > 1 ? {
                            delay: 3000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        } : false" :pagination="heroSlideCount > 1 ? { clickable: true } : false"
                        class="mySwiper theme-slider-dots dots-bottom-30"
                        :class="{ 'mySwiper--modern-hero': isModernLayout }">
                        <!-- Static fallback: shown only when the backend has no active banners. -->
                        <swiper-slide v-if="hasStaticVideoFallback" key="static-video-banner"
                            :class="{ 'hero-video-slide--only': heroSlideCount === 1 }">
                            <a class="hero-video-banner" :href="staticVideoBanner.link || undefined"
                                @click="handleVideoBannerClick">
                                <video ref="heroVideo" class="hero-video-banner__media"
                                    :src="staticVideoBanner.video_url" :poster="staticVideoBanner.poster || undefined"
                                    autoplay muted loop playsinline preload="metadata" @mouseenter="pauseHeroVideo"
                                    @mouseleave="playHeroVideo"></video>
                            </a>
                        </swiper-slide>

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

        <HomePageDeliveryShipping v-if="showContactInfo && isFeaturePaneLayout" :enums="enums" :config="configuration"
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

        <SplitScreenProductList v-if="isFeaturePaneLayout && homeContentReady && productListViewEnabled"
            :disable-margin="true" @ready="onFeaturePaneProductsReady" />
        <ProductPage v-else-if="isFeaturePaneLayout && homeContentReady" :disable-margin="true"
            @ready="onFeaturePaneProductsReady" />

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

const ScrollHero = defineAsyncComponent(() =>
    import("@/components/ui/ScrollHero.vue")
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
import { hideBootSplash } from "@/utils/bootSplash";
import { scheduleBackgroundRefresh } from "@/utils/scheduleBackgroundRefresh";
// import CompanyFooter from "../components/ui/CompanyFooter.vue";
const axios = require("axios").default;

function readStorefrontBootstrap() {
    try {
        return typeof window !== "undefined" ? window.__TLC_BOOTSTRAP__ ?? null : null;
    } catch (e) {
        return null;
    }
}

function readBootstrapProductListViewEnabled() {
    try {
        return !!readStorefrontBootstrap()?.productListViewSettings?.is_list_view_enabled;
    } catch (e) {
        return false;
    }
}

export default {
    components: {
        Swiper,
        SwiperSlide,
        ProductBanner,
        ScrollHero,
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
    bootSplashManual: true,
    data() {
        return {
            pageLoading: true,
            banners: [],
            sections: [],
            dataAvailable: false,
            homeContentReady: false,
            sliderLoading: true,
            scrollHeroLoading: true,
            scrollHeroReady: false,
            scrollHero: {
                enabled: false,
                desktop: [],
                mobile: [],
                scroll_height: 300,
            },
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
            featurePaneProductsReady: false,
            bootSplashFallbackTimer: null,

            // Temporary frontend-only video banner.
            // Use a direct MP4/WebM file URL here (not a YouTube page URL).
            staticVideoBanner: {
                video_url: "",
                poster: "",
                link: "",
            },
        };
    },
    computed: {

        ...mapState({
            customerToken: (state) => state.customerToken,
            isCustomerLogin: (state) => state.isCustomerLogin,
            configuration: (state) => state.siteSettings,
        }),

        ...mapGetters('layout', ['isSplitScreen', 'isFeaturePaneLayout', 'isMobile', 'isRtl', 'layoutType']),

        isModernLayout() {
            return this.layoutType === 'modern';
        },

        showHomeBanners() {
            // Modern content column always shows the hero swiper (header overlays it).
            if (this.isModernLayout) {
                return true;
            }
            return !this.isSplitScreen || this.isMobile;
        },

        forcedMobile() {
            if (this.isSplitScreen && !this.isMobile) {
                return true;
            }
            return this.isMobile;
        },

        mtClass() {
            if (this.isModernLayout) {
                return 'mt-0';
            }
            if (this.isSplitScreen && !this.isMobile) {
                // return 'mt-50';
                return 'mt-0';
            }
            return 'mt-1';
        },

        needsFeaturePaneProducts() {
            return this.isFeaturePaneLayout && !this.active_pagebuilder;
        },

        hasScrollHero() {
            return Boolean(
                this.scrollHero?.enabled === true &&
                Array.isArray(this.scrollHero?.desktop) &&
                this.scrollHero.desktop.length > 0
            );
        },

        hasHeroContent() {
            return this.banners.length > 0 || this.hasStaticVideoFallback;
        },

        hasStaticVideoFallback() {
            return this.banners.length === 0 && Boolean(this.staticVideoBanner.video_url);
        },

        heroSlideCount() {
            return this.banners.length + (this.hasStaticVideoFallback ? 1 : 0);
        },

    },
    watch: {
        pageLoading() {
            this.tryHideBootSplash();
        },
        needsFeaturePaneProducts() {
            this.tryHideBootSplash();
        },
    },
    mounted() {
        this.homeContentReady = false;
        document.title = localStorage.getItem("site_title");
        this.initHomeSections();

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

        // Never keep the global loader over an already-rendered page.
        this.bootSplashFallbackTimer = window.setTimeout(() => {
            hideBootSplash();
        }, 3000);

        // console.log('🟡 index.vue mounted!');

    },

    beforeUnmount() {
        removePageContentStyles(this.contentStyleId);

        if (this.bootSplashFallbackTimer) {
            window.clearTimeout(this.bootSplashFallbackTimer);
            this.bootSplashFallbackTimer = null;
        }
    },

    created() {
        this.fetchScrollHero();
        this.initSliders();
    },

    methods: {

        async fetchScrollHero() {
            this.scrollHeroLoading = true;
            this.scrollHeroReady = false;

            try {
                const response = await axios.get(
                    "/api/theme/tlcommerce/v1/scroll-hero"
                );

                const heroData = response?.data?.data;

                if (
                    response?.data?.success &&
                    heroData?.enabled &&
                    Array.isArray(heroData.desktop) &&
                    heroData.desktop.length > 0
                ) {
                    this.scrollHero = {
                        enabled: true,
                        desktop: heroData.desktop,
                        mobile: Array.isArray(heroData.mobile)
                            ? heroData.mobile
                            : [],
                        scroll_height: Number(heroData.scroll_height || 300),
                    };
                } else {
                    this.scrollHero = null;
                    this.scrollHeroReady = true;
                }
            } catch (error) {
                console.error("Unable to load Scroll Hero:", error);
                this.scrollHero = null;
                this.scrollHeroReady = true;
            } finally {
                this.scrollHeroLoading = false;
                this.tryHideBootSplash();
            }
        },

        disableScrollHero() {
            this.scrollHero = {
                enabled: false,
                desktop: [],
                mobile: [],
                scroll_height: 300,
            };
            this.scrollHeroReady = true;
            this.tryHideBootSplash();
        },

        onScrollHeroReady() {
            this.scrollHeroReady = true;
            hideBootSplash();

            if (this.bootSplashFallbackTimer) {
                window.clearTimeout(this.bootSplashFallbackTimer);
                this.bootSplashFallbackTimer = null;
            }
        },

        /**
         * Restrict every direct HTML5 hero video to its first 3 seconds.
         * This also catches videos rendered inside ProductBanner.vue.
         */
        limitHeroVideoToThreeSeconds(event) {
            const video = event.target;

            if (!(video instanceof HTMLVideoElement)) {
                return;
            }

            if (video.currentTime >= 3) {
                video.currentTime = 0;

                const playPromise = video.play();
                if (playPromise && typeof playPromise.catch === "function") {
                    playPromise.catch(() => { });
                }
            }
        },

        pauseHeroVideo(event) {
            event.currentTarget.pause();
        },

        playHeroVideo(event) {
            const playPromise = event.currentTarget.play();
            if (playPromise && typeof playPromise.catch === "function") {
                playPromise.catch(() => { });
            }
        },

        handleVideoBannerClick(event) {
            if (!this.staticVideoBanner.link) {
                event.preventDefault();
            }
        },

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
        initSliders() {
            const sliders = readStorefrontBootstrap()?.sliders;
            if (sliders && (Array.isArray(sliders.data) || sliders.success)) {
                this.applySliders(sliders);
                scheduleBackgroundRefresh(() => this.fetchSliders());
                return;
            }
            this.fetchSliders();
        },
        applySliders(payload) {
            this.banners = payload?.data ?? [];
            this.sliderLoading = false;
        },
        fetchSliders() {
            axios
                .get("/api/theme/tlcommerce/v1/active-sliders")
                .then((response) => {
                    if (response.status === 200) {
                        this.applySliders(response.data);
                    } else {
                        this.sliderLoading = false;
                    }
                })
                .catch(() => {
                    this.sliderLoading = false;
                });
        },
        initHomeSections() {
            const sections = readStorefrontBootstrap()?.homePageSections;
            if (
                sections &&
                typeof sections === "object" &&
                (sections.success || sections.active_pagebuilder || Array.isArray(sections.data))
            ) {
                this.applyHomeSections(sections);
                scheduleBackgroundRefresh(() => this.getSections());
                return;
            }
            this.getSections();
        },
        applyHomeSections(payload) {
            if (payload?.success) {
                this.dataAvailable = true;
                this.sections = payload.data ?? [];
                this.page = payload.page ?? {};
                this.page_section = payload.page_sections ?? {};
                this.active_pagebuilder = payload.active_pagebuilder ?? false;
                this.page_builder_widgets = payload.page_builder_widgets ?? {};
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
        },
        /**
         * Get active sections
         */
        getSections() {
            axios
                .get("/api/theme/tlcommerce/v1/active-home-page-sections")
                .then((response) => {
                    this.applyHomeSections(response.data);
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
                this.tryHideBootSplash();
            });
        },

        onFeaturePaneProductsReady() {
            this.featurePaneProductsReady = true;
            this.tryHideBootSplash();
        },

        tryHideBootSplash() {
            if (this.pageLoading) {
                return;
            }

            if (this.scrollHeroLoading) {
                return;
            }

            if (this.hasScrollHero && !this.scrollHeroReady) {
                return;
            }

            // Product lists and remaining hero frames continue in background.
            hideBootSplash();

            if (this.bootSplashFallbackTimer) {
                window.clearTimeout(this.bootSplashFallbackTimer);
                this.bootSplashFallbackTimer = null;
            }
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

.home__two .product-banner-overflow-auto .hero-video-slide--only {
    width: 100% !important;
    margin: 0 !important;
}

.hero-video-banner {
    display: block;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 12px;
    background: #000;
}

.hero-video-banner__media {
    display: block;
    width: 100%;
    height: 450px;
    object-fit: cover;
}

@media (max-width: 767px) {
    .hero-video-banner__media {
        height: 120px;
    }
}

/* Modern layout: edge-flush compact hero under overlay header */
.home__two--modern .product-banner--modern-hero {
    margin: 0 0 16px;
    width: 100%;
    border-radius: 0 0 24px 24px;
    overflow: hidden;
    box-shadow: none;
}

/* Beat global .slider-container { padding: 0 15px; max-width: … } */
.home__two--modern .product-banner--modern-hero .slider-container {
    width: 100%;
    max-width: none;
    padding: 0;
    margin: 0;
    overflow: hidden;
    border-radius: 0 0 24px 24px;
}

.home__two--modern .product-banner--modern-hero .mySwiper--modern-hero {
    overflow: hidden !important;
    width: 100%;
    border-radius: 0 0 24px 24px;
}

.home__two--modern .product-banner--modern-hero .swiper {
    overflow: hidden !important;
    /* beat .home__two .product-banner-overflow-auto .swiper { overflow: initial } */
    width: 100%;
}

.home__two--modern .product-banner--modern-hero .swiper-wrapper {
    width: 100%;
}

.home__two--modern .product-banner--modern-hero .swiper-slide {
    width: 100% !important;
    margin: 0 !important;
    border-radius: 0 !important;
    box-shadow: none !important;
}

.home__two--modern .product-banner--modern-hero .product-banner-item,
.home__two--modern .product-banner--modern-hero .slide-img,
.home__two--modern .product-banner--modern-hero .hero-video-banner,
.home__two--modern .product-banner--modern-hero .hero-video-banner__media,
.home__two--modern .product-banner--modern-hero img {
    width: 100%;
    border-radius: 0;
}

.home__two--modern .product-banner--modern-hero .hero-video-banner__media {
    height: 300px;
    max-height: 320px;
    object-fit: cover;
}

.home__two--modern .product-banner--modern-hero .product-banner-item .slide-img img {
    height: 300px;
    max-height: 320px;
    object-fit: cover;
    display: block;
}

.home__two--modern .product-banner--modern-hero .theme-slider-dots .swiper-pagination {
    bottom: 14px !important;
}

.home__two--modern .modern-hero-skeleton {
    margin: 0 0 16px;
    width: 100%;
    border-radius: 0 0 24px 24px;
    overflow: hidden;

    .skeleton,
    .w-100 {
        height: 300px !important;
        max-width: none;
    }
}
</style>
