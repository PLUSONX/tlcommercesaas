import {
  safeGetItem,
  safeSetItem,
  safeRemoveItem,
  safeSessionRemoveItem,
} from "./safeStorage";
import { reloadOnceWithCacheBuster } from "./reloadOnceWithCacheBuster";

const VERSION_KEY = "tlc_asset_version";

/** Cleared on deploy so Vuex re-fetches from API; auth and cart are kept. */
const LOCAL_KEYS_TO_PURGE = ["siteSettings", "siteProperties"];

const SESSION_LAYOUT_KEY = "tlc_active_layout";

function getDeployedVersion() {
  try {
    const boot = window.__TLC_BOOTSTRAP__;
    if (boot && boot.assetVersion) {
      return String(boot.assetVersion);
    }
  } catch (e) {
    // ignore
  }
  if (typeof __TLC_ASSET_VERSION__ !== "undefined" && __TLC_ASSET_VERSION__) {
    return String(__TLC_ASSET_VERSION__);
  }
  return null;
}

function getBundleVersion() {
  if (typeof __TLC_ASSET_VERSION__ !== "undefined" && __TLC_ASSET_VERSION__) {
    return String(__TLC_ASSET_VERSION__);
  }
  return null;
}

function versionReloadKey(deployedVersion) {
  return `tlc_version_reload_${deployedVersion}`;
}

function purgeChunkReloadFlags() {
  try {
    const prefix = "tlc_chunk_reload_";
    const keys = [];
    for (let i = 0; i < sessionStorage.length; i++) {
      const k = sessionStorage.key(i);
      if (k && k.startsWith(prefix)) {
        keys.push(k);
      }
    }
    keys.forEach((k) => sessionStorage.removeItem(k));
  } catch (e) {
    // sessionStorage may be blocked
  }
}

export function purgeStaleClientStorageIfNeeded() {
  const deployed = getDeployedVersion();
  if (!deployed) {
    return;
  }

  const bundle = getBundleVersion();
  if (bundle && bundle !== deployed) {
    reloadOnceWithCacheBuster(versionReloadKey(deployed));
    return;
  }

  const stored = safeGetItem(VERSION_KEY);
  if (stored === deployed) {
    return;
  }

  LOCAL_KEYS_TO_PURGE.forEach((key) => safeRemoveItem(key));
  safeSessionRemoveItem(SESSION_LAYOUT_KEY);
  purgeChunkReloadFlags();
  safeSetItem(VERSION_KEY, deployed);
  reloadOnceWithCacheBuster(versionReloadKey(deployed));
}

purgeStaleClientStorageIfNeeded();
