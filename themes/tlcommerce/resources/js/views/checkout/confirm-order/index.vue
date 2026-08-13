<template>
  <div class="" :class="{
    'force-mobile-layout': forcedMobile,
    'mobile-content-wrapper': forcedMobile,
    'confirm-order--rtl': isRtl
  }">
    <!-- <page-header :items="bItems" /> -->

    <div class="pt-60 pb-60 light-bg">
      <div class="custom-container2">
        <div class="row" v-if="!dataLoading">
          <div class="col-12" v-if="success">
            <div class="shadow-card py-5 mb-30">
              <div class="confirm-order-success-row">
                <div class="confirm-order-success-row__thumb">
                  <img
                    src="/themes/tlcommerce/assets/images/icons/completed-order.svg"
                    class="confirm-order-success-icon"
                    alt="Order"
                  />
                </div>
                <div class="confirm-order-success-row__body">
                  <h3 class="confirm-order-success-row__title">{{ $t("Thank You") }}!</h3>
                  <h4 class="confirm-order-success-row__order-id">
                    {{ $t("Order ID") }}: {{ orderDetails.order_code }}
                  </h4>
                </div>
              </div>
            </div>

            <div class="shadow-card mb-30">
              <h3 class="order-summery-title">{{ $t("Order Summery") }}</h3>

              <div class="row mt-4">
                <div class="col-lg-6">
                  <ul class="order-summery-list" v-if="orderDetails.pickup_point == null">
                    <li>
                      <span>{{ $t("Order Code") }}:</span>
                      <span>{{ orderDetails.order_code }}</span>
                    </li>
                    <template v-if="orderDetails.shipping_details">
                      <li>
                        <span>{{ $t("Name") }}:</span>
                        <span>{{ orderDetails.shipping_details.name }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Mobile") }}:</span>
                        <span>{{ orderDetails.shipping_details.phone }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Address") }}:</span>
                        <p>
                          <span v-if="orderDetails.shipping_details.address != null">{{
                            orderDetails.shipping_details.address }},</span>
                          <span v-if="orderDetails.shipping_details.city != null">{{ orderDetails.shipping_details.city
                          }},</span>
                          <span v-if="orderDetails.shipping_details.state != null">{{
                            orderDetails.shipping_details.state }},</span>
                          <span v-if="orderDetails.shipping_details.country">{{ orderDetails.shipping_details.country
                          }}.</span>
                        </p>
                      </li>
                      <li>
                        <span>{{ $t("Postal Code") }}:</span>
                        <span>{{
                          orderDetails.shipping_details.postal_code
                        }}</span>
                      </li>
                    </template>
                  </ul>
                  <ul class="order-summery-list" v-else>
                    <li>
                      <span>{{ $t("Order Code") }}:</span>
                      <span>{{ orderDetails.order_code }}</span>
                    </li>
                    <template v-if="orderDetails.pickup_point">
                      <li>
                        <span>{{ $t("Pickup Point") }}:</span>
                        <span>{{ orderDetails.pickup_point.name }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Mobile") }}:</span>
                        <span>{{ orderDetails.pickup_point.phone }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Address") }}:</span>
                        <span>{{ orderDetails.pickup_point.location }}</span>
                      </li>
                    </template>
                  </ul>
                </div>
                <div class="col-lg-6 mt-2 mt-lg-0">
                  <ul class="order-summery-list">
                    <li>
                      <span>{{ $t("Order Date") }}:</span>
                      <span>{{ orderDetails.order_date }}</span>
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Total Amount") }}:</span><the-currency
                        :amount="orderDetails.total_payable_amount"></the-currency>
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Order Status") }}:</span>
                      <span class="text-info">{{
                        orderDetails.delivery_status_label
                      }}</span>
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Payment Status") }}:</span>
                      <span class="text-info">{{
                        orderDetails.payment_status_label
                      }}</span>
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Payment method") }}:</span>
                      <span class="text-info">{{
                        orderDetails.payment_method
                      }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="shadow-card">
              <h3 class="checkout-title">{{ $t("Order Details") }}:</h3>

              <div class="table-responsive mt-3">
                <CTable class="cart-table">
                  <CTableHead>
                    <CTableRow>
                      <CTableHeaderCell>{{
                        $t("Product Name")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{
                        $t("Price") + "/" + $t("Unit")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Quantity") }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Total") }}</CTableHeaderCell>
                    </CTableRow>
                  </CTableHead>
                  <CTableBody>
                    <CTableRow v-for="product in orderDetails.products.data" :key="product.id">
                      <CTableDataCell class="w-50">
                        <div class="d-flex align-items-center confirm-order-product-row">
                          <div class="confirm-order-product-thumb">
                            <router-link :to="`/products/${product.permalink}`">
                              <img
                                :src="product.image"
                                :alt="product.name"
                                class="confirm-order-product-thumb__image"
                              />
                            </router-link>
                          </div>
                          <div class="item-info">
                            <!--Item Name-->
                            <router-link :title="`${product.name}`" :to="`/products/${product.permalink}`"
                              class="cart-product-name product-name text-capitalize">
                              {{ product.name }}
                            </router-link>
                            <!--End Item Name-->
                            <!--Variant-->
                            <div class="product-variant extra-addons-wrap d-flex flex-wrap"
                              v-if="product.variant != null">
                              <product-variant class="font-weight-medium fz-12" :variant="product.variant"
                                tag="p"></product-variant>
                            </div>
                            <!--End Variant-->
                            <!--Shop-->
                            <div class="extra-addons-wrap d-flex flex-wrap" v-if="product.shop != null">
                              <!-- <p class="product-shop fz-12">
                                {{ $t("Sold By") }}
                                <router-link
                                  :to="`/shop/${product.shop.shop_slug}`"
                                  target="_blank"
                                  class="link-danger"
                                >
                                  {{ product.shop.shop_name }}
                                </router-link>
                              </p> -->
                            </div>
                            <!--End shop-->
                          </div>
                        </div>
                      </CTableDataCell>
                      <CTableDataCell><the-currency :amount="product.unit_price"></the-currency>
                      </CTableDataCell>
                      <CTableDataCell>{{ product.quantity }}</CTableDataCell>
                      <CTableDataCell class="fw-medium"><the-currency
                          :amount="product.unit_price * product.quantity"></the-currency></CTableDataCell>
                    </CTableRow>
                  </CTableBody>
                </CTable>
              </div>
              <div class="order-details">
                <div class="table-responsive">
                  <table class="shop_table w-100">
                    <tbody>
                      <tr class="cart-subtotal">
                        <td>{{ $t("Subtotal") }}</td>
                        <td>
                          <span class="woocommerce-Price-amount amount"><bdi><span
                                class="woocommerce-Price-currencySymbol"></span>
                              <the-currency :amount="orderDetails.sub_total"></the-currency> </bdi></span>
                        </td>
                      </tr>
                      <tr class="shipping-cost font-weight-regular">
                        <td>{{ $t("Shipping Cost") }}</td>
                        <td>
                          <span class="woocommerce-Price-amount amount"><bdi><span
                                class="woocommerce-Price-currencySymbol">+</span>
                              <the-currency :amount="orderDetails.total_delivery_cost"></the-currency> </bdi></span>
                        </td>
                      </tr>
                      <tr class="order-tax font-weight-regular" v-if="orderDetails.total_tax > 0">
                        <td>
                          {{ $t("Tax") }}
                        </td>
                        <td>
                          <span class="woocommerce-Price-amount amount">
                            <bdi><span class="woocommerce-Price-currencySymbol">+</span>
                              <the-currency :amount="orderDetails.total_tax"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                      <tr class="order-savings font-weight-regular" v-if="orderDetails.total_discount > 0">
                        <td>
                          {{ $t("Discount") }}
                        </td>
                        <td>
                          <span class="woocommerce-Price-amount amount">
                            <bdi><span class="woocommerce-Price-currencySymbol">-</span>
                              <the-currency :amount="orderDetails.total_discount"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>

                      <tr class="order-total">
                        <td class="c1">{{ $t("Payable Total") }}</td>
                        <td>
                          <span class="woocommerce-Price-amount amount c1">
                            <bdi>
                              <span class="woocommerce-Price-currencySymbol"></span>
                              <the-currency :amount="orderDetails.total_payable_amount"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="mt-3 d-flex flex-wrap justify-content-between">
                <router-link to="/dashboard/purchase-history" class="btn btn_fill mb-1">
                  {{ $t("View Orders") }}
                </router-link>
                <router-link to="/products" class="btn btn_fill mb-1">
                  {{ $t("Shop More") }}
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12" v-else>
            <div class="shadow-card py-5 text-center mb-30">
              <the-not-found title="Order details not found"> </the-not-found>
            </div>
          </div>
        </div>
        <div v-if="dataLoading">
          <skeleton width="100%" height="300px" class="mb-30"> </skeleton>
          <skeleton width="100%" height="500px"> </skeleton>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import ProductVariant from "@/components/ui/ProductVariant.vue";
import axios from "axios";
import { mapState, mapGetters } from "vuex";
import {
  trackSocialPixels,
  hasTrackedPurchase,
  markPurchaseTracked,
} from "@/utils/trackSocialPixels";
import {
  CTable,
  CTableHead,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
} from "@coreui/vue";
export default {
  name: "SuccessOrder",
  layout: "main",
  components: {
    PageHeader,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    ProductVariant,
  },
  data() {
    return {
      pageTitle: this.$t("Confirm Order"),
      order_code: "",
      quantityValue: 1,
      subtotal: 990,
      deliveryCost: 50,
      couponDiscount: 100,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Confirm Order"),
          active: true,
        },
      ],
      orderDetails: {},
      success: false,
      dataLoading: true,
    };
  },
  computed: {
    ...mapState({
      customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
    }),

    ...mapGetters('layout', ['isFeaturePaneLayout', 'isMobile', 'isRtl']),

    forcedMobile() {
      if (this.isFeaturePaneLayout && !this.isMobile) {
        return true;
      }
      return this.isMobile;
    },

  },
  // computed: mapState({
  //   customerToken: (state) => state.customerToken,
  //   isCustomerLogin: (state) => state.isCustomerLogin,
  // }),
  mounted() {
    document.title = this.$t("Success Order");
    this.order_code = this.$route.params.id;
    if (this.isCustomerLogin) {
      this.customerOrderDetails();
    } else {
      this.guestCustomerOrderDetails();
    }

  },
  methods: {
    /**
     * Get successful order details
     *
     */
    customerOrderDetails() {
      axios
        .post(
          "/api/v1/ecommerce-core/customer/order/details",
          {
            order_code: this.order_code,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.orderDetails = response.data.data;
            this.success = true;
            try {
              this.trackMetaPurchase(response.data.data);
            } catch (trackingError) {
              console.error("trackMetaPurchase failed:", trackingError);
            }
          } else {
            this.$toast.error("Order not found");
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.$toast.error("Order not found");
          this.dataLoading = false;
        });
    },
    /**
     * Get guest customer order details
     */
    guestCustomerOrderDetails() {
      // console.log("guestCustomerOrderDetails method called!!!");
      axios
        .post("/api/v1/ecommerce-core/guest/order/details", {
          order_code: this.order_code,
        })
        .then((response) => {
          // console.log("response: ", response);
          // console.log("response.data.success: ", response.data.success);  // debug
          // console.log("response.data.data: ", response.data.data);        // debug

          if (response.data.success) {
            this.orderDetails = response.data.data;
            this.success = true;

            try {
              this.trackMetaPurchase(response.data.data);
            } catch (trackingError) {
              console.error("trackMetaPurchase failed:", trackingError);
            }

          } else {
            this.$toast.error("Order not found");
          }

          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
          console.error("Axios error: ", error);
          this.$toast.error("Order not found");
        });
    },


    trackMetaPurchase(order) {
      if (!order?.id || hasTrackedPurchase(order.id)) {
        return;
      }

      const items = order.products?.data ?? [];

      const contentIds = items.map((item) =>
        String(item.product_id ?? item.id)
      );

      const contents = items.map((item) => ({
        id: String(item.product_id ?? item.id),
        quantity: item.quantity ?? 1,
      }));

      const value = Number(order.total_payable_amount);

      markPurchaseTracked(order.id);

      trackSocialPixels(
        "Purchase",
        {
          content_ids: contentIds,
          contents: contents,
          content_type: "product",
          value: Number.isFinite(value) ? value : 0,
          currency: "KWD",
          num_items: items.length,
        },
        { eventID: String(order.id) }
      );
    }
    // guestCustomerOrderDetails() {
    //   console.log("guestCustomerOrderDetails method called!!!");
    //   axios
    //     .post("/api/v1/ecommerce-core/guest/order/details", {
    //       order_code: this.order_code,
    //     })
    //     .then((response) => {
    //       console.log("response: ", response);
    //       if (response.data.success) {
    //         this.orderDetails = response.data.data;
    //         this.success = true;
    //         this.trackMetaPurchase(response.data.data);
    //       } else {
    //         this.$toast.error("Order not found");
    //       }
    //       this.dataLoading = false;
    //     })
    //     .catch((error) => {
    //       this.dataLoading = false;
    //       console.log("error: ", error);
    //       this.$toast.error("Order not found");
    //     });
    // },

    // trackMetaPurchase(order) {
    //   if (window.fbq) {
    //     // Extracting product IDs from the order items
    //     const productIds = order.details.map(item => item.id);

    //     window.fbq('track', 'Purchase', {
    //       content_ids: productIds,
    //       content_type: 'product',
    //       value: order.total_payable_amount, // Ensure this matches your API field name
    //       currency: 'KD', // Replace with order.currency if available
    //       num_items: order.details.length
    //     });
    //     // console.log('Meta Purchase Tracked');
    //   }
    // }
  },
};
</script>

<style scoped>
.confirm-order-success-row {
  display: flex;
  gap: 12px;
  align-items: center;
}

.confirm-order-success-row__thumb {
  flex: 0 0 96px;
  width: 96px;
  flex-shrink: 0;
}

.confirm-order-success-icon {
  width: 96px;
  height: 96px;
  object-fit: contain;
  display: block;
}

.confirm-order-success-row__body {
  flex: 1;
  min-width: 0;
  text-align: left;
}

.confirm-order-success-row__title {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  line-height: 1.3;
}

.confirm-order-success-row__order-id {
  margin: 4px 0 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.4;
}

.confirm-order-product-row {
  gap: 12px;
}

.confirm-order-product-thumb {
  flex: 0 0 96px;
  width: 96px;
  flex-shrink: 0;
}

.confirm-order-product-thumb__image {
  width: 96px;
  height: 96px;
  object-fit: cover;
  border-radius: 12px;
  display: block;
}

.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.force-mobile-layout .product-content .image,
.force-mobile-layout .product-content .product-img {
  min-width: 0 !important;
  max-width: 100px;
}



.force-mobile-layout .cart-image-review {
  max-width: 100% !important;
  height: auto !important;
  flex-shrink: 1 !important;
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

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.confirm-order--rtl .confirm-order-success-row {
  flex-direction: row-reverse;
  direction: ltr;
}

.confirm-order--rtl .confirm-order-success-row__body {
  direction: rtl;
  text-align: right;
}

.confirm-order--rtl .order-summery-title,
.confirm-order--rtl .checkout-title {
  direction: rtl;
  text-align: right;
}

.confirm-order--rtl .order-summery-list li {
  direction: rtl;
}

.confirm-order--rtl .order-summery-list li > span:first-child {
  direction: rtl;
  text-align: right;
}

.confirm-order--rtl .order-summery-list li > span:last-child,
.confirm-order--rtl .order-summery-list li > p {
  direction: ltr;
  text-align: left;
}

.confirm-order--rtl .cart-table {
  direction: rtl;
}

.confirm-order--rtl .cart-table td .confirm-order-product-row {
  flex-direction: row-reverse;
  direction: ltr;
}

.confirm-order--rtl .product-name {
  direction: rtl;
  text-align: right;
}

.confirm-order--rtl .shop_table {
  direction: rtl;
}

.confirm-order--rtl .shop_table td:first-child {
  direction: rtl;
  text-align: right;
  padding-left: 9px;
  padding-right: 0;
}

.confirm-order--rtl .shop_table td:last-child {
  direction: ltr;
  text-align: left;
  padding-left: 0;
  padding-right: 9px;
}

.confirm-order--rtl .mt-3.d-flex.flex-wrap.justify-content-between {
  flex-direction: row-reverse;
  direction: ltr;
}
</style>
