<template>
  <!-- Loader -->
  <div class="product-details-loader" v-show="isLoading">
    <div class="loader-spinner"></div>
  </div>
  <!-- Product Details Content -->
  <div
    v-show="!isLoading"
    class="product-details-content"
    :class="{
  'product-details-content--rtl': isFeaturePaneLayout && isRtl,
  'product-details-content--modern': modernLayout
}"
  >
    <!--Flash deal info-->
    <div class="d-flex flash-deal flex-wrap justify-content-between mb-20 p-2 text-white"
      v-if="product.has_deal != null">
      <div class="align-items-center d-flex deal-title">
        <h5 class="text-white mb-0">{{ product.has_deal.deal_title }}</h5>
      </div>
      <div class="deal-dead-line">
        <countdown class="justify-content-lg-end" :deadline="product.has_deal.end_date" titleColor="white" />
      </div>
    </div>
    <!--End flash deal info-->
    <!-- Title -->
    <div
  v-if="modernLayout"
  class="modern-product-title-row"
>
  <h1 class="product-title">
    {{ product.name }}
  </h1>

  <button
    type="button"
    class="modern-product-wishlist"
    :aria-label="$t('Add to wishlist')"
    @click.prevent="addToWishlist"
  >
    <span class="material-icons">favorite_border</span>
  </button>
</div>

<h1 v-else class="product-title">
  {{ product.name }}
</h1>
    <!--End Title-->
    <!-- Rating -->
    <div class="align-items-center d-flex rating-wrap" v-if="
  config?.enable_product_reviews == enums.status.ACTIVE &&
      (config?.enable_product_star_rating == enums.status.ACTIVE ||
        product.total_reviews > 0)
    ">
      <div class="d-flex align-items-center mr-15" v-if="config?.enable_product_star_rating == enums.status.ACTIVE">
        <div class="product-rating-wrapper">
          <i :data-star="product.rating" :title="product.rating"></i>
        </div>
        <h4 class="ms-3 mb-0 c1 mt3px">{{ product.rating }}</h4>
      </div>
      <div class="rating-text-wrap mt5px flex-wrap d-flex" v-if="product.total_reviews > 0">
        <span class="c1 review-count" @click="gotoSec">
          ({{ product.total_reviews }}) {{ $t("Reviews") }}
        </span>
      </div>
    </div>
    <!-- End Rating -->
    <!-- Summary -->
    <div
      v-if="modernLayout && hasMeaningfulSummary"
      class="product-summary-section modern-description-section"
    >
      <h6 class="modern-description-heading">
        {{ $t("Description") }}
      </h6>

      <div
        class="product-description"
        v-html="product.summary"
      ></div>
    </div>

    <div
      v-else-if="!modernLayout && hasMeaningfulSummary"
      class="product-summary-section mt-20"
    >
      <div
        class="product-description"
        v-html="product.summary"
      ></div>
    </div>
    <!--End Summary-->
    <hr
  v-if="!modernLayout"
  class="divider bg-secondary"
/>
<!-- Product Discount Badge -->
<div
  v-if="Number(product.oldPrice) > Number(product.price)"
  class="product-discount-badge"
>
  <span class="material-icons">local_offer</span>

  <!-- Percentage -->
  <template v-if="isPercentageDiscount()">
    <strong>
      {{ discountPercentValue() }}% OFF
    </strong>
  </template>

  <!-- Flat -->
  <template v-else>
    <strong>
      <the-currency
        :amount="discountSavedAmount()"
        tag="span"
      ></the-currency>
      OFF
    </strong>
  </template>
</div>
<!-- End Product Discount Badge -->

    <!--Price Range-->
    <div class="product-price price-range" v-if="
      product.price_range_min &&
      product.price_range_max &&
      product.price_range_min != product.price_range_max
    ">
      <h6 class="mb-1">{{ $t("Price Range") }}</h6>
      <div class="align-items-center d-flex flex-wrap price">
        <h3 class="mb-0 mr-20">
          <the-currency :amount="product.price_range_min"></the-currency>
          -
          <the-currency :amount="product.price_range_max"></the-currency>
        </h3>
        <del v-if="product.price_range_min_old && product.price_range_max_old">
          <the-currency v-if="product.price_range_min_old > product.price_range_min"
            :amount="product.price_range_min_old"></the-currency>
          <span v-if="product.price_range_max_old > product.price_range_max">
            -
            <the-currency :amount="product.price_range_max_old"></the-currency>
          </span>
        </del>
      </div>
    </div>
    <!-- End  Price Range-->
    <!-- Total Price -->
    <div class="product-price unit-price mt-25">
      <h6 v-if="!modernLayout" class="mb-1">
  {{ $t("Price") }}
</h6>
      <div class="price d-flex align-items-center">
        <span
  class="price-current-wrap"
  :class="{ 'price-flash': priceFlash }"
  :style="priceFlashColor ? { '--price-flash-color': priceFlashColor } : {}"
