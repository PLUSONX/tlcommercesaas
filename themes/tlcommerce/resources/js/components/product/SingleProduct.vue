<template>
  <!-- Split-screen layout (desktop + mobile) -->
  <template v-if="isSplitScreen">
    <!--Product Page style-->
    <div v-if="styleEight" class="single-product-item single-product--split d-inline-block style--eight">
      <div class="position-relative overflow-hidden">
        <!-- Thumb -->
        <router-link :to="`/products/${item.slug}`" class="d-block">

          <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />

        </router-link>
        <!-- End Thumb -->

        <!-- Action buttons -->
        <router-link :to="`/products/${item.slug}`"
          class="product-action-buttons position-absolute fixed-top w-100 h-100 d-flex align-items-center justify-content-center">


          <!-- Add to Wishlist -->
          <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
            <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
          </button>
          <!-- End Add to Wishlist -->

          <!-- Quick view -->
          <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
            <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
          </button>
          <!-- End Quick view -->
        </router-link>
        <!-- End Action buttons -->
      </div>

      <!-- Summary -->
      <div class="d-flex flex-column justify-content-between product-summary">
        <!-- Rating -->
        <div class="star-rating" v-if="
          site_config?.enable_product_reviews == 1 &&
          site_config?.enable_product_star_rating == 1
        ">
          <div class="product-rating-wrapper">
            <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
          </div>
        </div>
        <!-- End Rating -->

        <!-- <div class="row justify-content-between align-items-baseline"> -->

        <div
          class="row product-title-price-row justify-content-between align-items-baseline d-flex flex-column flex-sm-row">
          <div class="col pe-0 product-title-col">
            <h4 class="product-title">
              <router-link :to="`/products/${item.slug}`" :title="item.name">
                {{ item.name }}
              </router-link>
            </h4>
          </div>

          <div class="col-auto text-start text-sm-end flex-shrink-0 product-price-col">

            <!-- Price -->
            <span class="product-price d-flex flex-wrap c1">
              <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
              <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
              <the-currency :amount="item.base_price" tag="del"
                v-if="!widgetSlider && item.base_price > item.price"></the-currency>
            </span>
            <!-- End Price -->
          </div>

        </div>

        <div class="button-group d-flex align-items-center justify-content-between">

          <button type="button" class="btn btn_fill btn-xs rounded" :disabled="item.quantity < 1"
            @click.prevent="placeOrder">
            {{ $t("Place Order") }}
          </button>

          <button type="button" :disabled="item.quantity < 1" class="btn btn_borderd btn-xs rounded"
            @click.prevent="addToCart">
            {{ $t("Add To Cart") }}
          </button>
        </div>

      </div>
      <!-- End Summary -->
      <!-- Quick View Modal -->
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="
          () => {
            visibleQuickView = false;
          }
        ">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>

          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>

              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
      <!-- End Quick View Modal -->
    </div>
    <!--End Product Page style-->

    <!--Product search style-->
    <div class="single-product-item style--eight d-flex flex-column m-0" v-else-if="small">
      <div class="position-relative overflow-hidden d-flex">
        <!-- Thumb -->
        <router-link :to="`/products/${item.slug}`" class="d-block pr-10">
          <v-lazy-image :src="cleanImage(item.thumbnail_image)" :alt="item.name" class="small-image" />

        </router-link>
        <!-- End Thumb -->
        <!-- Summary -->
        <div class="p-0">
          <!-- Title -->
          <h6 class="product-title m-0">
            <router-link :to="`/products/${item.slug}`">
              {{ item.name }}
            </router-link>
          </h6>
          <!-- End Title -->

          <!-- Price -->
          <span class="product-price">
            <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
            <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
            <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
          </span>
          <!-- End Price -->
        </div>
        <!-- End Summary -->
      </div>
    </div>
    <!--End Product search style-->

    <!--Compare Product search style-->
    <div class="single-product-item style--eight d-flex flex-column m-0" v-else-if="compareSearch">
      <div class="position-relative overflow-hidden d-flex">
        <!-- Thumb -->
        <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />

        <!-- End Thumb -->
        <!-- Summary -->
        <div class="p-0 ml-10">
          <!-- Title -->
          <h6 class="product-title m-0">
            {{ item.name }}
          </h6>
          <!-- End Title -->

          <!-- Price -->
          <span class="product-price">
            <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
            <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
            <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
          </span>
          <!-- End Price -->
        </div>
        <!-- End Summary -->
      </div>
    </div>
    <!--End Compare Product search style-->

    <!-- Wishlist style-->
    <CTableRow v-else-if="wishlistStyle">
      <CTableDataCell>
        <div class="d-flex align-items-center">
          <div class="product-img">
            <router-link :to="`/products/${item.slug}`">
              <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />

            </router-link>
          </div>
          <div>
            <router-link :to="`/products/${item.slug}`">
              <span class="product-name">{{ item.name }}</span>
            </router-link>
          </div>
        </div>
      </CTableDataCell>
      <CTableDataCell>
        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency v-else :amount="item.base_price" tag="span"></the-currency>
          <the-currency v-if="item.base_price > item.price" :amount="item.base_price" tag="del"
            class="ml-10"></the-currency>
        </span>
      </CTableDataCell>
      <CTableDataCell>
        <span class="cart-icon bg-light btn-circle" v-bind:title="$t('Add To Cart')" v-if="item.quantity > 0"
          @click.prevent="handleCartButton">
          <span class="material-icons"> shopping_cart </span>
        </span>
      </CTableDataCell>
      <CTableDataCell class="text-center">
        <span class="remove-icon bg-light btn-circle" title="remove item"
          @click.prevent="removeItemFromWishlist(item.id)">
          <span class="material-icons"> delete </span>
        </span>
      </CTableDataCell>
      <!-- Quick View Modal -->
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="
          () => {
            visibleQuickView = false;
          }
        ">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>

          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>

              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
      <!-- End Quick View Modal -->
    </CTableRow>
    <!-- End Wishlist style-->

    <!--Compare page style-->
    <div class="compare-style-product" v-else-if="compareStyle">
      <button class="btn btn_bordered" v-bind:title="$t('Add To Cart')" :disabled="item.quantity < 1" v-on="item.has_variant == 2
        ? { click: () => addToCart() }
        : { click: () => productQuickView() }
        ">
        {{ $t("Add To Cart") }}
      </button>
      <!-- Quick View Modal -->
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="
          () => {
            visibleQuickView = false;
          }
        ">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>

          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>

              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
      <!-- End Quick View Modal -->
    </div>
    <!--End compare page style-->

    <!--Home page style-->
    <div v-else class="single-product-item single-product--split d-flex flex-column">
      <div class="position-relative overflow-hidden">
        <!-- Thumb -->
        <router-link :to="`/products/${item.slug}`" class="d-block">
          <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />

        </router-link>
        <!-- End Thumb -->

        <!-- Action buttons -->
        <router-link :to="`/products/${item.slug}`"
          class="product-action-buttons position-absolute fixed-top w-100 h-100 d-flex align-items-center justify-content-center">
          <!-- Add to Cart -->
          <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')"
            @click.prevent="handleCartButton">
            <base-icon-svg name="cart" :height="17.5" :width="17.5" />
          </button>
          <!-- End Add to Cart -->

          <!-- Add to Wishlist -->
          <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
            <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
          </button>
          <!-- End Add to Wishlist -->

          <!-- Quick view -->
          <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
            <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
          </button>
          <!-- End Quick view -->
        </router-link>
        <!-- End Action buttons -->

        <!-- Discount -->
        <div v-if="item.discount > 0" class="badge-container">
          <span class="product-badge">
            <span v-if="item.discount.discountType == 1">
              -{{ item.discount }}%
            </span>
            <span v-else>
              -{{ item.discount.discount_amount }}{{ this.currency.symbol }}</span>
          </span>
        </div>
        <!-- End Discount -->
      </div>

      <!-- Summary -->
      <div class="product-summary text-center">
        <!-- Rating -->
        <div class="star-rating mx-auto" v-if="
          site_config?.enable_product_reviews == 1 &&
          site_config?.enable_product_star_rating == 1
        ">
          <div class="product-rating-wrapper">
            <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
          </div>
        </div>
        <!-- End Rating -->

        <!-- Title -->
        <h4 class="product-title">
          <router-link :to="`/products/${item.slug}`" :title="item.name">
            {{ item.name }}
          </router-link>
        </h4>
        <!-- End Title -->

        <!-- Price -->
        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
          <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
        </span>
        <!-- End Price -->
      </div>
      <!-- End Summary -->
      <!-- Quick View Modal -->
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="
          () => {
            visibleQuickView = false;
          }
        ">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>

          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>

              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
      <!-- End Quick View Modal -->
    </div>
    <!--End home page style-->
  </template>

  <!-- Default layout -->
  <template v-else>
    <!--Product Page style-->
    <div v-if="styleEight" class="single-product-item d-inline-block style--eight w-100"
      :class="{ 'single-product--listing': listingGrid }">
      <div class="position-relative overflow-hidden">
        <router-link :to="`/products/${item.slug}`" class="d-block">
          <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />
        </router-link>

        <router-link :to="`/products/${item.slug}`"
          class="product-action-buttons position-absolute fixed-top w-100 h-100 d-flex align-items-center justify-content-center">
          <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')"
            @click.prevent="handleCartButton">
            <base-icon-svg name="cart" :height="17.5" :width="17.5" />
          </button>

          <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
            <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
          </button>

          <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
            <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
          </button>
        </router-link>
      </div>

      <div class="d-flex flex-column justify-content-between product-summary">
        <div class="star-rating" v-if="
          site_config != null &&
          site_config.enable_product_reviews == 1 &&
          site_config.enable_product_star_rating == 1
        ">
          <div class="product-rating-wrapper">
            <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
          </div>
        </div>

        <div
          class="row product-title-price-row justify-content-between align-items-baseline d-flex flex-column flex-sm-row">
          <div class="col pe-0 product-title-col">
            <h4 class="product-title">
              <router-link :to="`/products/${item.slug}`" :title="item.name">
                {{ item.name }}
              </router-link>
            </h4>
          </div>

          <div class="col-auto text-start text-sm-end flex-shrink-0 product-price-col">
            <span class="product-price d-flex flex-wrap c1">
              <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
              <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
              <the-currency :amount="item.base_price" tag="del"
                v-if="!widgetSlider && item.base_price > item.price"></the-currency>
            </span>
          </div>
        </div>

        <div class="button-group d-flex align-items-center justify-content-between">
          <button type="button" class="btn btn_fill btn-xs rounded" :disabled="item.quantity < 1"
            @click.prevent="placeOrder">
            {{ $t("Place Order") }}
          </button>

          <button type="button" :disabled="item.quantity < 1" class="btn btn_borderd btn-xs rounded"
            @click.prevent="addToCart">
            {{ $t("Add To Cart") }}
          </button>
        </div>
      </div>

      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="() => { visibleQuickView = false; }">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>
          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>
              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
    </div>

    <div class="single-product-item style--eight d-flex flex-column m-0" v-else-if="small">
      <div class="position-relative overflow-hidden d-flex">
        <router-link :to="`/products/${item.slug}`" class="d-block pr-10">
          <v-lazy-image :src="cleanImage(item.thumbnail_image)" :alt="item.name" class="small-image" />
        </router-link>
        <div class="p-0">
          <h6 class="product-title m-0">
            <router-link :to="`/products/${item.slug}`">
              {{ item.name }}
            </router-link>
          </h6>
          <span class="product-price">
            <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
            <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
            <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
          </span>
        </div>
      </div>
    </div>

    <div class="single-product-item style--eight d-flex flex-column m-0" v-else-if="compareSearch">
      <div class="position-relative overflow-hidden d-flex">
        <v-lazy-image :src="cleanImage(item.thumbnail_image)" :alt="item.name" class="compare-product-image" />
        <div class="p-0 ml-10">
          <h6 class="product-title m-0">
            {{ item.name }}
          </h6>
          <span class="product-price">
            <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
            <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
            <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
          </span>
        </div>
      </div>
    </div>

    <CTableRow v-else-if="wishlistStyle">
      <CTableDataCell>
        <div class="d-flex align-items-center">
          <div class="product-img">
            <router-link :to="`/products/${item.slug}`">
              <img :src="cleanImage(item.thumbnail_image)" :alt="item.name" class="small-image" />
            </router-link>
          </div>
          <div>
            <router-link :to="`/products/${item.slug}`">
              <span class="product-name">{{ item.name }}</span>
            </router-link>
          </div>
        </div>
      </CTableDataCell>
      <CTableDataCell>
        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency v-else :amount="item.base_price" tag="span"></the-currency>
          <the-currency v-if="item.base_price > item.price" :amount="item.base_price" tag="del"
            class="ml-10"></the-currency>
        </span>
      </CTableDataCell>
      <CTableDataCell>
        <span class="cart-icon bg-light btn-circle" v-bind:title="$t('Add To Cart')" v-if="item.quantity > 0"
          @click.prevent="handleCartButton">
          <span class="material-icons"> shopping_cart </span>
        </span>
      </CTableDataCell>
      <CTableDataCell class="text-center">
        <span class="remove-icon bg-light btn-circle" title="remove item"
          @click.prevent="removeItemFromWishlist(item.id)">
          <span class="material-icons"> delete </span>
        </span>
      </CTableDataCell>
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="() => { visibleQuickView = false; }">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>
          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>
              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
    </CTableRow>

    <div class="compare-style-product" v-else-if="compareStyle">
      <button class="btn btn_bordered" v-bind:title="$t('Add To Cart')" :disabled="item.quantity < 1" v-on="item.has_variant == 2
        ? { click: () => addToCart() }
        : { click: () => productQuickView() }
        ">
        {{ $t("Add To Cart") }}
      </button>
      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="() => { visibleQuickView = false; }">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>
          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>
              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
    </div>

    <div v-else class="single-product-item d-flex flex-column">
      <div class="position-relative overflow-hidden">
        <router-link :to="`/products/${item.slug}`" class="d-block">
          <v-lazy-image class="w-100" :src="cleanImage(item.thumbnail_image)" :alt="item.name" />
        </router-link>

        <router-link :to="`/products/${item.slug}`"
          class="product-action-buttons position-absolute fixed-top w-100 h-100 d-flex align-items-center justify-content-center">
          <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')"
            @click.prevent="handleCartButton">
            <base-icon-svg name="cart" :height="17.5" :width="17.5" />
          </button>

          <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
            <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
          </button>

          <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
            <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
          </button>
        </router-link>

        <div v-if="item.discount > 0" class="badge-container">
          <span class="product-badge">
            <span v-if="item.discount.discountType == 1">
              -{{ item.discount }}%
            </span>
            <span v-else>
              -{{ item.discount.discount_amount }}{{ this.currency.symbol }}</span>
          </span>
        </div>
      </div>

      <div class="product-summary text-center">
        <div class="star-rating mx-auto" v-if="
          site_config != null &&
          site_config.enable_product_reviews == 1 &&
          site_config.enable_product_star_rating == 1
        ">
          <div class="product-rating-wrapper">
            <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
          </div>
        </div>

        <h4 class="product-title">
          <router-link :to="`/products/${item.slug}`">
            {{ item.name }}
          </router-link>
        </h4>

        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
          <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
        </span>
      </div>

      <teleport to="body">
        <CModal scrollable :visible="visibleQuickView" size="lg" @close="() => { visibleQuickView = false; }">
          <CModalHeader>
            <button class="btn-circle bg-black size-35" @click="close()">
              <base-icon-svg name="close" :width="10" :height="10" />
            </button>
          </CModalHeader>
          <CModalBody>
            <div class="row">
              <div class="col-lg-6 mb-30 mb-lg-0">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" />
              </div>
              <div class="col-lg-6">
                <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
              </div>
            </div>
          </CModalBody>
        </CModal>
      </teleport>
    </div>
  </template>
