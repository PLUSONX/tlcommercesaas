<template>
    <!-- <div class="shadow-card mb-30"> -->
    <div class="p-2">

        <template v-if="isActiveHomeDelivery">
            <!--Login user Checkout-->
            <div v-if="isCustomerLogin">
                <!--Shipping Address-->
                <div class="row" v-if="isActiveHomeDelivery">
                    <!-- <div class="col-12"> -->
                    <div>
                        <!-- <h5>{{ $t("Shipping Details") }}</h5> -->
                        <!-- <div class="save-adderss row" v-if="customerAddress.length > 0" > -->
                        <div v-if="customerAddress.length > 0">
                            <div class="col-lg-12 mb-4" v-for="address in customerAddress" :key="address.name">
                                <!-- <span class="custom-radio-btn" ref="addressRadio" :class="{
                                    active:
                                        customerShippingInfo != null &&
                                        address.id == customerShippingInfo.id,
                                }"> -->
                                <div class="address-card"
                                    :class="{ 'address-card--active': customerShippingInfo && address.id === customerShippingInfo.id }"
                                    @click="customerShippingInfo = address; checkedAddress()">
                                    <div class="selection-indicator"
                                        v-if="customerShippingInfo && address.id === customerShippingInfo.id">
                                        <i class="fas fa-check-circle"></i>
                                    </div>

                                    <div class="address-content">
                                        <h4 class="address-name">{{ address.name }}</h4>

                                        <p class="address-details">
                                            <span class="detail-item"><strong>{{ $t("Address:") }}</strong> {{
                                                address.address }}</span>
                                            <span class="detail-item"><strong>{{ $t("Phone:") }}</strong> {{
                                                address.phone }}</span>
                                            <span class="detail-item"><strong>{{ $t("Postal Code:") }}</strong> {{
                                                address.postal_code }}</span>
                                        </p>

                                        <div class="address-location">
                                            <span v-if="address.city">{{ address.city.name }}, </span>
                                            <span v-if="address.state">{{ address.state.name }}, </span>
                                            <span v-if="address.country">{{ address.country.name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- <label class="radio-label">
                                        <input name="customerShippingAddress" type="radio" :value="address"
                                            v-model="customerShippingInfo" @change.prevent="checkedAddress" :checked="customerShippingInfo != null &&
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
                                    </label> -->
                                <!-- </span> -->
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
                </div>
                <!--End Shipping Address-->

            </div>
            <!--End Login user Checkout-->

            <!--Guest Checkout -->
            <div v-if="!isCustomerLogin">


                <!--Guest Shipping Address-->
                <div class="row guest-shipping-address" v-if="isActiveHomeDelivery">
                    <div class="col-12">
                        <!-- <h5>{{ $t("Shipping Details") }}</h5> -->
                        <h5>{{ $t("Deliver To") }}</h5>
                    </div>
                    <!--
                    <div class="form-group mb-20 col-lg-6"
                        v-if="config?.enable_name_in_checkout == enums.status.ACTIVE">

                        <input type="text" v-bind:placeholder="$t('Your Name')" v-model="guestShippingInfo.name"
                            class="theme-input-style" />
                        <div v-for="error in errors" :key="error.shipping_name">
                            <p class="text-danger validation-error" v-if="error.shipping_name">
                                {{ error.shipping_name }}
                            </p>
                        </div>
                    </div> -->
                    <!-- <div class="form-group mb-20 col-lg-6"
                        v-if="config?.enable_email_in_checkout == enums.status.ACTIVE">

                        <input type="email" v-bind:placeholder="$t('Email Address')" v-model="guestShippingInfo.email"
                            class="theme-input-style" />
                        <div v-for="error in errors" :key="error.shipping_email">
                            <p class="text-danger validation-error" v-if="error.shipping_email">
                                {{ error.shipping_email }}
                            </p>
                        </div>
                    </div> -->
                    <!-- <div
            class="form-group mb-20 col-lg-6"
            v-if="config?.enable_phone_in_checkout == enums.status.ACTIVE"
          > -->
                    <!-- <div class="form-group mb-20 col-lg-6">
                        <input type="tel" v-bind:placeholder="$t('Phone Number')" v-model="guestShippingInfo.phone"
                            class="theme-input-style" />
                        <div v-for="error in errors" :key="error.shipping_phone">
                            <p class="text-danger validation-error" v-if="error.shipping_phone">
                                {{ error.shipping_phone }}
                            </p>
                        </div>
                    </div> -->
                    <!-- <div
            class="form-group mb-20 col-lg-6"
            v-if="config?.enable_address_in_checkout == enums.status.ACTIVE"
          > -->
                    <!-- <div class="form-group mb-20 col-lg-6">
                        <input type="text" class="theme-input-style" v-bind:placeholder="$t('Address')"
                            v-model="guestShippingInfo.address" />
                        <div v-for="error in errors" :key="error.shipping_address">
                            <p class="text-danger validation-error" v-if="error.shipping_address">
                                {{ error.shipping_address }}
                            </p>
                        </div>
                    </div> -->

                    <div v-if="config?.enable_post_code_in_checkout == enums.status.ACTIVE" :class="config?.hide_country_state_city_in_checkout == enums.status.ACTIVE
                        ? 'form-group mb-20 col-lg-12'
                        : 'form-group mb-20 col-lg-6'
                        ">
                        <input type="text" class="theme-input-style" v-bind:placeholder="$t('Postal Code')"
                            v-model="guestShippingInfo.postal_code" />
                        <div v-for="error in errors" :key="error.shipping_postal_code">
                            <p class="text-danger validation-error" v-if="error.shipping_postal_code">
                                {{ error.shipping_postal_code }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group mb-20 col-lg-6" v-if="
                        config?.hide_country_state_city_in_checkout !=
                        enums.status.ACTIVE && countries.length > 1
                    ">
                        <v-select :options="countries" v-model="guestShippingInfo.country" label="name"
                            :clearable="false"></v-select>
                        <div v-for="error in errors" :key="error.shipping_country">
                            <p class="text-danger validation-error" v-if="error.shipping_country">
                                {{ error.shipping_country }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group mb-20 col-lg-6" v-if="
                        config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
                    ">
                        <v-select :options="guestShippingInfo.states_options" v-model="guestShippingInfo.state"
                            label="name" :clearable="false"></v-select>
                        <div v-for="error in errors" :key="error.shipping_state">
                            <p class="text-danger validation-error" v-if="error.shipping_state">
                                {{ error.shipping_state }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group mb-20 col-lg-6"
                        v-if="config?.hide_country_state_city_in_checkout != enums.status.ACTIVE">
                        <v-select :options="guestShippingInfo.cities_options" v-model="guestShippingInfo.city"
                            label="name" :clearable="false" @option:selected="saveDeliverylocationInState"> </v-select>

                        <div v-for="error in errors" :key="error.shipping_city">
                            <p class="text-danger validation-error" v-if="error.shipping_city">
                                {{ error.shipping_city }}
                            </p>
                        </div>
                    </div>


                    <div class="mb-20 col-lg-12">
                        <div class="d-flex align-items-center justify-content-between p-10"
                            style="background: #f8f9fa; border-radius: 6px;">

                            <!-- Left: clock icon + earliest arrival -->
                            <div class="d-flex align-items-center">
                                <span class="material-icons mr-5"
                                    style="font-size: 18px; color: #6c757d;">schedule</span>
                                <span class="text-muted small">{{ $t('Earliest Arrival') }}</span>
                            </div>

                            <!-- Right: shown only after city is selected -->
                            <div v-if="guestShippingInfo.city != null && guestShippingInfo.city.id > 0"
                                style="margin-right: 5px;">
                                <strong>1 {{ $t('Hour') }}</strong>
                            </div>

                        </div>
                    </div>

                    <!-- <div class="form-group mb-20 col-lg-6" v-if="
                        config?.hide_country_state_city_in_checkout != enums.status.ACTIVE
                    ">
                        <v-select :options="guestShippingInfo.cities_options" v-model="guestShippingInfo.city"
                            label="name" :clearable="false"></v-select>
                        <div v-for="error in errors" :key="error.shipping_city">
                            <p class="text-danger validation-error" v-if="error.shipping_city">
                                {{ error.shipping_city }}
                            </p>
                        </div>
                    </div> -->
                </div>
                <!--End Guest Shipping Address-->

            </div>
            <!--End Guest Checkout -->
        </template>

    </div>
</template>

<script>

import axios from "axios";
import {
    CModal,
    CModalHeader,
    CModalTitle,
    CModalBody,
    CModalFooter,
} from "@coreui/vue";

export default {
    name: "homePageDeliveryShipping",
    components: {
        CModal,
        CModalHeader,
        CModalTitle,
        CModalBody,
        CModalFooter,
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
            isRestoringData: false,
        };
    },
    mounted() {

        // console.log("HomePageDeliveryShipping vue mounted!!!");
        // this.getPreviousData();
        this.getCounties();
        this.$store.dispatch("setFinalShippingCost", 0);
        if (
            this.isCustomerLogin &&
            this.customerShippingInfo != null &&
            this.isActiveHomeDelivery
        ) {
            this.getShippingOptions();
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
        "guestShippingInfo.country"() {
            this.guestShippingInfo.state = this.$t("Select State");
            this.guestShippingInfo.city = this.$t("Select City");
            // console.log("City reset takes place!!!")
            this.guestShippingInfo.cities_options = [];
            this.getStates("shipping_info");
        },
        "guestShippingInfo.state"() {
            this.guestShippingInfo.city = this.$t("Select City");
            this.getCities("shipping_info");
        },
        // "guestShippingInfo.country"() {
        //     if (this.isRestoringData) return; // 👈
        //     this.guestShippingInfo.state = this.$t("Select State");
        //     this.guestShippingInfo.city = this.$t("Select City");
        //     console.log("City reset takes place!!!")
        //     this.guestShippingInfo.cities_options = [];
        //     this.getStates("shipping_info");
        // },
        // "guestShippingInfo.state"() {
        //     if (this.isRestoringData) return; // 👈
        //     this.guestShippingInfo.city = this.$t("Select City");
        //     this.getCities("shipping_info");
        // },
        "guestShippingInfo.city"() {
            this.getShippingOptions();
        },
        isActivePickupPoint() {
            this.getShippingOptions();
        },
    },
    methods: {

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

            console.log("store: ", this.$store);

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

                this.isRestoringData = true;

                this.guestShippingInfo =
                    this.$store.state.shippingDetails != null
                        ? this.$store.state.shippingDetails
                        : emptyShippingData;

                this.guestCustomerInfo =
                    this.$store.state.guestCustomerInfo != null
                        ? this.$store.state.guestCustomerInfo
                        : {
                            name: "",
                            email: "",
                            password: "",
                            confirm_password: "",
                        };

                // Re-fetch states & cities if we have saved location data
                // if (this.guestShippingInfo.country?.id) {
                //     this.getStates("shipping_info").then(() => {
                //         if (this.guestShippingInfo.state?.id) {
                //             this.getCities("shipping_info");
                //         }
                //     });
                // }

                // this.$nextTick(() => {
                //     this.isRestoringData = false; // RESET FLAG
                // });
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
            }
            //Home Delivery
            if (!this.isActivePickupPoint && this.isActiveHomeDelivery) {
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

                axios
                    .post("/api/v1/ecommerce-core/get-shipping-options", {
                        location: city_id,
                        post_code: post_code,
                        products: JSON.stringify(this.$store.state.checkoutItems),
                        shipping_type: "home_delivery",
                    })
                    .then((response) => {
                        if (response.data.success) {
                            if (response.data.shipping_available) {
                                this.deliveryNotAvailable = false;
                            } else {
                                if (response.data.products) {
                                    this.shippingNotAvailableProducts = response.data.products;
                                    this.notAvailableProductsModal = true;
                                }
                                this.deliveryNotAvailable = true;
                            }
                        } else {
                            this.$toast.error(response.data.message);
                        }
                    })
                    .catch((error) => {
                        this.$store.dispatch("setFinalShippingCost", 0);
                        this.deliveryNotAvailable = true;
                    });
            }
        },

        saveDeliverylocationInState() {

            // console.log("saveDeliverylocationInState method called!!!")

            // console.log("shippingAddress: ", this.guestShippingInfo);

            this.$store.dispatch("storeShippingDetails", { ...this.guestShippingInfo });

            // this.$store.commit("homeStoreShippingDetails", { ...this.guestShippingInfo });


            // context.commit("homeStoreShippingDetails", this.guestShippingInfo);
        },

        goNextStep() {

            // console.log("----- goNextStep method called!!!------");
            if (!this.deliveryNotAvailable) {
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
                        //set guest customer details
                        this.$store.dispatch(
                            "storeGuestCustomerDetails",
                            this.guestCustomerInfo
                        );
                        //set billed to different address
                        this.$store.dispatch(
                            "storeIsActiveBillToDifferentAddress",
                            this.isActiveBillToDifferentAddress
                        );
                        //Set create new account in guest checkout
                        this.$store.dispatch(
                            "storeIsActiveCreateNewAccount",
                            this.isActiveCreateNewAccount
                        );
                    }
                    //set home delivery checkout
                    // this.$store.dispatch(
                    //     "storeHomeDeliveryCheckout",
                    //     this.isActiveHomeDelivery
                    // );
                    //set shipping address
                    this.$store.dispatch("storeShippingDetails", shippingAddress);
                    //set billing address
                    this.$store.dispatch("storeBillingDetails", billingAddress);
                    // this.$emit("next-step");
                }

            } else {
                this.$toast.error(this.$t("Delivery not available in your location"));
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
     * Get counties list
     */
        getCounties() {
            axios
                //.get("/api/v1/ecommerce-core/get-countries")
                .post("/api/v1/ecommerce-core/get-countries", null)
                .then((response) => {
                    if (response.data.success) {
                        this.countries = response.data.data.countries;
                        if (this.countries.length == 1) {
                            this.guestShippingInfo.country = this.countries[0];
                            getStates("shipping_info");
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
                            this.guestShippingInfo.states_options = response.data.data.states;
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
                            this.guestShippingInfo.cities_options = response.data.data.cities;
                        }
                    }
                })
                .catch((error) => { });
        },


    }
}

</script>

<style>
@media screen and (max-width: 768px) {

    /* 1. Force the font to 16px to satisfy the browser's zoom-check */
    :deep(.v-select .vs__search),
    :deep(.v-select .vs__selected),
    :deep(.v-select input) {
        font-size: 16px !important;
    }

    /* 2. If the text now looks too big, scale it back down visually */
    /* This keeps the "hit box" large but the text small */
    :deep(.v-select .vs__dropdown-toggle) {
        transform: scale(0.9);
        transform-origin: left center;
        width: 111%;
        /* Compensate for the scale-down to keep it full width */
    }
}

.address-card {
    position: relative;
    padding: 1.25rem;
    border: 2px solid #e2e8f0;
    /* Light gray border */
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #ffffff;
    margin-bottom: 1rem;
}

/* Hover Effect */
.address-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Selected/Active State */
.address-card--active {
    border-color: #4a90e2;
    /* Modern Blue */
    background-color: #f8fbff;
}

.address-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
}

.address-details {
    font-size: 0.9rem;
    line-height: 1.6;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.detail-item {
    display: block;
    /* Puts each detail on a new line */
}

.address-location {
    font-size: 0.85rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    font-weight: 500;
}

.selection-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: #4a90e2;
    font-size: 1.2rem;
}
</style>