<template>
  <div class="shadow-card mb-30" :class="{ 'delivery-shipping--rtl': isRtl }">
    <div class="delivery-shipping__header d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h3 class="checkout-title">{{ $t("Delivery & Shipping") }}</h3>
      <router-link to="/dashboard/address" class="btn_underline" v-if="isCustomerLogin">{{ $t("Manage Address")
      }}</router-link>
    </div>

    <!--Delivery Options-->
    <div class="row mt-3">
      <div class="col-12">
        <ul class="form-selector-list hide-radio justify-content-between list-unstyled mb-3">
          <li class="m-0 m-w-100 mb-3 single-form-selector">
            <span class="custom-radio-btn" :class="{ active: isActiveHomeDelivery }">
              <label>
                <input type="radio" value="Pickup" class="shipping-method delivery-type-pickup" name="delivery-options"
                  :checked="isActiveHomeDelivery" v-on:change="
                    () => {
                      isActiveHomeDelivery = true;
                      isActivePickupPoint = false;
                      errors = [];
                    }
                  " />
                <span class="icon-wrap"><span class="material-icons"> local_shipping </span></span>
                <span class="label-title">{{ $t("Home Delivery") }}</span>
              </label>
            </span>
          </li>
          <li class="m-0 m-w-100 mb-3 single-form-selector" v-if="true">
            <!-- <li
            class="m-0 m-w-100 mb-3 single-form-selector"
            v-if="
              config?.enable_pickuppoint_in_checkout == enums.status.ACTIVE &&
              config?.is_active_pickuppoint == enums.status.ACTIVE
            "
          > -->
            <!-- <span
              class="custom-radio-btn"
              :class="{ active: isActivePickupPoint }"
            > -->
            <!-- <label>
                <input
                  type="radio"
                  value="Delivery"
                  class="shipping-method delivery-type-delivery"
                  name="delivery-options"
                  :checked="isActivePickupPoint"
                  v-on:change="
                    () => {
                      isActiveHomeDelivery = false;
                      isActivePickupPoint = true;
                      errors = [];
                    }
                  "
                />
                <span class="icon-wrap"
                  ><span class="material-icons">
                    store_mall_directory
                  </span></span
                >
                <span class="label-title">{{ $t("Collect From Store") }}</span>
              </label> -->
            <!-- </span> -->
          </li>
        </ul>
      </div>
    </div>
    <!--End Delivery Options-->

    <!--Home Delivery-->
    <template v-if="isActiveHomeDelivery">
      <!--Login user Checkout-->
      <div v-if="isCustomerLogin">
        <!--Shipping Address-->
        <div class="row" v-if="isActiveHomeDelivery">
          <div class="col-12">
            <h5>{{ $t("Shipping Details") }}</h5>
            <div class="save-adderss row" v-if="customerAddress.length > 0">
              <div class="col-lg-12 mb-4" v-for="address in customerAddress" :key="address.name">
                <span class="custom-radio-btn" ref="addressRadio" :class="{
                  active:
                    customerShippingInfo != null &&
                    address.id == customerShippingInfo.id,
                }">
                  <label class="radio-label">
                    <input name="customerShippingAddress" type="radio" :value="address" v-model="customerShippingInfo"
                      @change.prevent="checkedAddress" :checked="customerShippingInfo != null &&
                        address.id == customerShippingInfo.id
                        " />
                    <span class="radio-text">
                      <span class="font-weight-bold">{{ address.name }}</span>
                      <br />
                      <span> {{ $t("Address:") }} {{ address.address }} </span>
                      <br />
                      <span>{{ $t("Phone:") }} {{ address.phone }}</span>
                      <br />
                      <span>
                        {{ $t("Postal Code:") }} {{ address.postal_code }}
                      </span>
                      <br />
                      <span v-if="address.city != null">
                        {{ address.city.name }} ,
                      </span>
                      <span v-if="address.state != null">
                        {{ address.state.name }} ,
                      </span>
                      <span v-if="address.country != null">
                        {{ address.country.name }}
                      </span>
                    </span>
                  </label>
                </span>
              </div>
            </div>
            <div class="save-adderss row" v-else>
              <div class="col-lg-12 mb-4">
                <p class="alert alert-danger">{{ $t("No address found") }}</p>
                <router-link to="/dashboard/address" class="btn_underline">{{
                  $t("Add new address")
                }}</router-link>
              </div>
            </div>
          </div>
          <delivery-time-selector
            v-if="isSchedulingEnabled && deliverNowEnabled && deliverySchedulingEnabled"
            ref="deliveryTimeSelectorLoggedIn"
            :config="config"
            :enums="enums"
          />
        </div>
        <!--End Shipping Address-->
        <!--Billing Address-->
        <!-- <div class="row" v-if="
          config?.enable_billing_address == enums.status.ACTIVE &&
          config?.use_shipping_address_as_billing_address !=
          enums.status.ACTIVE
        ">
          <div class="col-12">
            <h5>{{ $t("Billing Details") }}</h5>
            <div class="save-adderss row">
              <div class="col-lg-12 mb-4" v-for="address in customerAddress" :key="address.name">
                <span class="custom-radio-btn" ref="billingAddressRadio" :class="{
                  active:
                    customerBillingInfo != null &&
                    address.id == customerBillingInfo.id,
                }">
                  <label class="radio-label">
                    <input name="customerBillingAddress" type="radio" :value="address" v-model="customerBillingInfo"
                      @change.prevent="checkedBillingAddress" :checked="customerBillingInfo != null &&
                        address.id == customerBillingInfo.id
                        " />
                    <span class="radio-text">
                      <span class="font-weight-bold">{{ address.name }}</span>
                      <br />
                      <span> {{ $t("Address:") }} {{ address.address }} </span>
                      <br />
                      <span>{{ $t("Phone:") }} {{ address.phone }}</span>
                      <br />
                      <span>
                        {{ $t("Postal Code:") }} {{ address.postal_code }}
                      </span>
                      <br />
                      <span v-if="address.city != null">
                        {{ address.city.name }} ,
                      </span>
                      <span v-if="address.state != null">
                        {{ address.state.name }} ,
                      </span>
                      <span v-if="address.country != null">
                        {{ address.country.name }}
                      </span>
                    </span>
                  </label>
                </span>
              </div>
            </div>
          </div>
        </div> -->
        <!--End Billing Address-->
      </div>
      <!--End Login user Checkout-->

      <!--Guest Checkout -->
      <div v-if="!isCustomerLogin">
        <!-- <div
          class="row"
          v-if="
            config?.enable_personal_info_guest_checkout == enums.status.ACTIVE
          "
        > -->

        <div class="row">

          <h5>{{ $t("Personal Information") }}</h5>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Your Name") }}
              <span class="text-danger" v-if="
                isActivePickupPoint ||
                config?.enable_personal_info_guest_checkout == enums.status.ACTIVE
              ">*</span>
            </label>
            <input type="text" v-bind:placeholder="$t('Your Name')" class="theme-input-style"
              v-model="guestCustomerInfo.name" />
            <div v-for="error in errors" :key="error.customer_name">
              <p class="text-danger validation-error" v-if="error.customer_name">
                {{ error.customer_name }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Email") }}
              <span class="text-danger" v-if="
                isActivePickupPoint ||
                config?.enable_personal_info_guest_checkout == enums.status.ACTIVE
              ">*</span>
            </label>
            <input type="email" v-bind:placeholder="$t('Email')" v-model="guestCustomerInfo.email"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.customer_email">
              <p class="text-danger validation-error" v-if="error.customer_email">
                {{ error.customer_email }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6" v-if="
            config?.create_account_in_guest_checkout == enums.status.ACTIVE
          ">
            <label class="d-flex gap-1 radio-label">
              <input name="createNewAccount" type="checkbox"
                @change="isActiveCreateNewAccount = !isActiveCreateNewAccount" :checked="isActiveCreateNewAccount" />
              <span class="radio-text"> {{ $t("Create an Account") }} ? </span>
            </label>
          </div>
          <div class="row m-0 p-0" v-if="
            isActiveCreateNewAccount &&
            config?.create_account_in_guest_checkout == enums.status.ACTIVE
          ">
            <div class="form-group mb-20 col-lg-6">
              <input type="password" placeholder="Password" class="theme-input-style"
                v-model="guestCustomerInfo.password" />
              <div v-for="error in errors" :key="error.customer_password">
                <p class="text-danger validation-error" v-if="error.customer_password">
                  {{ error.customer_password }}
                </p>
              </div>
            </div>
            <div class="form-group mb-20 col-lg-6">
              <input type="password" placeholder="Confirm Password" v-model="guestCustomerInfo.confirm_password"
                class="theme-input-style" />
              <div v-for="error in errors" :key="error.customer_confirm_password">
                <p class="text-danger validation-error" v-if="error.customer_confirm_password">
                  {{ error.customer_confirm_password }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <!--Guest Shipping Address-->
        <div class="row guest-shipping-address" v-if="isActiveHomeDelivery">
          <div class="col-12">
            <h5>{{ $t("Shipping Details") }}</h5>
          </div>
          <div class="form-group mb-20 col-lg-6" v-if="config?.enable_name_in_checkout == enums.status.ACTIVE">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Your Name") }}
              <span class="text-danger" v-if="config?.name_required_in_checkout == enums.status.ACTIVE">*</span>
            </label>
            <input type="text" v-bind:placeholder="$t('Your Name')" v-model="guestShippingInfo.name"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.shipping_name">
              <p class="text-danger validation-error" v-if="error.shipping_name">
                {{ error.shipping_name }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
  <label class="font-weight-bold fz-12 mb-2">
    {{ $t("Email") }}
  </label>

  <input
    type="email"
    v-bind:placeholder="$t('Email')"
    v-model="guestCustomerInfo.email"
    class="theme-input-style"
  />
            <div v-for="error in errors" :key="error.shipping_email">
              <p class="text-danger validation-error" v-if="error.shipping_email">
                {{ error.shipping_email }}
              </p>
            </div>
          </div>
          <!-- <div
            class="form-group mb-20 col-lg-6"
            v-if="config?.enable_phone_in_checkout == enums.status.ACTIVE"
          > -->
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Phone Number") }} <span class="text-danger">*</span>
            </label>
            <input type="tel" v-bind:placeholder="kuwaitMobileHint" v-model="guestShippingInfo.phone"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.shipping_phone">
              <p class="text-danger validation-error" v-if="error.shipping_phone">
                {{ error.shipping_phone }}
              </p>
            </div>
          </div>
          <!-- <div
            class="form-group mb-20 col-lg-6"
            v-if="config?.enable_address_in_checkout == enums.status.ACTIVE"
          > -->
          <!-- <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Address") }} <span class="text-danger">*</span>
            </label>
            <input type="text" class="theme-input-style" v-bind:placeholder="$t('Address')"
              v-model="guestShippingInfo.address" />
            <div v-for="error in errors" :key="error.shipping_address">
              <p class="text-danger validation-error" v-if="error.shipping_address">
                {{ error.shipping_address }}
              </p>
            </div>
          </div> -->

          <!-- <div v-if="config?.enable_post_code_in_checkout == enums.status.ACTIVE" :class="config?.hide_country_state_city_in_checkout == enums.status.ACTIVE
            ? 'form-group mb-20 col-lg-12'
            : 'form-group mb-20 col-lg-6'
            ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Postal Code") }}
              <span class="text-danger" v-if="config?.post_code_required_in_checkout == enums.status.ACTIVE">*</span>
            </label>
            <input type="text" class="theme-input-style" v-bind:placeholder="$t('Postal Code')"
              v-model="guestShippingInfo.postal_code" />
            <div v-for="error in errors" :key="error.shipping_postal_code">
              <p class="text-danger validation-error" v-if="error.shipping_postal_code">
                {{ error.shipping_postal_code }}
              </p>
            </div>
          </div> -->

          <div class="form-group mb-20 col-lg-6" v-if="
            config?.hide_country_state_city_in_checkout !=
            enums.status.ACTIVE && countries.length > 1
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Country") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="countries" v-model="guestShippingInfo.country" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.shipping_country">
              <p class="text-danger validation-error" v-if="error.shipping_country">
                {{ error.shipping_country }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6 checkout-state-city-half" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("State") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="guestShippingInfo.states_options" v-model="guestShippingInfo.state" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.shipping_state">
              <p class="text-danger validation-error" v-if="error.shipping_state">
                {{ error.shipping_state }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6 checkout-state-city-half" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("City") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="guestShippingInfo.cities_options" v-model="guestShippingInfo.city" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.shipping_city">
              <p class="text-danger validation-error" v-if="error.shipping_city">
                {{ error.shipping_city }}
              </p>
            </div>
          </div>

          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Address") }} <span class="text-danger">*</span>
            </label>
            <input type="text" class="theme-input-style" v-bind:placeholder="$t('Address')"
              v-model="guestShippingInfo.address" />
            <div v-for="error in errors" :key="error.shipping_address">
              <p class="text-danger validation-error" v-if="error.shipping_address">
                {{ error.shipping_address }}
              </p>
            </div>
          </div>
          <!-- <div class="form-group mb-20 col-lg-6" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <v-select :options="guestShippingInfo.cities_options" v-model="guestShippingInfo.city" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.shipping_city">
              <p class="text-danger validation-error" v-if="error.shipping_city">
                {{ error.shipping_city }}
              </p>
            </div>
          </div> -->
          <delivery-time-selector
            v-if="isSchedulingEnabled && deliverNowEnabled && deliverySchedulingEnabled"
            ref="deliveryTimeSelectorGuest"
            :config="config"
            :enums="enums"
          />
        </div>
        <!--End Guest Shipping Address-->
        <!--Bill to different Address-->
        <!-- <div class="row" v-if="
          config?.use_shipping_address_as_billing_address !=
          enums.status.ACTIVE &&
          config?.enable_billing_address == enums.status.ACTIVE
        ">
          <div class="col-12">
            <div class="form-group mb-20 col-lg-6" v-if="isActiveHomeDelivery">
              <label class="d-flex gap-1 radio-label">
                <input name="billToDifferentAddress" type="checkbox" @change="
                  isActiveBillToDifferentAddress =
                  !isActiveBillToDifferentAddress
                  " />
                <span class="radio-text">
                  {{ $t("Bill to Different Address") }} ?
                </span>
              </label>
            </div>
          </div>
        </div> -->
        <!--End Bill to different Address-->
        <!--Guest Billing Address-->
        <!-- <div class="row guest-billing-address" v-if="
          config?.enable_billing_address == enums.status.ACTIVE &&
          config?.use_shipping_address_as_billing_address !=
          enums.status.ACTIVE &&
          isActiveBillToDifferentAddress
        ">
          <div class="col-12">
            <h5>{{ $t("Billing Details") }}</h5>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Your Name") }} <span class="text-danger">*</span>
            </label>
            <input type="text" v-bind:placeholder="$t('Your Name')" v-model="guestBillingInfo.name"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.billing_name">
              <p class="text-danger validation-error" v-if="error.billing_name">
                {{ error.billing_name }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Email Address") }} <span class="text-danger">*</span>
            </label>
            <input type="email" v-bind:placeholder="$t('Email Address')" v-model="guestBillingInfo.email"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.billing_email">
              <p class="text-danger validation-error" v-if="error.billing_email">
                {{ error.billing_email }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Phone Number") }} <span class="text-danger">*</span>
            </label>
            <input type="tel" v-bind:placeholder="kuwaitMobileHint" v-model="guestBillingInfo.phone"
              class="theme-input-style" />
            <div v-for="error in errors" :key="error.billing_phone">
              <p class="text-danger validation-error" v-if="error.billing_phone">
                {{ error.billing_phone }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Address") }} <span class="text-danger">*</span>
            </label>
            <input type="text" class="theme-input-style" v-bind:placeholder="$t('Address')"
              v-model="guestBillingInfo.address" />
            <div v-for="error in errors" :key="error.billing_address">
              <p class="text-danger validation-error" v-if="error.billing_address">
                {{ error.billing_address }}
              </p>
            </div>
          </div>
          <div :class="config?.hide_country_state_city_in_checkout == enums.status.ACTIVE
            ? 'form-group mb-20 col-lg-12'
            : 'form-group mb-20 col-lg-6'
            ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Postal Code") }}
              <span class="text-danger" v-if="config?.post_code_required_in_checkout != 2">*</span>
            </label>
            <input type="text" class="theme-input-style" v-bind:placeholder="$t('Postal Code')"
              v-model="guestBillingInfo.postal_code" />
            <div v-for="error in errors" :key="error.billing_postal_code">
              <p class="text-danger validation-error" v-if="error.billing_postal_code">
                {{ error.billing_postal_code }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("Country") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="countries" v-model="guestBillingInfo.country" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.billing_country">
              <p class="text-danger validation-error" v-if="error.billing_country">
                {{ error.billing_country }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("State") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="guestBillingInfo.states_options" v-model="guestBillingInfo.state" label="name"
              :clearable="false"></v-select>
            <div v-for="error in errors" :key="error.billing_state">
              <p class="text-danger validation-error" v-if="error.billing_state">
                {{ error.billing_state }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6" v-if="
            config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
          ">
            <label class="font-weight-bold fz-12 mb-2">
              {{ $t("City") }} <span class="text-danger">*</span>
            </label>
            <v-select :options="guestBillingInfo.cities_options" v-model="guestBillingInfo.city" :clearable="false"
              label="name"></v-select>
            <div v-for="error in errors" :key="error.billing_city">
              <p class="text-danger validation-error" v-if="error.billing_city">
                {{ error.billing_city }}
              </p>
            </div>
          </div>
        </div> -->
        <!--End Guest Billing Address-->
      </div>
      <!--End Guest Checkout -->

      <!--Booking Time: shown and required only when backend Book Now is enabled-->
      <booking-time-checkout
        v-if="isActiveHomeDelivery && displayConfigLoaded && bookingNowEnabled"
        ref="bookingTimeCheckout"
        :config="config"
        :enums="enums"
        @hours-selected="$emit('booking-hours-selected', $event)"
      />
    </template>
    <!--End Home delivery-->

    <!--Pickup point Selector-->
    <!-- <div class="row" v-if="isActivePickupPoint">
      <div class="row" v-if="!isCustomerLogin">
        <h5>{{ $t("Personal Information") }}</h5>
        <div class="form-group mb-20 col-lg-6">
          <input
            type="text"
            v-bind:placeholder="$t('Your Name')"
            class="theme-input-style"
            v-model="guestCustomerInfo.name"
          />
          <div v-for="error in errors" :key="error.customer_name">
            <p class="text-danger validation-error" v-if="error.customer_name">
              {{ error.customer_name }}
            </p>
          </div>
        </div>
        <div class="form-group mb-20 col-lg-6">
          <input
            type="email"
            v-bind:placeholder="$t('Email')"
            v-model="guestCustomerInfo.email"
            class="theme-input-style"
          />
          <div v-for="error in errors" :key="error.customer_email">
            <p class="text-danger validation-error" v-if="error.customer_email">
              {{ error.customer_email }}
            </p>
          </div>
        </div>
        <div
          class="form-group mb-20 col-lg-6"
          v-if="config?.create_account_in_guest_checkout == enums.status.ACTIVE"
        >
          <label class="d-flex gap-1 radio-label">
            <input
              name="createNewAccount"
              type="checkbox"
              @change="isActiveCreateNewAccount = !isActiveCreateNewAccount"
              :checked="isActiveCreateNewAccount"
            />
            <span class="radio-text"> {{ $t("Create an Account") }} ? </span>
          </label>
        </div>
        <div
          class="row m-0 p-0"
          v-if="
            isActiveCreateNewAccount &&
            config?.create_account_in_guest_checkout == enums.status.ACTIVE
          "
        >
          <div class="form-group mb-20 col-lg-6">
            <input
              type="password"
              placeholder="Password"
              class="theme-input-style"
              v-model="guestCustomerInfo.password"
            />
            <div v-for="error in errors" :key="error.customer_password">
              <p
                class="text-danger validation-error"
                v-if="error.customer_password"
              >
                {{ error.customer_password }}
              </p>
            </div>
          </div>
          <div class="form-group mb-20 col-lg-6">
            <input
              type="password"
              placeholder="Confirm Password"
              v-model="guestCustomerInfo.confirm_password"
              class="theme-input-style"
            />
            <div v-for="error in errors" :key="error.customer_confirm_password">
              <p
                class="text-danger validation-error"
                v-if="error.customer_confirm_password"
              >
                {{ error.customer_confirm_password }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group mb-20 col-lg-12">
        <h5>{{ $t("Pickup Point") }}</h5>
        <v-select
          :options="pickupPoints"
          :clearable="false"
          :searchable="true"
          v-model="selectedPickupPoints"
          label="name"
          class="pickup-point-select"
        >
          <template v-slot:selected-option="option">
            <div class="row">
              <p class="col-12 font-weight-bold mb-0">{{ option.name }}</p>
              <p v-if="option.location" class="mb-0">
                <span>{{ option.location }}</span>
              </p>
              <p v-if="option.phone" class="mb-0">
                <span>{{ option.phone }}</span>
              </p>
              <p class="mb-0" v-if="option.zone_name != null">
                <span>{{ option.zone_name }}</span>
              </p>
            </div>
          </template>
          <template v-slot:option="option">
            <p class="col-12 font-weight-bold mb-0">{{ option.name }}</p>
            <p class="mb-0">
              <span>{{ option.location }}</span>
            </p>
            <p class="mb-0">
              <span>{{ option.phone }}</span>
            </p>
            <p class="mb-0" v-if="option.zone_name != null">
              <span>{{ option.zone_name }}</span>
            </p>
          </template>
        </v-select>
        <div v-for="error in errors" :key="error.pickup_point">
          <p class="text-danger validation-error" v-if="error.pickup_point">
            {{ error.pickup_point }}
          </p>
        </div>
      </div>
    </div> -->
    <!--End Pickup Point Selector-->

    <!--Delivery not available alert-->
    <div class="row m-0" v-if="
      (deliveryNotAvailable &&
        guestShippingInfo &&
        guestShippingInfo.city &&
        guestShippingInfo.city.id) ||
      (deliveryNotAvailable &&
        customerShippingInfo &&
        customerShippingInfo.city &&
        customerShippingInfo.city.id)
    ">
      <div class="alert alert-danger col-12">
        <p class="d-flex align-items-center justify-content-center">
          {{ $t("Delivery not available at this location") }}
        </p>
      </div>
    </div>
    <!--End delivery not available alert-->

    <!--Action area-->
    <!-- Continue removed: Place Order on Payment runs validateAndPersist + prep + submit -->
    <!-- <div class="row">
      <div class="col-12 checkout-cta-stack">
        <button type="button" class="btn btn_fill m-w-100 mb-10 justify-content-center" @click.prevent="goPreviousStep">
          <span class="material-icons me-2"> arrow_back </span>
          {{ $t("Previous") }}
        </button>
        <button type="button"
          class="btn btn_fill checkout-cta-primary w-100 justify-content-center"
          :disabled="deliveryNotAvailable" @click.prevent="goNextStep">
          {{ $t("Continue") }}
          <span class="material-icons ms-2"> arrow_forward </span>
        </button>
      </div>
    </div> -->
    <!--End Action Area-->

    <!--Shipping mot available Product modal-->
    <CModal :visible="notAvailableProductsModal" size="lg" @close.prevent="
      () => {
        notAvailableProductsModal = false;
      }
    ">
      <CModalHeader>
        <CModalTitle>{{ $t("Shipping not available products") }}</CModalTitle>
        <button class="btn-circle bg-black size-35" @click.prevent="
          () => {
            notAvailableProductsModal = false;
          }
        ">
          <base-icon-svg name="close" :width="10" :height="10" />
        </button>
      </CModalHeader>
      <CModalBody>
        <div class="row mb-20">
          <div class="col-12">
            <table class="border-bottom-0 cart-table table-responsive w-100">
              <tbody>
                <tr class="font-weight-bold">
                  <td>{{ $t("Product") }}</td>
                  <td>{{ $t("Quantity") }}</td>
                  <td class="text-right">{{ $t("Total") }}</td>
                </tr>
                <tr class="products" v-for="tdata in shippingNotAvailableProducts" :key="tdata.id">
                  <td>
                    <div class="d-flex align-items-center">
                      <router-link to="#">
                        <img :src="tdata.image" :alt="tdata.name" class="cart-image mr-10 rounded-circle" />
                      </router-link>
                      <span>
                        <router-link to="#" class="product-name">{{
                          tdata.name
                        }}</router-link>
                        <div class="extra-addons-wrap d-flex flex-wrap">
                          <span class="product-variant" v-if="tdata.variant">
                            <span class="font-weight-medium">{{
                              tdata.variant
                            }}</span>
                          </span>
                        </div>
                      </span>
                    </div>
                  </td>
                  <td>
                    {{ tdata.quantity }}
                  </td>
                  <td>
                    <the-currency :amount="tdata.unitPrice * tdata.quantity"></the-currency>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </CModalBody>
      <CModalFooter>
        <button type="button" class="btn btn_fill" @click.prevent="
          () => {
            notAvailableProductsModal = false;
            $emit('previous-step');
          }
        ">
          {{ $t("Update cart") }}
        </button>
      </CModalFooter>
    </CModal>
    <!--End Shipping mot available Product modal-->
  </div>
</template>
<script>
import axios from "axios";
import {
  prepareCheckoutShipping,
  applyShippingCostAndTax,
  buildProductPackages,
} from "@/utils/checkoutShippingPrep";
// import { mapState } from "vuex";
import { mapGetters } from "vuex";
import {
  CModal,
  CModalHeader,
  CModalTitle,
  CModalBody,
  CModalFooter,
} from "@coreui/vue";
import DeliveryTimeSelector from "./DeliveryTimeSelector.vue";
import BookingTimeSelector from "./BookingTimeSelector.vue";
import BookingTimeCheckout from "./BookingTimeCheckout.vue";
import {
  isValidKuwaitMobile,
  KUWAIT_MOBILE_ERROR,
  KUWAIT_MOBILE_HINT,
} from "@/utils/kuwaitPhone";
export default {
  name: "deliveryShipping",
  emits: ["next-step", "previous-step", "shipping-prep-updated", "booking-hours-selected"],
  components: {
    CModal,
    CModalHeader,
    CModalTitle,
    CModalBody,
    CModalFooter,
    DeliveryTimeSelector,
    BookingTimeSelector,
    BookingTimeCheckout
  },
  props: {
    config: {
      type: Object,
      required: true,
    },
    enums: {
      type: Object,
      required: true,
    },
    customerAddress: {
      type: Array,
      required: false,
    },
    pickupPoints: {
      type: Array,
      required: false,
    },
    isCustomerLogin: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      loading: true,
      isActiveHomeDelivery: true,
      isActivePickupPoint: false,
      // isActiveHomeDelivery: true,
      // isActivePickupPoint: false,
      isActiveCreateNewAccount: false,
      isActiveBillToDifferentAddress: false,
      deliveryNotAvailable: false,
      selectedShippingOption: {},
      selectedPickupPoints: "",
      countries: [],
      guestCustomerInfo: {},
      guestBillingInfo: {},
      guestShippingInfo: {},
      shippingNotAvailableProducts: [],
      notAvailableProductsModal: false,
      customerShippingInfo:
        this.customerAddress.find(
          (address) => address.default_shipping === this.enums.status.ACTIVE
        ) || null,
      customerBillingInfo:
        this.customerAddress.find(
          (address) => address.default_billing === this.enums.status.ACTIVE
        ) || null,
      errors: [],
      isRestoringShipping: false,
      bookingNowEnabled: false,
      bookingSchedule: null,
      deliverNowEnabled: false,
      deliverySchedulingEnabled: false,
      displayConfigLoaded: false,
    };
  },
  computed: {
    ...mapGetters('layout', ['isRtl']),
    isSchedulingEnabled() {
      return (
        this.displayConfigLoaded &&
        this.isActiveHomeDelivery &&
        !this.isActivePickupPoint &&
        this.deliverNowEnabled &&
        this.deliverySchedulingEnabled
      );
    },
    bookingTimeValue() {
      const schedule = this.bookingSchedule;

      if (!schedule?.schedule_date || !schedule?.start_time || !schedule?.end_time) {
        return null;
      }

      const date = new Date(`${schedule.schedule_date}T00:00:00`);
      const dateLabel = date.toLocaleDateString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
      });

      return `${dateLabel} · ${this.formatBookingTime(schedule.start_time)} - ${this.formatBookingTime(schedule.end_time)}`;
    },
    kuwaitMobileHint() {
      return this.$t(KUWAIT_MOBILE_HINT);
    },
    kuwaitMobileError() {
      return this.$t(KUWAIT_MOBILE_ERROR);
    },
  },
  mounted() {

    this.restoreBookingSchedule();
        this._bookingExpiryTimer = setInterval(() => this.restoreBookingSchedule(), 30000);
    this.fetchDisplayConfig();
    this._displayConfigVisibilityHandler = () => {
      if (document.visibilityState === "visible") this.fetchDisplayConfig();
    };
    document.addEventListener("visibilitychange", this._displayConfigVisibilityHandler);

    // console.log("shipping details test: ", this.$store.state.shippingDetails);

    if (this.$store.state.shippingDetails != null && !this.isCustomerLogin) {

      this.isRestoringShipping = true;

      this.guestShippingInfo = JSON.parse(
        JSON.stringify(this.$store.state.shippingDetails)
      );

      // console.log("guestShippingInfo 0: ", this.guestShippingInfo);


      this.getStates("shipping_info");

      // this.isRestoringShipping = false;


    }

    if (!this.isCustomerLogin) {
      this.restoreGuestContactFromStore();
    }

    // this.getPreviousData();
    this.getCounties();

    const persistedCityId = this.$store.state.shippingDetails?.city?.id;
    const hasCheckoutItems = (this.$store.state.checkoutItems || []).length > 0;
    const shouldKeepShippingCost =
      this.isActiveHomeDelivery &&
      !this.isActivePickupPoint &&
      persistedCityId &&
      hasCheckoutItems;

    if (!shouldKeepShippingCost) {
      this.$store.dispatch("setFinalShippingCost", 0);
    }

    if (
      this.isCustomerLogin &&
      this.customerShippingInfo != null &&
      this.isActiveHomeDelivery
    ) {
      this.getShippingOptions();
      if (this.customerShippingInfo?.city?.id) {
        this.refreshShippingRatesFromStore();
      }
    }

  },
  beforeUnmount() {
        if (this._bookingExpiryTimer) clearInterval(this._bookingExpiryTimer);
    if (this._displayConfigVisibilityHandler) {
      document.removeEventListener("visibilitychange", this._displayConfigVisibilityHandler);
    }
  },
  watch: {
    "customerShippingInfo.id"() {
      this.getShippingOptions();
    },
    "guestBillingInfo.country"() {
      this.guestBillingInfo.state = this.$t("Select State");
      this.guestBillingInfo.city = this.$t("Select City");
      this.guestBillingInfo.cities_options = [];
      this.getStates("billing_info");
    },
    "guestBillingInfo.state"() {
      this.guestBillingInfo.city = this.$t("Select City");
      this.getCities("billing_info");
    },


    // "guestShippingInfo.country"() {
    //   this.guestShippingInfo.state = this.$t("Select State");
    //   this.guestShippingInfo.city = this.$t("Select City");
    //   this.guestShippingInfo.cities_options = [];
    //   this.getStates("shipping_info");
    // },
    // "guestShippingInfo.state"() {
    //   this.guestShippingInfo.city = this.$t("Select City");
    //   this.getCities("shipping_info");
    // },


    "guestShippingInfo.country"() {

      if (this.isRestoringShipping) return;

      this.guestShippingInfo.state = this.$t("Select State");
      this.guestShippingInfo.city = this.$t("Select City");
      this.guestShippingInfo.cities_options = [];

      this.getStates("shipping_info");
    },

    "guestShippingInfo.state"() {

      if (this.isRestoringShipping) return;

      this.guestShippingInfo.city = this.$t("Select City");

      this.getCities("shipping_info");
    },


    "guestShippingInfo.city"() {
      if (this.isRestoringShipping) {
        return;
      }
      this.getShippingOptions();
    },
    isActivePickupPoint() {
      this.getShippingOptions();
    },
    "guestCustomerInfo.name"() {
      this.persistGuestCustomerInfoToStore();
    },
    "guestCustomerInfo.email"() {
      this.persistGuestCustomerInfoToStore();
    },
    "guestShippingInfo.name"() {
      this.persistGuestShippingDetailsToStore();
    },
    "guestShippingInfo.email"() {
      this.persistGuestShippingDetailsToStore();
    },
    "guestShippingInfo.phone"() {
      this.persistGuestShippingDetailsToStore();
    },
    "guestShippingInfo.address"() {
      this.persistGuestShippingDetailsToStore();
    },
  },
  methods: {
    clearStudioBooking() {
      try { localStorage.removeItem("tlcommerce_studio_booking_hold"); } catch (e) { /* ignore */ }
      try { document.cookie = "studio_booking_token=; path=/; max-age=0; SameSite=Lax"; } catch (e) { /* ignore */ }
    },
    restoreBookingSchedule() {
      try {
        const stored = localStorage.getItem("tlcommerce_studio_booking_hold");
        const booking = stored ? JSON.parse(stored) : null;
        if (!booking?.booking_token) {
          this.bookingSchedule = null;
          return;
        }
        if (booking.expires_at) {
          const expiresAt = new Date(booking.expires_at).getTime();
          if (!Number.isNaN(expiresAt) && expiresAt <= Date.now()) {
            this.clearStudioBooking();
            this.bookingSchedule = null;
            return;
          }
        }
        this.bookingSchedule = booking;
      } catch (error) {
        this.bookingSchedule = null;
      }
    },
    handleBookingReleased() {
      this.bookingSchedule = null;
    },
    flagEnabled(value, fallback = false) {
      if (value === undefined || value === null) return fallback;
      return value === true || value === 1 || value === "1" || value === "true" || value === "enabled";
    },
        async fetchDisplayConfig() {
      let payload = {};
      try {
        const response = await axios.get("/api/v1/ecommerce-core/studio-booking/config", {
          headers: { Accept: "application/json" },
        });
        payload = response.data || {};
      } catch (error) {
        payload = {};
      }

      // Deliver Now flags come from the same endpoint the home page uses.
      let arrival = null;
      try {
        const cityId = this.$store.state.shippingDetails?.city?.id || null;
        const stateId = this.$store.state.shippingDetails?.state?.id || null;
        const res = await axios.post("/api/v1/ecommerce-core/earliest-arrival", {
          city_id: cityId,
          state_id: stateId,
        });
        arrival = res.data || null;
      } catch (error) {
        arrival = null;
      }

      const deliverRaw =
        arrival && arrival.deliver_now_enabled !== undefined
          ? arrival.deliver_now_enabled
          : payload.deliver_now_enabled;
      this.deliverNowEnabled = this.flagEnabled(deliverRaw, false);
      this.deliverySchedulingEnabled =
        payload.delivery_scheduling_enabled !== undefined
          ? this.flagEnabled(payload.delivery_scheduling_enabled, false)
          : this.deliverNowEnabled;
      const bookingRaw =
        arrival && arrival.booking_now_enabled !== undefined
          ? arrival.booking_now_enabled
          : (payload.booking_now_enabled ?? payload.enabled);
      this.bookingNowEnabled = this.flagEnabled(bookingRaw, false);
      this.displayConfigLoaded = true;

      // When Book Now is OFF, booking is completely optional/inactive.
      // Remove any old hold so it cannot create a hidden requirement later.
      if (!this.bookingNowEnabled) {
        this.bookingSchedule = null;
        this.clearStudioBooking();
      }

      if (!this.isSchedulingEnabled) {
        this.$store.dispatch("storeDeliverySchedule", null);
      }
    },
    // Backwards-compatible alias for older callers.
    fetchBookingNowState() {
      return this.fetchDisplayConfig();
    },
    openBookingTimeEdit() {
      this.$refs.bookingTimeSelectorCheckout?.openEditModal();
    },
    handleBookingConfirmed(booking) {
      this.bookingSchedule = booking || null;
    },
    formatBookingTime(value) {
      const match = String(value || "").match(/^(\d{1,2}):(\d{2})/);
      if (!match) return value || "";

      let hour = Number(match[1]);
      const minute = match[2];
      const suffix = hour >= 12 ? "pm" : "am";
      hour = hour % 12 || 12;

      return `${hour}:${minute} ${suffix}`;
    },
    getActiveDeliveryTimeSelector() {
      if (!this.isSchedulingEnabled) {
        return null;
      }
      return this.isCustomerLogin
        ? this.$refs.deliveryTimeSelectorLoggedIn
        : this.$refs.deliveryTimeSelectorGuest;
    },
    validateDeliverySchedule() {
      if (!this.isSchedulingEnabled) {
        return true;
      }
      const selector = this.getActiveDeliveryTimeSelector();
      if (!selector) {
        return true;
      }
      const valid = selector.validateSchedule();
      if (!valid) {
        const message =
          selector.scheduleError || this.$t("Please select a delivery time option.");
        this.errors.push({ delivery_schedule: message });
        this.$toast.error(message);
      }
      return valid;
    },
        validateBookingTime() {
      // Book Now is required ONLY when the backend toggle is ON.
      if (!this.displayConfigLoaded || !this.bookingNowEnabled || !this.isActiveHomeDelivery) {
        return true;
      }

      const bookingCheckout = this.$refs.bookingTimeCheckout;

      // 1) hours must be selected
      const hasBooking = Boolean(
        bookingCheckout &&
        typeof bookingCheckout.hasBookingSelection === "function" &&
        bookingCheckout.hasBookingSelection()
      );

      if (!hasBooking) {
        const message = this.$t("Please select your booking hours before checkout.");
        this.errors.push({ booking_time: message });
        this.$toast.error(message);
        bookingCheckout?.openEdit?.();
        return false;
      }

      // 2) every item being checked out must have quantity == booked hours
      const hours = bookingCheckout.getBookingHours ? bookingCheckout.getBookingHours() : 0;
      const items = this.$store.state.checkoutItems || [];
      const mismatch = hours > 0 && items.some((item) => (parseInt(item.quantity) || 0) !== hours);

      if (mismatch) {
        const message =
          this.$t("Quantity and booking hours must match. Please review your booking time.") +
          " (" + hours + ")";
        this.errors.push({ booking_time: message });
        this.$toast.error(message);
        bookingCheckout?.openEdit?.();   // opens the calendar on the customer's chosen hours
        return false;
      }

      return true;
    },
    restoreGuestContactFromStore() {
      const stored = this.$store.state.guestCustomerInfo;
      if (stored) {
        this.guestCustomerInfo = {
          name: stored.name || "",
          email: stored.email || "",
          password: "",
          confirm_password: "",
        };
      }

      if (this.guestCustomerInfo.name && !this.guestShippingInfo.name) {
        this.guestShippingInfo.name = this.guestCustomerInfo.name;
      }
      if (this.guestCustomerInfo.email && !this.guestShippingInfo.email) {
        this.guestShippingInfo.email = this.guestCustomerInfo.email;
      }

      if (this.guestShippingInfo.name && !this.guestCustomerInfo.name) {
        this.guestCustomerInfo.name = this.guestShippingInfo.name;
      }
      if (this.guestShippingInfo.email && !this.guestCustomerInfo.email) {
        this.guestCustomerInfo.email = this.guestShippingInfo.email;
      }
    },
    persistGuestCustomerInfoToStore() {
      if (this.isCustomerLogin || this.isRestoringShipping) {
        return;
      }
      this.$store.dispatch("storeGuestCustomerDetails", {
        name: this.guestCustomerInfo.name || "",
        email: this.guestCustomerInfo.email || "",
        password: "",
        confirm_password: "",
      });
    },
    persistGuestShippingDetailsToStore() {
      if (this.isCustomerLogin || this.isRestoringShipping) {
        return;
      }
      if (!this.isActiveHomeDelivery) {
        return;
      }
      this.$store.dispatch("storeShippingDetails", {
        ...this.guestShippingInfo,
      });
    },
    /**
     * Will get previously save data
     */
    getPreviousData() {
      let emptyShippingData = {
        id: "",
        name: "",
        email: "",
        phone_code:
          localStorage.getItem("country") != null
            ? JSON.parse(localStorage.getItem("country")).phone_code
            : "",
        phone: "",
        postal_code: "",
        address: "",
        country: this.$t("Select Country"),
      };
      let emptyBillingData = {
        id: "",
        name: "",
        email: "",
        phone_code:
          localStorage.getItem("country") != null
            ? JSON.parse(localStorage.getItem("country")).phone_code
            : "",
        phone: "",
        postal_code: "",
        address: "",
        country: this.$t("Select Country"),
      };

      // console.log("store: ", this.$store);

      this.isActivePickupPoint = this.$store.state.isActivePickupPoint;
      this.isActiveHomeDelivery = this.$store.state.isActiveHomeDelivery;

      if (this.isCustomerLogin) {
        //Shipping address
        if (this.$store.state.shippingDetails != null) {
          this.customerShippingInfo = this.$store.state.shippingDetails.name
            ? this.$store.state.shippingDetails
            : emptyShippingData;
        }

        //Billing address
        if (this.$store.state.billingDetails != null) {
          this.customerBillingInfo = this.$store.state.billingDetails
            ? this.$store.state.billingDetails
            : emptyShippingData;
        }
      } else {
        this.isActiveBillToDifferentAddress =
          this.$store.state.isActiveBillToDifferentAddress;

        this.isActiveCreateNewAccount =
          this.$store.state.isActiveCreateNewAccount;

        this.guestBillingInfo =
          this.$store.state.billingDetails != null
            ? this.$store.state.billingDetails
            : emptyBillingData;


        this.guestShippingInfo =
          this.$store.state.shippingDetails != null
            ? this.$store.state.shippingDetails
            : emptyShippingData;

        // console.log("guestShippingInfo: ", this.guestShippingInfo);

        this.guestCustomerInfo =
          this.$store.state.guestCustomerInfo != null
            ? this.$store.state.guestCustomerInfo
            : {
              name: "",
              email: "",
              password: "",
              confirm_password: "",
            };
      }
      this.selectedPickupPoints =
        this.$store.state.pickupPoint != null
          ? this.$store.state.pickupPoint
          : {
            id: "",
            name: this.$t("Select pickup point"),
            location: "",
            phone: "",
            zone_id: "",
            zone_name: "",
          };
      this.loading = false;
    },
    /**
     * Re-fetch shipping packages and totals when city is restored from Vuex.
     */
    async refreshShippingRatesFromStore() {
      if (!this.isActiveHomeDelivery || this.isActivePickupPoint) {
        return;
      }

      const checkoutItems = this.$store.state.checkoutItems || [];
      if (!checkoutItems.length) {
        return;
      }

      let city_id = null;
      if (this.isCustomerLogin && this.customerShippingInfo?.city?.id) {
        city_id = this.customerShippingInfo.city.id;
      } else if (!this.isCustomerLogin && this.guestShippingInfo?.city?.id) {
        city_id = this.guestShippingInfo.city.id;
      } else if (this.$store.state.shippingDetails?.city?.id) {
        city_id = this.$store.state.shippingDetails.city.id;
      }

      if (!city_id) {
        return;
      }

      if (!this.isCustomerLogin && this.guestShippingInfo?.city?.id) {
        this.$store.dispatch("storeShippingDetails", this.guestShippingInfo);
      }

      const result = await prepareCheckoutShipping({
        store: this.$store,
        config: this.config,
        enums: this.enums,
      });

      if (result.success) {
        this.$emit("shipping-prep-updated", {
          shippingPackages: result.shippingPackages,
          productPackages: result.productPackages,
          isActiveHomeDelivery: result.isActiveHomeDelivery,
        });
      }
    },
    /**
     * Calculate shipping cost
     */
    getShippingOptions() {
      this.notAvailableProductsModal = false;
      this.shippingNotAvailableProducts = [];
      //Pickup point delivery
      if (this.isActivePickupPoint && !this.isActiveHomeDelivery) {
        this.$store.dispatch("setFinalShippingCost", 0);
        this.deliveryNotAvailable = false;
        return;
      }
      //Home Delivery
      if (!this.isActivePickupPoint && this.isActiveHomeDelivery) {
        if (this.isRestoringShipping) {
          return;
        }

        const checkoutItems = this.$store.state.checkoutItems || [];
        if (!checkoutItems.length) {
          return;
        }

        let city_id = null;
        let post_code = null;
        //Logged customer city id & post code
        if (this.isCustomerLogin && this.customerShippingInfo != null) {
          post_code = this.customerShippingInfo.postal_code;
          city_id =
            this.customerShippingInfo.city != null
              ? this.customerShippingInfo.city.id
              : null;
        }
        //Guest Customer city id & post code
        if (!this.isCustomerLogin && this.guestShippingInfo != null) {
          post_code = this.guestShippingInfo.postal_code;
          city_id =
            this.guestShippingInfo.city != null
              ? this.guestShippingInfo.city.id
              : null;
        }

        if (!city_id) {
          return;
        }

        if (!this.isCustomerLogin) {
          this.persistGuestShippingDetailsToStore();
        }

        axios
          .post("/api/v1/ecommerce-core/get-shipping-options", {
            location: city_id,
            post_code: post_code,
            products: JSON.stringify(checkoutItems),
            coupons: JSON.stringify(this.$store.state.couponDiscount || []),
            shipping_type: "home_delivery",
          })
          .then((response) => {
            if (response.data.success) {
              if (response.data.shipping_available) {
                this.deliveryNotAvailable = false;
                const shippingPackages = response.data.options || [];
                applyShippingCostAndTax({
                  store: this.$store,
                  config: this.config,
                  enums: this.enums,
                  shippingPackages,
                  isActiveHomeDelivery: this.isActiveHomeDelivery,
                });
                const productPackages = buildProductPackages({
                  shippingPackages,
                  config: this.config,
                  enums: this.enums,
                  isActiveHomeDelivery: this.isActiveHomeDelivery,
                });
                this.$emit("shipping-prep-updated", {
                  shippingPackages,
                  productPackages,
                  isActiveHomeDelivery: this.isActiveHomeDelivery,
                });
              } else {
                if (response.data.products) {
                  this.shippingNotAvailableProducts = response.data.products;
                  this.notAvailableProductsModal = true;
                }
                this.deliveryNotAvailable = true;
                this.$store.dispatch("setFinalShippingCost", 0);
              }
            } else {
              this.$toast.error(response.data.message);
            }
          })
          .catch(() => {
            this.$store.dispatch("setFinalShippingCost", 0);
            this.deliveryNotAvailable = true;
          });
      }
    },
    /**
     * Go to previous step (cart)
     */
    goPreviousStep() {
      this.$emit("previous-step");
    },
    /**
     * Validate delivery form and persist shipping/billing state to Vuex.
     * Used by Place Order pipeline (no step emit).
     * @returns {boolean}
     */
    validateAndPersist() {
      if (!this.validateData()) {
        return false;
      }
      if (this.deliveryNotAvailable) {
        this.$toast.error(this.$t("Delivery not available in your location"));
        return false;
      }
      //Home delivery checkout
      if (this.isActiveHomeDelivery && !this.isActivePickupPoint) {
        let shippingAddress = {};
        let billingAddress = {};
        if (this.isCustomerLogin) {
          shippingAddress = this.customerShippingInfo;
          billingAddress =
            this.config?.use_shipping_address_as_billing_address ==
              this.enums.status.ACTIVE
              ? this.customerShippingInfo
              : this.customerBillingInfo;
        } else {
          shippingAddress = this.guestShippingInfo;
          billingAddress =
            this.config?.use_shipping_address_as_billing_address ==
              this.enums.status.ACTIVE ||
              !this.isActiveBillToDifferentAddress
              ? this.guestShippingInfo
              : this.guestBillingInfo;
          this.$store.dispatch(
            "storeGuestCustomerDetails",
            this.guestCustomerInfo
          );
          this.$store.dispatch(
            "storeIsActiveBillToDifferentAddress",
            this.isActiveBillToDifferentAddress
          );
          this.$store.dispatch(
            "storeIsActiveCreateNewAccount",
            this.isActiveCreateNewAccount
          );
        }
        this.$store.dispatch(
          "storeHomeDeliveryCheckout",
          this.isActiveHomeDelivery
        );
        this.$store.dispatch("storeShippingDetails", shippingAddress);
        this.$store.dispatch("storeBillingDetails", billingAddress);
        return true;
      }
      //Pickup point checkout
      if (this.isActivePickupPoint && !this.isActiveHomeDelivery) {
        if (!this.isCustomerLogin) {
          this.$store.dispatch(
            "storeGuestCustomerDetails",
            this.guestCustomerInfo
          );
          this.$store.dispatch(
            "storeIsActiveBillToDifferentAddress",
            this.isActiveBillToDifferentAddress
          );
          this.$store.dispatch(
            "storeIsActiveCreateNewAccount",
            this.isActiveCreateNewAccount
          );
        }
        this.$store.dispatch(
          "storePickoupPoint",
          this.selectedPickupPoints
        );
        this.$store.dispatch(
          "storePickoupPointCheckout",
          this.isActivePickupPoint
        );
        return true;
      }
      return false;
    },
    /**
     * Will submit shipping info (legacy step advance; Continue CTA commented out)
     */
    goNextStep() {
      if (this.validateAndPersist()) {
        this.$emit("next-step");
      }
    },

    /**
     * Change shipping address
     * @param {*} e
     */
    checkedAddress(e) {
      this.$refs.addressRadio.forEach((element) => {
        element.classList.remove("active");
      });

      e.target.parentElement.parentElement.classList.add("active");
    },
    /**
     * Change  billing address
     * @param {*} e
     */
    checkedBillingAddress(e) {
      this.$refs.billingAddressRadio.forEach((element) => {
        element.classList.remove("active");
      });

      e.target.parentElement.parentElement.classList.add("active");
    },
    /**
     * Validate checkout data
     */
    validateData() {
      this.errors = [];
      //validate data from pickup point
      if (this.isActivePickupPoint) {
        if (!this.selectedPickupPoints.zone_id) {
          this.errors.push({
            pickup_point: this.$t("Please choose a pickup point"),
          });
        }
        if (!this.isCustomerLogin) {
          //Validate Personal Information
          if (!this.guestCustomerInfo.name) {
            this.errors.push({ customer_name: this.$t("Name is required") });
          }
          // if (!this.guestCustomerInfo.email) {
          //   this.errors.push({ customer_email: this.$t("Email is required") });
          // }
          //Password validation
          // if (this.isActiveCreateNewAccount) {
          //   if (!this.guestCustomerInfo.password) {
          //     this.errors.push({
          //       customer_password: this.$t("Password is required"),
          //     });
          //   }
          //   if (!this.guestCustomerInfo.confirm_password) {
          //     this.errors.push({
          //       customer_confirm_password: this.$t("Please confirm password"),
          //     });
          //   }
          //   if (
          //     this.guestCustomerInfo.password !=
          //     this.guestCustomerInfo.confirm_password
          //   ) {
          //     this.errors.push({
          //       customer_password: this.$t("Password does not match"),
          //     });
          //   }
          // }
        }
      }

      //validate data for home delivery
      if (this.isActiveHomeDelivery) {
        //Checkout
        if (this.isCustomerLogin) {
          //Validate customer shipping address
          if (this.customerShippingInfo == null) {
            this.errors.push({
              customer_shipping_address: this.$t(
                "Please select shipping address"
              ),
            });
            this.$toast.error(this.$t("Please select shipping address"));
          } else if (!isValidKuwaitMobile(this.customerShippingInfo.phone)) {
            this.errors.push({
              customer_shipping_address: this.kuwaitMobileError,
            });
            this.$toast.error(this.kuwaitMobileError);
          }
          //validate customer billing address
          if (
            this.config?.enable_billing_address == this.enums.status.ACTIVE &&
            this.config?.use_shipping_address_as_billing_address !=
            this.enums.status.ACTIVE
          ) {
            if (this.customerBillingInfo == null) {
              this.errors.push({
                customer_billing_address: this.$t(
                  "Please select billing address"
                ),
              });
              this.$toast.error(this.$t("Please select billing address"));
            } else if (!isValidKuwaitMobile(this.customerBillingInfo.phone)) {
              this.errors.push({
                customer_billing_address: this.kuwaitMobileError,
              });
              this.$toast.error(this.kuwaitMobileError);
            }
          }
        }

        //Guest Checkout
        if (!this.isCustomerLogin) {
          //Validate Personal Information
          if (
            !this.guestCustomerInfo.name &&
            this.config?.enable_personal_info_guest_checkout ==
            this.enums.status.ACTIVE
          ) {
            this.errors.push({ customer_name: this.$t("Name is required") });
          }
          // if (
          //   !this.guestCustomerInfo.email &&
          //   this.config?.enable_personal_info_guest_checkout ==
          //   this.enums.status.ACTIVE
          // ) {
          //   this.errors.push({ customer_email: this.$t("Email is required") });
          // }
          //Password validation
          // if (this.isActiveCreateNewAccount) {
          //   if (!this.guestCustomerInfo.password) {
          //     this.errors.push({
          //       customer_password: this.$t("Password is required"),
          //     });
          //   }
          //   if (!this.guestCustomerInfo.confirm_password) {
          //     this.errors.push({
          //       customer_confirm_password: this.$t("Please conform password"),
          //     });
          //   }
          //   if (
          //     this.guestCustomerInfo.password !=
          //     this.guestCustomerInfo.confirm_password
          //   ) {
          //     this.errors.push({
          //       customer_password: this.$t("Password does not match"),
          //     });
          //   }
          // }
          //Validate Billing Address
          if (
            this.config?.enable_billing_address == this.enums.status.ACTIVE &&
            this.config?.use_shipping_address_as_billing_address !=
            this.enums.status.ACTIVE &&
            this.isActiveBillToDifferentAddress
          ) {
            //name validation
            if (!this.guestBillingInfo.name) {
              this.errors.push({ billing_name: this.$t("Name is required") });
            }
            //email validation
            if (!this.guestBillingInfo.email) {
              this.errors.push({ billing_email: this.$t("Email is required") });
            }

            if (!this.guestBillingInfo.phone) {
              this.errors.push({ billing_phone: this.$t("Phone is required") });
            } else if (!isValidKuwaitMobile(this.guestBillingInfo.phone)) {
              this.errors.push({ billing_phone: this.kuwaitMobileError });
            }

            if (!this.guestBillingInfo.address) {
              this.errors.push({
                billing_address: this.$t("Address is required"),
              });
            }

            if (this.config.post_code_required_in_checkout != 2) {
              if (!this.guestBillingInfo.postal_code) {
                this.errors.push({
                  billing_postal_code: this.$t("Postal code is required"),
                });
              }
            }

            //If country , state and city option not hide
            if (this.config.hide_country_state_city_in_checkout != 1) {
              if (!this.guestBillingInfo.country.id) {
                this.errors.push({
                  billing_country: this.$t("Please select a country"),
                });
              }
              if (!this.guestBillingInfo.state.id) {
                this.errors.push({
                  billing_state: this.$t("Please select a state"),
                });
              }
              if (!this.guestBillingInfo.city.id) {
                this.errors.push({
                  billing_city: this.$t("Please select a city"),
                });
              }
            }
          }

          //Validate Shipping Address

          //name validation
          if (
            !this.guestShippingInfo.name &&
            this.config?.enable_name_in_checkout == this.enums.status.ACTIVE &&
            this.config?.name_required_in_checkout == this.enums.status.ACTIVE
          ) {
            this.errors.push({ shipping_name: this.$t("Name is required") });
          }

          //email validation
          if (
            !this.guestShippingInfo.email &&
            this.config?.enable_email_in_checkout == this.enums.status.ACTIVE &&
            this.config?.email_required_in_checkout == this.enums.status.ACTIVE
          ) {
            this.errors.push({ shipping_email: this.$t("Email is required") });
          }

          //Phone validation
          if (!this.guestShippingInfo.phone) {
            this.errors.push({ shipping_phone: this.$t("Phone is required") });
          } else if (!isValidKuwaitMobile(this.guestShippingInfo.phone)) {
            this.errors.push({ shipping_phone: this.kuwaitMobileError });
          }

          //address validation
          if (!this.guestShippingInfo.address) {
            this.errors.push({
              shipping_address: this.$t("Address is required"),
            });
          }

          //postal code validation
          if (
            !this.guestShippingInfo.postal_code &&
            this.config?.post_code_required_in_checkout ==
            this.enums.status.ACTIVE &&
            this.config?.post_code_required_in_checkout ==
            this.enums.status.ACTIVE
          ) {
            this.errors.push({
              shipping_postal_code: this.$t("Postal code is required"),
            });
          }

          //If country , state and city option not hide
          if (
            this.config?.hide_country_state_city_in_checkout ==
            this.enums.status.IN_ACTIVE
          ) {
            if (!this.guestShippingInfo.country.id) {
              this.errors.push({
                shipping_country: this.$t("Please select a country"),
              });
            }
            if (!this.guestShippingInfo.state.id) {
              this.errors.push({
                shipping_state: this.$t("Please select a state"),
              });
            }
            if (!this.guestShippingInfo.city.id) {
              this.errors.push({
                shipping_city: this.$t("Please select a city"),
              });
            }
          }
        }
      }

      if (this.isSchedulingEnabled && this.isActiveHomeDelivery) {
        if (!this.validateDeliverySchedule()) {
          return false;
        }
      }

      if (!this.validateBookingTime()) {
        return false;
      }

      //return result
      if (this.errors.length > 0) {
        return false;
      } else {
        return true;
      }
    },
    /**
     * Get counties list
     */
    getCounties() {
      axios
        //.get("/api/v1/ecommerce-core/get-countries")
        .post("/api/v1/ecommerce-core/get-countries", null)
        .then((response) => {
          if (response.data.success) {
            this.countries = response.data.data.countries;

            if (this.isRestoringShipping && this.guestShippingInfo.country?.id) {
              const match = this.countries.find(
                c => c.id === this.guestShippingInfo.country.id
              );

              if (match) {
                this.guestShippingInfo.country = match;
              }

              return; // prevent further modification
            }

            if (this.countries.length == 1) {
              this.guestShippingInfo.country = this.countries[0];
              this.getStates("shipping_info");
            }
          }
        })
        .catch((error) => {
          this.countries = [];
        });
    },
    /**
     * Will get state list of a country
     */
    getStates(origin) {
      let country_id = "";
      if (origin === "billing_info") {
        country_id = this.guestBillingInfo.country
          ? this.guestBillingInfo.country.id
          : "";
      } else if (origin === "shipping_info") {
        country_id = this.guestShippingInfo.country
          ? this.guestShippingInfo.country.id
          : "";
      }
      axios
        .post("/api/v1/ecommerce-core/get-states-of-countries", {
          country_id: country_id,
        })
        .then((response) => {
          if (response.data.success) {
            if (origin === "billing_info") {
              this.guestBillingInfo.states_options = response.data.data.states;
            }
            if (origin === "shipping_info") {
              // console.log("guestShippingInfo 1: ", this.guestShippingInfo);

              this.guestShippingInfo.states_options = response.data.data.states;

              // console.log("guestShippingInfo 2: ", this.guestShippingInfo);

              if (this.guestShippingInfo.state?.id) {
                const match =
                  this.guestShippingInfo.states_options.find(
                    s => s.id === this.guestShippingInfo.state.id
                  );

                if (match) {
                  this.guestShippingInfo.state = match;

                  // Now that state is rebound â†’ load cities
                  this.getCities("shipping_info");
                }
              }
            }
          }
        })
        .catch((error) => { });
    },
    /**
     * Will get cities list of a state
     */
    getCities(origin) {
      let state = "";
      if (origin === "billing_info") {
        state = this.guestBillingInfo.state
          ? this.guestBillingInfo.state.id
          : "";
      } else if (origin === "shipping_info") {
        state = this.guestShippingInfo.state
          ? this.guestShippingInfo.state.id
          : "";
      }
      axios
        .post("/api/v1/ecommerce-core/get-cities-of-state", {
          state_id: state,
        })
        .then((response) => {
          if (response.data.success) {
            if (origin === "billing_info") {
              this.guestBillingInfo.cities_options = response.data.data.cities;
            }

            if (origin === "shipping_info") {
              const wasRestoring = this.isRestoringShipping;

              this.guestShippingInfo.cities_options = response.data.data.cities;

              if (this.guestShippingInfo.city?.id) {
                const match =
                  this.guestShippingInfo.cities_options.find(
                    c => c.id === this.guestShippingInfo.city.id
                  );

                if (match) {
                  this.guestShippingInfo.city = match;
                }
              }

              this.isRestoringShipping = false;

              if (wasRestoring) {
                this.$nextTick(() => {
                  this.refreshShippingRatesFromStore();
                });
              }
            }
          }
        })
        .catch((error) => { });
    },
  },
};
</script>
<style lang="scss" scoped>
.product-name {
  display: block;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.checkout-cta-stack {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.checkout-cta-primary {
  min-height: 48px;
  width: 100% !important;
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: 600;
  border-radius: 8px;
}

.checkout-cta-secondary {
  min-height: 44px;
  width: 100% !important;
  border-radius: 8px;
}

.shadow-card {
  overflow: visible;
}

.checkout-state-city-half {
  overflow: visible;
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.delivery-shipping--rtl .delivery-shipping__header {
  flex-direction: row-reverse;
  direction: ltr;
}

.delivery-shipping--rtl .delivery-shipping__header .checkout-title {
  flex: 0 1 auto;
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .delivery-shipping__header .btn_underline {
  flex: 0 1 auto;
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .form-selector-list {
  justify-content: flex-end !important;
  direction: ltr;
  width: 100%;
}

.delivery-shipping--rtl .form-selector-list > .single-form-selector:has(.custom-radio-btn) {
  width: auto;
  max-width: 100%;
  flex: 0 1 auto;
  margin-right: 0 !important;
  margin-inline-start: auto;
}

.delivery-shipping--rtl .form-selector-list > .single-form-selector:not(:has(.custom-radio-btn)) {
  display: none;
  margin: 0 !important;
  width: 0 !important;
  flex: 0 0 0 !important;
}

.delivery-shipping--rtl .form-selector-list .custom-radio-btn {
  display: inline-block;
  width: auto;
  direction: ltr;
}

.delivery-shipping--rtl .form-selector-list .custom-radio-btn label {
  flex-direction: row;
  direction: ltr;
  justify-content: flex-start;
  width: auto;
}

.delivery-shipping--rtl .form-selector-list .icon-wrap {
  margin-right: 0;
  margin-inline-end: 10px;
}

.delivery-shipping--rtl .form-selector-list .label-title {
  flex: 0 1 auto;
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl h5 {
  width: 100%;
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .save-adderss,
.delivery-shipping--rtl .guest-shipping-address,
.delivery-shipping--rtl .row > .form-group {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .save-adderss .radio-label {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-start;
  width: 100%;
}

.delivery-shipping--rtl .save-adderss .radio-text {
  flex: 1 1 auto;
  min-width: 0;
  direction: rtl;
  text-align: right;
  margin-left: 0;
  margin-inline-start: 10px;
}

.delivery-shipping--rtl .form-group {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .form-group label {
  direction: rtl;
  text-align: right;
  display: block;
  width: 100%;
}

.delivery-shipping--rtl .form-group .radio-text {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .theme-input-style {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .radio-label.d-flex {
  flex-direction: row;
  direction: ltr;
  justify-content: flex-end;
  width: 100%;
}

.delivery-shipping--rtl .radio-label.d-flex .radio-text {
  flex: 0 1 auto;
}

.delivery-shipping--rtl :deep(.v-select) {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .alert {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .cart-table td .d-flex {
  flex-direction: row-reverse;
  direction: ltr;
}

.delivery-shipping--rtl .product-name {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .cart-table .text-right {
  direction: ltr;
  text-align: left;
}

.delivery-shipping--rtl .cart-image.mr-10 {
  margin-right: 0;
  margin-inline-end: 10px;
}

.booking-time-section {
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  padding-top: 20px;
}

.booking-time-summary {
  align-items: center;
  background: #f7f7f7;
  border-radius: 8px;
  display: flex;
  gap: 12px;
  justify-content: space-between;
  margin-top: 12px;
  padding: 12px 16px;
}

.booking-time-summary__content {
  align-items: center;
  display: flex;
  gap: 10px;
  min-width: 0;
}

.booking-time-summary__content > span:last-child {
  min-width: 0;
}

.booking-time-summary__icon {
  color: rgba(0, 0, 0, 0.6);
  flex-shrink: 0;
  font-size: 18px;
}

.booking-time-prompt {
  align-items: center;
  background: #eef4ff;
  border-radius: 8px;
  color: #3b6fd8;
  cursor: pointer;
  display: flex;
  gap: 10px;
  margin-top: 12px;
  padding: 12px 16px;
}

.booking-time-prompt:focus {
  outline: none;
}

.delivery-shipping--rtl .booking-time-summary {
  direction: ltr;
  flex-direction: row-reverse;
}

.delivery-shipping--rtl .booking-time-summary__content {
  direction: ltr;
  flex-direction: row-reverse;
}

.delivery-shipping--rtl .booking-time-summary__content > span:last-child {
  direction: rtl;
  text-align: right;
}

.delivery-shipping--rtl .booking-time-prompt {
  direction: ltr;
  flex-direction: row-reverse;
}

.delivery-shipping--rtl .booking-time-prompt > span:last-child {
  direction: rtl;
  text-align: right;
}
</style>
