<template>
  <div class="shipping-rate-picker shadow-card mb-20 p-3" v-if="showPicker">
    <h5 class="mb-3">{{ $t("Shipping Options") }}</h5>
    <div
      class="shipping-rate-picker__row border-bottom pb-2 mb-2"
      v-for="pkg in shippingPackages"
      :key="pkg.id"
    >
      <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
        <div class="min-w-0">
          <p class="mb-0 font-weight-bold text-truncate">{{ pkg.product.name }}</p>
          <small class="text-muted">{{ $t("Qty") }}: {{ pkg.product.quantity }}</small>
        </div>
        <the-currency :amount="pkg.default_option.shipping_cost"></the-currency>
      </div>
      <div
        class="pl-shipping large"
        @click.prevent="openPicker(pkg.id)"
      >
        <div class="pl-shipping-left">
          <div class="pl-shipping-left-title">
            {{ $t("Shipping") }}:
            <the-currency :amount="pkg.default_option.shipping_cost"></the-currency>
          </div>
          <div class="pl-shipping-left-cost" v-if="pkg.default_option.shipping_time">
            {{ $t("Estimated Delivery Time") }}:
            {{ pkg.default_option.shipping_time }}
          </div>
        </div>
        <div class="pl-shipping-right">
          <span class="material-icons"> arrow_forward_ios </span>
        </div>
      </div>
    </div>

    <CModal
      :visible="visiblePicker"
      size="md"
      @close="visiblePicker = false"
    >
      <CModalHeader>
        <CModalTitle>{{ $t("Shipping Options") }}</CModalTitle>
        <button
          class="btn-circle bg-black size-35"
          @click.prevent="visiblePicker = false"
        >
          <base-icon-svg name="close" :width="10" :height="10" />
        </button>
      </CModalHeader>
      <CModalBody>
        <div class="row save-adderss" v-if="activePackage">
          <div
            class="col-lg-12 mb-4"
            v-for="(option, index) in activePackage.options.data"
            :key="index"
          >
            <span
              class="custom-radio-btn"
              :class="{ active: option.id == activePackage.default_option.id }"
            >
              <label class="radio-label">
                <input
                  name="shippingOption"
                  type="radio"
                  :value="option"
                  v-model="activePackage.default_option"
                  :checked="option.id == activePackage.default_option.id"
                  @change.prevent="onOptionChange"
                />
                <span class="radio-text">
                  <span class="font-weight-bold">{{ option.title }}</span>
                  <small class="m-2" v-if="option.by"
                    >{{ $t("via") }} {{ option.by }}</small
                  >
                  <br />
                  <span>
                    {{ $t("Shipping Cost") }}:
                    <the-currency
                      :amount="option.shipping_cost"
                      class="ml-1"
                    ></the-currency>
                  </span>
                  <br />
                  <span v-if="option.shipping_time"
                    >{{ $t("Estimated Delivery:") }}
                    {{ option.shipping_time }}</span
                  >
                </span>
              </label>
            </span>
          </div>
        </div>
      </CModalBody>
    </CModal>
  </div>
</template>

<script>
import {
  CModal,
  CModalHeader,
  CModalTitle,
  CModalBody,
} from "@coreui/vue";
import {
  applyShippingCostAndTax,
  buildProductPackages,
} from "../../utils/checkoutShippingPrep";

export default {
  name: "ShippingRatePicker",
  components: {
    CModal,
    CModalHeader,
    CModalTitle,
    CModalBody,
  },
  emits: ["packages-updated"],
  props: {
    config: { type: Object, required: true },
    enums: { type: Object, required: true },
    shippingPackages: { type: Array, default: () => [] },
    isActiveHomeDelivery: { type: Boolean, default: true },
  },
  data() {
    return {
      visiblePicker: false,
      activePackageId: null,
    };
  },
  computed: {
    showPicker() {
      return (
        this.isActiveHomeDelivery &&
        this.config?.shipping_option == 3 &&
        this.shippingPackages.length > 0
      );
    },
    activePackage() {
      if (!this.activePackageId) {
        return null;
      }
      return this.shippingPackages.find((p) => p.id === this.activePackageId);
    },
  },
  methods: {
    openPicker(id) {
      this.activePackageId = id;
      this.visiblePicker = true;
    },
    onOptionChange() {
      applyShippingCostAndTax({
        store: this.$store,
        config: this.config,
        enums: this.enums,
        shippingPackages: this.shippingPackages,
        isActiveHomeDelivery: this.isActiveHomeDelivery,
      });
      const packages = buildProductPackages({
        shippingPackages: this.shippingPackages,
        config: this.config,
        enums: this.enums,
        isActiveHomeDelivery: this.isActiveHomeDelivery,
      });
      this.$emit("packages-updated", packages);
      this.visiblePicker = false;
    },
  },
};
</script>

<style scoped>
.pl-shipping.large {
  margin-top: 0;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #999;
  font-size: 12px;
  padding-bottom: 8px;
}

.pl-shipping-left {
  text-align: start;
  display: flex;
  flex-direction: column;
}

.pl-shipping-left-title {
  font-weight: 700;
  color: #222;
}

.pl-shipping-left-cost {
  font-size: 10px;
  color: #999;
}

.pl-shipping-right {
  padding-right: 8px;
}

.pl-shipping-right .material-icons {
  font-size: 16px;
}
</style>
