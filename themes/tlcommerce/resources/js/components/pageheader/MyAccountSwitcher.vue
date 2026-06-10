<template>
  <div class="my-account-wrap" ref="dropdownMenu" v-if="!dataLoading">
    <button type="button" class="btn-circle custom-icon-btn my-account-trigger"
      @click.prevent="showMyAccount = !showMyAccount">
      <span class="material-icons">person</span>
    </button>

    <div class="my-account-dropdown" v-if="showMyAccount">
      <ul class="list-unstyled mb-0" v-if="isCustomerLogin">
        <li>
          <router-link to="/dashboard" class="custom-menu" @click="showMyAccount = false">
            {{ $t("Dashboard") }}
          </router-link>
        </li>
        <li>
          <router-link to="#" @click.prevent="onLogout" class="custom-menu">
            {{ $t("Logout") }}
          </router-link>
        </li>
      </ul>
      <ul class="list-unstyled mb-0" v-else>
        <li>
          <router-link to="/login" class="custom-menu" @click="showMyAccount = false">
            {{ $t("Login") }}
          </router-link>
          <router-link to="/register" class="custom-menu" @click="showMyAccount = false">
            {{ $t("Registration") }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>

  <div class="my-account-wrap" v-if="dataLoading">
    <skeleton width="45px" height="45px" border-radius="50%"></skeleton>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "MyAccountSwitcher",
  emits: ["logout-customer"],
  props: {
    dataLoading: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
  data() {
    return {
      showMyAccount: false,
    };
  },
  computed: mapState({
    isCustomerLogin: (state) => state.isCustomerLogin,
  }),
  mounted() {
    document.addEventListener("click", this.close);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.close);
  },
  methods: {
    onLogout() {
      this.showMyAccount = false;
      this.$emit("logout-customer");
    },
    close(e) {
      const el = this.$refs.dropdownMenu;
      const target = e.target;

      if (el !== target && !el?.contains(target)) {
        this.showMyAccount = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.my-account-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.my-account-trigger {
  .material-icons {
    font-size: 20px;
  }
}

.my-account-dropdown {
  position: absolute;
  top: calc(100% + 12px);
  right: 0;
  z-index: 9999;
  min-width: 190px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(0, 0, 0, 0.08);
  overflow: hidden;
  animation: dropdownFade 0.2s ease-out;

  ul {
    margin: 0 !important;
    padding: 8px 0 !important;
    list-style: none !important;
    display: block !important;

    li {
      margin: 0 !important;
      padding: 0 !important;
      display: block !important;
      width: 100%;

      a {
        display: block;
        padding: 12px 20px;
        color: #333333 !important;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        text-align: left;

        &:hover {
          background-color: #f4f6f9;
          color: var(--c1, #000) !important;
          padding-left: 25px;
        }
      }
    }
  }
}

@keyframes dropdownFade {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
