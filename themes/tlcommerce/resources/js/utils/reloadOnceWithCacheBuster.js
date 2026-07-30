/**
 * One-shot hard navigation with a cache-buster query param so browsers /
 * in-app WebViews fetch a fresh HTML shell and JS assets.
 *
 * @param {string} sessionKey — unique per reload reason/version; prevents loops
 * @returns {boolean} true if navigation was triggered
 */
export function reloadOnceWithCacheBuster(sessionKey) {
  try {
    if (!sessionStorage.getItem(sessionKey)) {
      sessionStorage.setItem(sessionKey, "1");
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
