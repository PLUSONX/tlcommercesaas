import axios from 'axios';

const TIKTOK_EVENT_MAP = {
  ViewContent: 'ViewContent',
  AddToCart: 'AddToCart',
  InitiateCheckout: 'InitiateCheckout',
  Purchase: 'CompletePayment',
};

const SNAPCHAT_EVENT_MAP = {
  ViewContent: 'VIEW_CONTENT',
  AddToCart: 'ADD_CART',
  InitiateCheckout: 'START_CHECKOUT',
  Purchase: 'PURCHASE',
};

const ANALYTICS_TRACK_URL = '/api/v1/ecommerce-core/analytics/track';
const STORE_VISIT_SESSION_KEY = 'tlc_analytics_store_visit';

function mapTikTokEvent(event) {
  return TIKTOK_EVENT_MAP[event] || event;
}

function mapSnapchatEvent(event) {
  return SNAPCHAT_EVENT_MAP[event] || event;
}

function hasStoreVisitThisSession() {
  try {
    return window.sessionStorage?.getItem(STORE_VISIT_SESSION_KEY) === '1';
  } catch (e) {
    return false;
  }
}

function markStoreVisitThisSession() {
  try {
    window.sessionStorage?.setItem(STORE_VISIT_SESSION_KEY, '1');
  } catch (e) {}
}

/**
 * Extract a single product id from common pixel payload shapes.
 */
function extractProductId(payload = {}) {
  if (payload.content_id != null && payload.content_id !== '') {
    const id = Number(payload.content_id);
    return Number.isFinite(id) && id > 0 ? id : null;
  }

  const ids = payload.content_ids;
  if (Array.isArray(ids) && ids.length > 0) {
    const id = Number(ids[0]);
    return Number.isFinite(id) && id > 0 ? id : null;
  }

  if (ids != null && ids !== '') {
    const id = Number(ids);
    return Number.isFinite(id) && id > 0 ? id : null;
  }

  return null;
}

/**
 * Map FB-style event + payload to local analytics event_type.
 * Returns null for events we do not store (e.g. Purchase).
 */
function mapToAnalyticsEvent(event, payload = {}) {
  if (event === 'AddToCart') {
    return { event_type: 'add_to_cart', product_id: extractProductId(payload) };
  }

  if (event === 'InitiateCheckout') {
    return { event_type: 'checkout', product_id: null };
  }

  if (event === 'ViewContent') {
    if (payload.content_category === 'Store') {
      return { event_type: 'store_visit', product_id: null };
    }
    return { event_type: 'content_view', product_id: extractProductId(payload) };
  }

  return null;
}

/**
 * Best-effort local analytics write. Never throws into callers.
 * store_visit is recorded at most once per browser session.
 */
function persistAnalyticsEvent(event, payload = {}) {
  try {
    const mapped = mapToAnalyticsEvent(event, payload);
    if (!mapped) {
      return;
    }

    if (mapped.event_type === 'store_visit') {
      if (hasStoreVisitThisSession()) {
        return;
      }
      markStoreVisitThisSession();
    }

    axios
      .post(ANALYTICS_TRACK_URL, {
        event_type: mapped.event_type,
        product_id: mapped.product_id,
      })
      .catch(() => {});
  } catch (e) {
    console.error('[analytics] track failed', e);
  }
}

/**
 * Fire-and-forget social pixel tracking. Each provider is isolated so
 * a failure in one SDK never affects checkout or other trackers.
 * Also mirrors selected events into tl_com_analytics_events (best-effort).
 */
export function trackSocialPixels(event, payload = {}) {
  try {
    if (typeof window.fbq === 'function') {
      window.fbq('track', event, payload);
    }
  } catch (e) {
    console.error('[pixels] fbq failed', e);
  }

  try {
    if (typeof window.ttq?.track === 'function') {
      window.ttq.track(mapTikTokEvent(event), payload);
    }
  } catch (e) {
    console.error('[pixels] ttq failed', e);
  }

  try {
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', mapSnapchatEvent(event), payload);
    }
  } catch (e) {
    console.error('[pixels] snaptr failed', e);
  }

  persistAnalyticsEvent(event, payload);
}
