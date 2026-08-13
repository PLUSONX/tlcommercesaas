<template>
    <!-- Offcanvas -->
    <div class="offcanvas-container d-flex">
        <!-- Offcanvas Trigger -->
        <button
            ref="hamburger"
            class="hamburger"
            :class="{ active: isOffcanvasOpened }"
            @click="toggleOffcanvas"
        >
            <span class="bg-white"></span>
            <span class="bg-white"></span>
            <span class="bg-white"></span>
        </button>
        <!-- End Offcanvas Trigger -->

        <teleport to="body">
        <div class="offcanvas-wrapper" :class="{ open: isOffcanvasOpened, 'offcanvas-wrapper--rtl': isRtl }">
            <div ref="offcanvasPanel" :class="this.headerStyle.custom_header==1?'offcanvas-panel custom-offcanvas-panel w-100 h-100 bg-white':'offcanvas-panel w-100 h-100 bg-white'">
                <div :class="this.headerStyle.custom_header==1?'custom-offcanvas-header offcanvas-header position-relative':'offcanvas-header position-relative'">
                    <!-- Offcanvas Close -->
                    <span
                        class="offcanvas-close d-inline-flex align-items-center justify-content-center position-absolute text-white"
                        @click="toggleOffcanvas"
                    >
                        <base-icon-svg name="close" />
                    </span>
                    <!-- End Offcanvas Close -->

                    <!-- User Info -->
                    <div v-if="userInfo.name" class="user-info">
                        <div class="user-avatar mb-10">
                             <img
                                class="rounded-circle"
                                :src="customerProfileImage" 
                                :alt="userInfo.name"
                                width="70"
                                height="70"
                            />
                            <!-- <img
                                class="rounded-circle"
                                :src="userInfo.image"
                                :alt="userInfo.name"
                                width="70"
                                height="70"
                            /> -->
                        </div>
                        <h4 class="mb-0">{{ userInfo.name }}</h4>
                    </div>
                    <!-- End User Info -->

                    <!-- Login Register -->
                    <div v-else class="login-register mt-15">
                        <a href="/login">
                            {{ $t("Login") }}</a
                        >
                        |
                        <a href="/register">
                            {{ $t("Registration") }}
                        </a>
                    </div>
                    <!-- End Login Register -->
                </div>

                <div class="offcanvas-content bg-white">
                    <div class="offcanvas-menu">
                        <ul class="list-unstyled mb-0">
                            <template v-for="(item, index) in menuItems">
                                <menu-item
                                    v-if="!item.submenu"
                                    :key="`item-${index}`"
                                    :item="item"
                                    off-canvas
                                    :header-menu-style="headerMenuStyle"
                                />

                                <menu-dropdown
                                    v-else
                                    :key="`group-${index}`"
                                    :item="item"
                                    off-canvas
                                    :open-group="
                                        isGroupActive(item, $route.fullPath)
                                    "
                                    :header-menu-style="headerMenuStyle"
                                />
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </teleport>
    </div>
    <!-- End Offcanvas -->
</template>

<script>
import MenuItem from "./MenuItem.vue";
import MenuDropdown from "./MenuDropdown.vue";
import { mapGetters } from "vuex";
export default {
    components: {
        MenuItem,
        MenuDropdown,
    },
    props: {
        userInfo: {
            type: Object,
            default: null,
        },
        menuItems: {
            type: Array,
            required: true,
        },
        headerMenuStyle: {
            type: Object,
            required: false,
            default: () => {
                return {};
            },
        },
        headerStyle: {
            type: Object,
            required: false,
            default: () => {
                return {};
            },
        },
    },
    data() {
        return {
            isOffcanvasOpened: false,
        };
    },
    computed: {
        ...mapGetters("layout", ["isRtl"]),
        isGroupActive() {
            return (item, path) => {
                let openGroup = false;
                const func = (item) => {
                    if (item.submenu) {
                        item.submenu.forEach((item) => {
                            if (path === item.url) {
                                openGroup = true;
                            } else if (item.submenu) {
                                func(item);
                            }
                        });
                    }
                };
                func(item, path);
                return openGroup;
            };
        },

        customerProfileImage() {
            const image = this.userInfo?.image;

            if (!image) {
            return '/themes/tlcommerce/assets/images/icons/dashboard/profile.svg';
            }

            // Remove `/public` ONLY if it exists
            return image.replace(/^\/public/, '');
        }
    },
    mounted() {
        document.addEventListener("click", this.onDocumentClick);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.onDocumentClick);
    },
    methods: {
        toggleOffcanvas() {
            this.isOffcanvasOpened = !this.isOffcanvasOpened;
            document.body.classList.toggle("offcanvas-oppened");
        },
        closeOffcanvas() {
            if (!this.isOffcanvasOpened) {
                return;
            }
            this.isOffcanvasOpened = false;
            document.body.classList.remove("offcanvas-oppened");
        },
        onDocumentClick(e) {
            if (!this.isOffcanvasOpened) {
                return;
            }

            const panel = this.$refs.offcanvasPanel;
            const hamburger = this.$refs.hamburger;
            const target = e.target;

            if (panel && (panel === target || panel.contains(target))) {
                return;
            }

            if (hamburger && (hamburger === target || hamburger.contains(target))) {
                return;
            }

            this.closeOffcanvas();
        },
    },
};
</script>

<style lang="scss" scoped>
.offcanvas-wrapper--rtl {
  direction: rtl;
}
.offcanvas-wrapper--rtl :deep(.offcanvas-panel) {
  left: auto;
  right: 0;
  transform: translate3d(100%, 0, 0);
}
.offcanvas-wrapper--rtl.open :deep(.offcanvas-panel) {
  transform: none;
}
.offcanvas-wrapper--rtl :deep(.offcanvas-close) {
  right: auto;
  left: 0;
}
.offcanvas-wrapper--rtl :deep(.offcanvas-menu ul ul) {
  padding-left: 0;
  padding-right: 30px;
}
.offcanvas-wrapper--rtl :deep(.offcanvas-menu .submenu-button) {
  right: auto;
  left: 0;
}
.offcanvas-wrapper--rtl :deep(.offcanvas-menu a),
.offcanvas-wrapper--rtl :deep(.user-info),
.offcanvas-wrapper--rtl :deep(.login-register) {
  text-align: right;
}
</style>
