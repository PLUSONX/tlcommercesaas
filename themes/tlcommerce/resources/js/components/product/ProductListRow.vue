<template>

  <div class="product-list-row" :class="{ 'product-list-row--rtl': isRtl }">

    <div class="product-list-row__thumb">
      <single-product :item="item" thumbnail-only />
    </div>

    <div class="product-list-row__body">
      <h4 class="product-list-row__title" :title="item.name">
        <router-link :to="`/products/${item.slug}`">{{ item.name }}</router-link>
      </h4>

      <p v-if="item.summary" class="product-list-row__summary">
        {{ item.summary }}
      </p>

      <div class="product-list-row__footer">
        <span class="product-list-row__price c1">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
        </span>

        <button v-if="!cartLine" type="button" class="product-list-row__add-btn" :disabled="item.quantity < 1"
          @click.prevent.stop="handleAddClick">
          <span class="product-list-row__add-icon">+</span>
          {{ $t("Add") }}
        </button>

        <div v-else class="quantity-input text-center d-flex product-list-row__quantity" @click.stop>
          <button type="button" class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
            @click.prevent.stop="decreaseQuantity">
            <span class="material-icons"> remove </span>
          </button>

          <input :value="cartLine.quantity" type="number" class="border-0 text-center font-weight-bold w-100"
            @change="onQuantityInputChange" @click.stop />

          <button type="button" class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
            @click.prevent.stop="increaseQuantity">
            <span class="material-icons"> add </span>
          </button>
        </div>
      </div>
    </div>
  </div>



  <teleport to="body">

    <CModal scrollable :visible="visibleQuickView" size="lg" @close="closeQuickView">

      <CModalHeader>

        <button class="btn-circle bg-black size-35" @click="closeQuickView">

          <base-icon-svg name="close" :width="10" :height="10" />

        </button>

      </CModalHeader>



      <CModalBody :class="{ 'quick-view-modal-body--has-footer': showQuickViewCustomFooter }">
        <div class="quick-view-loader" v-show="!galleryReady || !contentReady">
          <div class="loader-spinner"></div>
        </div>

        <div class="row" v-show="galleryReady && contentReady" v-if="visibleQuickView && product.id">
          <div class="col-lg-6 mb-30 mb-lg-0">
            <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
              :product-name="product.name" :url="product.url" :summary="product.summary"
              :networks="product.shareOptions" :key="galleryKey" @ready="galleryReady = true" />
          </div>
          <div class="col-lg-6">
            <details-content v-if="visibleQuickView && product.id" :product="product" :force-show-actions="true"
              @color-variant-images="colorVariantImages" @variant-updating="variantUpdating = $event"
              @quantity-change="orderQuantity = $event" @ready="contentReady = true" :key="product.id" />
          </div>
        </div>
      </CModalBody>



      <custom-footer v-if="showQuickViewCustomFooter" class="quick-view-custom-footer" :item="product"
        :quantity-value="orderQuantity" :disabled="variantUpdating" />

    </CModal>

  </teleport>

</template>



<script>

import SingleProduct from "@/components/product/SingleProduct.vue";

import DetailsGallery from "@/components/product/DetailsGallery.vue";

import DetailsContent from "@/components/product/DetailsContent.vue";

import CustomFooter from "@/components/ui/CustomFooter.vue";

import { mapGetters, mapState } from "vuex";

import { findListItemCartLine } from "@/utils/cartLineMatch";

import { trackSocialPixels } from "@/utils/trackSocialPixels";

import {

  CModal,

  CModalHeader,

  CModalBody,

} from "@coreui/vue";



const axios = require("axios").default;



