<template>
  <div class="" :class="{
    'force-mobile-layout': forcedMobile,
    'mobile-content-wrapper': forcedMobile
  }">
    <!-- <page-header class="pt-3 pb-3" :items="bItems" /> -->
    <div class="shipping-info light-bg pt-60 pb-60">
      <div class="custom-container2">
        <form action="#" @submit.prevent>
          <div class="row">
            <!-- Stacked sections: cart only when empty; full stack when cart has items -->
            <div :class="hasCartItems
              ? (forcedMobile ? 'col-12' : 'col-lg-8')
              : 'col-12'">
              <!-- Cart -->
              <section id="checkout-cart" class="checkout-section mb-20" :class="sectionClass('cart')">
                <cart-step @next-step="goToDetails"
                  @checkout-items-synced="onCheckoutItemsSynced"></cart-step>
              </section>

              <!-- Shipping / Details -->
              <section v-if="hasCartItems" id="checkout-details" class="checkout-section mb-20"
                :class="sectionClass('details')">
                <div v-if="isLocked('details')" class="checkout-section__lock">
                  <span class="material-icons">lock</span>
                  <p>{{ $t("Complete cart to continue") }}</p>
                </div>
                <div v-else class="checkout-section__body">
                  <div v-if="sectionLoading.details" class="p-3">
                    <skeleton class="w-100 mb-20" height="80px"></skeleton>
                    <skeleton class="w-100" height="240px"></skeleton>
                  </div>
                  <delivery-shipping v-else ref="deliveryShipping" :enums="enums" :config="configuration"
                    :customer-address="customerAddress" :is-customer-login="isCustomerLogin"
                    :pickup-points="pickupPoints" @next-step="goToPayment"
                    @previous-step="goToCart"></delivery-shipping>
                </div>
              </section>

              <!-- Payment (+ optional rate picker) -->
              <section v-if="hasCartItems" id="checkout-payment" class="checkout-section mb-20"
                :class="sectionClass('payment')">
                <div v-if="isLocked('payment')" class="checkout-section__lock">
                  <span class="material-icons">lock</span>
                  <p>{{ $t("Complete cart to continue") }}</p>
                </div>
                <div v-else class="checkout-section__body">
                  <div v-if="sectionLoading.payment" class="p-3">
                    <skeleton class="w-100 mb-20" height="80px"></skeleton>
                    <skeleton class="w-100" height="200px"></skeleton>
                  </div>
                  <template v-else>
                    <shipping-rate-picker
                      v-if="showRatePicker"
                      :config="configuration"
                      :enums="enums"
                      :shipping-packages="shippingPackages"
                      :is-active-home-delivery="prepIsHomeDelivery"
                      @packages-updated="onPackagesUpdated"
                    ></shipping-rate-picker>
                    <payment-methods ref="paymentMethods" :enums="enums" :config="configuration"
                      :product-packages="product_packages" :is-customer-login="isCustomerLogin"
                      :total-payable-amount="totalPayableAmount"
                      @previous-step="focusSection('details')"
                      @request-place-order="finalizeAndPlaceOrder"></payment-methods>
                  </template>
                </div>
              </section>
            </div>

            <!-- Single shared order summary -->
            <div v-if="showOrderSummary" :class="forcedMobile ? 'col-12 mt-20' : 'col-lg-4'">
              <div class="checkout-summary-sticky">
                <order-summary :enums="enums" :config="configuration"
                  @get-total-payable="calculateTotalPayable"></order-summary>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import CartStep from "../../components/order-steps/cartStep.vue";
import deliveryShipping from "../../components/order-steps/deliveryShipping";
import PaymentMethods from "../../components/order-steps/paymentMethods";
import ShippingRatePicker from "../../components/order-steps/shippingRatePicker.vue";
import orderSummary from "../../components/order-steps/orderSummary";
import enums from "../../enums/enums";
import axios from "axios";
import { mapState, mapGetters } from "vuex";
import { prepareCheckoutShipping } from "../../utils/checkoutShippingPrep";

const VALID_STEPS = ["cart", "details", "payment"];

