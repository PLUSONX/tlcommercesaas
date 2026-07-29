import axios from "axios";
import {
  safeSessionJsonParse,
  safeSessionSetItem,
} from "../../utils/safeStorage";

/** Split-screen desktop column ratio — hardcoded; DB split_ratio is reference only. */
const SPLIT_SCREEN_RATIO = { content: 45, feature: 55 };

const LAYOUT_HINT_KEY = "tlc_active_layout";
const LAYOUT_FETCH_TIMEOUT_MS = 20000;

function readLayoutHint() {
  const hint = safeSessionJsonParse(LAYOUT_HINT_KEY, null);
  if (hint && typeof hint === "object" && hint.type) {
    return hint;
  }
  return null;
}

function readBootstrapLayout() {
  try {
    const boot =
      typeof window !== "undefined" ? window.__TLC_BOOTSTRAP__ : null;
    const layout = boot?.activeLayout;
    if (layout && typeof layout === "object" && layout.type) {
      return layout;
    }
  } catch (e) {
    // ignore
  }
  return null;
}

function readInitialLayout() {
  return readBootstrapLayout() || readLayoutHint();
}

function extractLayoutPayload(data) {
  if (!data || typeof data !== "object") {
    return null;
  }
  return data.layout ?? data.data?.layout ?? null;
}

const initialLayout = readInitialLayout();
const hasInitialLayout = !!(initialLayout && initialLayout.type);

if (hasInitialLayout) {
  safeSessionSetItem(LAYOUT_HINT_KEY, JSON.stringify(initialLayout));
}

export default {
    namespaced: true,

    state: {
        // Prefer server-injected bootstrap so first paint is correct and instant
        activeLayout: initialLayout,
        loading: !hasInitialLayout,
        hasFetched: hasInitialLayout,
        error: null,
        isMobileView: false,
        isRtl: false,
    },

    mutations: {
        SET_LAYOUT(state, layout) {
            state.activeLayout = layout;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_HAS_FETCHED(state, hasFetched) {
            state.hasFetched = hasFetched;
        },
        SET_ERROR(state, error) {
            state.error = error;
        },
        SET_MOBILE_VIEW(state, isMobile) {
            state.isMobileView = isMobile;
        },
        SET_IS_RTL(state, isRtl) {
            state.isRtl = isRtl;
        }
    },

    actions: {
        async fetchActiveLayout({ commit, state }) {
            const hasUsableLayout = !!(state.activeLayout?.type);
            // Only block UI when we have no server/session layout yet
            if (!hasUsableLayout) {
                commit('SET_LOADING', true);
            }
            try {
                const response = await axios.get('/api/theme/tlcommerce/v1/get-active-layout', {
                    timeout: LAYOUT_FETCH_TIMEOUT_MS,
                });
                // console.log("response: ", response);
                const layout = extractLayoutPayload(response.data);
                if (layout && typeof layout === 'object') {
                    commit('SET_LAYOUT', layout);
                    commit('SET_ERROR', null);
                    safeSessionSetItem(LAYOUT_HINT_KEY, JSON.stringify(layout));
                } else if (!state.activeLayout?.type) {
                    // No usable payload and no prior hint — clear to default
                    commit('SET_LAYOUT', null);
                    commit('SET_ERROR', null);
                } else {
                    // Keep existing bootstrap / hint; do not wipe split_screen
                    commit('SET_ERROR', null);
                }
            } catch (error) {
                commit('SET_ERROR', error.message);
                console.error('Failed to fetch layout:', error);
                // Keep existing activeLayout (bootstrap / session hint) on timeout/error
            } finally {
                commit('SET_LOADING', false);
                commit('SET_HAS_FETCHED', true);
            }
        },

        updateMobileView({ commit }, isMobile) {
            commit('SET_MOBILE_VIEW', isMobile);
        }
    },

    getters: {
        isSplitScreen: (state) => {
            // if (state.isMobileView) {
            //     return false; // Force default layout on mobile
            // }
            return state.activeLayout?.type === 'split_screen';
        },

        splitScreenSettings: (state) => {
            return state.activeLayout?.split_screen || null;
        },

        contentPosition: (state) => {
            return state.activeLayout?.split_screen?.content_position || 'left';
        },

        isRtl: (state) => state.isRtl,

        effectiveContentPosition: (state, getters) => {
            const base = getters.contentPosition;
            if (!getters.isRtl) return base;
            return base === 'left' ? 'right' : 'left';
        },

        featureImagePath: (state) => {
            //   console.log("state: ", state);
            // console.log("Banner Feature path: ", state.activeLayout?.split_screen?.feature_image_path);
            return state.activeLayout?.split_screen?.feature_image_path || null;
        },

        featureType: (state) => {
            return state.activeLayout?.split_screen?.feature_type || 'banner';
        },

        splitRatio: () => ({ ...SPLIT_SCREEN_RATIO }),

        contentColumnPercent: () => SPLIT_SCREEN_RATIO.content,

        isMobile: (state) => state.isMobileView
    }
};
