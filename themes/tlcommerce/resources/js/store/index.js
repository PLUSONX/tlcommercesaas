import { createStore } from "vuex";
import mutations from "./mutations.js";
import actions from "./actions.js";
import getters from "./getters.js";
import layout from './modules/layout';
import { safeGetItem, safeJsonParse, asArray } from "../utils/safeStorage";

function readBootstrapSiteProperties() {
  try {
    const props = window.__TLC_BOOTSTRAP__?.siteProperties;
    if (props && typeof props === "object" && Object.keys(props).length) {
      return props;
    }
  } catch (e) {
    // ignore
  }
  return null;
}

function readBootstrapSiteSettings() {
  try {
    const settings = window.__TLC_BOOTSTRAP__?.siteSettings;
    if (settings && typeof settings === "object" && Object.keys(settings).length) {
      return settings;
    }
  } catch (e) {
    // ignore
  }
  return null;
}

// vuex-multi-tab-state removed: it throws when localStorage is blocked
// (common in Instagram WebView) and can restore corrupt full-store snapshots.

const store = createStore({
  state() {
    return {
      notifications: [],
      siteSettings: safeJsonParse("siteSettings", null) || readBootstrapSiteSettings(),
      siteProperties: safeJsonParse("siteProperties", null) || readBootstrapSiteProperties(),
      currency: safeJsonParse("currency", {}) || {},
      defaultCurrency: safeJsonParse("default_currency", {}) || {},
      customerToken: safeJsonParse("customerToken", "") || "",
      customerInfo: safeJsonParse("customerInfo", {}) || {},
      customerDashboardInfo: safeJsonParse("customerDashboardInfo", null),
      isCustomerLogin: safeJsonParse("isCustomerLogin", false) || false,
      locale: safeGetItem("locale") || "en",
      compareItems: asArray(safeJsonParse("compareItems", [])),
      cart: asArray(safeJsonParse("cart", [])),
      checkoutItems: asArray(safeJsonParse("checkoutItems", [])),
      billingDetails: safeJsonParse("billingDetails", null),
      shippingDetails: safeJsonParse("shippingDetails", null),
      deliverySchedule: safeJsonParse("deliverySchedule", null),
      guestCustomerInfo: safeJsonParse("guestCustomerInfo", null),
      isActiveBillToDifferentAddress: safeJsonParse("isActiveBillToDifferentAddress", false) || false,
      isActiveCreateNewAccount: safeJsonParse("isActiveCreateNewAccount", false) || false,
      isActivePickupPoint: safeJsonParse("isActivePickupPoint", false) || false,
      isActiveHomeDelivery: safeJsonParse("isActiveHomeDelivery", true) !== false,
      pickupPoint: safeJsonParse("pickupPoint", null),
      shippingCost: safeJsonParse("shippingCost", 0) || 0,
      tax: safeJsonParse("tax", 0) || 0,
      couponDiscount: asArray(safeJsonParse("couponDiscount", [])),
      couponCode: safeJsonParse("couponCode", null),
      mode: safeGetItem("mode") || null,
      preloaderLoading: false,
      deliveryData: {},
      // Transient checkout prep (survives Checkout remount; cleared with cart flush)
      checkoutPrep: {
        productPackages: [],
        shippingPackages: [],
        isHomeDelivery: true,
      },
      // Transient UI guard: prevents unified checkout from resetting while we
      // are redirecting to the external payment gateway.
      checkoutLeavingForPayment: false,
      checkoutLeavingForPaymentStartedAt: null,
      checkoutPlaceOrderRequestId: 0,
      checkoutOrderCreating: false,
      /** PDP split-screen: hide CompanyFooter while CustomFooter (add to cart) is shown */
      productPageSuppressCompanyFooter: false,
    };
  },
  mutations,
  actions,
  getters,
  modules: {
    layout
  },
  plugins: [],
});

export default store;