export default {

  name: "ProductListRow",

  components: {

    SingleProduct,

    DetailsGallery,

    DetailsContent,

    CustomFooter,

    CModal,

    CModalHeader,

    CModalBody,

  },

  props: {

    item: {

      type: Object,

      required: true,

    },

  },

  data() {

    return {

      visibleQuickView: false,

      product: {},

      galleryKey: 0,

      variantUpdating: false,

      orderQuantity: 1,

      galleryReady: false,
      contentReady: false,

      quantityValue:

        this.item.min_qty != null && this.item.min_qty > 0

          ? parseInt(this.item.min_qty)

          : 1,

    };

  },

  watch: {
    visibleQuickView(val) {
      if (val) {
        this.galleryReady = false;
        this.contentReady = false;
      }
    },
  },

  computed: {

    ...mapState({

      cart: (state) => state.cart,

    }),

    ...mapGetters("layout", ["isSplitScreen", "isFeaturePaneLayout", "isMobile", "isRtl"]),

    // ...mapGetters("layout", ["isSplitScreen", "isMobile", "isRtl"]),

    cartLine() {

      return findListItemCartLine(this.cart, this.item);

    },

    maxPurchaseQty() {

      return this.item.max_qty != null &&

        parseInt(this.item.max_qty) > 0 &&

        parseInt(this.item.max_qty) < parseInt(this.item.quantity)

        ? parseInt(this.item.max_qty)

        : this.item.quantity;

    },

    showQuickViewCustomFooter() {

      return this.isFeaturePaneLayout && this.visibleQuickView && !!this.product?.id;

    },

  },

  methods: {

    handleAddClick() {

      if (this.item.has_variant == 2) {

        this.addToCart();

        return;

      }



      this.productQuickView();

    },

    effectiveMaxItem(line) {

      const itemMax = this.maxPurchaseQty;

      const lineMax = parseInt(line?.max_item, 10);

      if (!Number.isNaN(lineMax) && lineMax > 0) {

        return Math.min(lineMax, itemMax);

      }

      return itemMax;

    },

    effectiveMinItem(line) {

      let minItem = parseInt(line.min_item, 10);

      if (!minItem || minItem <= 0 || Number.isNaN(minItem)) {

        minItem = 1;

      }

      const maxItem = this.effectiveMaxItem(line);

      if (!Number.isNaN(maxItem) && minItem >= maxItem) {

        minItem = 1;

      }

      return minItem;

    },

    clampQuantity(rawQty, line) {

      const minItem = this.effectiveMinItem(line);

      let qty = parseInt(rawQty, 10);

      if (!Number.isFinite(qty)) {

        qty = minItem;

      }

      const maxItem = this.effectiveMaxItem(line);

      if (qty > maxItem) {

        qty = maxItem;

      } else if (qty < minItem) {

        qty = minItem;

      }

      return qty;

    },

    syncQuantity(quantity) {

      if (!this.cartLine) {

        return;

      }



      this.$store.dispatch("updateCartLineQuantity", {

        ...this.cartLine,

        quantity,

      });

    },

    decreaseQuantity() {

      const line = this.cartLine;

      if (!line) {

        return;

      }



      const minItem = this.effectiveMinItem(line);

      const currentQty = parseInt(line.quantity, 10);

      if (currentQty > 1 && currentQty > minItem) {

        this.syncQuantity(currentQty - 1);

      } else {

        this.$store.dispatch("removeCartItem", line.uid);

      }

    },

    increaseQuantity() {

      const line = this.cartLine;

      if (!line) {

        return;

      }



      const currentQty = parseInt(line.quantity, 10);

      const maxItem = this.effectiveMaxItem(line);

      if (currentQty > 0 && currentQty < maxItem) {

        this.syncQuantity(currentQty + 1);

      }

    },

    onQuantityInputChange(event) {

      const line = this.cartLine;

      if (!line) {

        return;

      }



      const minItem = this.effectiveMinItem(line);

      const rawQty = parseInt(event.target.value, 10);

      if (!Number.isFinite(rawQty) || rawQty <= 0 || rawQty < minItem) {

        this.$store.dispatch("removeCartItem", line.uid);

        return;

      }



      this.syncQuantity(this.clampQuantity(rawQty, line));

    },

    addToCart() {

      const cart_item = {

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

        max_item: this.maxPurchaseQty,

        min_item: this.item.min_qty,

        seller: this.item.seller,

        shop_name: this.item.shop != null ? this.item.shop.shop_name : null,

        shop_slug: this.item.shop != null ? this.item.shop.shop_slug : null,

      };



      trackSocialPixels("AddToCart", {

        content_id: this.item.id,

        content_name: this.item.name,

        content_type: "product",

        value: this.item.price,

        currency: "KWD",

      });



      this.$store.dispatch("addToCart", cart_item);

    },

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

          } else {

            this.$toast.error(this.$t("Product Loading Failed"));

          }

          this.$store.dispatch("showPreloader", false);

        })

        .catch(() => {

          this.$store.dispatch("showPreloader", false);

          this.$toast.error(this.$t("Product Loading Failed"));

        });

    },

    closeQuickView() {

      this.visibleQuickView = false;

    },

    colorVariantImages(color_id) {

      this.galleryKey = this.galleryKey + 1;

      axios

        .post("/api/v1/ecommerce-core/color-variant-images", {

          product_id: this.product.id,

          color_id: color_id,

        })

        .then((response) => {

          if (response.data.success) {

            this.product.galleryImages = response.data.images;

          }

        })

        .catch(() => { });

    },

  },

};

