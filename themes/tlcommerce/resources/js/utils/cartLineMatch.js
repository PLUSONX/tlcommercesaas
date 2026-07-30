/**
 * Cart line identity aligned with storeCartItems (id + variant label).
 */

export function normalizeProductId(id) {
  if (id == null || id === "") {
    return "";
  }
  return String(id);
}

/**
 * Build the variant label stored on cart rows (same logic as CustomFooter / DetailsContent).
 */
export function buildProductVariantLabel(product) {
  if (!product || product.has_variant != 1 || !product.selectedVariant) {
    return null;
  }

  const output = [];
  const variant_array = product.selectedVariant.split("/");

  for (let i = 0; i < variant_array.length; i++) {
    const single_variant = variant_array[i];
    const single_variant_array = single_variant.split(":");
    const variant_id = single_variant_array[0];
    const variant_value_ids = (single_variant_array[1] || "")
      .split(",")
      .filter(Boolean);
    const match_variant = product.attribute?.find(
      (attr) => attr.id == variant_id
    );
    if (!match_variant) {
      continue;
    }

    const variant_name = match_variant.title;

    const variant_value_name = variant_value_ids
      .map((variant_value_id) => {
        const match_variant_value = match_variant.options.find(
          (opt) => opt.id == variant_value_id
        );
        if (variant_name == "Color" || variant_name == "color") {
          return match_variant_value?.name;
        }
        return match_variant_value?.title;
      })
      .filter(Boolean)
      .join(",");

    output.push(`${variant_name}:${variant_value_name}`);
  }

  const text = output.join("/");
  return text || null;
}

/**
 * @param {Array} cart
 * @param {{ id: *, selectedVariant?: string|null, attribute?: Array }} product
 */
function cartRowMatchesProduct(row, product) {
  if (!product || product.id == null || !row) {
    return false;
  }
  const productId = normalizeProductId(product.id);
  const variantLabel = buildProductVariantLabel(product);
  if (normalizeProductId(row.id) !== productId) {
    return false;
  }
  return row.variant === variantLabel;
}

export function isProductLineInCart(cart, product) {
  return (cart || []).some((row) => cartRowMatchesProduct(row, product));
}

/**
 * @returns {object|null} matching cart row
 */
export function findProductCartLine(cart, product) {
  if (!product || product.id == null) {
    return null;
  }
  return (cart || []).find((row) => cartRowMatchesProduct(row, product)) ?? null;
}

/**
 * Cart line for split-screen product list rows.
 * @param {Array} cart
 * @param {{ id: *, has_variant: number }} item
 * @returns {object|null}
 */
export function findListItemCartLine(cart, item) {
  if (!item || item.id == null) {
    return null;
  }

  if (item.has_variant == 2) {
    return findProductCartLine(cart, { id: item.id, has_variant: 2 });
  }

  const productId = normalizeProductId(item.id);
  return (
    (cart || []).find((row) => normalizeProductId(row.id) === productId) ?? null
  );
}

export function resolveOrderQuantity(quantityValue) {
  const qty = Number(quantityValue);
  return Number.isFinite(qty) && qty >= 1 ? qty : 1;
}
