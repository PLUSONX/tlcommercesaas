/**
 * Build token context from ranked quiz API results.
 * @param {Array} results - [{ product_id, match_pct, rank, product: { name, slug, ... } }]
 */
export function buildResultContext(results) {
  const list = Array.isArray(results) ? results : [];
  const byRank = {};
  const top = list[0] || null;

  list.forEach((item, index) => {
    const rank = item.rank || index + 1;
    byRank[rank] = mapResultItem(item, rank);
    byRank[`product_${rank}`] = byRank[rank];
  });

  const topMapped = top ? mapResultItem(top, top.rank || 1) : emptyProduct();

  return {
    product: topMapped,
    match_pct: topMapped.match_pct,
    rank: topMapped.rank,
    byRank,
    results: list,
  };
}

function emptyProduct() {
  return {
    name: "",
    url: "",
    summary: "",
    price: "",
    image: "",
    match_pct: 0,
    rank: 0,
  };
}

function mapResultItem(item, rank) {
  const product = item.product || {};
  const slug = product.slug || "";
  return {
    name: product.name || "",
    url: slug ? `/products/${slug}` : "",
    summary: product.summary || product.short_description || "",
    price: formatPrice(product),
    image: productImageUrl(product),
    match_pct: item.match_pct ?? 0,
    rank: rank,
    raw: product,
  };
}

function productImageUrl(product) {
  if (!product) return "";
  const thumb = product.thumbnail_image;
  if (typeof thumb === "string") return thumb;
  if (thumb && typeof thumb === "object") {
    return thumb.medium || thumb.small || thumb.original || "";
  }
  return product.image || "";
}

function formatPrice(product) {
  if (!product) return "";
  const price = product.price ?? product.base_price;
  if (price == null || price === "") return "";
  return String(price);
}

/**
 * Replace {{product.name}}, {{product_2.match_pct}}, etc.
 */
export function resolveTokens(html, context) {
  if (!html || !context) return html || "";

  let out = String(html);

  out = out.replace(/\{\{product\.(\w+)\}\}/g, (_, key) => {
    return escapeHtml(String(context.product?.[key] ?? ""));
  });

  out = out.replace(/\{\{product_(\d+)\.(\w+)\}\}/g, (_, rank, key) => {
    const item = context.byRank?.[parseInt(rank, 10)] || context.byRank?.[`product_${rank}`];
    return escapeHtml(String(item?.[key] ?? ""));
  });

  out = out.replace(/\{\{match_pct\}\}/g, () => String(context.match_pct ?? ""));
  out = out.replace(/\{\{rank\}\}/g, () => String(context.rank ?? ""));

  return out;
}

function escapeHtml(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

export function getProductForRank(context, rank) {
  const r = parseInt(rank, 10) || 1;
  return context.byRank?.[r] || context.product || emptyProduct();
}

/**
 * Resolve an action URL for internal SPA navigation vs external anchor.
 * @returns {{ kind: 'router', to: string } | { kind: 'external', href: string } | null}
 */
export function resolveActionLink(url) {
  const raw = String(url || "").trim();
  if (!raw) return null;

  if (/^https?:\/\//i.test(raw)) {
    try {
      const parsed = new URL(raw);
      if (typeof window !== "undefined" && parsed.origin === window.location.origin) {
        return { kind: "router", to: parsed.pathname + parsed.search + parsed.hash };
      }
      return { kind: "external", href: raw };
    } catch {
      return { kind: "external", href: raw };
    }
  }

  if (raw.startsWith("//")) {
    return { kind: "external", href: raw };
  }

  const cleaned = raw.replace(/^\.\//, "").replace(/^\/+/, "");

  if (typeof window !== "undefined") {
    try {
      const parsed = new URL(cleaned, window.location.origin);
      if (parsed.origin !== window.location.origin) {
        return { kind: "external", href: parsed.href };
      }
      const to = parsed.pathname + parsed.search + parsed.hash;
      return { kind: "router", to: to.startsWith("/") ? to : `/${to}` };
    } catch {
      // fall through to manual normalization
    }
  }

  const path = `/${cleaned}`;
  return { kind: "router", to: path };
}

