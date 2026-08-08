<template>
  <div :class="{
    'force-mobile-layout': forcedMobile,
    'mobile-content-wrapper': forcedMobile,
    'cart-step--rtl': isRtl
  }">
    <div class="row" v-if="!dataLoading">
      <!--Cart Table-->
      <div class="col-12" v-if="tableData.length">
        <div class="shadow-card">
          <h3 class="checkout-title">{{ $t("Your Cart") }}</h3>
          <!--Items List-->
          <div class="row m-0">
            <div class="cart-step__toolbar align-items-center border-bottom col-12 d-flex justify-content-between pb-10 px-0">
              <div class="d-flex gap-2 selector">
                <input type="checkbox" class="item-selector text-black" v-model="all_selected" :checked="all_selected"
                  @click="selectOrDeselectItems" id="all-selector" />
                <label class="fz-12 pt-1 text-black" for="all-selector">
                  {{ $t("SELECT ALL") }}
                </label>
              </div>
              <div class="delete-all">
                <router-link to="#" class="d-flex" @click="clearCart">
                  <span class="material-icons"> delete </span>
                  <span class="fz-12">{{ $t("DELETE ALL") }}</span>
                </router-link>
              </div>
            </div>

            <div class="col-12 px-0 single-package" v-for="tdata in tableData" :key="tdata.uid"
              :class="{
                disableCart: tdata.is_available == 2,
              }">
              <div class="cart-item d-flex align-items-center gap-2">
                <input type="checkbox" v-model="tdata.is_selected" :checked="tdata.is_selected" class="item-selector"
                  @change="onItemSelectionChange" />
                <router-link :to="`/products/${tdata.permalink}`" class="cart-item__img">
                  <img :src="tdata.image" :alt="tdata.name" class="cart-image-review" />
                </router-link>
                <div class="cart-item__body d-flex flex-column flex-grow-1 min-w-0">
                  <router-link :to="`/products/${tdata.permalink}`">
                    <h5 class="product_name text-capitalize mb-0">
                      {{ tdata.name }}
                    </h5>
                  </router-link>
                  <p class="product-variant mb-0 fz-sm-12" v-if="tdata.variant">
                    <product-variant :variant="tdata.variant"></product-variant>
                  </p>
                  <p class="cart-item__price mb-0">
                    <the-currency :amount="tdata.unitPrice * tdata.quantity">
                    </the-currency>
                  </p>
                  <div class="extra-addons-wrap d-flex flex-wrap" v-if="tdata.shop_name != null && tdata.shop_slug">
                    <!-- <p class="product-shop fz-12">
                          {{ $t("Sold By") }}
                          <router-link
                            :to="`/shop/${tdata.shop_slug}`"
                            target="_blank"
                            class="c1"
                          >
                            {{ tdata.shop_name }}
                          </router-link>
                        </p> -->
                  </div>
                  <!--Attachment-->
                  <div class="extra-addons-wrap d-flex flex-wrap" v-if="tdata.attachment != null">
                    <div class="product-document">
                      <p class="font-weight-medium fz-12 mb-0">
                        {{ $t("Attachment :") }}
                        {{ tdata.attachment.file_name }}
                      </p>
                    </div>
                  </div>
                  <!--End Attachment-->
                  <div class="cart-item__actions d-flex align-items-center justify-content-between">
                    <!--Quantity-->
                    <div class="quantity-input text-center d-flex">
                      <button class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
                        @click.prevent="decrease(tdata.uid)">
                        <span class="material-icons"> remove </span>
                      </button>
                      <input v-model="tdata.quantity" type="number"
                        class="border-0 text-center font-weight-bold w-100"
                        @change="clampAndUpdateQuantity(tdata)" />
                      <button class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
                        @click.prevent="increase(tdata.uid)">
                        <span class="material-icons"> add </span>
                      </button>
                    </div>
                    <!--End Quantity-->
                    <!--Remove btn-->
                    <span class="cart-item__delete icon-wrap" @click.prevent="removeItem(tdata.uid)">
                      <span class="material-icons"> delete </span>
                    </span>
                    <!--End remove btn-->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--End Items-->
          <!--Minimum order amount alert-->
          <div class="col-12 d-lg-block d-lg-flex d-none flex-wrap my-2">
            <p class="alert alert-danger text-center w-100" v-if="
              !proceedToCheckout &&
              config?.enable_minumun_order_amount == 1
            ">
              {{ $t("Minimum Order Amount") }}
              <the-currency :amount="config.min_order_amount" tag="span"></the-currency>
            </p>
          </div>
          <!--End minimum order amount alert-->
          <!--Action Buttons-->
          <!-- <div class="mt-3 col-12 checkout-cta-stack">
                <router-link to="/products"
                  class="btn btn_border checkout-cta-secondary w-100 justify-content-center">
                  <span class="material-icons me-2"> arrow_back </span>
                  {{ $t("Continue Shopping") }}
                </router-link> -->
          <!-- Checkout removed: Details/Payment unlock when cart items sync -->
          <!-- <button type="button"
                  class="btn btn_fill checkout-cta-primary w-100 justify-content-center"
                  @click.prevent="createOrder" :disabled="!proceedToCheckout">
                  {{ $t("Checkout") }}
                  <span class="material-icons ms-2"> arrow_forward </span>
                </button> -->
          <!-- </div> -->
          <!--End Action Buttons-->
        </div>
      </div>
      <!--End Cart Table-->

      <!--No Items found-->
      <div class="col-12" v-if="!tableData.length" style="margin-top: 10px;">
        <div class="alert alert-danger">
          <p class="d-flex align-items-center justify-content-between">
            {{ $t("The Cart is Empty") }}
            <router-link to="/products" class="ml-4 font-weight-medium btn_underline">
              {{ $t("Back to Products") }}
            </router-link>
          </p>
        </div>
      </div>
      <!--No Items Found-->
    </div>

    <!--Preloader-->
    <div class="row" v-if="dataLoading">
      <div class="col-12">
        <skeleton class="w-100 mb-20" height="350px"></skeleton>
        <div class="mt-3 d-flex flex-wrap justify-content-between">
          <skeleton class="justify-content-center m-w-100 mb-20" tag="a" height="40px" width="150px"></skeleton>
          <skeleton class="justify-content-center m-w-100 mb-20" tag="a" height="40px" width="150px"></skeleton>
        </div>
      </div>
    </div>
    <!--End Preloader-->
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import OrderTotal from "@/components/product/OrderTotal.vue";
import enums from "../../enums/enums";
import ProductVariant from "@/components/ui/ProductVariant.vue";
import { mapState, mapGetters } from "vuex";
import axios from "axios";
import {
  CTable,
  CTableHead,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
} from "@coreui/vue";
export default {
  name: "CartStep",
  emits: ["next-step", "checkout-items-synced"],
  components: {
    ProductVariant,
    PageHeader,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    OrderTotal,
  },
  data() {
    return {
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Cart"),
          active: true,
        },
      ],
      proceedToCheckout: false,
      dataLoading: true,
      enums: enums,
      errors: [],
      tableData: [],
      coupon_code: "",
      couponApplying: false,
      checkoutItems: [],
      all_selected: true,
    };
  },

  computed: {
    ...mapState({
      customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
      customer_id: (state) => state.customerInfo != null ? state.customerInfo.id : null,
      config: (state) => state.siteSettings,
      couponDiscounts: (state) => state.couponDiscount ? state.couponDiscount : [],
    }),

    ...mapGetters('layout', ['isSplitScreen', 'isMobile', 'isRtl']),

    forcedMobile() {
      if (this.isSplitScreen && !this.isMobile) {
        return true;
      }
      return this.isMobile;
    },

    totalUnitPrice() {
      return this.tableData.reduce((accum, item) => {
        if (item.is_available == 2 || !item.is_selected) {
          return parseFloat(accum);
        }
        return parseFloat(accum) + parseFloat(item.unitPrice * item.quantity);
      }, 0.0);
    },

    totalDiscount() {
      return this.couponDiscounts.reduce((accum, item) => {
        return parseFloat(accum) + parseFloat(item.discount);
      }, 0.0);
    },

    enableApplyCoupon() {
      if (
        this.config?.is_active_coupon == this.enums.status.ACTIVE &&
        this.config?.enable_coupon_in_checkout == this.enums.status.ACTIVE
      ) {
        if (
          this.couponDiscounts.length > 0 &&
          this.config?.enable_multiple_coupon_in_checkout == this.enums.status.IN_ACTIVE
        ) {
          return false;
        } else if (
          this.config?.enable_coupon_in_checkout == this.enums.status.IN_ACTIVE
        ) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    },


  },
  // computed: {
  //    ...mapState({
  //   customerToken: (state) => state.customerToken,
  //   isCustomerLogin: (state) => state.isCustomerLogin,
  //   customer_id: (state) =>
  //     state.customerInfo != null ? state.customerInfo.id : null,
  //   config: (state) => state.siteSettings,
  //   couponDiscounts: (state) =>
  //     state.couponDiscount ? state.couponDiscount : [],
  //   totalUnitPrice() {
  //     return this.tableData.reduce((accum, item) => {
  //       if (item.is_available == 2 || !item.is_selected) {
  //         return parseFloat(accum);
  //       }

  //       return parseFloat(accum) + parseFloat(item.unitPrice * item.quantity);
  //     }, 0.0);
  //   },
  //   totalDiscount() {
  //     return this.couponDiscounts.reduce((accum, item) => {
  //       return parseFloat(accum) + parseFloat(item.discount);
  //     }, 0.0);
  //   },
  //   enableApplyCoupon() {
  //     if (
  //       this.config?.is_active_coupon == this.enums.status.ACTIVE &&
  //       this.config?.enable_coupon_in_checkout == this.enums.status.ACTIVE
  //     ) {
  //       if (
  //         this.couponDiscounts.length > 0 &&
  //         this.config?.enable_multiple_coupon_in_checkout ==
  //           this.enums.status.IN_ACTIVE
  //       ) {
  //         return false;
  //       } else if (
  //         this.config?.enable_coupon_in_checkout == this.enums.status.IN_ACTIVE
  //       ) {
  //         return false;
  //       } else {
  //         return true;
  //       }
  //     } else {
  //       return false;
  //     }
  //   },
  // }),

  // ...mapGetters('layout', {
  //       storeIsMobile: 'isMobile',        // Renamed to avoid collision
  //       storeIsSplitScreen: 'isSplitScreen'
  //   }),

  // isMobile() {

  //     console.log("Checking mobile status in Cart index.vue");

  //     // 1. Check Injection (from Layout)
  //     if (this.forceMobileView) return true;

  //     // 2. Check Vuex (via the renamed getter)
  //     if (this.storeIsMobile || this.storeIsSplitScreen) return true;

  //     // 3. Final fallback
  //     return window.innerWidth < 768;
  //   },



  // }

  mounted() {
    this.validateCartItems();
  },
  watch: {
    totalUnitPrice() {
      this.checkMinimumOrderAmount();
      this.syncCheckoutItems();
    },
  },
  methods: {
    //Select or deselect sector button
    selectOrDeselectButton() {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (!item.is_selected) {
          this.all_selected = false;
          break;
        }
        this.all_selected = true;
      }
    },
    onItemSelectionChange() {
      this.selectOrDeselectButton();
      this.syncCheckoutItems();
    },
    selectOrDeselectItems() {
      this.all_selected = !this.all_selected;
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (this.all_selected && item.is_available == 1) {
          item.is_selected = true;
        } else {
          item.is_selected = false;
        }
      }
      this.syncCheckoutItems();
    },

    /**
     * Will clear cart
     */
    clearCart() {
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        axios
          .post(
            "/api/v1/ecommerce-core/customer/cart/remove-item",
            {
              uid: "all",
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            this.$store.dispatch("showPreloader", false);
            if (response.data.success) {
              this.tableData = [];
              this.$store.dispatch("flushCustomerCart").then(() => {
                this.$toast.success(this.$t("Cart clear successfully"));
              });
            }
          })
          .catch((error) => {
            this.$store.dispatch("showPreloader", false);
          });
      } else {
        this.tableData = [];
        this.$store.dispatch("flushCustomerCart").then(() => {
          this.$toast.success(this.$t("Cart clear successfully"));
        });
      }
    },
    /**
     * Validate cart items
     */
    validateCartItems() {
      axios
        .post("/api/v1/ecommerce-core/cart/validate-cart-items", {
          items: JSON.stringify(this.$store.state.cart),
        })
        .then((response) => {
          if (response.data.success) {
            //this.tableData = response.data.items;
            this.tableData = response.data.items.map(item => {
              return {
                ...item,
                image: item.image.replace(/^\/public/, '') // remove leading /public
              };
            });
            this.checkMinimumOrderAmount();
            this.syncCheckoutItems();
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
        });
    },
    /**
     *
     */
    checkMinimumOrderAmount() {
      if (this.config?.enable_minumun_order_amount == this.enums.status.ACTIVE) {
        if (this.totalUnitPrice < this.config.min_order_amount) {
          this.proceedToCheckout = false;
        } else {
          this.proceedToCheckout = true;
        }
      } else {
        this.proceedToCheckout = true;
      }
    },
    nextStep() {
      this.$emit("go-next-step");
    },
    /**
     * Resolve a usable min purchase qty for cart rows.
     * Missing/invalid min_item, or min_item >= max_item (CustomFooter bug), fall back to 1.
     */
    effectiveMinItem(item) {
      let minItem = parseInt(item.min_item);
      if (!minItem || minItem <= 0 || isNaN(minItem)) {
        minItem = 1;
      }
      const maxItem = parseInt(item.max_item);
      if (!isNaN(maxItem) && minItem >= maxItem) {
        minItem = 1;
      }
      return minItem;
    },
    clampAndUpdateQuantity(tdata) {
      const minItem = this.effectiveMinItem(tdata);
      const qty = parseInt(tdata.quantity);
      if (!Number.isFinite(qty) || qty <= 0 || qty < minItem) {
        this.removeItem(tdata.uid);
        return;
      }
      if (qty > tdata.max_item) {
        tdata.quantity = tdata.max_item;
      } else {
        tdata.quantity = qty;
      }
      this.updateCartItems(tdata);
    },
    /**
     * Decrease item number from cart
     *
     * @param {*} id
     */
    decrease(id) {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.uid === id) {
          const minItem = this.effectiveMinItem(item);
          if (item.quantity > 1 && item.quantity > minItem) {
            item.quantity--;
            this.updateCartItems(item);
          } else {
            this.removeItem(id);
          }
        }
      }
    },
    /**
     * Increase item number from cart
     *
     * @param {*} id
     */
    increase(id) {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.uid === id) {
          if (item.quantity > 0 && item.quantity < item.max_item) {
            item.quantity++;
            this.updateCartItems(item);
          } else {
            return;
          }
        }
      }
    },
    /**
     * This method will update cart
     */
    updateCartItems(item) {
      //For authenticate customer
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        axios
          .post(
            "/api/v1/ecommerce-core/customer/cart/update-cart-item",
            {
              item: JSON.stringify(item),
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            this.$store.dispatch("showPreloader", false);
            if (response.data.success) {
              this.$store.dispatch("updateCart", this.tableData);
              this.$store.dispatch("flushCouponData");
              this.syncCheckoutItems();
            } else {
              this.$store.dispatch("updateCart", []);
              this.$store.dispatch("flushCouponData");
              this.syncCheckoutItems();
            }
          })
          .catch((error) => {
            this.$store.dispatch("showPreloader", false);
            this.$store.dispatch("updateCart", []);
            this.$store.dispatch("flushCouponData");
            this.syncCheckoutItems();
          });
      }
      //For guest customer
      if (!this.isCustomerLogin) {
        this.$store.dispatch("updateCart", this.tableData);
        this.$store.dispatch("flushCouponData");
        this.syncCheckoutItems();
      }
    },

    /**
     * Remove item from cart
     *
     * @param {*} index
     */
    removeItem(index) {
      let updatedTableData = this.tableData.filter(
        (item) => item.uid !== index
      );
      this.tableData = updatedTableData;
      //For authenticate customer
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        axios
          .post(
            "/api/v1/ecommerce-core/customer/cart/remove-item",
            {
              uid: index,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            this.$store.dispatch("showPreloader", false);
            if (response.data.success) {
              this.$store.dispatch("updateCart", updatedTableData).then(() => {
                this.$store.dispatch("flushCouponData").then(() => {
                  this.$toast.success("Product remove from cart successfully");
                  this.syncCheckoutItems();
                });
              });
            }
          })
          .catch((error) => {
            this.$store.dispatch("showPreloader", false);
          });
      }
      //For guest customer
      if (!this.isCustomerLogin) {
        this.$store.dispatch("updateCart", updatedTableData).then(() => {
          this.$store.dispatch("flushCouponData").then(() => {
            this.$toast.success(
              this.$t("Product remove from cart successfully")
            );
            this.syncCheckoutItems();
          });
        });
      }
    },
    /**
     * Will apply coupon code
     *
     */
    applyCoupon() {
      this.prepareCheckoutItems();
      let checkCoupon = this.couponDiscounts.find(
        (coupon) => coupon.coupon_code == this.coupon_code
      );
      if (checkCoupon) {
        this.$toast.error(this.$t("Coupon applied successfully"));
        return 0;
      }
      this.couponApplying = true;
      axios
        .post("/api/v1/ecommerce-core/apply-coupon", {
          coupon_code: this.coupon_code,
          products: JSON.stringify(this.checkoutItems),
          customer_id: this.isCustomerLogin ? this.customer_id : null,
        })
        .then((response) => {
          if (response.data.success) {
            if (response.data.discount > 0) {
              let coupon_details = {
                discount: response.data.discount,
                id: response.data.coupon_id,
                coupon_code: this.coupon_code,
                allow_free_shipping: response.data.free_shipping,
              };

              this.$store
                .dispatch("storeCouponDiscount", coupon_details)
                .then(() => {
                  this.$toast.success(this.$t("Coupon applied successfully"));
                  this.coupon_code = "";
                });
            }

            if (response.data.discount < 1) {
              this.$toast.error("Coupon is not applied");
            }
          }

          if (!response.data.success) {
            this.$toast.error(response.data.message);
          }

          this.couponApplying = false;
        })
        .catch((error) => {
          this.couponApplying = false;
          this.$toast.error(this.$t("Something wrong, Please try again"));
        });
    },
    /**
     * Remove Applied coupon
     */
    removeCoupon(code) {
      this.$store.dispatch("removeCouponDiscount", code).then(() => {
        this.$toast.success(this.$t("Coupon Remove Successfully"));
      });
    },
    //Prepare checkout items
    prepareCheckoutItems() {
      this.checkoutItems = [];
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.is_available == 1 && item.is_selected) {
          this.checkoutItems.push(item);
        }
      }
    },
    /**
     * Keep Vuex checkoutItems in sync (replaces Checkout button).
     */
    syncCheckoutItems() {
      this.prepareCheckoutItems();
      this.$store
        .dispatch("addItemsToCheckoutItems", this.checkoutItems)
        .then(() => {
          this.$emit("checkout-items-synced", {
            count: this.checkoutItems.length,
            canProceed:
              this.proceedToCheckout && this.checkoutItems.length > 0,
          });
        });
    },
    /**
     * Proceed to details step (legacy; Checkout CTA commented out)
     */
    createOrder() {
      this.syncCheckoutItems();
      if (this.checkoutItems.length > 0 && this.proceedToCheckout) {
        this.$emit("next-step");
      }
      if (this.checkoutItems.length < 1) {
        this.$toast.error(this.$t("No item selected for checkout"));
      }
    },
  },
};
</script>
<style scoped>
/* ============================================
   FORCE MOBILE STYLES WHEN SPLIT SCREEN ACTIVE
   ============================================ */

