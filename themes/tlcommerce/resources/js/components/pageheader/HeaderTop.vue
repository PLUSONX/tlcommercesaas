<template>
  <!-- Header Top Bar -->
  <div class="custom-header-top-bar header-top-bar">
    <div class="custom-container2">
      <div class="row align-items-center">
        <div class="col-lg-6 col-md-6 col-sm-4 col-4">
          <div class="header-info-wrap">
            <ul class="list-unstyled header-info">
              <!-- Menu -->
              <template v-if="dataLoading">
                <li class="desktop">
                  <skeleton
                    class="router-link-active router-link-exact-active"
                    height="12px"
                    border-radius="10px"
                    width="80px"
                  >
                  </skeleton>
                </li>
                <li class="desktop">
                  <skeleton
                    class="router-link-active router-link-exact-active"
                    height="12px"
                    border-radius="10px"
                    width="100px"
                  >
                  </skeleton>
                </li>
                <li class="desktop">
                  <skeleton
                    class="router-link-active router-link-exact-active"
                    height="12px"
                    border-radius="10px"
                    width="90px"
                  >
                  </skeleton>
                </li>
              </template>
              <template v-else>
                <template v-for="(item, index) in leftMenuItems">
                  <template v-if="item.submenu">
                    <menu-item
                      v-if="item.submenu && item.submenu.length < 1"
                      :key="`item-${index}`"
                      :item="item"
                      :header-menu-style="headerMenuStyle"
                    />

                    <menu-dropdown
                      v-else
                      :key="`group-${index}`"
                      :item="item"
                      :open-group="isGroupActive(item, $route.fullPath)"
                      :header-menu-style="headerMenuStyle"
                    />
                  </template>
                  <template v-else>
                    <menu-item
                      :key="`item-${index}`"
                      :item="item"
                      class="mobile-none"
                      :header-menu-style="headerMenuStyle"
                    />
                  </template>
                </template>
              </template>

              <!-- End Menu -->
            </ul>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-8 col-8">
          <div class="header-info-wrap">
            <ul class="list-unstyled header-info justify-content-end">
              <!-- Right header Menus -->
              <template v-if="dataLoading" class="desktop">
                <li class="desktop">
                  <skeleton
                    class="router-link-active router-link-exact-active"
                    height="12px"
                    border-radius="10px"
                    width="80px"
                  >
                  </skeleton>
                </li>
                <li class="desktop">
                  <skeleton
                    class="router-link-active router-link-exact-active"
                    height="12px"
                    border-radius="10px"
                    width="100px"
                  >
                  </skeleton>
                </li>
              </template>
              <template v-else>
                <template
                  v-for="(item, index) in rightMenuItems"
                  class="d-none d-lg-block"
                >
                  <template v-if="item.submenu">
                    <menu-item
                      v-if="item.submenu && item.submenu.length < 1"
                      :key="`item-${index}`"
                      :item="item"
                      :header-menu-style="headerMenuStyle"
                    />

                    <menu-dropdown
                      v-else
                      :key="`group-${index}`"
                      :item="item"
                      :open-group="isGroupActive(item, $route.fullPath)"
                      :header-menu-style="headerMenuStyle"
                    />
                  </template>
                  <template v-else>
                    <menu-item
                      :key="`item-${index}`"
                      :item="item"
                      class="mobile-none"
                      :header-menu-style="headerMenuStyle"
                    />
                  </template>
                </template>
              </template>
              <!--Bell icon-->
              <template v-if="isCustomerLogin">
                <li ref="notificationDropdownMenu" v-if="!dataLoading">
                  <a
                    href="#"
                    class="btn-notification-bell border"
                    @click.prevent="showNotification = !showNotification"
                  >
                    <span class="material-icons"> notifications </span>
                    <span
                      class="count position-absolute d-flex align-items-center justify-content-center"
                      >{{ totalNotifications }}</span
                    >
                  </a>
                  <div class="notification-dropdown" v-if="showNotification">
                    <div
                      class="bg-light d-flex justify-content-between p-2 notification-header"
                    >
                      <h6 class="mb-0">
                        {{ $t("Notification") }}
                      </h6>
                      <a
                        href="#"
                        class="mb-0"
                        v-if="totalNotifications > 0"
                        @click.prevent="markAsReadAll"
                        >{{ $t("Mark all as read") }}</a
                      >
                    </div>
                    <ul
                      class="list-unstyled p-3 pt-0"
                      v-if="totalNotifications > 0"
                    >
                      <li
                        v-for="(notification, index) in notifications"
                        :key="index"
                      >
                        <a
                          href="#"
                          class="notification-item d-flex align-items-center"
                          @click.prevent="readNotification(notification)"
                        >
                          <div class="content">
                            <div class="mb-2">
                              <p class="time">
                                {{ notification.time }}
                              </p>
                            </div>
                            <div
                              class="main-text"
                              v-html="notification.message"
                            ></div>
                          </div>
                        </a>
                      </li>
                    </ul>
                    <ul class="list-unstyled p-3" v-else>
                      <li>
                        <p>
                          {{ $t("You have no unread notification") }}
                        </p>
                      </li>
                    </ul>
                  </div>
                </li>
                <li v-if="dataLoading">
                  <skeleton
                    width="30px"
                    height="30px"
                    border-radius="50%"
                  ></skeleton>
                </li>
              </template>
              <!--End Bell icon-->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Header Top Bar -->
</template>
<script>
import { mapState } from "vuex";
import MenuItem from "../../components/menu/MenuItem.vue";
import MenuDropdown from "../../components/menu/MenuDropdown.vue";
import axios from "axios";
export default {
  name: "HeaderTop",
  components: {
    MenuItem,
    MenuDropdown,
  },
  props: {
    leftMenuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    rightMenuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    dataLoading: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
  data() {
    return {
      showNotification: false,
    };
  },
  computed: mapState({
    isCustomerLogin: (state) => state.isCustomerLogin,
    customerToken: (state) => state.customerToken,
    notifications: (state) => state.notifications,
    totalNotifications: (state) =>
      state.notifications.length ? state.notifications.length : 0,
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
  }),
  mounted() {
    document.addEventListener("click", this.close);
  },
  methods: {
    /**
     * Will read notification
     */
    readNotification(notification) {
      axios
        .post(
          "/api/v1/ecommerce-core/customer/mark-as-read-single-notification",
          {
            id: notification.id,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            if (notification.link != null) {
              window.location.href = notification.link;
            } else {
              this.$store.dispatch(
                "customerNotificationAction",
                response.data.unread_notification.data
              );
            }
          }
        })
        .catch((error) => {});
    },
    /**
     * Will mark as read all notification
     */
    markAsReadAll() {
      axios
        .get("/api/v1/ecommerce-core/customer/mark-as-read-all-notification", {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.$store.dispatch("customerNotificationAction", []);
          }
        })
        .catch((error) => {});
    },
    /**
     * Will close dropdown area
     *
     * @param {*} e
     */
    close(e) {
      let el3 = this.$refs.notificationDropdownMenu;
      let target = e.target;

      if (this.isCustomerLogin) {
        if (el3 !== target && !el3?.contains(target)) {
          this.showNotification = false;
        }
      }
    },
  },
};
</script>
<style scoped>
@media (max-width: 991px) {
  .mobile-none {
    display: none;
  }
}
</style>
