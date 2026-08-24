<template>
  <div class="product-page-root" :class="{
    'has-product-footer': showProductStickyFooter,
    'product-page--rtl': isFeaturePaneLayout && isRtl,
  }">
    <!-- ============================= -->
    <!-- MODERN PRODUCT DETAIL LAYOUT -->
    <!-- ============================= -->
    <div v-if="layoutType === 'modern'" class="page-container modern-product-page">
      <div class="productDetails modern-product-scroll">

        <!-- Product loaded -->
        <template v-if="!productLoading && product != null">

          <!-- Product Gallery -->
          <div class="modern-product-gallery">
            <details-gallery :gallery-images="modernProductGallery" :thumbnail-image="modernProductThumbnail"
              :voucher-list="product.voucher_list" :product-name="product.name" :url="product.url"
              :summary="product.summary" :networks="product.shareOptions" :key="galleryKey" :modern-layout="true" />
          </div>

          <!-- Product Information -->
          <div class="modern-product-information">
            <details-content :product="product" :quantity-seed="orderQuantity" :modern-layout="true"
              @goto-section="goSec" @color-variant-images="colorVariantImages"
              @variant-updating="variantUpdating = $event" @quantity-change="onOrderQuantityChange" />

            <!-- PDF specification -->
            <div v-if="product.pdf_specifications" class="modern-product-pdf">
              <a :href="product.pdf_specifications" target="_blank" rel="noopener">
                {{ $t("Download Pdf Specifications") }}
              </a>
            </div>

            <!-- Reviews -->
            <div v-if="hasProductReviewsTab" ref="productDetails" class="modern-product-reviews">
              <h3 class="modern-section-title">
                {{ $t("Buyer Review") }}
              </h3>

              <ProductReview :product-id="product.id" :config="site_config" :key="product.id" />
            </div>
          </div>

        </template>

        <!-- Product not found -->
        <div v-else-if="!productLoading" class="modern-product-not-found">
          <the-not-found title="Sorry! Product not found"></the-not-found>
        </div>

        <!-- Loading -->
        <div v-else class="modern-product-loading">
          <gallery-image-skeleton />
          <product-details-skeleton />
        </div>

      </div>
    </div>

    <!-- ============================= -->
    <!-- EXISTING FEATURE-PANE LAYOUT -->
    <!-- DO NOT CHANGE ITS CONTENT -->
    <!-- ============================= -->
    <div v-else-if="isFeaturePaneLayout" class="page-container split-screen-product-layout">
      <div class="productDetails split-screen-product-scroll" :class="{
        'force-mobile-layout': forcedMobile,
        'mobile-content-wrapper': forcedMobile,
      }">
        <!-- <page-header :items="bItems" /> -->

        <!-- Product details Hash Menu -->
        <div class="product-details-hash-menu d-lg-none" ref="hashMenu" style="margin-top: 10px;">
          <div class="custom-container2">
            <ul>
              <li @click.prevent="goSec('overview')">{{ $t("Overview") }}</li>
              <!-- <li @click.prevent="goSec('quickConnect')">
              {{ $t("Quick Connect") }}
            </li> -->
              <li v-if="showProductDetailsSection" @click.prevent="goSec('productDetails')">
                {{ $t("Product Details") }}
              </li>
              <!-- <li @click.prevent="goSec('recommendations')">
              {{ $t("Recommendations") }}
            </li> -->
            </ul>
          </div>
        </div>
        <!-- End Product details Hash Menu -->

        <div class="pt-4 pb-4 light-bg" ref="overview">
          <div class="custom-container2">
            <div class="product-details" v-if="!productLoading">
              <div class="row">
                <template v-if="product != null">
                  <div class="col-lg-5">
                    <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                      :product-name="product.name" :url="product.url" :summary="product.summary"
                      :networks="product.shareOptions" :key="galleryKey" />
                  </div>
                  <div class="col-lg-7">
                    <details-content :product="product" :quantity-seed="orderQuantity" @goto-section="goSec"
                      @color-variant-images="colorVariantImages" @variant-updating="variantUpdating = $event"
                      @quantity-change="onOrderQuantityChange" />
                  </div>
                </template>
                <div class="row" v-else>
                  <the-not-found title="Sorry! Product not found"></the-not-found>
                </div>
              </div>
            </div>

            <div class="product-details1" v-if="productLoading">
              <div class="row">
                <div class="col-lg-5">
                  <gallery-image-skeleton></gallery-image-skeleton>
                </div>
                <div class="col-lg-7">
                  <product-details-skeleton></product-details-skeleton>
                </div>
              </div>
            </div>

            <!--Product info card-->
            <!-- <div class="row mt-lg-4" v-if="!productLoading && product != null">
            <div class="col-12">
              <ul class="info-list shadow-card" ref="quickConnect">
                <li class="info-item" v-if="product.condition">
                  <h4>{{ $t("Conditions") }}</h4>
                  <p>{{ $t("Product Condition") }}</p>
                  <span class="c1">{{ product.condition }}</span>
                </li>
                <li class="info-item" v-if="product.is_authentic">
                  <h4>{{ $t("Authentic") }}</h4>
                  <p>{{ $t("Authentic") }}</p>
                  <span class="c1">{{ product.is_authentic }}</span>
                </li>
                <li class="info-item" v-if="product.is_active_cod">
                  <h4>{{ $t("Payment Option") }}</h4>
                  <p>{{ $t("Cash on Delivery") }}</p>
                  <span class="c1">{{ product.is_active_cod }}</span>
                </li>
                <li class="info-item">
                  <h4>{{ $t("Return Options") }}</h4>
                  <p>{{ $t("Change of mind is not applicable") }}</p>
                  <span class="c1">{{ product.return_option }}</span>
                </li>
                <li class="info-item">
                  <h4>{{ $t("Warranty") }}</h4>
                  <p>{{ $t("Seller warranty") }}</p>
                  <p v-if="product.has_warranty == enums.status.ACTIVE">
                    <span class="c1"
                      >{{ product.warrenty_days }} {{ $t("days") }}
                      <span class="c1" v-if="product.has_replacement_warranty">
                        {{ $t("replacement") }}</span
                      >
                      <span class="c1"> {{ $t("warranty") }}</span>
                    </span>
                  </p>
                  <p class="c1" v-else>
                    {{ $t("Not available") }}
                  </p>
                </li>
              </ul>
            </div>
          </div> -->
            <!--End product info card-->

            <!--Tab Content-->
            <div class="row mt-45" v-if="!productLoading && product != null">
              <!--Product widgets-->
              <div class="col-lg-3 d-none d-lg-block">
                <div class="widget_wrap">
                  <single-shop :shop="product.shopInfo" widgetTitle v-if="product.shopInfo != null"></single-shop>
                  <WidgetTopCategory :categories="topCategories" link="true" />
                  <WidgetTopSellingProduct :products="topSellingProducts" />
                </div>
              </div>
              <!--End Product widgets-->
              <!--Product details tabs-->
              <div class="col-lg-9" v-if="showProductDetailsSection">
                <div class="overview-wrap" ref="productDetails">
                  <CNav variant="tabs" role="tablist" class="product-description-tabs bg-light">
                    <CNavItem v-if="hasProductOverviewContent" role="presentation">
                      <a href="javascript:void(0);" class="nav-link" :class="{ active: tabPaneActiveKey === 1 }"
                        @click.prevent="toggleProductDetailsTab(1)">
                        {{ $t("Product Overview") }}
                      </a>
                    </CNavItem>
                    <CNavItem v-if="hasProductReviewsTab" role="presentation">
                      <a href="javascript:void(0);" class="nav-link" :class="{ active: tabPaneActiveKey === 2 }"
                        @click.prevent="toggleProductDetailsTab(2)">
                        {{ $t("Buyer Review") }}
                      </a>
                    </CNavItem>
                  </CNav>
                  <div class="tab-content" v-show="tabPaneActiveKey !== null">
                    <div v-if="hasProductOverviewContent" v-show="tabPaneActiveKey === 1" role="tabpanel"
                      aria-labelledby="home-tab" class="product-overview-pane">
                      <div v-if="hasMeaningfulHtml(product.description)" v-html="product.description"></div>
                      <div v-if="product.pdf_specifications">
                        <a :href="product.pdf_specifications" target="_blank">Download Pdf Specifications</a>
                      </div>
                    </div>
                    <div v-if="hasProductReviewsTab" v-show="tabPaneActiveKey === 2" role="tabpanel"
                      aria-labelledby="profile-tab" class="product-reviews-pane">
                      <ProductReview :product-id="product.id" :config="site_config" :key="product.id"></ProductReview>
                    </div>
                  </div>
                </div>
              </div>
              <!--End product details tabs-->
            </div>
            <!--End Tab Content-->
          </div>
        </div>

        <!-- Related Product -->
        <!-- <section
        class="bg-white mt-n1 pb-30 pt-30"
        ref="recommendations"
        v-if="!productLoading && product != null"
      >
        <div class="custom-container2">
          <div class="row align-items-center mb-30">
            <div class="col-md-6">
              <section-title :title="sectionTitle" />
            </div>
          </div>
  
          <swiper
            v-if="relatedProducts.length"
            :slidesPerView="6"
            :modules="modules"
            :spaceBetween="1"
            :loop="true"
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
              v-for="(item, index) in relatedProducts"
              :key="`slide-${index}`"
            >
              <single-product :item="item" />
            </swiper-slide>
          </swiper>
        </div>
      </section> -->
        <!-- End Related Product -->




      </div>

    </div>
    <div v-else class="productDetails">
      <page-header :items="bItems" />

      <!-- Product details Hash Menu -->
      <div class="product-details-hash-menu d-lg-none" ref="hashMenu">
        <div class="custom-container2">
          <ul>
            <li @click.prevent="goSec('overview')">{{ $t("Overview") }}</li>
            <li @click.prevent="goSec('quickConnect')">
              {{ $t("Quick Connect") }}
            </li>
            <li v-if="showProductDetailsSection" @click.prevent="goSec('productDetails')">
              {{ $t("Product Details") }}
            </li>
            <li @click.prevent="goSec('recommendations')">
              {{ $t("Recommendations") }}
            </li>
          </ul>
        </div>
      </div>
      <!-- End Product details Hash Menu -->

      <div class="pt-4 pb-4 light-bg" ref="overview">
        <div class="custom-container2">
          <div class="product-details" v-if="!productLoading">
            <div class="row">
              <template v-if="product != null">
                <div class="col-lg-5">
                  <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                    :product-name="product.name" :url="product.url" :summary="product.summary"
                    :networks="product.shareOptions" :key="galleryKey" />
                </div>
                <div class="col-lg-7 default-product-actions">
                  <details-content :product="product" :quantity-seed="orderQuantity" @goto-section="goSec"
                    @color-variant-images="colorVariantImages" @variant-updating="variantUpdating = $event"
                    @quantity-change="onOrderQuantityChange" />
                </div>
              </template>
              <div class="row" v-else>
                <the-not-found title="Sorry! Product not found"></the-not-found>
              </div>
            </div>
          </div>

          <div class="product-details1" v-if="productLoading">
            <div class="row">
              <div class="col-lg-5">
                <gallery-image-skeleton></gallery-image-skeleton>
              </div>
              <div class="col-lg-7">
                <product-details-skeleton></product-details-skeleton>
              </div>
            </div>
          </div>

          <!--Product info card-->
          <div class="row mt-lg-4" v-if="!productLoading && product != null">
            <div class="col-12">
              <ul class="info-list shadow-card" ref="quickConnect">
                <li class="info-item" v-if="product.condition">
                  <h4>{{ $t("Conditions") }}</h4>
                  <p>{{ $t("Product Condition") }}</p>
                  <span class="c1">{{ product.condition }}</span>
                </li>
                <li class="info-item" v-if="product.is_authentic">
                  <h4>{{ $t("Authentic") }}</h4>
                  <p>{{ $t("Authentic") }}</p>
                  <span class="c1">{{ product.is_authentic }}</span>
                </li>
                <li class="info-item" v-if="product.is_active_cod">
                  <h4>{{ $t("Payment Option") }}</h4>
                  <p>{{ $t("Cash on Delivery") }}</p>
                  <span class="c1">{{ product.is_active_cod }}</span>
                </li>
                <li class="info-item">
                  <h4>{{ $t("Return Options") }}</h4>
                  <p>{{ $t("Change of mind is not applicable") }}</p>
                  <span class="c1">{{ product.return_option }}</span>
                </li>
                <li class="info-item">
                  <h4>{{ $t("Warranty") }}</h4>
                  <p>{{ $t("Seller warranty") }}</p>
                  <p v-if="product.has_warranty == enums.status.ACTIVE">
                    <span class="c1">{{ product.warrenty_days }} {{ $t("days") }}
                      <span class="c1" v-if="product.has_replacement_warranty">
                        {{ $t("replacement") }}</span>
                      <span class="c1"> {{ $t("warranty") }}</span>
                    </span>
                  </p>
                  <p class="c1" v-else>
                    {{ $t("Not available") }}
                  </p>
                </li>
              </ul>
            </div>
          </div>
          <!--End product info card-->

          <!--Tab Content-->
          <div class="row mt-45" v-if="!productLoading && product != null">


            <!--Product widgets-->
            <div class="col-lg-3 d-none d-lg-block">
              <div class="widget_wrap">
                <!-- <single-shop :shop="product.shopInfo" widgetTitle v-if="product.shopInfo != null"></single-shop>
                <WidgetTopCategory :categories="topCategories" link="true" /> -->
                <WidgetTopSellingProduct :products="topSellingProducts" />
              </div>
            </div>
            <!--End Product widgets-->


            <!--Product details tabs-->
            <div class="col-lg-9" v-if="showProductDetailsSection">
              <div class="overview-wrap" ref="productDetails">
                <CNav variant="tabs" role="tablist" class="product-description-tabs bg-light">
                  <CNavItem v-if="hasProductOverviewContent" role="presentation">
                    <a href="javascript:void(0);" class="nav-link" :class="{ active: tabPaneActiveKey === 1 }"
                      @click.prevent="toggleProductDetailsTab(1)">
                      {{ $t("Product Overview") }}
                    </a>
                  </CNavItem>
                  <CNavItem v-if="hasProductReviewsTab" role="presentation">
                    <a href="javascript:void(0);" class="nav-link" :class="{ active: tabPaneActiveKey === 2 }"
                      @click.prevent="toggleProductDetailsTab(2)">
                      {{ $t("Buyer Review") }}
                    </a>
                  </CNavItem>
                </CNav>
                <div class="tab-content" v-show="tabPaneActiveKey !== null">
                  <div v-if="hasProductOverviewContent" v-show="tabPaneActiveKey === 1" role="tabpanel"
                    aria-labelledby="home-tab" class="product-overview-pane">
                    <div v-if="hasMeaningfulHtml(product.description)" v-html="product.description"></div>
                    <div v-if="product.pdf_specifications">
                      <a :href="product.pdf_specifications" target="_blank">Download Pdf Specifications</a>
                    </div>
                  </div>
                  <div v-if="hasProductReviewsTab" v-show="tabPaneActiveKey === 2" role="tabpanel"
                    aria-labelledby="profile-tab" class="product-reviews-pane">
                    <ProductReview :product-id="product.id" :config="site_config" :key="product.id"></ProductReview>
                  </div>
                </div>
              </div>
            </div>
            <!--End product details tabs-->
          </div>
          <!--End Tab Content-->
        </div>
      </div>

      <!-- Related Product -->
      <section class="bg-white mt-n1 pb-30 pt-30" ref="recommendations" v-if="!productLoading && product != null">
        <div class="custom-container2">
          <div class="row align-items-center mb-30">
            <div class="col-md-6">
              <section-title :title="sectionTitle" />
            </div>
          </div>

          <swiper v-if="relatedProducts.length" :slidesPerView="6" :modules="modules" :spaceBetween="1" :loop="true"
            class="product-grid-slider theme-slider-dots" :breakpoints="{
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
            <swiper-slide v-for="(item, index) in relatedProducts" :key="`slide-${index}`">
              <single-product :item="item" />
            </swiper-slide>
          </swiper>
        </div>
      </section>
      <!-- End Related Product -->
    </div>

    <custom-footer v-if="showProductStickyFooter" class="custom-footer" :item="productData"
      :quantity-value="orderQuantity" :disabled="variantUpdating" :style="splitContentFooterStyle" />
  </div>