/* When split screen is on, apply all mobile styles */
/* .force-mobile-layout {
  max-width: 100px !important;
  width: 100% !important;
  margin: 0 auto;
} */

/* <!-- ADD THIS: Non-scoped styles that apply globally --> */

/* Force mobile styles globally when this class exists */


/* .force-mobile-layout .fz-sm-12 {
  font-size: 12px !important;
  line-height: 14px !important;
}

.force-mobile-layout .fz-sm-14 {
  font-size: 14px !important;
  line-height: 14px !important;
}

.force-mobile-layout .quantity-input input {
  height: 34px;
  font-size: 14px;
} */

/* ============================================
   ADD ALL OTHER MOBILE STYLES HERE
   Copy EVERYTHING from your @media query below
   and prefix with .force-mobile-layout
   ============================================ */

/* For example, if you have these in @media: */
/* .force-mobile-layout .cart-table {
  display: block !important;
}

.force-mobile-layout .cart-item {
  flex-direction: column !important;
}

.force-mobile-layout .product-image {
  width: 100% !important;
} */

.force-mobile-layout {
  /* transform: scale(0.57); */
  /* font-size: 14px; */
  width: 100%;
}

.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.force-mobile-layout .cart-item__img,
.force-mobile-layout .cart-image-review {
  width: 64px !important;
  height: 64px !important;
  max-width: 64px !important;
  flex-shrink: 0 !important;
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


.item-selector {
  min-width: 14px;
  cursor: pointer;
}

.pl-0 {
  padding-left: 0;
}

.disableCart {
  position: relative;
}

.disableCart:after {
  content: "Not Available";
  text-align: center;
  font-size: 20px;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #fff;
  -webkit-backdrop-filter: blur(20px);
  backdrop-filter: blur(51px);
  opacity: 0.8;
  z-index: 1;
  line-height: 77px;
}

.close-btn {
  position: relative;
  z-index: 99;
}

.single-package {
  margin-bottom: 8px;
}

.cart-item {
  border: 1px solid #e8e8e8;
  border-radius: 12px;
  padding: 8px 10px;
  background: #fff;
}

.cart-item__img {
  display: block;
  flex-shrink: 0;
  width: 64px;
  height: 64px;
  border-radius: 8px;
  overflow: hidden;
}

.cart-item__img .cart-image-review {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.cart-item__body {
  gap: 2px;
  min-width: 0;
}

.cart-item__price {
  color: #3b3b3b;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.2;
}

.cart-item__actions {
  margin-top: 4px;
}

.cart-item__delete {
  color: #e53935;
  cursor: pointer;
  line-height: 1;
  display: flex;
  align-items: center;
}

.cart-item__delete .material-icons {
  font-size: 22px;
}

.cart-image {
  width: 90px !important;
  height: 90px !important;
}

.product_name {
  display: block;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 14px;
  font-weight: 700;
  line-height: 1.25;
  color: #111;
}

.product-variant {
  line-height: 1.2;
}

.product-price {
  color: #3b3b3b;
  font-size: 16px;
  font-weight: 700;
}

.quantity-input {
  border: 1px solid #e5e5e5;
  border-radius: 20px;
  overflow: hidden;
  height: 28px;
  align-items: center;
  min-width: 88px;
  max-width: 100px;
}

.quantity-input .material-icons {
  font-size: 16px;
  line-height: 1;
}

.quantity-input button {
  width: 28px;
  height: 28px;
  flex-shrink: 0;
}

.quantity-input input {
  height: 26px;
  font-size: 13px;
  padding: 0;
  min-width: 0;
  -moz-appearance: textfield;
}

.quantity-input input::-webkit-outer-spin-button,
.quantity-input input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

@media (max-width: 575px) {
  .fz-sm-12 {
    font-size: 12px !important;
    line-height: 14px !important;
  }

  .fz-sm-14 {
    font-size: 14px !important;
    line-height: 14px !important;
  }

  .quantity-input input {
    height: 26px;
    font-size: 13px;
  }
}

.icon-wrap {
  z-index: 9;
  cursor: pointer;
}

.checkout-cta-stack {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.checkout-cta-primary {
  min-height: 48px;
  width: 100% !important;
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: 600;
  border-radius: 8px;
}

.checkout-cta-secondary {
  min-height: 44px;
  width: 100% !important;
  border-radius: 8px;
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.cart-step--rtl .cart-step__toolbar {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .cart-step__toolbar .selector {
  flex: 0 1 auto;
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-end;
}

.cart-step--rtl .cart-step__toolbar .selector label {
  direction: rtl;
  text-align: right;
}

.cart-step--rtl .cart-step__toolbar .delete-all {
  flex: 0 1 auto;
}

.cart-step--rtl .cart-step__toolbar .delete-all .d-flex {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .cart-step__toolbar .delete-all .fz-12 {
  direction: rtl;
  text-align: right;
}

.cart-step--rtl .cart-item {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .cart-item__body {
  direction: rtl;
  text-align: right;
}

.cart-step--rtl .cart-item__actions {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .quantity-input {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .checkout-title {
  direction: rtl;
  text-align: right;
}

.cart-step--rtl .alert {
  direction: rtl;
  text-align: right;
}

.cart-step--rtl .alert-danger p.d-flex {
  flex-direction: row-reverse;
  direction: ltr;
}

.cart-step--rtl .alert-danger .btn_underline {
  margin-inline-start: 1.5rem;
  margin-left: 0;
}

.cart-step--rtl .disableCart:after {
  direction: rtl;
}
</style>
