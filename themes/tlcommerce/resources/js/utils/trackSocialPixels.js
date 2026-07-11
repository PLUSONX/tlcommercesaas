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

function mapTikTokEvent(event) {
  return TIKTOK_EVENT_MAP[event] || event;
}

function mapSnapchatEvent(event) {
  return SNAPCHAT_EVENT_MAP[event] || event;
}

/**
 * Fire-and-forget social pixel tracking. Each provider is isolated so
 * a failure in one SDK never affects checkout or other trackers.
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
}
