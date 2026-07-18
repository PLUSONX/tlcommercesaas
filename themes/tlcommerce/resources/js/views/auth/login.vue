<template>
  <div class="">
    <!-- <page-header class="pt-3 pb-3" :items="bItems" /> -->
    <div class="pt-60 pb-60">
      <div class="custom-container2">
        <div class="login-form-wrap">
          <form class="loginForm white-box px-3 py-4 p-md-5">

            <div class="d-flex justify-content-between align-items-center w-100 ml-3">

              <h3 class="mb-4">{{ $t("Login") }}</h3>

              <router-link to="/" class="back-home-btn">
                <span class="material-icons">arrow_back</span>
                <span class="back-home-btn__label">{{ $t("Home") }}</span>
              </router-link>

            </div>



            <p v-bind:class="notificationClass" v-if="notification">
              {{ notification }}
            </p>
            <div class="row">
              <div class="col-12">
                <div class="form-group mb-20">
                  <label class="font-weight-bold fz-12 mb-2">
                    {{ $t("Email") }} <span class="text-danger">*</span></label>
                  <div class="">
                    <input type="email" v-bind:placeholder="$t('Email')" class="theme-input-style"
                      v-model="customerData.email" />
                    <template v-if="errors.email">
                      <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.email" :key="index">
                        {{ error }}
                      </p>
                    </template>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group mb-20">
                  <label class="font-weight-bold fz-12 mb-2">
                    {{ $t("Password")
                    }}<span class="text-danger">*</span></label>
                  <div class="">
                    <input type="password" v-bind:placeholder="$t('Password')" class="theme-input-style"
                      v-model="customerData.password" />
                    <template v-if="errors.password">
                      <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.password" :key="index">
                        {{ error }}
                      </p>
                    </template>
                  </div>
                </div>
              </div>
            </div>

            <div class="align-items-baseline row">
              <div class="col-sm-6">
                <button @click.prevent="customerLogin" :disabled="formSubmitting" class="btn btn-fill">
                  <span v-if="formSubmitting">
                    <CSpinner component="span" size="sm" aria-hidden="true" />
                    {{ $t("Please wait") }}
                  </span>
                  <span v-else>
                    {{ $t("Login") }}
                  </span>
                </button>
              </div>
              <div class="col-sm-6">
                <span class="d-block mt-3">
                  <router-link to="/password/forgot" class="btn_underline">
                    {{ $t("Forgot Password") }} ?
                  </router-link>
                </span>
              </div>
            </div>
            <!-- <div class="row" v-if="$store?.state?.siteProperties?.customer_limit_over == 0"> -->
            <div class="row">
              <div class="col-12">
                <span class="d-block mt-3">
                  {{ $t("If you have no account") }}.
                  <router-link to="/register" class="btn_underline">
                    {{ $t("Register Here") }}
                  </router-link>
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import axios from "axios";
import { CSpinner } from "@coreui/vue";
export default {
  components: {
    PageHeader,
    CSpinner,
  },
  data() {
    return {
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Login"),
          active: true,
        },
      ],
      customerData: {
        email: "",
        password: "",
      },
      errors: [],
      notification: "",
      notificationClass: "alert alert-info",
      formSubmitting: false,
    };
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prevRoute = from;
    });
  },
  mounted() {
    document.title = this.$t("Login");
  },
  computed: {
    goToAfterLogin() {
      let url = this.prevRoute ? this.prevRoute.path : "/dashboard";
      if (
        url == "/password/forgot" ||
        url == "/password/reset" ||
        url == "/email/reset" ||
        url == "/customer/email-verification"
      ) {
        return "/dashboard";
      } else {
        return url;
      }
    },
  },
  methods: {
    /**
     * customer login
     */
    customerLogin() {
      this.formSubmitting = true;
      this.notification = "";
      this.errors = [];
      axios
        .post("/api/v1/ecommerce-core/auth/customer-login", {
          email: this.customerData.email,
          password: this.customerData.password,
        })
        .then((response) => {
          if (response.data.success && response.data.access_token != null) {
            this.$toast.success(this.$t("Login successful"));
            this.$store.dispatch("customerLogin", response.data).then(() => {
              this.$store.dispatch("flushCartData");
              this.$store.dispatch("getCustomerCartItems");
              this.$router.push(this.goToAfterLogin);
            });
          } else {
            this.formSubmitting = false;
            this.notification = response.data.message;
            this.notificationClass = "alert alert-danger";
          }
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          this.formSubmitting = false;
        });
    },
  },
};
</script>

<style scoped>
/* ── Back to Home Button ──────────────────────────────────────── */
.back-home-btn {
  /* position: fixed;*/
  /* top: 30px;
  left: 16px; */
  /* z-index: 1050; */
  margin-bottom: 10px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  color: #2d3748;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 7px 14px;
  border-radius: 50px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  text-decoration: none;
  transition: all 0.2s ease;
}

.back-home-btn:hover {
  background: #f7fafc;
  border-color: #4a90e2;
  color: #4a90e2;
  box-shadow: 0 4px 12px rgba(74, 144, 226, 0.15);
  text-decoration: none;
}

.back-home-btn .material-icons {
  font-size: 1rem;
  line-height: 1;
}
</style>