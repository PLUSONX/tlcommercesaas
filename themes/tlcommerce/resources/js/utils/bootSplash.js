const LOADING_KEYS = [
  "pageLoading",
  "productLoading",
  "productsLoading",
  "dataLoading",
];

let initialNavigationPending = true;

export function isInitialNavigationPending() {
  return initialNavigationPending;
}

export function markInitialNavigationDone() {
  initialNavigationPending = false;
}

export function hideBootSplash() {
  markInitialNavigationDone();
  if (
    typeof window !== "undefined" &&
    typeof window.__TLC_HIDE_BOOT_LOADER__ === "function"
  ) {
    window.__TLC_HIDE_BOOT_LOADER__();
  }
}

function isRouteView(vm) {
  const matched = vm.$route && vm.$route.matched;
  if (!matched || !matched.length) {
    return false;
  }
  const last = matched[matched.length - 1];
  return last.instances && last.instances.default === vm;
}

/**
 * Hide the Blade boot overlay once the first route view's primary
 * loading flag becomes false. Pages that manage hide themselves
 * (e.g. home on split-screen) set bootSplashManual.
 */
export function createBootSplashMixin() {
  return {
    mounted() {
      if (!isInitialNavigationPending()) {
        return;
      }
      if (this.$options.bootSplashManual) {
        return;
      }
      if (!isRouteView(this)) {
        return;
      }

      const key = LOADING_KEYS.find((name) =>
        Object.prototype.hasOwnProperty.call(this.$data, name)
      );

      if (!key) {
        this.$nextTick(() => hideBootSplash());
        return;
      }

      if (!this[key]) {
        this.$nextTick(() => hideBootSplash());
        return;
      }

      this.$watch(key, (loading) => {
        if (!loading) {
          hideBootSplash();
        }
      });
    },
  };
}