>
          <the-currency :amount="product.price" tag="h3" class="mb-0"></the-currency>
        </span> 
        <the-currency v-if="product.oldPrice > product.price" :amount="product.oldPrice" tag="del"
          class="ml-20"></the-currency>
      </div>
    </div>
    <!-- End Total Price -->
    <!-- Option Choice Form -->
    <div class="option-choice-form mt-4" v-if="product.attribute">
      <!--Variant options-->
      <div v-for="(attr, i) in product.attribute" :key="i">
        <div class="mb-3">
          <label class="option-label font-weight-bold text-capitalize">{{
            attr.title
          }}</label>
          <span v-if="attr.multi_select" class="multi-select-count ml-10">
            {{ selectedOptionCount(attr) }} / {{ attr.multi_select_limit }}
          </span>

          <!-- Option List: split-screen list-cards (desktop + mobile) -->
          <template v-if="isFeaturePaneLayout">
            <div class="option-list-cards d-flex flex-column">
              <component :is="attr.multi_select ? 'div' : 'button'" v-for="(option, j) in attr.options" :key="option.id"
                :type="attr.multi_select ? undefined : 'button'" class="option-list-card text-start" :class="{
                  'is-selected': attr.multi_select
                    ? getOptionSelectionCount(attr, option) > 0
                    : isOptionSelected(attr, option),
                  'option-list-card--multi-select': attr.multi_select,
                }" :aria-pressed="attr.multi_select
                  ? getOptionSelectionCount(attr, option) > 0
                  : isOptionSelected(attr, option)"
                @click.prevent="!attr.multi_select && updateSelectedVariant(attr, option)">
                <template v-if="attr.title == 'color'">
                  <span class="option-list-card__swatch" :style="{ backgroundColor: option.value }">
                    <img v-if="option.image" :src="cleanImage(option.image)" :alt="`${option.name}`" />
                  </span>
                  <span class="option-list-card__label text-capitalize">{{
                    option.name
                  }}</span>
                </template>

                <template v-else-if="attr.title == 'size'">
                  <span class="option-list-card__label text-uppercase">
                    <strong>{{ option.title }}</strong>
                  </span>
                </template>

                <template v-else>
                  <span class="option-list-card__label text-capitalize">
                    <strong>{{ option.title }}</strong>
                  </span>
                </template>

                <div v-if="attr.multi_select" class="multi-select-option-qty" @click.stop>
                  <button type="button" class="multi-select-option-qty__btn"
                    :disabled="getOptionSelectionCount(attr, option) === 0"
                    @click.prevent="decreaseOptionSelection(attr, option)">
                    −
                  </button>
                  <span class="multi-select-option-qty__count">{{
                    getOptionSelectionCount(attr, option)
                  }}</span>
                  <button type="button" class="multi-select-option-qty__btn" :disabled="!canAddOptionSelection(attr)"
                    @click.prevent="increaseOptionSelection(attr, option)">
                    +
                  </button>
                </div>
              </component>
            </div>
          </template>
          <!-- Option List: default chips -->
          <template v-else>
            <div class="checkbox-group d-flex flex-wrap">
              <div v-for="(option, j) in attr.options" :key="option.id">
                <div v-if="attr.multi_select" class="multi-select-option d-flex align-items-center" :class="{
                  'is-selected': getOptionSelectionCount(attr, option) > 0,
                }">
                  <span class="multi-select-option__label checkmark text-capitalize" :class="{
                    'p-0': attr.title == 'color',
                    'text-uppercase': attr.title == 'size',
                  }" :style="attr.title == 'color'
                    ? {
                      backgroundColor: option.value,
                      height: '50px',
                      width: '50px',
                    }
                    : null">
                    <template v-if="attr.title == 'color'">
                      <img :src="cleanImage(option.image)" :alt="`${option.name}`" v-if="option.image" />
                      <p v-else>{{ option.name }}</p>
                    </template>
                    <template v-else-if="attr.title == 'size'">
                      <strong class="text-uppercase">{{ option.title }}</strong>
                    </template>
                    <template v-else>
                      <strong>{{ option.title }}</strong>
                    </template>
                  </span>
                  <div class="multi-select-option-qty">
                    <button type="button" class="multi-select-option-qty__btn"
                      :disabled="getOptionSelectionCount(attr, option) === 0"
                      @click.prevent="decreaseOptionSelection(attr, option)">
                      −
                    </button>
                    <span class="multi-select-option-qty__count">{{
                      getOptionSelectionCount(attr, option)
                    }}</span>
                    <button type="button" class="multi-select-option-qty__btn" :disabled="!canAddOptionSelection(attr)"
                      @click.prevent="increaseOptionSelection(attr, option)">
                      +
                    </button>
                  </div>
                </div>
                <label v-else class="custom-checkbox--two position-relative"
                  :for="`p${product.id}-${attr.id}-option-${option.id}`">
                  <input :id="`p${product.id}-${attr.id}-option-${option.id}`" type="radio"
                    :name="`product_${product.id}_${attr.id}`" :value="option" :checked="isOptionSelected(attr, option)"
                    v-on:change="updateSelectedVariant(attr, option)" />

                  <template v-if="attr.title == 'color'">
                    <span class="checkmark p-0" :style="{
                      backgroundColor: option.value,
                      height: '50px',
                      width: '50px',
                    }">
                      <img :src="cleanImage(option.image)" :alt="`${option.name}`" v-if="option.image" />
                      <!-- <img
                        :src="option.image"
                        :alt="`${option.name}`"
                        v-if="option.image"
                      /> -->
                      <p v-else>{{ option.name }}</p>
                    </span>
                  </template>

                  <template v-else-if="attr.title == 'size'">
                    <span class="checkmark">
                      <strong class="text-uppercase">{{ option.title }}</strong>
                    </span>
                  </template>

                  <template v-else>
                    <span class="checkmark text-capitalize">
                      <strong>{{ option.title }}</strong>
                    </span>
                  </template>
                </label>
              </div>
            </div>
          </template>
          <!-- End Option List -->
        </div>
      </div>
      <!--End variant options-->
    </div>
    <!-- End Option Choice Form -->
    <!-- Quantity -->
    <div class="mt-20 mb-2 product-details-quantity">
      <h6 class="fz-12">{{ $t("Quantity") }}</h6>
      <div class="d-flex align-items-center">
        <!-- Quantity Input -->
        <div class="quantity-input text-center d-flex">
          <button class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
            :disabled="quantityValue <= min_qty" @click.prevent="decreaseQuantity">
            <base-icon-svg name="minus" :height="12" :width="12" />
          </button>
          <input v-model="quantityValue" type="number" class="border-0 text-center font-weight-bold w-100" />
          <button class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
            :disabled="product.quantity < 1 || quantityValue >= max_qty" @click.prevent="increaseQuantity">
            <base-icon-svg name="plus" :height="12" :width="12" />
          </button>
        </div>
        <!-- End Quantity Input -->
        <!-- <div v-if="!modernLayout" class="ml-15 fz-12">
          <p v-if="product.quantity > 0" class="c1">
            {{ product.quantity }}
            {{ product.quantity > 1 ? "items" : "item" }}
            {{ $t("are available") }}
          </p>
          <p v-else class="text-danger">{{ $t("Sold out") }}</p>
        </div> -->
      </div>
    </div>
    <!-- End Quantity -->
    <!--Attachment-->
    <div class="mt-25 product-details-attachment-area" v-if="product.attatchment_title != null">
      <div class="align-items-center attach-input-wrapper d-flex mb-10">
        <h6 class="mb-0 mr-10 text-capitalize">
          {{ product.attatchment_title }}
        </h6>
        <input type="file" name="attach" ref="attachment" @change="addAttachment()" />
      </div>
      <template v-if="errors.attachment">
        <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.attachment" :key="index">
          {{ error }}
        </p>
      </template>
      <p class="fz-12 mt-1" v-else>
        {{ $t("Compatible file extensions to upload: png, jpg, pdf") }}
      </p>
    </div>
    <!--End attachment-->
    <!--Action buttons-->
    <hr class="divider bg-secondary" />
    <div class="product-details-action-area" v-if="!showSplitProductFooter">
      <div class="button-group d-flex align-items-center flex-wrap gap-3">
        <!--Place order button-->
        <button type="button" class="btn btn_fill" :disabled="product.quantity < 1 || variantUpdating"
          @click.prevent="placeOrder">
          <span v-if="variantUpdating" class="btn-loading-content">
            <span class="btn-loader" aria-hidden="true"></span>
            {{ $t("Please wait") }}
          </span>
          <span v-else>{{ $t("Place Order") }}</span>
        </button>
        <!--End place order button-->
        <!--Add to cart button-->
        <button type="button" :disabled="product.quantity < 1 || variantUpdating" class="btn btn_borderd"
          @click.prevent="addToCart">
          <span v-if="variantUpdating" class="btn-loading-content">
            <span class="btn-loader" aria-hidden="true"></span>
            {{ $t("Please wait") }}
          </span>
          <span v-else>{{ $t("Add To Cart") }}</span>
        </button>
        <!--End add to cart button-->
        <div class="btn-group-right d-flex flex-md-column align-items-center align-items-md-start">
          <!--Desktop compare button-->
          <button class="icon_btn d-none d-md-inline-flex btn-compare" @click.prevent="addToCompare"
            v-if="config?.enable_product_compare == 1">
            <div class="icon-wrapper">
              <span class="material-icons"> compare_arrows </span>
            </div>
            <strong class="ms-1">{{ $t("Add to Compare") }}</strong>
          </button>
          <!--End Desktop compare button-->
          <!--Add to wishlist button-->
          <button :class="{ 'w-100': config?.enable_product_compare != 1 }" class="icon_btn btn-wishlist mt-md-2"
            @click.prevent="addToWishlist">
            <div class="icon-wrapper">
              <span class="material-icons"> favorite_border </span>
            </div>
            <strong class="d-none d-md-inline-block ms-1">{{
              $t("Add to wishlist")
            }}</strong>
          </button>
          <!--End Add to wishlist button-->
          <!--Mobile add to compare button-->
          <button class="icon_btn btn-chat d-inline-flex d-md-none" @click.prevent="addToCompare"
            v-if="config?.enable_product_compare == 1">
            <div class="icon-wrapper">
              <span class="material-icons"> compare_arrows </span>
            </div>
          </button>
          <!--End Mobile compare button-->
        </div>
      </div>
    </div>
    <!--End action buttons-->
  </div>
  <!-- End Product Details Content -->