</template>

<script>
import DetailsGallery from "@/components/product/DetailsGallery.vue";
import DetailsContent from "@/components/product/DetailsContent.vue";
import VLazyImage from "v-lazy-image";
const axios = require("axios").default;
import { mapState, mapGetters } from "vuex";
import { trackSocialPixels } from "@/utils/trackSocialPixels";
import {
  CModal,
  CButton,
  CModalHeader,
  CModalTitle,
  CModalBody,
  CModalFooter,
  CTableDataCell,
  CTableHeaderCell,
  CTableHead,
  CTableRow,
} from "@coreui/vue";

export default {
  name: "SingleProduct",
  emits: ["remove-wishlist"],
  components: {
    "v-lazy-image": VLazyImage,
    DetailsGallery,
    DetailsContent,
    CModal,
    CButton,
    CModalHeader,
    CModalBody,
    CModalFooter,
    CModalTitle,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    CTableRow,
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
    styleEight: {
      type: Boolean,
      default: false,
    },
    widgetSlider: {
      type: Boolean,
      default: false,
    },
    small: {
      type: Boolean,
      default: false,
    },
    compareSearch: {
      type: Boolean,
      default: false,
    },
    wishlistStyle: {
      type: Boolean,
      default: false,
    },
    compareStyle: {
      type: Boolean,
      default: false,
    },
    listingGrid: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      quantityValue:
        this.item.min_qty != null && this.item.min_qty > 0
          ? parseInt(this.item.min_qty)
          : 1,
      visibleQuickView: false,
      product: {},
      galleryKey: 0,
    };
  },
  computed: {
    ...mapState({
      customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
      site_config: (state) => state.siteSettings,
      currency: (state) => state.currency,
    }),
    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),
    min_qty() {
      return this.item.min_qty != null && parseInt(this.item.min_qty) > 0
        ? parseInt(this.item.min_qty)
        : 1;
    },
    max_qty() {
      return this.item.max_qty != null &&
        parseInt(this.item.max_qty) > 0 &&
        parseInt(this.item.max_qty) < parseInt(this.item.quantity)
        ? parseInt(this.item.max_qty)
        : this.item.quantity;
    },
  },
  mounted() {
    trackSocialPixels('ViewContent', {
      content_ids: [this.item.id],
      content_name: this.item.name,
      content_type: 'product',
      value: this.item.price,
      currency: 'KD'
    });
  },
  methods: {

    cleanImage(img) {
      if (!img) return '';

      // Use 'let' to declare the variable
      let image_url = img;

      image_url = image_url.replace(/^\/public/, '');

      // console.log("image_url_vue: ", image_url);

      return img.replace(/^\/public/, '');
    },

    /**
         * Place order
         */
    placeOrder() {
      // console.log("-----PlaceOrder method called!!-----");

      // console.log("item: ", this.item);
      let image = this.item.thumbnail_image;
      // if (this.item?.galleryImages[0]?.type == "image") {
      //   image = this.item?.galleryImages[0]?.regular;
      // } else {
      //   image = this.item?.galleryImages[1]?.regular;
      // }
      // let cart_item = {
      //   uid: Date.now(),
      //   id: this.item.id,
      //   name: this.item.name,
      //   permalink: this.item.permalink,
      //   image: image,
      //   variant: this.product_variant,
      //   variant_code: this.item.selectedVariant,
      //   unitPrice: this.item.price,
      //   oldPrice: this.item.oldPrice,
      //   quantity: this.quantityValue,
      //   attachment: this.attachment,
      //   max_item: this.max_qty,
      //   min_item: this.min_qty,
      //   seller: this.item.seller,
      //   shop_name:
      //     this.item.shopInfo != null ? this.item.shopInfo.name : null,
      //   shop_slug:
      //     this.item.shopInfo != null ? this.item.shopInfo.slug : null,
      // };

      let cart_item = {
        uid: Date.now(),
        id: this.item.id,
        name: this.item.name,
        permalink: this.item.slug,
        image: this.item.thumbnail_image,
        variant: null,
        variant_code: null,
        unitPrice: this.item.price,
        oldPrice: this.item.base_price,
        attachment: null,
        quantity: this.quantityValue,
        max_item: this.max_qty,
        min_item: this.min_qty,
        seller: this.item.seller,
        shop_name: this.item.shop != null ? this.item.shop.shop_name : null,
        shop_slug: this.item.shop != null ? this.item.shop.shop_slug : null,
      };

      trackSocialPixels('AddToCart', {
        content_ids: this.item.id,
        content_name: this.item.name,
        content_type: 'product',
        value: this.item.price,
        currency: 'KWD'
      });
      // console.log("cart-item: ", cart_item);

      this.$store.dispatch("addToCart", cart_item);
      this.$router.push("/cart");
    },

    handleCartButton(event) {

      // console.log('handleCartButton called');

      // console.log('itemHasVariant: ', this.item.has_variant);

      event.preventDefault();

      if (this.item.has_variant == 2) {
        this.addToCart();
      } else {
        this.productQuickView();
      }
    },
    /**
     * Product quick view
     */
    productQuickView() {
      this.$store.dispatch("showPreloader", true);
      axios
        .post("/api/v1/ecommerce-core/product-details", {
          permalink: this.item.slug,
        })
        .then((response) => {
          if (response.data.success) {
            this.product = response.data.data;
            this.visibleQuickView = true;
            this.$store.dispatch("showPreloader", false);
          } else {
            this.$store.dispatch("showPreloader", false);
            this.$toast.error(this.$t("Product Loading Failed"));
          }
        })
        .catch((error) => {
          this.$store.dispatch("showPreloader", false);
          this.$toast.error(this.$t("Product Loading Failed"));
        });
    },
    /**
     * Color variant gallery images
     */
    colorVariantImages(color_id) {
      this.$store.dispatch("showPreloader", true);
      this.galleryKey = this.galleryKey + 1;
      axios
        .post("/api/v1/ecommerce-core/color-variant-images", {
          product_id: this.product.id,
          color_id: color_id,
        })
        .then((response) => {
          if (response.data.success) {
            this.product.galleryImages = response.data.images;
            this.$store.dispatch("showPreloader", false);
          } else {
            this.$store.dispatch("showPreloader", false);
          }
        })
        .catch((error) => {
          this.$store.dispatch("showPreloader", false);
        });
    },
    /**
     * Store items to cart
     */
    addToCart() {
      // console.log('addToCart called');
      let cart_item = {
        uid: Date.now(),
        id: this.item.id,
        name: this.item.name,
        permalink: this.item.slug,
        image: this.item.thumbnail_image,
        variant: null,
        variant_code: null,
        unitPrice: this.item.price,
        oldPrice: this.item.base_price,
        attachment: null,
        quantity: this.quantityValue,
        max_item: this.max_qty,
        min_item: this.min_qty,
        seller: this.item.seller,
        shop_name: this.item.shop != null ? this.item.shop.shop_name : null,
        shop_slug: this.item.shop != null ? this.item.shop.shop_slug : null,
      };

      trackSocialPixels('AddToCart', {
        content_id: this.item.id,
        content_name: this.item.name,
        content_type: 'product',
        value: this.item.price, // Total value for the items added
        currency: 'KWD' // You can pass this as a prop if you have multi-currency
      });

      this.$store.dispatch("addToCart", cart_item);
    },
    /**
     * Add to wishlist
     */
    addToWishlist() {
      // console.log('addToWishlist called');
      if (this.isCustomerLogin) {
        axios
          .post(
            "/api/v1/ecommerce-core/customer/store-product-to-wishlist",
            {
              product_id: this.item.id,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.$store.dispatch("refreshCustomerDashboardInfo");
              this.$toast.success(
                this.$t("Product added to wishlist successfully")
              );
            } else {
              this.$toast.error(this.$t("Product add to wishlist failed"));
            }
          })
          .catch((error) => {
            this.$toast.error(this.$t("Product add to wishlist failed"));
          });
      } else {
        this.$toast.error("Please login");
        this.$router.push("/login");
      }
    },
    /**
     *Remove product from wishlist
     */
    removeItemFromWishlist(product_id) {
      if (this.isCustomerLogin) {
        axios
          .post(
            "/api/v1/ecommerce-core/customer/product-remove-from-wishlist",
            {
              product_id: product_id,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.$emit("remove-wishlist");
              this.$store.dispatch("refreshCustomerDashboardInfo");
              this.$toast.success(
                this.$t("Product remove from wishlist successfully")
              );
            } else {
              this.$toast.error(this.$t("Product remove from wishlist failed"));
            }
          })
          .catch((error) => {
            this.$toast.error(this.$t("Product remove from wishlist failed"));
          });
      } else {
        // console.log('addToWishlist called');
        this.$toast.error("addToWishlist called");
        // this.$toast.error("Please login");
        this.$router.push("/login");
      }
    },
    close() {
      this.visibleQuickView = false;
    },


  },
};
</script>
<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.fixed-top {
  z-index: 99 !important;
}

