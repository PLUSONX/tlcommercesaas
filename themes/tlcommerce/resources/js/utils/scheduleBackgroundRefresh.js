/**
 * Run non-critical work after first paint (idle callback or timeout fallback).
 * @param {() => void} fn
 * @param {number} delayMs — fixed delay when > 0; otherwise idle/1500ms fallback
 */
export function scheduleBackgroundRefresh(fn, delayMs = 0) {
  const run = () => {
    try {
      fn();
    } catch (e) {
      console.warn("[tlcommerce] background refresh failed", e);
    }
  };

  if (delayMs > 0) {
    setTimeout(run, delayMs);
    return;
  }

  if (typeof requestIdleCallback === "function") {
    requestIdleCallback(run, { timeout: 3000 });
  } else {
    setTimeout(run, 1500);
  }
}