export default {
  name: "Checkout",
  components: {
    PageHeader,
    CartStep,
    deliveryShipping,
    PaymentMethods,
    ShippingRatePicker,
    orderSummary,
  },
  data() {
    return {
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Checkout"),
          active: true,
        },
      ],
      enums: enums,
      totalPayableAmount: 0,
      customerAddress: [],
      pickupPoints: [],
      currentStep: "cart",
      unlockedThrough: 0,
      product_packages: [],
      shippingPackages: [],
      prepIsHomeDelivery: true,
      checkoutConfigReady: false,
      sectionLoading: {
        details: false,
        payment: false,
      },
      pendingPaymentPrep: false,
    };
  },
  computed: {
    ...mapState({
      customerToken: (state) => state.customerToken,
      isCustomerLogin: (state) => state.isCustomerLogin,
      configuration: (state) => state.siteSettings,
      checkoutItems: (state) => state.checkoutItems,
      storedCheckoutPrep: (state) => state.checkoutPrep,
      cart: (state) => state.cart,
    }),

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    forcedMobile() {
      if (this.isSplitScreen && !this.isMobile) {
        return true;
      }
      return this.isMobile;
    },

    hasCartItems() {
      return Array.isArray(this.cart) && this.cart.length > 0;
    },

    tableData() {
      return this.checkoutItems || [];
    },

    showOrderSummary() {
      return this.hasCartItems;
    },

    showRatePicker() {
      return (
        this.prepIsHomeDelivery &&
        this.configuration?.shipping_option == 3 &&
        this.shippingPackages.length > 0
      );
    },

    currentStepIndex() {
      return VALID_STEPS.indexOf(this.currentStep);
    },
  },
  watch: {
    hasCartItems(hasItems) {
      if (!hasItems) {
        this.resetCheckoutToEmptyCart();
      }
    },
  },
  beforeMount() {
    this.restorePrepFromStore();
    this.resolveInitialStep();
    if (!this.hasCartItems) {
      this.resetCheckoutToEmptyCart();
    }
  },
  mounted() {
    this.updateDocumentTitle();
    this.CheckCheckoutConfig();
    if (this.pendingPaymentPrep) {
      this.ensurePaymentPrep();
    }
    this.$nextTick(() => {
      this.scrollToSection(this.currentStep, false);
    });
  },
  methods: {
    normalizeStepQuery(step) {
      if (step === "review") {
        return "payment";
      }
      return step;
    },
    resetCheckoutToEmptyCart() {
      this.currentStep = "cart";
      this.unlockedThrough = 0;
      this.product_packages = [];
      this.shippingPackages = [];
      this.prepIsHomeDelivery = true;
      this.pendingPaymentPrep = false;
      this.sectionLoading.details = false;
      this.sectionLoading.payment = false;
      this.$store.dispatch("flushCheckoutPrep");
      this.updateDocumentTitle();
    },
    restorePrepFromStore() {
      const prep = this.storedCheckoutPrep;
      if (!prep) {
        return;
      }
      if (Array.isArray(prep.productPackages) && prep.productPackages.length) {
        this.product_packages = prep.productPackages;
      }
      if (Array.isArray(prep.shippingPackages) && prep.shippingPackages.length) {
        this.shippingPackages = prep.shippingPackages;
      }
      this.prepIsHomeDelivery = prep.isHomeDelivery !== false;
    },
    persistPrepToStore() {
      this.$store.dispatch("storeCheckoutPrep", {
        productPackages: this.product_packages,
        shippingPackages: this.shippingPackages,
        isHomeDelivery: this.prepIsHomeDelivery,
      });
    },
    stepIndex(stepId) {
      return VALID_STEPS.indexOf(stepId);
    },
    isStepActive(stepId) {
      return this.currentStep === stepId;
    },
    isStepCompleted(stepId) {
      const idx = this.stepIndex(stepId);
      return idx > -1 && idx < this.currentStepIndex;
    },
    isUnlocked(stepId) {
      return this.stepIndex(stepId) <= this.unlockedThrough;
    },
    isLocked(stepId) {
      return !this.isUnlocked(stepId);
    },
    canScrollToStep(stepId) {
      return this.isUnlocked(stepId);
    },
    sectionClass(stepId) {
      return {
        "is-active": this.isStepActive(stepId),
        "is-completed": this.isStepCompleted(stepId),
        "is-locked": this.isLocked(stepId),
      };
    },
    resolveInitialStep() {
      let queryStep = this.normalizeStepQuery(this.$route.query.step);
      if (queryStep && VALID_STEPS.includes(queryStep)) {
        if (queryStep !== "cart" && this.tableData.length < 1) {
          this.focusSection("cart", { scroll: false });
          return;
        }
        if (queryStep === "payment") {
          this.unlockedThrough = Math.max(this.unlockedThrough, 2);
          this.currentStep = "payment";
          if (this.product_packages.length < 1) {
            // Restore failed — re-prep on mount; do not bounce to details yet
            this.pendingPaymentPrep = true;
          }
          return;
        }
        const idx = this.stepIndex(queryStep);
        // Details and payment unlock together after cart
        this.unlockedThrough = Math.max(
          this.unlockedThrough,
          idx >= 1 ? 2 : idx
        );
        this.currentStep = queryStep;
        return;
      }
      if (this.tableData.length < 1) {
        this.focusSection("cart", { scroll: false });
      } else if (this.product_packages.length > 0) {
        this.unlockedThrough = Math.max(this.unlockedThrough, 2);
        this.focusSection("payment", { scroll: false });
      } else {
        this.unlockedThrough = Math.max(this.unlockedThrough, 2);
        this.focusSection("details", { scroll: false });
      }
    },
    /**
     * In-page step change only — never sync ?step= (MainLayout remounts on fullPath).
     */
    focusSection(step, { scroll = true } = {}) {
      const idx = this.stepIndex(step);
      if (idx < 0) {
        return;
      }
      this.unlockedThrough = Math.max(this.unlockedThrough, idx);
      this.currentStep = step;
      this.updateDocumentTitle();
      if (scroll) {
        this.$nextTick(() => {
          setTimeout(() => this.scrollToSection(step), 50);
        });
      }
    },
    updateDocumentTitle() {
      if (this.currentStep === "cart") {
        document.title = this.$t("Cart");
      } else {
        document.title = this.$t("Checkout");
      }
    },
    getScrollContainer() {
      return document.querySelector(".content-split-nudge") || null;
    },
    scrollToSection(stepId, smooth = true) {
      const el = document.getElementById(`checkout-${stepId}`);
      if (!el) {
        return;
      }
      const container = this.getScrollContainer();
      if (container) {
        const containerRect = container.getBoundingClientRect();
        const elRect = el.getBoundingClientRect();
        const top = elRect.top - containerRect.top + container.scrollTop - 12;
        container.scrollTo({
          top: Math.max(0, top),
          behavior: smooth ? "smooth" : "auto",
        });
        return;
      }
      el.scrollIntoView({
        behavior: smooth ? "smooth" : "auto",
        block: "start",
      });
    },
    goToCart() {
      this.focusSection("cart");
    },
    /**
     * Unlock Details + Payment without requiring Cart Checkout click.
     * scroll=false keeps the user at the cart on auto-unlock.
     */
    unlockShippingAndPayment({ scroll = false } = {}) {
      if (
        this.configuration?.enable_guest_checkout ==
          this.enums.status.IN_ACTIVE &&
        !this.isCustomerLogin
      ) {
        return;
      }
      this.getPickupPoints();
      if (this.isCustomerLogin && this.customerAddress.length < 1) {
        if (this.sectionLoading.details) {
          this.unlockedThrough = Math.max(this.unlockedThrough, 2);
          return;
        }
        this.sectionLoading.details = true;
        this.unlockedThrough = Math.max(this.unlockedThrough, 2);
        this.getCustomerAddress(() => {
          this.sectionLoading.details = false;
          this.unlockedThrough = Math.max(this.unlockedThrough, 2);
          if (scroll) {
            this.focusSection("details");
          }
        });
        return;
      }
      this.unlockedThrough = Math.max(this.unlockedThrough, 2);
      if (scroll) {
        this.focusSection("details");
      }
    },
    onCheckoutItemsSynced({ canProceed } = {}) {
      if (canProceed) {
        this.unlockShippingAndPayment({ scroll: false });
        return;
      }
      if (this.hasCartItems) {
        this.unlockedThrough = 0;
        this.currentStep = "cart";
        this.updateDocumentTitle();
      }
    },
    goToDetails() {
      if (this.tableData.length < 1) {
        this.$toast.error(this.$t("No item selected for checkout"));
        this.focusSection("cart");
        return;
      }
      if (
        this.configuration?.enable_guest_checkout ==
          this.enums.status.IN_ACTIVE &&
        !this.isCustomerLogin
      ) {
        this.$toast.error(this.$t("Please login to complete checkout"));
        this.$router.push("/login");
        return;
      }
      this.unlockShippingAndPayment({ scroll: true });
    },
    async runShippingPrep() {
      const result = await prepareCheckoutShipping({
        store: this.$store,
        config: this.configuration,
        enums: this.enums,
      });
      if (!result.success) {
        return false;
      }
      this.shippingPackages = result.shippingPackages || [];
      this.product_packages = result.productPackages || [];
      this.prepIsHomeDelivery = !!result.isActiveHomeDelivery;
      this.persistPrepToStore();
      return true;
    },
    async ensurePaymentPrep() {
      this.pendingPaymentPrep = false;
      this.sectionLoading.payment = true;
      const ok = await this.runShippingPrep();
      this.sectionLoading.payment = false;
      if (!ok) {
        this.$toast.error(
          this.$t("Delivery not available in your location")
        );
        this.focusSection("details", { scroll: false });
        return;
      }
      this.focusSection("payment", { scroll: true });
    },
    /**
     * After Details: auto-run shipping prep, then open Payment (Review collapsed).
     * Does not touch the URL — avoids MainLayout remount on ?step= change.
     */
    async goToPayment() {
      this.sectionLoading.payment = true;
      this.unlockedThrough = Math.max(this.unlockedThrough, 2);
      this.currentStep = "payment";
      this.updateDocumentTitle();

      const ok = await this.runShippingPrep();
      this.sectionLoading.payment = false;

      if (!ok) {
        this.$toast.error(
          this.$t("Delivery not available in your location")
        );
        this.focusSection("details");
        return;
      }

      this.focusSection("payment");
    },
    /**
     * Place Order pipeline: validate delivery → shipping prep → create order.
     */
    async finalizeAndPlaceOrder() {
      const delivery = this.$refs.deliveryShipping;
      if (!delivery || typeof delivery.validateAndPersist !== "function") {
        this.$toast.error(this.$t("Complete cart to continue"));
        this.focusSection("cart");
        return;
      }

      const valid = delivery.validateAndPersist();
      if (!valid) {
        this.focusSection("details");
        return;
      }

      // Do not toggle sectionLoading.payment — it unmounts PaymentMethods / drops ref.
      const ok = await this.runShippingPrep();

      if (!ok) {
        this.$toast.error(
          this.$t("Delivery not available in your location")
        );
        this.focusSection("details");
        return;
      }

      if (!this.product_packages || this.product_packages.length < 1) {
        this.$toast.error(
          this.$t("Delivery not available in your location")
        );
        this.focusSection("details");
        return;
      }

      await this.$nextTick();
      const payment = this.$refs.paymentMethods;
      if (payment && typeof payment.createOrder === "function") {
        payment.createOrder();
      }
    },
    onPackagesUpdated(packages) {
      this.product_packages = packages;
      this.persistPrepToStore();
    },
    CheckCheckoutConfig() {
      if (
        this.currentStep !== "cart" &&
        this.configuration?.enable_guest_checkout ==
          this.enums.status.IN_ACTIVE &&
        !this.isCustomerLogin
      ) {
        this.$toast.error(this.$t("Please login to complete checkout"));
        this.$router.push("/login");
        return;
      }
      if (this.isCustomerLogin && this.unlockedThrough >= 1) {
        this.getCustomerAddress();
      }
      this.getPickupPoints();
      this.checkoutConfigReady = true;
    },
    getCustomerAddress(onDone = null) {
      axios
        .post("/api/v1/ecommerce-core/customer/get-customer-all-address", null, {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.customerAddress = response.data.data;
          } else {
            this.customerAddress = [];
          }
          if (typeof onDone === "function") {
            onDone();
          }
        })
        .catch(() => {
          this.customerAddress = [];
          if (typeof onDone === "function") {
            onDone();
          }
        });
    },
    getPickupPoints() {
      axios
        .post("/api/v1/pickup-points/active-list")
        .then((response) => {
          if (response.data.success) {
            this.pickupPoints = response.data.data;
          } else {
            this.pickupPoints = [];
          }
        })
        .catch(() => {
          this.pickupPoints = [];
        });
    },
    calculateTotalPayable(amount) {
      this.totalPayableAmount = amount;
    },
  },
};
</script>