/* --- Default layout (original) --- */
.single-product-item {
  background-color: #f7f8fa;
  margin-bottom: 30px;

  .badge-container {
    position: absolute;
    right: 0;
    top: 0;
    width: 90px;
    height: 90px;

    .product-badge {
      position: absolute;
      width: calc(100% * 2);
      left: -30%;
      transform: rotate(45deg);
      top: 5px;
      padding: 9px 10px;
      font-weight: 500;
      color: #fff;
      background-color: $c1;
      text-align: center;
      font-size: 13px;
    }
  }

  .product-action-buttons {
    transition: 0.2s ease-in;
    opacity: 0;
    visibility: hidden;

    >button {
      width: 36px;
      min-width: 36px;
      height: 36px;
      transform: scale(0.8);

      &:nth-child(2) {
        transition-delay: 0.05s;
      }

      &:nth-child(3) {
        transition-delay: 0.1s;
      }

      &:not(:last-child) {
        margin-right: 5px;
      }
    }
  }

  .product-price {
    font-size: 16px;
    font-weight: 700;
    color: $c1;

    del {
      font-weight: 700;
      font-size: 12px;
      margin-left: 12px;
      color: $text-color-light;
    }

    @media (min-width: 479px) {
      font-size: 15px;

      del {
        font-size: 14px;
      }
    }
  }

  .product-title {
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    display: -webkit-box;
    font-size: 15px;
    font-weight: 500;
    height: 40px;
    line-height: 1.4;
    margin: 8px 0;
    overflow: hidden;

    @media (max-width: 479px) {
      font-size: 15px;
    }
  }

  .product-summary {
    padding: 20px;
    padding-top: 24px;
    min-height: 134px;

    @media (max-width: 479px) {
      padding: 14px;
      padding-top: 18px;
    }
  }

  &:hover {
    .product-action-buttons {
      opacity: 1;
      visibility: visible;

      >button {
        transform: scale(1);
      }

      @media (max-width: 479px) {
        display: none !important;
      }
    }
  }

  &.style--eight {
    box-shadow: 3px 3px 30px rgba(0, 0, 0, 0.03);
    background-color: #fff;

    .product-title {
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 1;
      display: -webkit-box;
      overflow: hidden;
      text-overflow: ellipsis;
      height: auto;
      white-space: normal;
      word-break: break-word;
      font-weight: 500;
      margin: 8px 0 8px;
    }

    .product-price {
      font-size: 15px;
      font-weight: 900;
      color: #363232;
    }

    .text-rating {
      .rating {
        font-size: 12px;
      }
    }

    &:hover {
      box-shadow: 5px 5px 60px rgba(0, 0, 0, 0.05);
    }
  }

  &--two {
    .product-thumb {
      width: 160px;
      height: 160px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }

    &.small {
      .product-thumb {
        width: 100px;
        height: 100px;
      }

      p {
        font-size: 14px;
      }
    }

    .old-price {
      position: relative;
      color: $title-color;
      font-size: 16px;
      font-weight: $medium;
      margin-right: 13px;

      &::after {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        height: 1px;
        background-color: $c1;
        content: "";
      }
    }

    .new-price {
      font-size: 13px;
      font-weight: $medium;
      background-color: $c1;
      color: $white;
      padding: 4px 6px;
      border-radius: 50px;
    }
  }
}