</template>

<script>
const axios = require("axios").default;
import Countdown from "../ui/Countdown.vue";
import { mapState, mapGetters } from "vuex";
import enums from "../../enums/enums";
import appConfig from "../../config.js";

import {
  addOptionSelection,
  applySingleOptionSelection,
  canAddOptionSelection as canAddOptionSelectionUtil,
  findInvalidMultiSelectAttribute,
  getOptionSelectionCount as getOptionSelectionCountUtil,
  getSelectedOptionIds as getSelectedOptionIdsUtil,
  removeOneOptionSelection,
  selectedOptionCount as selectedOptionCountUtil,
  stripMultiSelectSegments,
} from "@/utils/variantSelection";
export default {
  emits: ["goto-section", "color-variant-images", "variant-updating", "quantity-change", "ready"],
  components: {
    Countdown,
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
    forceShowActions: {
      type: Boolean,
      default: false,
    },
    quantitySeed: {
      type: Number,
      default: null,
    },
      modernLayout: {
    type: Boolean,
    default: false,
  },
  },
data() {
  return {
    enums: enums,
    amountTypes: appConfig.amount_type,
    errors: [],
    attachment: null,
    variantUpdating: false,
    isLoading: true,
    priceFlash: false,
    priceFlashColor: null,
    priceFlashTimeout: null,
    flashColors: ["#ff5a1f", "#0d6efd", "#198754", "#d63384", "#6f42c1", "#fd7e14"],
    flashColorIndex: 0,
    quantityValue:
  this.product.min_item_on_purchase != null &&
    this.product.min_item_on_purchase > 0
    ? parseInt(this.product.min_item_on_purchase)
    : 1,
  };
},
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isCustomerLogin: (state) => state.isCustomerLogin,
    config: (state) => state.siteSettings,
    product_variant() {
      if (this.product.has_variant == 1) {
        const output = [];
        const variant_array = this.product.selectedVariant.split("/");
        for (let i = 0; i < variant_array.length; i++) {
          let single_variant = variant_array[i];
          let single_variant_array = single_variant.split(":");
          let variant_id = single_variant_array[0];
          let variant_value_ids = (single_variant_array[1] || "")
            .split(",")
            .filter(Boolean);
          let match_variant = this.product.attribute.find(
            (item) => item.id == variant_id
          );

          if (!match_variant) {
            continue;
          }

          let variant_name = match_variant.title;

          let variant_value_name = variant_value_ids
            .map((variant_value_id) => {
              let match_variant_value = match_variant.options.find(
                (item) => item.id == variant_value_id
              );
              if (variant_name == "Color" || variant_name == "color") {
                return match_variant_value?.name;
              }
              return match_variant_value?.title;
            })
            .filter(Boolean)
            .join(",");

          let variant = variant_name + ":" + variant_value_name;

          output.push(variant);
        }
        let text = output.join("/");
        return text;
      } else {
        return null;
      }
    },
    min_qty() {
      return this.product.min_item_on_purchase != null &&
        parseInt(this.product.min_item_on_purchase) > 0
        ? parseInt(this.product.min_item_on_purchase)
        : 1;
    },
    max_qty() {
      return this.product.max_item_on_purchase != null &&
        parseInt(this.product.max_item_on_purchase) > 0 &&
        parseInt(this.product.max_item_on_purchase) <
        parseInt(this.product.quantity)
        ? parseInt(this.product.max_item_on_purchase)
        : this.product.quantity;
    },

    ...mapGetters('layout', ['isFeaturePaneLayout', 'isMobile', 'isRtl']),

    isSplitScreenDesktop() {
      return this.isFeaturePaneLayout;
    },

    showSplitProductFooter() {
      if (this.forceShowActions && !this.isFeaturePaneLayout) return false;
      return this.isFeaturePaneLayout || this.isMobile;
    },

    hasMeaningfulSummary() {
      return this.hasMeaningfulHtml(this.product.summary);
    },

  }),
  watch: {
     "product.id"() {
    this.startLoading();
  },
    quantitySeed: {
      handler(value) {
        if (value == null || value === "") {
          return;
        }
        const parsed = parseInt(value, 10);
        if (!Number.isFinite(parsed) || parsed === this.quantityValue) {
          return;
        }
        this.quantityValue = parsed;
      },
      immediate: true,
    },
    quantityValue() {
      //Check minimum qty
      if (this.quantityValue < parseInt(this.min_qty)) {
        this.quantityValue = parseInt(this.min_qty);
      }
      //Check maximum qty
      if (this.quantityValue > parseInt(this.max_qty)) {
        this.quantityValue = parseInt(this.max_qty);
      }
      this.quantityValue = parseInt(this.quantityValue);
      this.$emit("quantity-change", this.quantityValue);
    },
  },
  mounted() {
     this.initializeMultiSelectAttributes();
  this.$emit("quantity-change", this.quantityValue);
  this.startLoading();
  },
