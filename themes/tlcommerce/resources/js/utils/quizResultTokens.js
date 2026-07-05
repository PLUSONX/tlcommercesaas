/**
 * Build token context from ranked quiz API results.
 * @param {Array} results - [{ product_id, match_pct, rank, product: { name, slug, ... } }]
 * @param {Array} [productProfiles] - quiz layout_config.results.product_profiles
 */
import { formatMultilineHtml, normalizeMultilineText, escapeHtml } from "./multilineText";

export function buildResultContext(results, productProfiles = []) {
  const list = Array.isArray(results) ? results : [];
  const profiles = Array.isArray(productProfiles) ? productProfiles : [];
  const byRank = {};
  const top = list[0] || null;

  list.forEach((item, index) => {
    const rank = item.rank || index + 1;
    byRank[rank] = mapResultItem(item, rank, profiles);
    byRank[`product_${rank}`] = byRank[rank];
  });

  const topMapped = top ? mapResultItem(top, top.rank || 1, profiles) : emptyProduct();

  return {
    product: topMapped,
    match_pct: topMapped.match_pct,
    rank: topMapped.rank,
    byRank,
    results: list,
  };
}

function profileForProductId(profiles, productId) {
  if (!productId || !Array.isArray(profiles)) return null;
  return (
    profiles.find(
      (p) => p && p.enabled !== false && Number(p.product_id) === Number(productId)
    ) || null
  );
}

function emptyProduct() {
  return {
    name: "",
    url: "",
    summary: "",
    price: "",
    image: "",
    tagline: "",
    color: "",
    match_pct: 0,
    rank: 0,
  };
}

function mapResultItem(item, rank, profiles = []) {
  const product = item.product || {};
  const slug = product.slug || "";
  const productId = item.product_id ?? product.id ?? null;
  const profile = profileForProductId(profiles, productId);
  const summaryRaw = product.summary ?? product.short_description ?? "";
  const summary = normalizeMultilineText(summaryRaw);
  return {
    name: product.name || "",
    url: slug ? `/products/${slug}` : "",
    summary,
    price: formatPrice(product),
    image: productImageUrl(product),
    tagline: profile?.tagline || "",
    color: profile?.color || "",
    match_pct: item.match_pct ?? 0,
    rank: rank,
    product_id: productId,
    raw: product,
  };
}

function productImageUrl(product) {
  if (!product) return "";
  const thumb = product.thumbnail_image;
  if (typeof thumb === "string" && thumb.trim()) return thumb.trim();
  if (thumb && typeof thumb === "object") {
    return thumb.medium || thumb.small || thumb.original || "";
  }
  const fallback = product.image || "";
  return typeof fallback === "string" ? fallback : "";
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
    return formatTokenValue(key, context.product?.[key] ?? "");
  });

  out = out.replace(/\{\{product_(\d+)\.(\w+)\}\}/g, (_, rank, key) => {
    const item = context.byRank?.[parseInt(rank, 10)] || context.byRank?.[`product_${rank}`];
    return formatTokenValue(key, item?.[key] ?? "");
  });

  out = out.replace(/\{\{match_pct\}\}/g, () => String(context.match_pct ?? ""));
  out = out.replace(/\{\{rank\}\}/g, () => String(context.rank ?? ""));

  return out;
}

function formatTokenValue(key, value) {
  if (key === "summary") {
    return formatMultilineHtml(value);
  }
  return escapeHtml(String(value ?? ""));
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