/* --- Split-screen layout (production) --- */
.single-product--split.single-product-item {
  margin-bottom: 0;
  border-radius: 12px;

  .badge-container {
    width: 70px;
    height: 70px;
  }

  .product-price {
    line-height: 1;
  }

  .product-title {
    font-size: 14px;
    height: auto;
    line-height: 1.35;
    margin: 8px 0 4px;
    text-transform: uppercase;

    a {
      color: inherit;
      text-decoration: none;
    }

    @media (max-width: 767px) {
      font-size: 12px;
      line-height: 1.3;
      margin: 0;
    }

    @media (max-width: 479px) {
      font-size: 12px;
      padding: 0;
    }
  }

  .product-title-price-row {
    flex-direction: column !important;
    align-items: stretch !important;
    margin-bottom: 0;
    margin-left: 0 !important;
    margin-right: 0 !important;
    --bs-gutter-x: 0;
    width: 100%;

    .product-title-col,
    .product-price-col {
      width: 100%;
      max-width: 100%;
      flex: 0 0 auto;
      padding-left: 0;
      padding-right: 0;
    }

    .product-price-col {
      text-align: start !important;
      margin-top: 2px;
    }

    .product-title {
      margin-bottom: 4px;
    }

    .product-price {
      margin-top: 0;
      line-height: 1.15;
      flex-wrap: wrap;
      gap: 2px 6px;
    }
  }

  .product-summary {
    padding: 10px;
    min-height: 134px;

    @media (min-width: 480px) and (max-width: 501px) {
      padding-left: 15% !important;
    }

    @media (max-width: 767px) {
      .product-title {
        margin-bottom: 0;
      }

      .product-price {
        margin-top: 0;
        line-height: 1.15;
      }
    }

    @media (max-width: 479px) {
      padding: 12px 8px 14px;
      min-height: auto;
    }
  }

  &.style--eight {
    .product-title {
      margin: 6px 0 2px;

      @media (max-width: 767px) {
        margin: 0;
      }
    }

    .product-summary .product-price {
      @media (max-width: 767px) {
        margin-top: 0;
      }
    }
  }
}

