import axios from "axios";
import { useToast } from 'vue-toast-notification';
import router from '../router/index'
const $toast = useToast();
export default {
  /**
 * Set Location
 */
  setDeliveryData(context, payload) {
    context.commit("SET_DELIVERY_DATA", payload);
  },
  /**
 * Set site settings
 */
  siteSettings(context, payload) {
    context.commit("siteSettings", payload);
  },
  siteProperties(context, payload) {
    context.commit("siteProperties", payload);
  },
  changeScreenMode(context, payload) {
    context.commit("changeScreenMode", payload);
  },
  changeCurrency(context, payload) {
    context.commit("changeCurrency", payload);
  },
  showPreloader(context, payload) {
    context.commit("showPreloader", payload);
  },
  /**
   * Store cart items
   */
  addToCart(context, data) {
    if (this.state.isCustomerLogin) {
      context.commit("showPreloader", true);
      let formData = new FormData();
      formData.append("item", JSON.stringify(data));
      return axios.post('/api/v1/ecommerce-core/customer/cart/store-cart-item', formData, {
        headers: {
          Authorization: `Bearer ${this.state.customerToken}`,
        },
      }).then((response) => {
        context.commit("showPreloader", false);
        if (response.data.success) {
          context.commit("storeCartItems", data);
        }
        if (!response.data.success) {
          $toast.error(this.state.$t("Cross the available quantity"));
        }
      }).catch((error) => {
        context.commit("showPreloader", false);
        $toast.error(this.state.$t("Action failed. Please try again"));
      });;
    }

    // console.log("isCustomerLogin: ", this.state.isCustomerLogin);

    // console.log("siteSettings: ", this.state.siteSettings);

    // console.log("enable_guest_checkout: ", this.state.siteSettings?.enable_guest_checkout);

    if (!this.state.isCustomerLogin || (this.state.siteSettings != null && this.state.siteSettings?.enable_guest_checkout == 1)) {
      // Add to cart if logged in OR guest checkout is enabled
      context.commit("storeCartItems", data);
    } else {
      // Only redirect if NOT logged in AND guest checkout is disabled
      $toast.error(this.state.$t("Please login"));
      router.push('/login')
    }

    // if (!this.state.isCustomerLogin && this.state.siteSettings != null && this.state.siteSettings.enable_guest_checkout == 1) {
    //   context.commit("storeCartItems", data);
    // } else {
    //   $toast.error(this.state.$t("Please login"));
    //   router.push('/login')
    // }
  },
  /**
  * Add data to checkout items
  */
  addItemsToCheckoutItems(context, data) {
    context.commit("addItemsToCheckoutItems", data);
  },
  /**
   * Add item to compare list
   */
  addItemToCompareItems(context, data) {
    context.commit("addItemToCompareItems", data);
  },
  /**
   * Update compare product list
   */
  updateCompareItems(context, data) {
    context.commit("updateCompareItems", data);
  },
  flushCustomerCart(context) {
    context.commit("flushCustomerCart");
  },
  /**
   * Store cart items
   */
  updateCart(context, data) {
    context.commit("updateCart", data);
  },
  /**
   * Remove a single cart line by uid (product list / qty→0).
   */
  removeCartItem(context, uid) {
    if (uid == null) {
      return;
    }
    const state = context.state;
    const updatedCart = (state.cart || []).filter((item) => item.uid !== uid);

    if (state.isCustomerLogin) {
      context.commit("showPreloader", true);
      return axios
        .post(
          "/api/v1/ecommerce-core/customer/cart/remove-item",
          { uid },
          {
            headers: {
              Authorization: `Bearer ${state.customerToken}`,
            },
          }
        )
        .then((response) => {
          context.commit("showPreloader", false);
          if (response.data.success) {
            context.commit("updateCart", updatedCart);
            context.dispatch("flushCouponData");
          }
        })
        .catch(() => {
          context.commit("showPreloader", false);
          $toast.error(state.$t("Action failed. Please try again"));
        });
    }

    context.commit("updateCart", updatedCart);
    context.dispatch("flushCouponData");
  },
  /**
   * Update a single cart line quantity (PDP, cart UI).
   */
  updateCartLineQuantity(context, item) {
    const state = context.state;
    const qty = parseInt(item?.quantity, 10);
    if (!Number.isFinite(qty) || qty <= 0) {
      return context.dispatch("removeCartItem", item?.uid);
    }
    const payload = { ...item, quantity: item.quantity };

    if (state.isCustomerLogin) {
      context.commit("showPreloader", true);
      const formData = new FormData();
      formData.append("item", JSON.stringify(payload));
      return axios
        .post("/api/v1/ecommerce-core/customer/cart/update-cart-item", formData, {
          headers: {
            Authorization: `Bearer ${state.customerToken}`,
          },
        })
        .then((response) => {
          context.commit("showPreloader", false);
          if (response.data.success) {
            context.commit("setCartLineQuantity", payload);
            context.dispatch("flushCouponData");
          } else {
            $toast.error(state.$t("Cross the available quantity"));
          }
        })
        .catch(() => {
          context.commit("showPreloader", false);
          $toast.error(state.$t("Action failed. Please try again"));
        });
    }

    context.commit("setCartLineQuantity", payload);
    context.dispatch("flushCouponData");
  },
  /**
   * Home delivery checkout action
   */
  storeHomeDeliveryCheckout(context, data) {
    context.commit("storeHomeDeliveryCheckout", data);
  },
  /**
   * Pickup point checkout action
   */
  storePickoupPointCheckout(context, data) {
    context.commit("storePickoupPointCheckout", data);
  },
  /**
  * Store pickup point details
  */
  storePickoupPoint(context, data) {
    context.commit("storePickoupPoint", data);
  },
  /**
   * Store billing details
   */
  storeBillingDetails(context, data) {
    context.commit("storeBillingDetails", data);
  },
  /**
   * Store shipping details
   */
  storeShippingDetails(context, data) {
    // console.log("storeShippingDetails action.js called: ", data);
    context.commit("storeShippingDetails", data);
  },
  storeDeliverySchedule(context, data) {
    context.commit("storeDeliverySchedule", data);
  },
  /**
   * Store guest customer details
   */
  storeGuestCustomerDetails(context, data) {
    context.commit("storeGuestCustomerDetails", data);
  },
  /**
   * Store is_active_ship_to_different_address
   */
  storeIsActiveBillToDifferentAddress(context, data) {
    context.commit("storeIsActiveBillToDifferentAddress", data);
  },
  /**
   * Store is_active_create_guest_account
   */
  storeIsActiveCreateNewAccount(context, data) {
    context.commit("storeIsActiveCreateNewAccount", data);
  },
  /**
   * Store order shipping cost
   */
  setFinalShippingCost(context, data) {
    context.commit("setFinalShippingCost", data);
  },
  /**
   * Store order tax
   */
  setFinalTax(context, data) {
    context.commit("setFinalTax", data);
  },
  /**
   * Persist checkout shipping prep (product packages) for remount survival
   */
  storeCheckoutPrep(context, data) {
    context.commit("storeCheckoutPrep", data);
  },
  /**
   * Clear checkout prep
   */
  flushCheckoutPrep(context) {
    context.commit("flushCheckoutPrep");
  },
  /**
   * Marks that checkout is redirecting to an external gateway.
   * Used by the unified checkout page to avoid a visible cart reset flash.
   */
  setCheckoutLeavingForPayment(context, { leaving = false, startedAt = null } = {}) {
    context.commit("setCheckoutLeavingForPayment", {
      leaving,
      startedAt,
    });
  },
  requestCheckoutPlaceOrder(context) {
    context.commit("requestCheckoutPlaceOrder");
  },
  setCheckoutOrderCreating(context, value) {
    context.commit("setCheckoutOrderCreating", value);
  },
  /**
   * Store coupon discount
   */
  storeCouponDiscount(context, data) {
    context.commit("storeCouponDiscount", data);
  },
  /**
   * Remove applied coupon
   */
  removeCouponDiscount(context, data) {
    context.commit("removeCouponDiscount", data);
  },
  /**
   * Remove  all applied coupons
   */
  flushCouponData(context) {
    context.commit("flushCouponData");
  },
  /**
   * Flash cart data
   */
  flushCartData(context) {
    context.commit("flushCartData");
  },
  /**
   * Set customer login state
   */
  customerLogin(context, data) {
    context.commit("customerLogin", data);
  },
  /**
   * Set customer login customer info
   */
  loggedCustomerInfo(context, data) {
    context.commit("loggedCustomerInfo", data);
  },
  /**
  * Customer logout
  */
  customerLogout(context) {
    context.commit("customerLogout");
  },
  /**
   * Refresh customer dashboard info
   * 
   */
  async refreshCustomerDashboardInfo({ commit, state }) {
    return await axios.get('/api/v1/ecommerce-core/customer/customer-summary', {
      headers: {
        Authorization: `Bearer ${state.customerToken}`,
      },
    }).then((response) => {
      if (response.data.success) {
        commit("setCustomerDashboardSummary", response.data.dasboard_content);
      }
    });
  },
  /**
 * Get customer cart items
 * 
 */
  async getCustomerCartItems({ commit, state }) {
    return await axios.get('/api/v1/ecommerce-core/customer/cart/cart-items-list', {
      headers: {
        Authorization: `Bearer ${state.customerToken}`,
      },
    }).then((response) => {
      if (response.data.success) {
        commit("updateCart", response.data.data);
      }
    });
  },
  /**
  * Will store customer notification
  * 
  */
  customerNotificationAction(context, data) {
    context.commit("customerNotificationMutation", data);
  }
};