methods: {
  hasActiveDiscount() {
    const price = Number(this.product.price || 0);
    const oldPrice = Number(this.product.oldPrice || 0);

    if (oldPrice > price && price >= 0) {
      return true;
    }

    const minPrice = Number(this.product.price_range_min || 0);
    const minOld = Number(this.product.price_range_min_old || 0);
    const maxPrice = Number(this.product.price_range_max || 0);
    const maxOld = Number(this.product.price_range_max_old || 0);

    return (
      (minOld > minPrice && minPrice > 0) ||
      (maxOld > maxPrice && maxPrice > 0)
    );
  },

  /**
   * Detect whether the discount is Percentage or Flat.
   *
   * Priority:
   * 1. Use discount_type from the product when the API provides it.
   * 2. For variant products, infer the type safely from the min/max
   *    original and discounted price ranges.
   * 3. If discount_amount exists but discount_type does not, compare
   *    the configured amount against the selected variant calculation.
   */
  discountMode() {
    const configuredDiscount = Number(this.product.discount_amount || 0);
    const rawType =
      this.product.discount_type !== undefined &&
      this.product.discount_type !== null
        ? String(this.product.discount_type).toLowerCase()
        : "";

    const percentType =
      this.amountTypes?.percent !== undefined &&
      this.amountTypes?.percent !== null
        ? String(this.amountTypes.percent).toLowerCase()
        : "";

    const flatType =
      this.amountTypes?.flat !== undefined &&
      this.amountTypes?.flat !== null
        ? String(this.amountTypes.flat).toLowerCase()
        : "";

    // Exact type from API/admin data when available.
    if (
      rawType &&
      (
        (percentType && rawType === percentType) ||
        rawType === "percent" ||
        rawType === "percentage"
      )
    ) {
      return "percent";
    }

    if (
      rawType &&
      (
        (flatType && rawType === flatType) ||
        rawType === "flat"
      )
    ) {
      return "flat";
    }

    // Variant fallback:
    // Percentage => percentage saving is the same on min and max prices.
    // Flat       => absolute saving is the same on min and max prices.
    const minOld = Number(this.product.price_range_min_old || 0);
    const minPrice = Number(this.product.price_range_min || 0);
    const maxOld = Number(this.product.price_range_max_old || 0);
    const maxPrice = Number(this.product.price_range_max || 0);

    if (
      minOld > minPrice &&
      maxOld > maxPrice &&
      minOld > 0 &&
      maxOld > 0
    ) {
      const minSaving = minOld - minPrice;
      const maxSaving = maxOld - maxPrice;

      const minPercent = (minSaving / minOld) * 100;
      const maxPercent = (maxSaving / maxOld) * 100;

      const samePercentage = Math.abs(minPercent - maxPercent) < 0.01;
      const sameFlatAmount = Math.abs(minSaving - maxSaving) < 0.001;

      if (samePercentage && !sameFlatAmount) {
        return "percent";
      }

      if (sameFlatAmount && !samePercentage) {
        return "flat";
      }
    }

    // Single selected variant fallback when configured amount exists.
    const oldPrice = Number(this.product.oldPrice || 0);
    const price = Number(this.product.price || 0);

    if (
      configuredDiscount > 0 &&
      oldPrice > price &&
      oldPrice > 0
    ) {
      const actualSaving = oldPrice - price;

      const expectedPercentSaving =
        oldPrice * (configuredDiscount / 100);

      if (
        Math.abs(actualSaving - expectedPercentSaving) <
        Math.abs(actualSaving - configuredDiscount)
      ) {
        return "percent";
      }

      return "flat";
    }

    // Final fallback. This only applies when the API gives no discount
    // metadata and there is not enough range information to distinguish.
    return "flat";
  },

  isPercentageDiscount() {
    return this.discountMode() === "percent";
  },

  discountPercentValue() {
    const configuredDiscount = Number(this.product.discount_amount || 0);

    // If API supplied the exact configured percentage, use it.
    if (configuredDiscount > 0 && this.isPercentageDiscount()) {
      return configuredDiscount;
    }

    // For variants, derive the percentage from the already-calculated
    // discounted/original price. This does NOT change the product price.
    const oldPrice = Number(this.product.oldPrice || 0);
    const price = Number(this.product.price || 0);

    if (oldPrice > price && oldPrice > 0) {
      const percent = ((oldPrice - price) / oldPrice) * 100;

      return Number(
        Number.isInteger(Number(percent.toFixed(6)))
          ? percent.toFixed(0)
          : percent.toFixed(2)
      );
    }

    const maxOld = Number(this.product.price_range_max_old || 0);
    const maxPrice = Number(this.product.price_range_max || 0);

    if (maxOld > maxPrice && maxOld > 0) {
      const percent = ((maxOld - maxPrice) / maxOld) * 100;

      return Number(
        Number.isInteger(Number(percent.toFixed(6)))
          ? percent.toFixed(0)
          : percent.toFixed(2)
      );
    }

    return 0;
  },

  discountSavedAmount() {
    const configuredDiscount = Number(this.product.discount_amount || 0);

    // If API supplied the exact configured flat amount, use it.
    if (configuredDiscount > 0 && !this.isPercentageDiscount()) {
      return configuredDiscount;
    }

    const oldPrice = Number(this.product.oldPrice || 0);
    const price = Number(this.product.price || 0);

    if (oldPrice > price) {
      return Number((oldPrice - price).toFixed(3));
    }

    const maxOld = Number(this.product.price_range_max_old || 0);
    const maxPrice = Number(this.product.price_range_max || 0);

    if (maxOld > maxPrice) {
      return Number((maxOld - maxPrice).toFixed(3));
    }

    return 0;
  },

  hasMeaningfulHtml(value) {
    if (!value || typeof value !== "string") {
      return false;
    }
    const text = value
      .replace(/<[^>]*>/g, "")
      .replace(/&nbsp;/gi, " ")
      .trim();
    return text.length > 0;
  },

  startLoading() {
    const minTime = new Promise((resolve) => setTimeout(resolve, 600));
    const imagesLoaded = new Promise((resolve) => {
      this.$nextTick(() => {
        const imgs = this.$el.querySelectorAll ? this.$el.querySelectorAll("img") : [];
        if (!imgs.length) return resolve();
        let loaded = 0;
        imgs.forEach((img) => {
          if (img.complete) {
            loaded++;
            if (loaded === imgs.length) resolve();
          } else {
            img.addEventListener("load", () => { loaded++; if (loaded === imgs.length) resolve(); });
            img.addEventListener("error", () => { loaded++; if (loaded === imgs.length) resolve(); });
          }
        });
      });
    });
    Promise.all([minTime, imagesLoaded]).then(() => {
      this.isLoading = false;
      this.$emit("ready");
    });
  },
  nextFlashColor() {
    const color = this.flashColors[this.flashColorIndex % this.flashColors.length];
    this.flashColorIndex++;
    return color;
  },

    decreaseQuantity() {
      if (this.quantityValue > this.min_qty) {
        this.quantityValue--;
      }
    },
    increaseQuantity() {
      if (this.quantityValue < this.max_qty) {
        this.quantityValue++;
      }
    },

    cleanImage(img) {
      if (!img) return '';
      return img.startsWith('/public') ? img.slice(7) : img;
    },
    gotoSec() {
      this.$emit("goto-section", "productDetails");
    },
    /**
     * Load Color variant images
     *
     */
    colorVariantImages(color_id) {
      this.$emit("color-variant-images", color_id);
    },
    getSelectedOptionIds(attr) {
      return getSelectedOptionIdsUtil(attr, this.product.selectedVariant);
    },
    selectedOptionCount(attr) {
      return selectedOptionCountUtil(attr, this.product.selectedVariant);
    },
    getOptionSelectionCount(attr, option) {
      return getOptionSelectionCountUtil(
        attr,
        option,
        this.product.selectedVariant
      );
    },
    canAddOptionSelection(attr) {
      return canAddOptionSelectionUtil(attr, this.product.selectedVariant);
    },
    initializeMultiSelectAttributes() {
      this.product.selectedVariant = stripMultiSelectSegments(
        this.product.attribute,
        this.product.selectedVariant
      );
    },
    validateMultiSelectSelections() {
      const invalidAttribute = findInvalidMultiSelectAttribute(
        this.product.attribute,
        this.product.selectedVariant
      );

      if (!invalidAttribute) {
        return true;
      }

      this.$toast.error(
        `${this.$t("Please select")} ${invalidAttribute.multi_select_limit} ${invalidAttribute.title}`
      );
      return false;
    },
    increaseOptionSelection(attr, option) {
      const result = addOptionSelection(
        attr,
        option,
        this.product.selectedVariant
      );
      if (!result.added) {
        this.$toast.error(
          `${this.$t("You can select up to")} ${attr.multi_select_limit} ${attr.title}`
        );
        return;
      }
      this.product.selectedVariant = result.selectedVariant;
    },
    decreaseOptionSelection(attr, option) {
      const result = removeOneOptionSelection(
        attr,
        option,
        this.product.selectedVariant
      );
      if (result.removed) {
        this.product.selectedVariant = result.selectedVariant;
      }
    },
    /**
     * Get product variant price
     *
     */
    updateSelectedVariant(attr, option) {
      if (attr.id == "color" || attr.title == "color") {
        this.colorVariantImages(option.id);
      }
      if (attr.multi_select) {
        return;
      }

      this.product.selectedVariant = applySingleOptionSelection(
        attr,
        option,
        this.product.selectedVariant
      );

      this.variantUpdating = true;
      this.$emit("variant-updating", true);
      axios
        .post("/api/v1/ecommerce-core/single-variant-info", {
          id: this.product.id,
          variant: this.product.selectedVariant,
          choice: attr.id,
          option: option.id,
        })
        .then((response) => {
          if (response.data.success) {
            this.product.price = response.data.base_price;
            this.product.selectedVariant = response.data.new_variant;
            this.product.quantity = response.data.quantity;
            this.product.oldPrice = response.data.oldPrice;

            if (this.priceFlashTimeout) {
  clearTimeout(this.priceFlashTimeout);
              }
              this.priceFlash = false;
              this.$nextTick(() => {
                this.priceFlashColor = this.nextFlashColor();
                this.priceFlash = true;
                // small delay just to retrigger the pulse animation class next time,
                // NOT to revert the color
                this.priceFlashTimeout = setTimeout(() => {
                  this.priceFlash = false; // only resets the animation trigger class
                }, 700);
              });
          } else {
            this.$toast.error(this.$t("Something went wrong"));
          }
        })
        .catch((error) => {
          this.$toast.error(this.$t("Something went wrong"));
        })
        .finally(() => {
          this.variantUpdating = false;
          this.$emit("variant-updating", false);
        });
    },
    /**
     * Whether this option is part of the current selectedVariant
     */
    isOptionSelected(attr, option) {
      if (!this.product.selectedVariant) {
        if (attr.multi_select) {
          return false;
        }
        return attr.options[0] && attr.options[0].id == option.id;
      }
      const parts = this.product.selectedVariant.split("/");
      const key = String(attr.id);
      const match = parts.find((p) => p.split(":")[0] == key);
      if (!match) return false;
      if (attr.multi_select) {
        return (match.split(":")[1] || "")
          .split(",")
          .includes(String(option.id));
      }
      return match.split(":")[1] == String(option.id);
    },
    /**
     * Place order
     */
    placeOrder() {
      if (this.variantUpdating) {
        return;
      }
      if (!this.validateMultiSelectSelections()) {
        return;
      }
      let image = "";
      if (this.product.galleryImages[0].type == "image") {
        image = this.product.galleryImages[0].regular;
      } else {
        image = this.product.galleryImages[1].regular;
      }
      let cart_item = {
        uid: Date.now(),
        id: this.product.id,
        name: this.product.name,
        permalink: this.product.permalink,
        image: image,
        variant: this.product_variant,
        variant_code: this.product.selectedVariant,
        unitPrice: this.product.price,
        oldPrice: this.product.oldPrice,
        quantity: this.quantityValue,
        attachment: this.attachment,
        max_item: this.max_qty,
        min_item: this.min_qty,
        seller: this.product.seller,
        shop_name:
          this.product.shopInfo != null ? this.product.shopInfo.name : null,
        shop_slug:
          this.product.shopInfo != null ? this.product.shopInfo.slug : null,
      };

      // console.log("cart-item-DetailsContent: ", cart_item);

      this.$store.dispatch("addToCart", cart_item);
      this.$router.push("/cart");
    },
    /**
     * Store items to cart
     */
    addToCart() {
      if (this.variantUpdating) {
        return;
      }
      if (!this.validateMultiSelectSelections()) {
        return;
      }
      let image = "";
      if (this.product.galleryImages[0].type == "image") {
        image = this.product.galleryImages[0].regular;
      } else {
        image = this.product.galleryImages[1].regular;
      }
      let cart_item = {
        uid: Date.now(),
        id: this.product.id,
        name: this.product.name,
        permalink: this.product.permalink,
        image: image,
        variant: this.product_variant,
        variant_code: this.product.selectedVariant,
        unitPrice: this.product.price,
        oldPrice: this.product.oldPrice,
        quantity: this.quantityValue,
        attachment: this.attachment,
        max_item: this.max_qty,
        min_item: this.min_qty,
        seller: this.product.seller,
        shop_name:
          this.product.shopInfo != null ? this.product.shopInfo.name : null,
        shop_slug:
          this.product.shopInfo != null ? this.product.shopInfo.slug : null,
      };

      this.$store.dispatch("addToCart", cart_item);
    },
    /**
     * Add attachment with order
     */
    addAttachment() {
      this.errors = [];
      let formData = new FormData();
      formData.append(
        "attachment_old",
        this.attachment != null ? this.attachment.file_id : null
      );
      formData.append("attachment", this.$refs.attachment.files[0]);
      axios
        .post("/api/v1/ecommerce-core/upload-attachment-in-order", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.attachment = response.data.attatchment;
            this.$toast.success(this.$t("Attachment upload successfully"));
          } else {
            this.$toast.error(this.$t("Attachment upload failed"));
          }
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          } else {
            this.$toast.error(this.$t("Attachment upload failed"));
          }
        });
    },
    /**
     * Add to wishlist
     */
    addToWishlist() {
      if (this.isCustomerLogin) {
        axios
          .post(
            "/api/v1/ecommerce-core/customer/store-product-to-wishlist",
            {
              product_id: this.product.id,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.$store.dispatch("refreshCustomerDashboardInfo");
              this.$toast.success("Product added to wishlist successfully");
            } else {
              this.$toast.error("Product add to wishlist failed");
            }
          })
          .catch((error) => {
            this.$toast.error("Product add to wishlist failed");
          });
      } else {
        this.$toast.error("Please login");
        this.$router.push("/login");
      }
    },
    /**
     * Add to compare
     */
    addToCompare() {
      this.$store.dispatch("addItemToCompareItems", this.product.id);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.product-details-loader {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  width: 100%;

  .loader-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba($c1, 0.2);
    border-top-color: $c1;
    border-radius: 50%;
    animation: product-details-spin 0.7s linear infinite;
  }
}

