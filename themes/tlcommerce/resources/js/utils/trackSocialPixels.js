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
const INITIATE_CHECKOUT_SESSION_KEY = 'tlc_pixel_initiate_checkout';
const PURCHASE_SESSION_PREFIX = 'tlc_pixel_purchase_';
const PIXEL_WAIT_MS = 5000;
const PIXEL_POLL_MS = 100;

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

function sessionGet(key) {
  try {
    return window.sessionStorage?.getItem(key);
  } catch (e) {
    return null;
  }
}

function sessionSet(key, value) {
  try {
    window.sessionStorage?.setItem(key, value);
  } catch (e) {}
}

function sessionRemove(key) {
  try {
    window.sessionStorage?.removeItem(key);
  } catch (e) {}
}

export function hasTrackedInitiateCheckout() {
  return sessionGet(INITIATE_CHECKOUT_SESSION_KEY) === '1';
}

export function markInitiateCheckoutTracked() {
  sessionSet(INITIATE_CHECKOUT_SESSION_KEY, '1');
}

export function hasTrackedPurchase(orderId) {
  if (orderId == null || orderId === '') {
    return false;
  }
  return sessionGet(PURCHASE_SESSION_PREFIX + String(orderId)) === '1';
}

export function markPurchaseTracked(orderId) {
  if (orderId == null || orderId === '') {
    return;
  }
  sessionSet(PURCHASE_SESSION_PREFIX + String(orderId), '1');
  sessionRemove(INITIATE_CHECKOUT_SESSION_KEY);
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

function coercePayload(payload = {}) {
  const next = { ...payload };
  if (next.value != null && next.value !== '') {
    const n = Number(next.value);
    if (Number.isFinite(n)) {
      next.value = n;
    } else {
      delete next.value;
    }
  }
  return next;
}

function waitFor(predicate, timeoutMs = PIXEL_WAIT_MS) {
  return new Promise((resolve) => {
    if (typeof window === 'undefined') {
      resolve(false);
      return;
    }
    if (predicate()) {
      resolve(true);
      return;
    }
    const start = Date.now();
    const id = setInterval(() => {
      if (predicate()) {
        clearInterval(id);
        resolve(true);
        return;
      }
      if (Date.now() - start >= timeoutMs) {
        clearInterval(id);
        resolve(false);
      }
    }, PIXEL_POLL_MS);
  });
}

function metaTrackOptions(options = {}) {
  if (!options || typeof options !== 'object') {
    return null;
  }
  if (options.eventID != null && options.eventID !== '') {
    return { eventID: String(options.eventID) };
  }
  return Object.keys(options).length ? options : null;
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

async function fireFacebook(event, payload, options) {
  try {
    const ready = await waitFor(() => typeof window.fbq === 'function');
    if (!ready) {
      return false;
    }
    if (options) {
      window.fbq('track', event, payload, options);
    } else {
      window.fbq('track', event, payload);
    }
    return true;
  } catch (e) {
    console.error('[pixels] fbq failed', e);
    return false;
  }
}

async function fireTikTok(event, payload) {
  try {
    const ready = await waitFor(() => typeof window.ttq?.track === 'function');
    if (!ready) {
      return;
    }
    window.ttq.track(mapTikTokEvent(event), payload);
  } catch (e) {
    console.error('[pixels] ttq failed', e);
  }
}

async function fireSnapchat(event, payload) {
  try {
    const ready = await waitFor(() => typeof window.snaptr === 'function');
    if (!ready) {
      return;
    }
    window.snaptr('track', mapSnapchatEvent(event), payload);
  } catch (e) {
    console.error('[pixels] snaptr failed', e);
  }
}

/**
 * Fire-and-forget social pixel tracking. Each provider is isolated so
 * a failure in one SDK never affects checkout or other trackers.
 * Also mirrors selected events into tl_com_analytics_events (best-effort).
 * Retries briefly if the pixel stub is not on window yet.
 * Resolves true when Facebook fbq('track') ran.
 */
export function trackSocialPixels(event, payload = {}, options = {}) {
  const normalized = coercePayload(payload);
  const fbOptions = metaTrackOptions(options);

  const fbFired = fireFacebook(event, normalized, fbOptions);
  fireTikTok(event, normalized);
  fireSnapchat(event, normalized);

  persistAnalyticsEvent(event, normalized);

  return fbFired;
}