/* Shared utilities */
.small-image {
  width: 65px;
}

.star-rating {
  font-size: 18px !important;
}

.compare-product-image {
  width: 60px !important;
  height: 60px !important;
  min-width: 60px;
}

/* Place Order / Add To Cart buttons — default styleEight listings */
.style--eight {
  .button-group {
    width: 100%;
    gap: 0.5em;
  }

  .btn-xs {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    padding: 0.75em 0.5em;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
    -webkit-text-size-adjust: 100%;
    text-size-adjust: 100%;
  }

  @media (max-width: 768px) {
    .btn-xs {
      font-size: 12px !important;
    }
  }
}

/* Split-screen compact button scaling */
.single-product--split {
  .button-group {
    width: 100%;
    gap: 0.5em;
  }

  .btn-xs {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    padding: 1em 1em;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
    -webkit-text-size-adjust: 100%;
    text-size-adjust: 100%;

    @media (max-width: 1250px) and (min-width: 920px) {
      font-size: 0.60rem;
    }

    @media (max-width: 919px) and (min-width: 768px) {
      font-size: 0.45rem;
    }
  }

  @media (max-width: 768px) {
    .btn-xs {
      font-size: 12px !important;
    }
  }

  @media (max-width: 500px) {
    .button-group {
      width: 100% !important;
      gap: 0.35em;
      flex-wrap: wrap;
    }

    .btn-xs {
      font-size: 12px !important;
      padding: 0.65em 0.5em;
      white-space: normal;
      min-width: 0;
    }

    .row.flex-column {
      flex-direction: column !important;
      align-items: flex-start !important;

      .col-auto {
        max-width: 100% !important;
      }
    }
  }
}