</script>



<style scoped>
.product-list-row {

  display: flex;

  gap: 12px;

  padding: 12px 0;

  border-bottom: 1px solid #f0f0f0;

}



.product-list-row__thumb {

  flex: 0 0 96px;

  width: 96px;

}



.product-list-row__thumb :deep(.product-list-thumb-only__image) {

  width: 96px;

  height: 96px;

  object-fit: cover;

  border-radius: 12px;

}



.product-list-row__body {

  flex: 1;

  min-width: 0;

  display: flex;

  flex-direction: column;

}



.product-list-row__title {

  margin: 0 0 4px;

  font-size: 16px;

  font-weight: 700;

  line-height: 1.3;

}



.product-list-row__title a {

  color: inherit;

  text-decoration: none;

}



.product-list-row__summary {

  margin: 0 0 8px;

  color: #6b7280;

  font-size: 13px;

  line-height: 1.4;

  display: -webkit-box;

  -webkit-line-clamp: 2;

  -webkit-box-orient: vertical;

  overflow: hidden;

}



.product-list-row__footer {

  margin-top: auto;

  display: flex;

  align-items: center;

  justify-content: flex-end;

  gap: 12px;

}



.product-list-row__price {

  font-weight: 700;

  font-size: 15px;

}



.product-list-row__add-btn {

  display: inline-flex;

  align-items: center;

  gap: 4px;

  padding: 6px 14px;

  border: 1px solid #ff5a1f;

  border-radius: 999px;

  background: #fff;

  color: #ff5a1f;

  font-weight: 600;

  font-size: 14px;

  line-height: 1;

}



.product-list-row__add-btn:disabled {

  opacity: 0.5;

  cursor: not-allowed;

}



.product-list-row__add-icon {

  font-size: 16px;

  line-height: 1;

}



.product-list-row__quantity.quantity-input {

  border: 1px solid #e5e5e5;

  border-radius: 20px;

  overflow: hidden;

  height: 28px;

  align-items: center;

  min-width: 88px;

  max-width: 100px;

  background-color: transparent;

}



.product-list-row__quantity.quantity-input .material-icons {

  font-size: 16px;

  line-height: 1;

}



.product-list-row__quantity.quantity-input button {

  width: 28px;

  height: 28px;

  flex-shrink: 0;

}



.product-list-row__quantity.quantity-input input {

  height: 26px;

  font-size: 13px;

  padding: 0;

  min-width: 0;

  -moz-appearance: textfield;

}



.product-list-row__quantity.quantity-input input::-webkit-outer-spin-button,

.product-list-row__quantity.quantity-input input::-webkit-inner-spin-button {

  -webkit-appearance: none;

  margin: 0;

}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.product-list-row--rtl {
  flex-direction: row-reverse;
}

.product-list-row--rtl .product-list-row__title,
.product-list-row--rtl .product-list-row__summary {
  direction: rtl;
  text-align: right;
}

.product-list-row--rtl .product-list-row__footer {
  flex-direction: row-reverse;
  justify-content: flex-end;
}

.product-list-row--rtl .product-list-row__price {
  direction: rtl;
  unicode-bidi: plaintext;
}

.product-list-row--rtl .product-list-row__add-btn {
  direction: rtl;
}

.product-list-row--rtl .product-list-row__quantity.quantity-input {
  direction: ltr;
}
</style>
