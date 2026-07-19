import axios from "axios";

/**
 * Read delivery context from Vuex (same inputs Review Order used).
 */
export function getDeliveryContext(store) {
  const shippingDetails = store.state.shippingDetails;
  const isActiveHomeDelivery = !!store.state.isActiveHomeDelivery;
  const isActivePickupPoint = !!store.state.isActivePickupPoint;

  const selectedPickupPoint =
    store.state.pickupPoint != null
      ? store.state.pickupPoint
      : {
          id: "",
          name: "",
          location: "",
          phone: "",
          zone_id: null,
          zone_name: "",
        };

  let city_id = null;
  let post_code = null;
  if (isActiveHomeDelivery && shippingDetails) {
    city_id =
      shippingDetails.city != null ? shippingDetails.city.id : null;
    post_code = shippingDetails.postal_code;
  }

  return {
    isActiveHomeDelivery,
    isActivePickupPoint,
    selectedPickupPoint,
    city_id,
    post_code,
    shippingDetails,
  };
}

/**
 * Apply shipping + tax totals to the store.
 */
export function applyShippingCostAndTax({
  store,
  config,
  enums,
  shippingPackages,
  isActiveHomeDelivery,
}) {
  const total_shipping_cost = shippingPackages.reduce((accum, item) => {
    return (
      parseFloat(accum) + parseFloat(item.default_option.shipping_cost)
    );
  }, 0.0);

  store.dispatch(
    "setFinalShippingCost",
    isActiveHomeDelivery ? total_shipping_cost : 0
  );

  const total_tax = shippingPackages.reduce((accum, item) => {
    return parseFloat(accum) + parseFloat(item.tax);
  }, 0.0);

  store.dispatch(
    "setFinalTax",
    config?.enable_tax_in_checkout == enums.status.ACTIVE ? total_tax : 0
  );
}

/**
 * Build product_packages payload for payment / order create.
 */
export function buildProductPackages({
  shippingPackages,
  config,
  enums,
  isActiveHomeDelivery,
}) {
  const final_product_packages = [];
  for (let i = 0; i < shippingPackages.length; i++) {
    final_product_packages.push({
      uid: shippingPackages[i].id,
      tax:
        config?.enable_tax_in_checkout == enums.status.ACTIVE
          ? shippingPackages[i].tax
          : 0,
      product_id: shippingPackages[i].product.id,
      quantity: shippingPackages[i].product.quantity,
      unitPrice: shippingPackages[i].product.unitPrice,
      oldPrice: shippingPackages[i].product.oldPrice,
      variant_code: shippingPackages[i].product.variant_code,
      variant: shippingPackages[i].product.variant,
      image: shippingPackages[i].product.image,
      shipping_cost: isActiveHomeDelivery
        ? shippingPackages[i].default_option.shipping_cost
        : 0,
      shipping_rate_id: isActiveHomeDelivery
        ? shippingPackages[i].default_option.id
        : "",
      attatchment:
        shippingPackages[i].product.attachment != null
          ? shippingPackages[i].product.attachment.file_id
          : null,
    });
  }
  return final_product_packages;
}

/**
 * Fetch shipping options, update store totals, build product packages.
 * @returns {Promise<{success: boolean, shippingPackages?: Array, productPackages?: Array, isActiveHomeDelivery?: boolean}>}
 */
export async function prepareCheckoutShipping({ store, config, enums }) {
  const ctx = getDeliveryContext(store);

  try {
    const response = await axios.post(
      "/api/v1/ecommerce-core/get-shipping-options",
      {
        location: ctx.isActiveHomeDelivery ? ctx.city_id : null,
        shipping_type: ctx.isActiveHomeDelivery
          ? "home_delivery"
          : "pickup_delivery",
        post_code: ctx.post_code,
        products: JSON.stringify(store.state.checkoutItems),
        coupons: JSON.stringify(store.state.couponDiscount),
        zone_id:
          ctx.isActivePickupPoint &&
          ctx.selectedPickupPoint.zone_id != null
            ? ctx.selectedPickupPoint.zone_id
            : null,
      }
    );

    if (response.data.success && response.data.shipping_available) {
      const shippingPackages = response.data.options;
      applyShippingCostAndTax({
        store,
        config,
        enums,
        shippingPackages,
        isActiveHomeDelivery: ctx.isActiveHomeDelivery,
      });
      const productPackages = buildProductPackages({
        shippingPackages,
        config,
        enums,
        isActiveHomeDelivery: ctx.isActiveHomeDelivery,
      });
      return {
        success: true,
        shippingPackages,
        productPackages,
        isActiveHomeDelivery: ctx.isActiveHomeDelivery,
      };
    }

    store.dispatch("setFinalShippingCost", 0);
    return { success: false };
  } catch (error) {
    store.dispatch("setFinalShippingCost", 0);
    return { success: false };
  }
}