/* Products index listing grid (default layout, listingGrid prop) */
.single-product--listing.style--eight {
  container-type: inline-size;
  container-name: productListingCard;
  display: flex;
  flex-direction: column;
  width: 100%;
  box-sizing: border-box;

  .product-summary {
    box-sizing: border-box;
  }

  .button-group {
    width: 100%;
    gap: 0.5em;
    min-width: 0;
  }

  .btn-xs {
    flex: 1 1 0;
    min-width: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    padding: 0.65em 0.4em;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
    -webkit-text-size-adjust: 100%;
    text-size-adjust: 100%;

    @media (max-width: 1400px) and (min-width: 1200px) {
      font-size: 0.65rem;
    }

    @media (max-width: 1200px) and (min-width: 992px) {
      font-size: 0.50rem;
    }

    // @media (max-width: 1250px) and (min-width: 920px) {
    //   font-size: 0.60rem;
    // }

    @media (max-width: 919px) and (min-width: 768px) {
      font-size: 0.45rem;
    }
  }

  @container productListingCard (max-width: 340px) {
    .btn-xs {
      font-size: 11px;
      padding: 0.55em 0.35em;
      white-space: normal;
      line-height: 1.15;
    }
  }

  @container productListingCard (max-width: 300px) {
    .btn-xs {
      font-size: 10px;
      padding: 0.5em 0.3em;
    }
  }

  @container productListingCard (max-width: 260px) {
    .btn-xs {
      font-size: 9px;
      padding: 0.45em 0.25em;
    }
  }

  .product-title-price-row {
    flex-direction: column !important;
    align-items: stretch !important;
    margin-bottom: 0;
    margin-left: 0 !important;
    margin-right: 0 !important;
    --bs-gutter-x: 0;
    width: 100%;

    .product-title-col,
    .product-price-col {
      width: 100%;
      max-width: 100%;
      flex: 0 0 auto;
      padding-left: 0;
      padding-right: 0;
    }

    .product-price-col {
      text-align: start !important;
      margin-top: 2px;
    }

    .product-title {
      margin-bottom: 4px;
    }

    .product-price {
      margin-top: 0;
      line-height: 1.15;
      flex-wrap: wrap;
      gap: 2px 6px;
    }
  }

  @media (min-width: 992px) and (max-width: 1800px) {
    .btn-xs {
      font-size: 12px;
      padding: 0.6em 0.35em;
      white-space: normal;
      min-width: 0;
    }
  }

  @media (max-width: 767px) {
    .button-group {
      gap: 0.35em;
      flex-wrap: wrap;
    }

    .btn-xs {
      font-size: 11px !important;
      padding: 0.55em 0.4em;
      white-space: normal;
      min-width: 0;
      line-height: 1.15;
    }
  }

  @media (max-width: 500px) {
    .button-group {
      width: 100% !important;
      gap: 0.35em;
      flex-wrap: wrap;
    }

    .btn-xs {
      font-size: 8px !important;
      padding: 0.65em 0.5em;
      white-space: normal;
      min-width: 0;
    }

    .row.flex-column {
      flex-direction: column !important;
      align-items: flex-start !important;

      .col-auto {
        max-width: 100% !important;
      }
    }
  }
}
</style>
