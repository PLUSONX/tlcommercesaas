<template>
  <div class="button-group d-flex align-items-center justify-content-between">
    <button
      type="button"
      class="btn btn_fill btn-xs rounded"
      :disabled="isOutOfStock"
      @click.prevent="placeOrder"
    >
      {{ $t("Place Order") }}
    </button>

    <button
      type="button"
      class="btn btn_borderd btn-xs rounded"
      :disabled="isOutOfStock"
      @click.prevent="addToCart"
    >
      {{ $t("Add To Cart") }}
    </button>
  </div>
</template>

<script>
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
  computed: {
    isOutOfStock() {
      // Basic check: disable if quantity is less than 1 or if item doesn't exist
      return !this.item || this.item.quantity < 1;
    },
    // Centralized cart object to avoid duplication
    cartItemPayload() {
      return {
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
        max_item: this.item.max_qty || 100, // Fallback values
        min_item: this.item.min_qty || 1,
        seller: this.item.seller,
        shop_name: this.item.shop?.shop_name || null,
        shop_slug: this.item.shop?.shop_slug || null,
      };
    }
  },
  methods: {
    trackPixel(eventName) {
      if (window.fbq) {
        window.fbq('track', eventName, {
          content_ids: [this.item.id],
          content_name: this.item.name,
          content_type: 'product',
          value: this.item.price * this.quantityValue,
          currency: 'KD'
        });
      }
    },

    placeOrder() {
      this.trackPixel('InitiateCheckout');
      this.$store.dispatch("addToCart", this.cartItemPayload);
      this.$router.push("/cart");
    },

    addToCart() {
      this.trackPixel('AddToCart');
      this.$store.dispatch("addToCart", this.cartItemPayload);
      // Optional: Add a toast notification here
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
  background: #fff; /* Ensure it's visible over content */
  position: sticky;
  bottom: 0;
}

.btn {
  flex: 1; /* Makes buttons equal width */
  display: flex;
  align-items: center;
  justify-content: center;
  height: 45px; /* Consistent height */
}
</style>