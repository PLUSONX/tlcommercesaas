const axios = require("axios").default;

/** Split-screen desktop column ratio — hardcoded; DB split_ratio is reference only. */
const SPLIT_SCREEN_RATIO = { content: 45, feature: 55 };

export default {
    namespaced: true,

    state: {
        activeLayout: null,
        loading: false,
        error: null,
        isMobileView: false
    },

    mutations: {
        SET_LAYOUT(state, layout) {
            state.activeLayout = layout;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        },
        SET_MOBILE_VIEW(state, isMobile) {
            state.isMobileView = isMobile;
        }
    },

    actions: {
        async fetchActiveLayout({ commit }) {
            commit('SET_LOADING', true);
            try {
                const response = await axios.get('/api/theme/tlcommerce/v1/get-active-layout');
                // console.log("response: ", response);
                commit('SET_LAYOUT', response.data.layout);
                commit('SET_ERROR', null);
            } catch (error) {
                commit('SET_ERROR', error.message);
                console.error('Failed to fetch layout:', error);
            } finally {
                commit('SET_LOADING', false);
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