</template>



<script>
import { mapState, mapGetters, mapMutations } from "vuex";
import { Pagination } from "swiper";
const axios = require("axios").default;
import enums from "../../../enums/enums";
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";

const PageHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/PageHeader.vue")
);
const DetailsContent = defineAsyncComponent(() =>
  import("@/components/product/DetailsContent.vue")
);

const ProductReview = defineAsyncComponent(() =>
  import("@/components/product/ProductReview.vue")
);

const SingleProduct = defineAsyncComponent(() =>
  import("@/components/product/SingleProduct.vue")
);

const WidgetTopCategory = defineAsyncComponent(() =>
  import("@/components/widget/WidgetTopCategory.vue")
);

const WidgetTopSellingProduct = defineAsyncComponent(() =>
  import("@/components/widget/WidgetTopSellingProduct.vue")
);

const GalleryImageSkeleton = defineAsyncComponent(() =>
  import("@/components/skeleton/GalleryImageSkeleton.vue")
);

const ProductDetailsSkeleton = defineAsyncComponent(() =>
  import("@/components/skeleton/ProductDetailsSkeleton.vue")
);

import DetailsGallery from "@/components/product/DetailsGallery.vue";

const SingleShop = defineAsyncComponent(() =>
  import("@/components//shop/SingleShop.vue")
);