<style scoped>
.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.force-mobile-layout .product-content .image,
.force-mobile-layout .product-content .product-img {
  min-width: 0 !important;
  max-width: 100px;
}

.force-mobile-layout .cart-image-review {
  max-width: 100% !important;
  height: auto !important;
  flex-shrink: 1 !important;
}

.force-mobile-layout .d-none.d-lg-block,
.force-mobile-layout .d-lg-block {
  display: none !important;
}

.force-mobile-layout .d-block.d-lg-none,
.force-mobile-layout .d-lg-none {
  display: block !important;
}

.force-mobile-layout .d-block.d-lg-none.col-12 {
  width: 100% !important;
  display: flex !important;
  flex-wrap: wrap !important;
  justify-content: space-between !important;
}

/* Stacked sections */
.checkout-section {
  position: relative;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
  overflow: hidden;
  scroll-margin-top: 16px;
}

.checkout-section.is-active {
  box-shadow: 0 0 0 2px var(--theme-primary, #0d6efd), 0 1px 4px rgba(0, 0, 0, 0.06);
}

.checkout-section.is-locked {
  min-height: 120px;
  background: #f8f9fa;
}

.checkout-section__lock {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 40px 16px;
  color: #6c757d;
  text-align: center;
}

.checkout-section__lock .material-icons {
  font-size: 28px;
  opacity: 0.7;
}

.checkout-section__lock p {
  margin: 0;
  font-size: 13px;
}

.checkout-section__body {
  min-height: 40px;
}

.checkout-sidebar-sticky,
.checkout-summary-sticky {
  position: sticky;
  top: 16px;
}
</style>
