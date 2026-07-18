const CHUNK_RELOAD_KEY = "tlc_chunk_reload_v220";

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
  try {
    if (!sessionStorage.getItem(CHUNK_RELOAD_KEY)) {
      sessionStorage.setItem(CHUNK_RELOAD_KEY, "1");
      const url = new URL(window.location.href);
      url.searchParams.set("_tlc_cb", String(Date.now()));
      window.location.replace(url.toString());
      return true;
    }
  } catch (e) {
    // sessionStorage may be blocked; fall through
  }
  return false;
}
