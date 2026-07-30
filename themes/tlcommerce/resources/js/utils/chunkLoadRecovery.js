import { reloadOnceWithCacheBuster } from "./reloadOnceWithCacheBuster";

const CHUNK_RELOAD_KEY = `tlc_chunk_reload_${__TLC_ASSET_VERSION__}`;

export function isChunkLoadError(err) {
  const msg = err && (err.message || String(err));
  return /Loading chunk|ChunkLoadError|Failed to fetch dynamically imported module|Importing a module script failed/i.test(
    msg || ""
  );
}

/**
 * One-shot hard navigation so browsers / Instagram WebView pick up a fresh
 * HTML shell and main.js after a stale or mid-deploy async chunk miss.
 */
export function reloadOnceForChunkError() {
  return reloadOnceWithCacheBuster(CHUNK_RELOAD_KEY);
}
