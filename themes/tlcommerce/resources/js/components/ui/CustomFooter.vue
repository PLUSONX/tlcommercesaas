<template>
  <div class="button-group d-flex align-items-center justify-content-between" v-if="item">
    <button type="button" class="btn btn_fill btn-xs rounded" @click.prevent="placeOrder">
      {{ $t("Place Order") }}
    </button>

    <button type="button" class="btn btn_borderd btn-xs rounded" @click.prevent="addToCart">
      {{ $t("Add To Cart") }}
    </button>
  </div>
</template>

<script>
import { trackSocialPixels } from "@/utils/trackSocialPixels";

export default {
  name: "CustomFooter",
  props: {
    item: {
      type: Object,
      required: true,
    },
    // Assuming these come from a parent component (like a quantity selector)
    quantityValue: {
      type: Number,
      default: 1
    }
  },

  methods: {

    /**
     * Place order
     */
    placeOrder() {
      // console.log("-----PlaceOrder method called!!-----");

      // console.log("item: ", this.item);

      let image = "";
      if (this.item.galleryImages[0].type == "image") {
        image = this.item.galleryImages[0].regular;
      } else {
        image = this.item.galleryImages[1].regular;
      }

      let cart_item = {
        uid: Date.now(),
        id: this.item.id,
        name: this.item.name,
        permalink: this.item.permalink,
        image: image,
        // variant: this.product_variant,
        // variant_code: this.product.selectedVariant,
        unitPrice: this.item.price,
        oldPrice: this.item.oldPrice,
        quantity: this.quantityValue ?? this.item.quantity ?? 1,
        attachment: this.attachment ?? null,
        max_item: this.max_qty ?? this.item.quantity ?? 0,
        min_item: this.min_qty ?? this.item.quantity ?? 0,
        seller: this.item.seller,
        shop_name:
          this.item.shopInfo != null ? this.item.shopInfo.name : null,
        shop_slug:
          this.item.shopInfo != null ? this.item.shopInfo.slug : null,
      };

      trackSocialPixels('AddToCart', {
        content_id: this.item.id,
        content_name: this.item.name,
        content_type: 'product',
        value: this.item.price, // Total value for the items added
        currency: 'KWD' // You can pass this as a prop if you have multi-currency
      });


      this.$store.dispatch("addToCart", cart_item);
      this.$router.push("/cart");
    },

    /**
     * Store items to cart
     */
    addToCart() {

      // console.log('addToCart called in CustomFooter Vue');

      // console.log("item: ", this.item);

      let image = "";
      if (this.item.galleryImages[0].type == "image") {
        image = this.item.galleryImages[0].regular;
      } else {
        image = this.item.galleryImages[1].regular;
      }
      let cart_item = {
        uid: Date.now(),
        id: this.item.id,
        name: this.item.name,
        permalink: this.item.permalink,
        image: image,
        // variant: this.product_variant,
        // variant_code: this.product.selectedVariant,
        unitPrice: this.item.price,
        oldPrice: this.item.oldPrice,
        quantity: this.quantityValue ?? this.item.quantity ?? 1,
        attachment: this.attachment ?? null,
        max_item: this.max_qty ?? this.item.quantity ?? 0,
        min_item: this.min_qty ?? this.item.quantity ?? 0,
        seller: this.item.seller,
        shop_name:
          this.item.shopInfo != null ? this.item.shopInfo.name : null,
        shop_slug:
          this.item.shopInfo != null ? this.item.shopInfo.slug : null,
      };

      // console.log("cart_item: ", cart_item);

      trackSocialPixels('AddToCart', {
        content_id: this.item.id,
        content_name: this.item.name,
        content_type: 'product',
        value: this.item.price, // Total value for the items added
        currency: 'KWD' // You can pass this as a prop if you have multi-currency
      });

      this.$store.dispatch("addToCart", cart_item);
    },


  }
};
</script>

<style scoped>
.button-group {
  width: 100%;
  gap: 10px;
  display: flex;
  /* Combines safe area for iPhones and standard padding */
  padding: 10px 10px calc(env(safe-area-inset-bottom, 0px) + 10px) 10px;
  background: #fff;
  /* Ensure it's visible over content */
  position: sticky;
  bottom: 0;
}

.btn {
  flex: 1;
  /* Makes buttons equal width */
  display: flex;
  align-items: center;
  justify-content: center;
  height: 45px;
  /* Consistent height */
}
</style>