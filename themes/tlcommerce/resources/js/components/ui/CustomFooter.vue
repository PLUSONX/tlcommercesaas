<template>
  <div class="button-group d-flex align-items-center justify-content-between" v-if="item">
    <button type="button" class="btn btn_fill btn-xs rounded" :disabled="disabled"
      @click.prevent="placeOrder">
      {{ $t("Place Order") }}
    </button>

    <button type="button" class="btn btn_borderd btn-xs rounded" :disabled="disabled"
      @click.prevent="addToCart">
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
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    product_variant() {
      if (this.item.has_variant == 1 && this.item.selectedVariant) {
        const output = [];
        const variant_array = this.item.selectedVariant.split("/");
        for (let i = 0; i < variant_array.length; i++) {
          let single_variant = variant_array[i];
          let single_variant_array = single_variant.split(":");
          let variant_id = single_variant_array[0];
          let variant_value_id = single_variant_array[1];
          let match_variant = this.item.attribute?.find(
            (attr) => attr.id == variant_id
          );
          if (!match_variant) {
            continue;
          }

          let variant_name = match_variant.title;

          let match_variant_value = match_variant.options.find(
            (opt) => opt.id == variant_value_id
          );
          let variant_value_name = "";
          if (variant_name == "Color" || variant_name == "color") {
            variant_value_name = match_variant_value?.name;
          } else {
            variant_value_name = match_variant_value?.title;
          }

          let variant = variant_name + ":" + variant_value_name;

          output.push(variant);
        }
        let text = output.join("/");
        return text;
      } else {
        return null;
      }
    },
  },

  methods: {

    /**
     * Place order
     */
    placeOrder() {
      if (this.disabled) {
        return;
      }
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
        variant: this.product_variant,
        variant_code: this.item.selectedVariant ?? null,
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
      if (this.disabled) {
        return;
      }

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
        variant: this.product_variant,
        variant_code: this.item.selectedVariant ?? null,
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
