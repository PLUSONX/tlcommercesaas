<template>
  <div class="order-details shadow-card" :class="{ 'order-summary--rtl': isRtl }">
    <h3 class="checkout-title">{{ $t("Summary") }}</h3>
    <div class="table-responsive">
      <!--Coupon Apply Area-->
      <div v-if="showCouponAboveTotals" class="coupon mb-3">
        <div class="form-group order-summary__coupon d-flex gap-1">
          <input class="form-control me-1" type="text" v-model="coupon_code" v-bind:placeholder="$t('Your Coupon')" />
          <button type="submit" class="btn coupon-btn btn_fill py-0" :disabled="couponApplying"
            @click.prevent="applyCoupon">
            <span v-if="couponApplying">
              <CSpinner component="span" size="sm" aria-hidden="true" />
              {{ $t("Wait") }}
            </span>
            <span v-else>
              {{ $t("Apply") }}
            </span>
          </button>
        </div>
      </div>
      <!--End Coupon apply area-->
      <table class="shop_table w-100">
        <tbody>
          <tr class="font-weight-bold">
            <td>{{ $t("Product") }}</td>
            <td>{{ $t("Total") }}</td>
          </tr>
          <tr class="products font-weight-regular" v-for="tdata in tableData" :key="tdata.id || tdata.uid">
            <td>
              <span class="product-line">
                <span class="product-name">{{ tdata.name }}</span>
                <span class="product-qty">x{{ tdata.quantity }}</span>
              </span>
            </td>
            <td>
              <the-currency :amount="tdata.unitPrice * tdata.quantity"></the-currency>
            </td>
          </tr>

          <!-- <tr class="font-weight-bold">
            <td>{{ $t("Subtotal") }}</td>
            <td>
              <span class="woocommerce-Price-amount amount">
                <bdi>
                  <span class="woocommerce-Price-currencySymbol"></span>
                  <the-currency :amount="totalUnitPrice"></the-currency>
                </bdi>
              </span>
            </td>
          </tr> -->
          <!--Tax-->
          <tr class="shipping-cost font-weight-regular" v-if="config?.enable_tax_in_checkout == enums.status.ACTIVE">
            <td>{{ $t("Tax") }}</td>
            <td>
              <span class="woocommerce-Price-amount amount">
                <bdi>
                  <span class="woocommerce-Price-currencySymbol">+</span>
                  <the-currency :amount="totalTax"></the-currency>
                </bdi>
              </span>
            </td>
          </tr>
          <!--End Tax-->
          <!--Shipping Cost-->
          <tr class="shipping-cost font-weight-regular">
            <td>{{ $t("Shipping Cost") }}</td>
            <td>
              <span class="woocommerce-Price-amount amount">
                <bdi>
                  <span class="woocommerce-Price-currencySymbol">+</span>
                  <the-currency :amount="shippingCost"></the-currency>
                </bdi>
              </span>
            </td>
          </tr>
          <!--End Shipping Cost-->
          <!--Discount-->
          <template v-if="
            couponDiscounts.length > 0 &&
            config?.enable_coupon_in_checkout == enums.status.ACTIVE
          ">
            <tr class="order-savings font-weight-regular" v-for="(discount, index) in couponDiscounts" :key="index">
              <td class="d-flex">
                <span class="c1">{{ discount.coupon_code }}</span>
                <a href="#" class="material-icons c1 mt-1" @click.prevent="removeCoupon(discount.coupon_code)">delete
                </a>
              </td>
              <td>
                <span class="woocommerce-Price-amount amount c1">
                  <bdi>
                    <span class="woocommerce-Price-currencySymbol">-</span>
                    <the-currency :amount="discount.discount"></the-currency>
                  </bdi>
                </span>
              </td>
            </tr>
          </template>
          <!--End Discount-->
          <!--Total Payable Amount-->
          <tr class="order-total">
            <td class="c1">{{ $t("Payable Total") }}</td>
            <td>
              <span class="woocommerce-Price-amount amount c1">
                <bdi>
                  <span class="woocommerce-Price-currencySymbol"></span>
                  <the-currency :amount="totalPayable"></the-currency>
                </bdi>
              </span>
            </td>
          </tr>

          <!--End Total Payable Amount-->
        </tbody>
      </table>

      <!--Coupon Apply Area-->
      <div v-if="showCouponBelowTotals" class="coupon coupon-below-total mt-3">
        <div class="form-group order-summary__coupon d-flex gap-1">
          <input class="form-control me-1" type="text" v-model="coupon_code" v-bind:placeholder="$t('Your Coupon')" />
          <button type="submit" class="btn coupon-btn btn_fill py-0" :disabled="couponApplying"
            @click.prevent="applyCoupon">
            <span v-if="couponApplying">
              <CSpinner component="span" size="sm" aria-hidden="true" />
              {{ $t("Wait") }}
            </span>
            <span v-else>
              {{ $t("Apply") }}
            </span>
          </button>
        </div>
      </div>
      <!--End Coupon apply area-->
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapState, mapGetters } from "vuex";
import { CSpinner } from "@coreui/vue";
export default {
  name: "OrderSummary",
  components: {
    CSpinner,
  },
  props: {
    config: {
      type: Object,
      required: true,
    },
    enums: {
      type: Object,
      required: true,
    },
    couponBelowPayableTotal: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      coupon_code: "",
      couponApplying: false,
    };
  },
  emits: ["get-total-payable"],
  computed: {
    ...mapState({
      shippingCost: (state) => (state.shippingCost ? state.shippingCost : 0),
      totalTax: (state) => (state.tax ? state.tax : 0),
      couponDiscounts: (state) =>
        state.couponDiscount ? state.couponDiscount : [],
      checkoutItems: (state) => state.checkoutItems || [],
      cart: (state) => state.cart || [],
      isCustomerLogin: (state) => state.isCustomerLogin,
      customer_id: (state) =>
        state.customerInfo != null ? state.customerInfo.id : null,
    }),
    ...mapGetters('layout', ['isRtl']),
    tableData() {
      if (Array.isArray(this.checkoutItems) && this.checkoutItems.length > 0) {
        return this.checkoutItems;
      }
      return (this.cart || []).filter(
        (item) => item.is_available == 1 && item.is_selected
      );
    },
    totalUnitPrice() {
      return this.tableData.reduce((accum, item) => {
        return parseFloat(accum) + parseFloat(item.unitPrice * item.quantity);
      }, 0.0);
    },
    totalPayable() {
      let sum = this.totalUnitPrice + this.shippingCost + this.totalTax;
      let sub = this.totalSaving;
      let payable = sum - sub;
      return payable;
    },
    totalSaving() {
      if (this.config?.enable_coupon_in_checkout == this.enums.status.ACTIVE) {
        return this.couponDiscounts.reduce((accum, item) => {
          return parseFloat(accum) + parseFloat(item.discount);
        }, 0.0);
      } else {
        return 0;
      }
    },
    enableApplyCoupon() {
      if (
        this.config?.is_active_coupon == this.enums.status.ACTIVE &&
        this.config?.enable_coupon_in_checkout == this.enums.status.ACTIVE
      ) {
        if (
          this.couponDiscounts.length > 0 &&
          this.config?.enable_multiple_coupon_in_checkout ==
          this.enums.status.IN_ACTIVE
        ) {
          return false;
        }
        return true;
      }
      return false;
    },
    showCouponAboveTotals() {
      return this.enableApplyCoupon && !this.couponBelowPayableTotal;
    },
    showCouponBelowTotals() {
      return this.enableApplyCoupon && this.couponBelowPayableTotal;
    },
  },
  watch: {
    totalPayable() {
      this.changeOrderTotal();
    },
  },
  mounted() {
    this.changeOrderTotal();
  },
  methods: {
    /**
     * Will change order total
     */
    changeOrderTotal() {
      this.$emit("get-total-payable", this.totalPayable);
    },
    applyCoupon() {
      let checkCoupon = this.couponDiscounts.find(
        (coupon) => coupon.coupon_code == this.coupon_code
      );
      if (checkCoupon) {
        this.$toast.error(this.$t("Coupon applied successfully"));
        return 0;
      }
      const products = this.tableData;
      if (!products.length) {
        this.$toast.error(this.$t("No item selected for checkout"));
        return;
      }
      this.couponApplying = true;
      axios
        .post("/api/v1/ecommerce-core/apply-coupon", {
          coupon_code: this.coupon_code,
          products: JSON.stringify(products),
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
        .catch(() => {
          this.couponApplying = false;
          this.$toast.error(this.$t("Something wrong, Please try again"));
        });
    },
    removeCoupon(code) {
      this.$store.dispatch("removeCouponDiscount", code).then(() => {
        this.$toast.success(this.$t("Coupon Remove Successfully"));
      });
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.coupon-btn {
  display: inline-flex;
  align-items: center;
  text-transform: capitalize;
  padding: 9px 20px 10px;
  font-size: 16px;
  font-weight: 700;
  background-color: $c1;
  color: #ffffff;
  border: none;
  cursor: pointer;
  border-radius: 8px;
}

.coupon-below-total {
  width: 100%;
}

.product-line {
  display: inline-flex;
  align-items: baseline;
  max-width: 100%;
  gap: 4px;
}

.product-name {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.product-qty {
  flex-shrink: 0;
  white-space: nowrap;
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.order-summary--rtl .checkout-title {
  direction: rtl;
  text-align: right;
}

.order-summary--rtl .shop_table {
  direction: rtl;
}

.order-summary--rtl .shop_table td:first-child {
  direction: rtl;
  text-align: right;
  padding-left: 9px;
  padding-right: 0;
}

.order-summary--rtl .shop_table td:last-child {
  direction: ltr;
  text-align: left;
  padding-left: 0;
  padding-right: 9px;
}

.order-summary--rtl .product-line {
  direction: rtl;
  flex-direction: row-reverse;
}

.order-summary--rtl .product-name {
  direction: rtl;
  text-align: right;
}

.order-summary--rtl .order-summary__coupon {
  flex-direction: row-reverse;
  direction: ltr;
}

.order-summary--rtl .order-summary__coupon .form-control {
  direction: rtl;
  text-align: right;
}

.order-summary--rtl .order-summary__coupon .form-control.me-1 {
  margin-right: 0 !important;
  margin-inline-end: 0.25rem;
}

.order-summary--rtl .order-savings td.d-flex {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-start;
}
</style>