import CustomFooter from '@/components/ui/CustomFooter.vue'
import { isProductLineInCart, findProductCartLine } from '@/utils/cartLineMatch'
import { trackSocialPixels } from '@/utils/trackSocialPixels'

import {
  CNav,
  CNavItem,
  CTable,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
  CTableHead,
} from "@coreui/vue";
export default {
  name: "ProductDetails",
  components: {
    ProductDetailsSkeleton,
    GalleryImageSkeleton,
    DetailsGallery,
    PageHeader,
    DetailsContent,
    ProductReview,
    SingleProduct,
    WidgetTopCategory,
    WidgetTopSellingProduct,
    Swiper,
    SwiperSlide,
    Pagination,
    CNav,
    CNavItem,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    SingleShop,
    CustomFooter
  },
  inject: {
    setModernPageHeaderImage: {
      default: null,
    },
  },
  data() {
    return {
      enums: enums,
      product: null,
      tabPaneActiveKey: 1,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Products"),
          href: "/products",
        },
      ],
      topCategories: [],
      relatedProducts: [],
      topSellingProducts: [],
      galleryKey: 1,
      modernThumbnail: null,
      modernGalleryImages: [],
      modernBaseGalleryImages: [],
      modernMediaLoading: false,
      sectionTitle: this.$t("Related Products"),
      productLoading: true,
      variantUpdating: false,
      orderQuantity: 1,
      _skipCartQuantitySync: false,
      _orderQuantitySyncTimer: null,
      _isUnmounted: false,
      requestCancelSource: null,
    };
  },
  computed: {
    ...mapState({
      site_config: (state) => state.siteSettings,
      cart: (state) => state.cart,
    }),

    ...mapGetters('layout', [
      'layoutType',
      'isFeaturePaneLayout',
      'isMobile',
      'isRtl',
      'contentColumnPercent',
      'effectiveContentPosition',
    ]),

    modernProductThumbnail() {
      return this.modernThumbnail || null;
    },

    modernProductGallery() {
      if (
        Array.isArray(this.modernGalleryImages) &&
        this.modernGalleryImages.length > 0
      ) {
        return this.modernGalleryImages;
      }

      // Safe fallback while the Modern-only media request is loading.
      // Other themes continue to use product.galleryImages exactly as before.
      return Array.isArray(this.product?.galleryImages)
        ? this.product.galleryImages
        : [];
    },

    isSplitScreenDesktop() {
      return this.isFeaturePaneLayout;
    },

    forcedMobile() {
      return this.isSplitScreenDesktop;
    },

    isCurrentProductInCart() {
      return isProductLineInCart(this.cart, this.product);
    },

    showProductStickyFooter() {
      return (
        !!this.productData &&
        (this.isFeaturePaneLayout || this.isMobile) &&
        !this.isCurrentProductInCart
      );
    },

    splitContentFooterStyle() {
      if (!this.isFeaturePaneLayout || this.isMobile) return {};

      const style = { width: `${this.contentColumnPercent}%` };

      if (this.effectiveContentPosition === 'right') {
        style.right = '0';
        style.left = 'auto';
      } else {
        style.left = '0';
      }

      return style;
    },

    productData() {
      return this.product ?? null
    },

    hasProductOverviewContent() {
      if (!this.product) {
        return false;
      }
      return (
        this.hasMeaningfulHtml(this.product.description) ||
        !!this.product.pdf_specifications
      );
    },

    hasProductReviewsTab() {
      if (
        this.site_config?.enable_product_reviews != this.enums.status.ACTIVE ||
        !this.product
      ) {
        return false;
      }
      return parseInt(this.product.total_reviews, 10) > 0;
    },

    showProductDetailsSection() {
      return this.hasProductOverviewContent || this.hasProductReviewsTab;
    },


  },
  // computed: mapState({
  //   site_config: (state) => state.siteSettings,
  // }),
  setup() {
    return {
      modules: [Pagination],
    };
  },
  watch: {
    showProductStickyFooter: {
      handler(value) {
        this.syncProductPageCompanyFooter(value);
      },
      immediate: true,
    },
    product: {
      handler(newVal, oldVal) {
        this.syncOrderQuantityFromProduct();
        if (!oldVal || newVal?.id !== oldVal?.id) {
          this.syncProductDetailsTab();
        }
      },
    },
    "product.selectedVariant"() {
      this.syncOrderQuantityFromProduct();
    },
    cart: {
      handler() {
        if (!this.product || !this.isCurrentProductInCart) {
          return;
        }
        const line = findProductCartLine(this.cart, this.product);
        if (!line) {
          return;
        }
        const cartQty = parseInt(line.quantity, 10) || 1;
        if (cartQty === parseInt(this.orderQuantity, 10)) {
          return;
        }
        this._skipCartQuantitySync = true;
        this.orderQuantity = cartQty;
        this.$nextTick(() => {
          this._skipCartQuantitySync = false;
        });
      },
      deep: true,
    },
    $route(to, from) {
      if (to.name !== "product") {
        return;
      }
      if (from?.name === "product" && from.params?.id === to.params?.id) {
        return;
      }
      this.tabPaneActiveKey = 1;
      this.modernThumbnail = null;
      this.modernGalleryImages = [];
      this.modernBaseGalleryImages = [];
      this.modernMediaLoading = false;
      if (typeof this.setModernPageHeaderImage === "function") {
        this.setModernPageHeaderImage(null);
      }
      this.getProductDetails();
      window.scrollTo(0, 0);
    },
  },
  mounted() {
    window.addEventListener("scroll", this.scrollHandler);
    this.beginRequestCycle();
    this.getProductDetails();
    this.getCategories();
    this.getTopSellingProducts();
  },
  beforeUnmount() {
    if (typeof this.setModernPageHeaderImage === "function") {
      this.setModernPageHeaderImage(null);
    }
    this._isUnmounted = true;
    if (this._orderQuantitySyncTimer) {
      clearTimeout(this._orderQuantitySyncTimer);
      this._orderQuantitySyncTimer = null;
    }
    this.setProductPageSuppressCompanyFooter(false);
    this.requestCancelSource?.cancel("navigated away");
    window.removeEventListener("scroll", this.scrollHandler);
    this.productLoading = true;
    this.product = null;
    this.modernThumbnail = null;
    this.modernGalleryImages = [];
    this.modernBaseGalleryImages = [];
    this.modernMediaLoading = false;
    this.relatedProducts = [];
  },
  methods: {
    ...mapMutations(["setProductPageSuppressCompanyFooter"]),

    syncProductPageCompanyFooter(showCustomFooter) {
      if (this.$route.name !== "product") {
        this.setProductPageSuppressCompanyFooter(false);
        return;
      }
      this.setProductPageSuppressCompanyFooter(!!showCustomFooter);
    },

    defaultOrderQuantity() {
      const product = this.product;
      if (!product) {
        return 1;
      }
      if (
        product.min_item_on_purchase != null &&
        parseInt(product.min_item_on_purchase, 10) > 0
      ) {
        return parseInt(product.min_item_on_purchase, 10);
      }
      return 1;
    },

    syncOrderQuantityFromProduct() {
      if (!this.product) {
        return;
      }
      this._skipCartQuantitySync = true;
      const line = findProductCartLine(this.cart, this.product);
      if (line) {
        this.orderQuantity = parseInt(line.quantity, 10) || 1;
      } else {
        this.orderQuantity = this.defaultOrderQuantity();
      }
      this.$nextTick(() => {
        this._skipCartQuantitySync = false;
      });
    },

    onOrderQuantityChange(value) {
      this.orderQuantity = value;
      this.queueSyncOrderQuantityToCart();
    },

    queueSyncOrderQuantityToCart() {
      if (this._orderQuantitySyncTimer) {
        clearTimeout(this._orderQuantitySyncTimer);
      }
      this._orderQuantitySyncTimer = setTimeout(() => {
        this._orderQuantitySyncTimer = null;
        this.syncOrderQuantityToCart();
      }, 300);
    },

    syncOrderQuantityToCart() {
      if (
        this._skipCartQuantitySync ||
        !this.isCurrentProductInCart ||
        !this.product
      ) {
        return;
      }
      const line = findProductCartLine(this.cart, this.product);
      if (!line) {
        return;
      }
      const qty = parseInt(this.orderQuantity, 10);
      if (!Number.isFinite(qty)) {
        return;
      }
      if (parseInt(line.quantity, 10) === qty) {
        return;
      }
      this.$store.dispatch("updateCartLineQuantity", {
        ...line,
        quantity: qty,
      });
    },

    beginRequestCycle() {
      this.requestCancelSource?.cancel("navigated away");
      this.requestCancelSource = axios.CancelToken.source();
    },

    getCancelToken() {
      if (!this.requestCancelSource) {
        this.requestCancelSource = axios.CancelToken.source();
      }
      return this.requestCancelSource.token;
    },

    normalizeModernMediaPath(value) {
      if (!value || typeof value !== "string") {
        return null;
      }

      const trimmed = value.trim();
      if (!trimmed) {
        return null;
      }

      if (
        /^(https?:)?\/\//i.test(trimmed) ||
        trimmed.startsWith("data:") ||
        trimmed.startsWith("blob:")
      ) {
        return trimmed;
      }

      // Keep the same path behavior already used throughout this storefront.
      return trimmed;
    },

    mergeModernGalleryImages(primaryImages = [], secondaryImages = []) {
      const combined = [
        ...(Array.isArray(primaryImages) ? primaryImages : []),
        ...(Array.isArray(secondaryImages) ? secondaryImages : []),
      ];

      const seen = new Set();

      return combined.filter((image) => {
        if (!image) return false;

        const rawKey =
          image.regular ||
          image.zoom ||
          image.video_link ||
          image.thumbnail;

        if (!rawKey) return false;

        const key = String(rawKey).split("?")[0];
        if (seen.has(key)) return false;

        seen.add(key);
        return true;
      });
    },

    getModernProductMedia() {
      // This request is intentionally Modern-only. Existing themes and their
      // existing product-details/gallery behavior are not touched.
      if (
        this.layoutType !== "modern" ||
        !this.product?.id ||
        this.$route.name !== "product"
      ) {
        return;
      }

      this.modernMediaLoading = true;
      this.modernThumbnail = null;
      this.modernGalleryImages = [];
      this.modernBaseGalleryImages = [];

      if (typeof this.setModernPageHeaderImage === "function") {
        this.setModernPageHeaderImage(null);
      }

      axios
        .post(
          "/api/v1/ecommerce-core/modern-product-media",
          {
            product_id: this.product.id,
          },
          { cancelToken: this.getCancelToken() }
        )
        .then((response) => {
          if (
            this._isUnmounted ||
            this.$route.name !== "product" ||
            this.layoutType !== "modern"
          ) {
            return;
          }

          if (!response.data?.success) {
            return;
          }

          this.modernThumbnail = this.normalizeModernMediaPath(
            response.data.thumbnail
          );

          const mediaGallery = Array.isArray(response.data.galleryImages)
            ? response.data.galleryImages.filter(Boolean)
            : [];

          this.modernBaseGalleryImages = [...mediaGallery];
          this.modernGalleryImages = [...mediaGallery];

          // Re-create only the Modern gallery after its dedicated media arrives.
          this.galleryKey += 1;

          if (typeof this.setModernPageHeaderImage === "function") {
            this.setModernPageHeaderImage(this.modernThumbnail || null);
          }

          console.log("MODERN THUMBNAIL:", this.modernThumbnail);
          console.log("MODERN GALLERY:", this.modernGalleryImages);
          console.log("MODERN GALLERY COUNT:", this.modernGalleryImages.length);
        })
        .catch((error) => {
          if (axios.isCancel(error) || this._isUnmounted) {
            return;
          }

          console.error("Modern product media error:", error);
          this.modernThumbnail = null;
          this.modernGalleryImages = [];
          this.modernBaseGalleryImages = [];

          if (typeof this.setModernPageHeaderImage === "function") {
            this.setModernPageHeaderImage(null);
          }
        })
        .finally(() => {
          this.modernMediaLoading = false;
        });
    },

    /**
     * Get single product details
     */
    getProductDetails() {
      if (this._isUnmounted || this.$route.name !== "product") {
        return;
      }
      this.beginRequestCycle();
      this.orderQuantity = 1;

      if (this.layoutType === "modern") {
        this.modernThumbnail = null;
        this.modernGalleryImages = [];
        this.modernBaseGalleryImages = [];
        this.modernMediaLoading = false;
        if (typeof this.setModernPageHeaderImage === "function") {
          this.setModernPageHeaderImage(null);
        }
      }

      axios
        .post(
          "/api/v1/ecommerce-core/product-details",
          {
            permalink: this.$route.params.id,
            preview:
              typeof this.$route.query.preview != "undefined"
                ? this.$route.query.preview
                : null,
          },
          { cancelToken: this.getCancelToken() }
        )
        .then((response) => {
          if (this._isUnmounted || this.$route.name !== "product") {
            return;
          }
          if (response.data) {
            this.getRelatedProducts(response.data.data.id);
            document.title = response.data.data.name;
            this.product = response.data.data;
            this.productLoading = false;

            if (this.layoutType === "modern") {
              this.getModernProductMedia();
            }

            this.syncOrderQuantityFromProduct();
            this.syncProductDetailsTab();

            trackSocialPixels('ViewContent', {
              content_ids: [this.product.id],
              content_name: this.product.name,
              content_type: 'product',
              value: this.product.price,
              currency: 'KWD',
            });
          } else {
            this.productLoading = false;
          }
        })
        .catch((error) => {
          if (axios.isCancel(error) || this._isUnmounted) {
            return;
          }
          this.productLoading = false;
        });
    },
    /**
     * Top selling  Products
     */
    getTopSellingProducts(id) {
      if (this._isUnmounted || this.$route.name !== "product") {
        return;
      }
      axios
        .post(
          "/api/v1/ecommerce-core/top-selling-products",
          {},
          { cancelToken: this.getCancelToken() }
        )
        .then((response) => {
          if (this._isUnmounted || this.$route.name !== "product") {
            return;
          }
          this.topSellingProducts = response.data?.data ?? [];
        })
        .catch((error) => {
          if (axios.isCancel(error) || this._isUnmounted) {
            return;
          }
          this.topSellingProducts = [];
        });
    },

    /**
     * Related Products
     */
    getRelatedProducts(id) {
      if (this._isUnmounted || this.$route.name !== "product") {
        return;
      }
      axios
        .post(
          "/api/v1/ecommerce-core/related-products",
          { id: id },
          { cancelToken: this.getCancelToken() }
        )
        .then((response) => {
          if (this._isUnmounted || this.$route.name !== "product") {
            return;
          }
          this.relatedProducts = response.data?.data ?? [];
        })
        .catch((error) => {
          if (axios.isCancel(error) || this._isUnmounted) {
            return;
          }
          this.relatedProducts = [];
        });
    },
    /**
     * Get top categories
     *
     */
    getCategories() {
      if (this._isUnmounted || this.$route.name !== "product") {
        return;
      }
      axios
        .get("/api/v1/ecommerce-core/parent-categories", {
          cancelToken: this.getCancelToken(),
        })
        .then((response) => {
          if (this._isUnmounted || this.$route.name !== "product") {
            return;
          }
          if (response.status === 200) {
            this.topCategories = response.data?.data ?? [];
          }
        })
        .catch((error) => {
          if (axios.isCancel(error) || this._isUnmounted) {
            return;
          }
          this.topCategories = [];
        });
    },
    /**
     * Color variant gallery images
     */
    colorVariantImages(color_id) {
      axios
        .post("/api/v1/ecommerce-core/color-variant-images", {
          product_id: this.product.id,
          color_id: color_id,
        })
        .then((response) => {
          if (!response.data.success) {
            return;
          }

          // Preserve the original behavior for every non-Modern theme.
          if (this.layoutType !== "modern") {
            this.galleryKey = this.galleryKey + 1;
            this.product.galleryImages = response.data.images;
            return;
          }

          // Modern keeps all saved Gallery Images visible. Variant images, when
          // present, are placed first without deleting the Modern base gallery.
          this.modernGalleryImages = this.mergeModernGalleryImages(
            response.data.images,
            this.modernBaseGalleryImages
          );
          this.galleryKey = this.galleryKey + 1;
        })
        .catch((error) => {
        });
    },
    goSec(sec) {
      this.$nextTick(() => {
        const element = this.$refs[sec];
        if (!element) {
          return;
        }
        const top = element.offsetTop;
        window.scrollTo(0, top - 110);
      });
    },

    toggleProductDetailsTab(tabKey) {
      if (this.tabPaneActiveKey === tabKey) {
        this.tabPaneActiveKey = null;
      } else {
        this.tabPaneActiveKey = tabKey;
      }
    },

    hasMeaningfulHtml(value) {
      if (!value || typeof value !== "string") {
        return false;
      }
      const text = value
        .replace(/<[^>]*>/g, "")
        .replace(/&nbsp;/gi, " ")
        .trim();
      return text.length > 0;
    },

    syncProductDetailsTab() {
      if (!this.showProductDetailsSection) {
        return;
      }
      this.tabPaneActiveKey = this.hasProductOverviewContent ? 1 : 2;
    },

    scrollHandler() {
      const hashMenu = this.$refs.hashMenu;
      if (hashMenu) {
        window.pageYOffset > 450
          ? hashMenu?.classList.add("active")
          : hashMenu?.classList.remove("active");
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../assets/sass/00-abstracts/01-variables";

.shadow-card {
  border-radius: 6px;
  overflow: hidden;
}

.widget_wrap :deep(.widget-style-1) {
  border-radius: 6px;
  overflow: hidden;
}

.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

// .force-mobile-layout .product-content .image,
// .force-mobile-layout .product-content .product-img {
//   min-width: 0 !important;
//   max-width: 100px;
// }



// .force-mobile-layout .cart-image-review {
//   max-width: 100% !important;
//   height: auto !important;
//   flex-shrink: 1 !important;
// }

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


.product-details {
  padding: 30px;
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  border: 1px solid #f7f8fa;
  border-radius: 6px;
  overflow: hidden;

  @media only screen and (max-width: 575px) {
    padding: 0px 15px;
  }
}

.productDetails:not(.split-screen-product-scroll) {
  :deep(.default-product-actions .product-details-action-area .button-group .btn_fill),
  :deep(.default-product-actions .product-details-action-area .button-group .btn_borderd) {
    border-radius: 6px !important;
  }
}

.product-details1 {
  border-radius: 6px;
  overflow: hidden;
}

.overview-wrap {
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  border: 1px solid #f7f8fa;
  border-radius: 6px;
  overflow: hidden;

  ul {
    li {
      &:not(:last-child) {
        margin-bottom: 5px;
      }
    }
  }

  p {
    &:not(:last-child) {
      margin-bottom: 30px;
    }
  }

  .tab-content {
    padding: 30px;

    @media only screen and (max-width: 575px) {
      padding: 0px 15px;
    }
  }
}

.product-description-tabs {
  border-bottom: none !important;

  @media only screen and (max-width: 575px) {
    flex-direction: column;
  }

  li {
    margin-bottom: 0 !important;

    a {
      color: #666666;
      font-size: 18px;
      font-weight: 700;
      padding: 11px 20px;
      line-height: 1.2;
      font-family: $title-font;
      border: none;
      position: relative;
      border-radius: 0px;

      @media only screen and (max-width: 575px) {
        text-align: center;
      }

      &:after {
        font-family: "Material Icons";
        content: "\e5cf";
        position: relative;
        top: 3px;
        left: 5px;
        font-size: 16px;
      }

      &.active {
        color: #fff;
        background-color: $c1;

        &:after {
          content: "\e5ce";
        }
      }
    }
  }
}

.info-list {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 30px 0;
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  border: 1px solid #f7f8fa;
  max-width: 100%;
  overflow-x: auto;

  @media only screen and (max-width: 1199px) {
    padding: 0;
  }

  li {
    padding: 0 30px;
    flex-grow: 1;

    &:not(:last-child) {
      border-right: 1px solid #e7eaef;
    }

    @media only screen and (max-width: 1199px) {
      padding: 15px;
      box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
      flex-grow: inherit;
      min-width: 220px;
    }

    p {
      margin-bottom: 0;
    }

    span,
    a {
      font-size: 13px;
    }
  }
}

.specification-table {
  h4 {
    font-size: 21px;
    font-weight: 500;
    margin-bottom: 16px;
  }

  .table {
    th {
      background-color: #f7f8fa;
    }

    th,
    td {
      border-color: #f7f8fa;
    }

    tr {
      &:hover {
        background-color: rgba($color: #fafafa, $alpha: 0.5);
      }
    }
  }
}

.product-details-hash-menu {
  position: fixed;
  width: 100%;
  top: 75px;
  visibility: hidden;
  opacity: 0;
  transition: all 0.3s ease;
  left: 0;
  box-shadow: 7px 7px 60px rgb(0 0 0 / 7%);
  padding: 10px 0;
  background-color: #fff;

  ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    white-space: nowrap;
    overflow: auto;

    li {
      user-select: none;
      cursor: pointer;

      &:not(:last-child) {
        margin-right: 20px;
      }

      &.active {
        color: $c1;
      }
    }
  }

  &.active {
    top: 65px;
    visibility: visible;
    opacity: 1;
    z-index: 9;
  }
}


/* Desktop split-screen only — nested scroll for the shop panel */
.split-screen-product-layout {
  display: flex;
  flex-direction: column;
}

.split-screen-product-scroll {
  flex: 1;
  overflow-y: auto;
}

.product-page-root.has-product-footer .productDetails,
.product-page-root.has-product-footer .split-screen-product-scroll {
  padding-bottom: 70px;
}

.custom-footer {
  position: fixed;
  bottom: 0;
  z-index: 100;
  background: #fff;

  @media (max-width: 769px) {
    width: 100% !important;
    left: 0 !important;
    right: auto !important;
  }
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.product-page--rtl .product-details-hash-menu ul {
  direction: rtl;
  text-align: right;
}

.product-page--rtl .product-description-tabs {
  direction: rtl;
}

.product-page--rtl .overview-wrap :deep(.tab-content) {
  direction: rtl;
  text-align: right;
}

/* ========================================
   MODERN PRODUCT DETAIL PAGE
======================================== */

.modern-product-page {
  width: 100%;
  min-height: 100%;
  background: #f7f4f2;
}

.modern-product-scroll {
  width: 100%;
  min-height: 100%;
  padding: 0 0 90px;
  background: #f7f4f2;
}

.modern-product-gallery {
  width: 100%;
  background: #f8f5f3;
}

.modern-product-information {
  width: calc(100% - 16px);
  margin: 8px auto 0;
  background: #fff;
  border-radius: 22px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(45, 33, 27, 0.04);
}

.modern-product-pdf {
  padding: 0 16px 20px;

  a {
    font-size: 12px;
    font-weight: 600;
  }
}

.modern-product-reviews {
  margin-top: 8px;
  padding: 18px 16px 24px;
  border-top: 1px solid #eee9e6;

  .modern-section-title {
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 700;
  }
}

.modern-product-loading {
  padding: 16px;
  background: #fff;
}

.modern-product-not-found {
  padding: 40px 16px;
  background: #fff;
}

@media (max-width: 575px) {
  .modern-product-information {
    width: calc(100% - 12px);
    margin-top: 6px;
    border-radius: 18px;
  }
}
</style>
