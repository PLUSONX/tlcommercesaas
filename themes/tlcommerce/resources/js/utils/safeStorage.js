/**
 * Safe localStorage helpers — Instagram / restrictive WebViews can throw or
 * leave corrupt values that would otherwise white-screen the SPA at boot.
 */

export function safeGetItem(key) {
  try {
    return localStorage.getItem(key);
  } catch (e) {
    return null;
  }
}

export function safeSetItem(key, value) {
  try {
    localStorage.setItem(key, value);
    return true;
  } catch (e) {
    return false;
  }
}

export function safeRemoveItem(key) {
  try {
    localStorage.removeItem(key);
    return true;
  } catch (e) {
    return false;
  }
}

/**
 * Parse a localStorage JSON value. Clears the key if corrupt.
 * @param {string} key
 * @param {*} fallback
 */
export function safeJsonParse(key, fallback = null) {
  try {
    const raw = localStorage.getItem(key);
    if (raw == null || raw === "" || raw === "undefined") {
      return fallback;
    }
    return JSON.parse(raw);
  } catch (e) {
    safeRemoveItem(key);
    return fallback;
  }
}

export function asArray(value, fallback = []) {
  return Array.isArray(value) ? value : fallback;
}

/** sessionStorage variants (layout hint — safer than full-store multi-tab sync) */

export function safeSessionGetItem(key) {
  try {
    return sessionStorage.getItem(key);
  } catch (e) {
    return null;
  }
}

export function safeSessionSetItem(key, value) {
  try {
    sessionStorage.setItem(key, value);
    return true;
  } catch (e) {
    return false;
  }
}

export function safeSessionRemoveItem(key) {
  try {
    sessionStorage.removeItem(key);
    return true;
  } catch (e) {
    return false;
  }
}

export function safeSessionJsonParse(key, fallback = null) {
  try {
    const raw = sessionStorage.getItem(key);
    if (raw == null || raw === "" || raw === "undefined") {
      return fallback;
    }
    return JSON.parse(raw);
  } catch (e) {
    safeSessionRemoveItem(key);
    return fallback;
  }
}