@keyframes product-details-spin {
  to {
    transform: rotate(360deg);
  }
}

@keyframes priceFlashAnim {
  0% {
    color: var(--price-flash-color, $c1);
  }
  100% {
    color: #222;
  }
}

.unit-price .price h3 {
  font-size: 25px;
  font-weight: 700;
  // color: #222;
}

/* Price range sizing — applies everywhere, LTR and RTL */
.product-price.price-range {
  max-width: 240px;

  h6 {
    font-size: 12px;
  }

  .price {
    h3 {
      font-size: 16px;
      margin-right: 10px !important;
    }

    del {
      font-size: 12px;
    }
  }
}

.price-current-wrap.price-flash {
  :deep(h3) {
    animation: priceFlashPulse 0.7s ease;
  }
}

@keyframes priceFlashPulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.06); }
  100% { transform: scale(1); }
}

.price-current-wrap {
  :deep(h3) {
    color: var(--price-flash-color, #222);
    transition: color 0.25s ease;
  }
}

// .price-flash {
//   animation: priceFlashAnim 0.7s ease;
// }

.option-label {
  font-weight: 700;
  line-height: 1;
  margin-bottom: 10px;
  font-family: $title-font;
}

.btn-loading-content {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Split-screen attribute list-cards */
.option-list-cards {
  gap: 8px;
  margin-top: 8px;
}

.option-list-card {
  position: relative;
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  margin: 0;
  padding: 12px 14px;
  border: 1px solid #e6e6e6;
  border-radius: 6px;
  background-color: #fff;
  cursor: pointer;
  transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;

  input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
  }

  &:hover {
    border-color: rgba($c1, 0.5);
    background-color: rgba($c1, 0.04);
  }

  &--multi-select {
    cursor: default;
  }

  &.is-selected {
    border-color: $c1;
    background-color: rgba($c1, 0.08);
    box-shadow: 0 0 0 1px $c1;
  }

  &:disabled:not(.is-selected) {
    cursor: not-allowed;
    opacity: 0.55;
  }

  &__swatch {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__label {
    flex: 1;
    line-height: 1.3;
    font-size: 14px;
  }
}

.multi-select-option {
  gap: 8px;
  margin-right: 10px;
  margin-bottom: 10px;

  &.is-selected .multi-select-option__label {
    border-color: $c1;
  }

  &__label {
    min-width: 48px;
    text-align: center;
  }
}

.multi-select-option-qty {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-left: auto;
  flex-shrink: 0;

  &__btn {
    width: 28px;
    height: 28px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    line-height: 1;
    font-size: 16px;
    padding: 0;

    &:disabled {
      opacity: 0.4;
      cursor: not-allowed;
    }
  }

  &__count {
    min-width: 18px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
  }
}

.button-group {
  margin-top: -15px;

  .btn {
    padding: 10px 22px;
    margin-right: 15px;
  }

  .btn-wishlist,
  .btn-compare {
    .material-icons {
      color: $c1;
      font-size: 18px;
    }
  }

  >* {
    margin-top: 15px;
  }

  @media only screen and (max-width: 767px) {
    margin: 0;
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #ddd;
    left: 0;
    justify-content: center;
    z-index: 9;
    height: 60px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    gap: 0 !important;

    .btn {
      padding: 8px 16px;
      margin-right: 0;
      width: 35%;
      border-radius: 0;
      height: 100%;
      line-height: 1.2;

      &:hover {
        background-color: $c1;
      }

      &:nth-child(2) {
        background-color: #f2380f;

        &:hover {
          background-color: #f2380f;
        }
      }
    }

    .btn-wishlist,
    .btn-compare {
      .material-icons {
        color: #fff;
      }
    }

    .btn-group-right {
      width: 30%;

      .icon_btn {
        background-color: #f26110;
        width: 50%;
        height: 60px;

        &.btn-chat {
          background-color: #ff7624;
        }
      }

      .icon-wrapper {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border: 1px solid #fff;
        border-radius: 50%;
        color: #fff;
        font-size: 18px;
        justify-content: center;
      }
    }

    >* {
      margin-top: 0px;
    }
  }
}

.product-title {
  margin-top: -1px;
  margin-bottom: 15px;
  font-size: 24px;
  font-weight: 500;
}

.btn:disabled {
  color: white;
  pointer-events: none;
  background-color: $c1;
  border-color: var(--bs-btn-disabled-border-color);
  opacity: var(--bs-btn-disabled-opacity);
}

.deal-title {
  font-size: 20px;
  font-weight: 600;
  color: #ffffff;
}

.flash-deal {
  background-color: $c1;
}

.mt3px {
  margin-top: 3px;
}

.mt5px {
  margin-top: 5px;
}

.divider {
  margin-top: 25px !important;
  margin-bottom: 25px !important;
}

.mt-25 {
  margin-top: 25px;
}

.product-discount-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 12px;
  margin: 8px 0 16px;
  border: 1px solid var(--mainC);
  border-radius: 20px;
  color: var(--mainC);
  font-size: 13px;
  font-weight: 600;
  line-height: 1;
}

.product-discount-badge .material-icons {
  font-size: 17px;
}

.product-discount-badge strong {
  font-weight: 700;
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.product-details-content--rtl {

  .product-title,
  .product-summary-section,
  h6 {
    direction: rtl;
    text-align: right;
  }

  .rating-wrap {
    width: 100%;
    direction: rtl;
    justify-content: flex-start;

    >.d-flex.align-items-center {
      flex-direction: row-reverse;
      direction: ltr;
    }

    .rating-text-wrap {
      direction: rtl;
      text-align: right;
    }

    [data-star] {
      text-align: right;
    }
  }



  .product-price .price {
    direction: ltr;
    text-align: right;
    justify-content: flex-end;
  }

  .unit-price .price del {
    margin-left: 0;
    margin-right: 20px;
  }

  .price-range {
    width: 100%;
    text-align: right;

    .price {
      width: 100%;
      justify-content: flex-end;

      h3 {
        margin-right: 0 !important;
        margin-left: 20px;
      }

      del {
        margin-right: 0 !important;
        margin-left: 0;
      }
    }
  }

  .option-choice-form {
    width: 100%;
    direction: rtl;
    text-align: right;
  }

  .option-choice-form .mb-3 {
    width: 100%;
  }

  .option-label {
    display: block;
    width: 100%;
    direction: rtl;
    text-align: right;
  }

  .multi-select-count {
    display: inline-block;
    width: 100%;
    text-align: right;
    margin-left: 0;
    margin-inline-start: 0;
  }

  .option-list-cards {
    width: 100%;
    align-self: stretch;
  }

  .option-list-card {
    direction: rtl;
    text-align: right !important;
    justify-content: flex-start;

    &__label {
      text-align: right;
      flex: 1 1 auto;
    }
  }

  .multi-select-option-qty {
    margin-left: 0;
    margin-right: auto;
    direction: ltr;
  }

  .product-details-quantity .d-flex {
    flex-direction: row-reverse;
    direction: ltr;
    justify-content: flex-end;
  }

  .attach-input-wrapper {
    flex-direction: row-reverse;
    direction: ltr;
    justify-content: flex-end;
  }

  .attach-input-wrapper h6 {
    margin-right: 0;
    margin-left: 10px;
  }
}

/* ========================================
   MODERN THEME PRODUCT INFORMATION
======================================== */

.product-details-content--modern {
  position: relative;
  width: 100%;
  padding: 14px 16px 24px;
  background: #fff;

  .modern-product-title-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
  }

  .product-title {
    flex: 1;
    margin: 0;
    color: #24201e;
    font-size: 17px;
    line-height: 1.3;
    font-weight: 600;
  }

  .modern-product-wishlist {
    flex: 0 0 auto;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 0;
    outline: 0;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #766963;
    cursor: pointer;

    .material-icons {
      font-size: 21px;
    }
  }

  .unit-price {
    margin-top: 4px !important;

    .price {
      align-items: center;
    }

    :deep(h3) {
      margin: 0;
      color: #6f4c3b;
      font-size: 15px;
      line-height: 1.3;
      font-weight: 700;
    }

    :deep(del) {
      margin-left: 8px !important;
      color: #aaa;
      font-size: 12px;
    }
  }

  .modern-description-section {
    margin-top: 18px;
  }

  .modern-description-heading {
    margin: 0 0 7px;
    color: #24201e;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 700;
  }

  .product-description {
    color: #5f5a57;
    font-size: 12px;
    line-height: 1.55;

    :deep(p) {
      margin-bottom: 8px;
    }

    :deep(p:last-child) {
      margin-bottom: 0;
    }
  }

  .option-choice-form {
    margin-top: 22px !important;
  }

  .option-label {
    color: #24201e;
    font-size: 13px;
  }

  .option-list-card {
    padding: 10px 12px;
    border-radius: 10px;
    border-color: #ece7e4;

    &.is-selected {
      border-color: #795548;
      background: rgba(121, 85, 72, 0.06);
      box-shadow: 0 0 0 1px #795548;
    }
  }

  .product-details-quantity {
    margin-top: 20px;
  }

  .quantity-input {
    height: 38px;
    border: 1px solid #e8e3e0;
    border-radius: 999px;
    overflow: hidden;
    background: #faf9f8;

    button {
      width: 38px;
    }

    input {
      min-width: 42px;
      background: transparent;
    }
  }
}
</style>
