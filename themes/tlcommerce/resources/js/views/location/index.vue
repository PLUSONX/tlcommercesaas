<template>

    <div :class="mtClass">

        <!-- Back to Homepage Button (fixed top-left) -->
        <!-- <router-link to="/" class="back-home-btn">
            <span class="material-icons">arrow_back</span>
            <span class="back-home-btn__label">{{ $t("Home") }}</span>
        </router-link> -->

        <template v-if="isActiveHomeDelivery">

            <!-- Logged-in user: show saved addresses -->
            <div v-if="isCustomerLogin">
                <div class="row" v-if="isActiveHomeDelivery">
                    <div>
                        <div v-if="customerAddress.length > 0">
                            <div class="col-lg-12 mb-4" v-for="address in customerAddress" :key="address.name">
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
                            </div>
                        </div>
                        <div class="save-adderss row" v-else>
                            <div class="col-lg-12 mb-4">
                                <p class="alert alert-danger">{{ $t("No address found") }}</p>
                                <router-link to="/dashboard/address" class="btn_underline">{{ $t("Add new address")
                                    }}</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guest Checkout -->
            <div v-if="!isCustomerLogin">



                <div class="guest-shipping-address p-1 mb-0" v-if="isActiveHomeDelivery">

                    <!-- <div class="flex items-center justify-between">

                        <div class="header-title px-4 mb-3">
                            <h3 class="product_header mb-0">{{ $t("Deliver To") }}</h3>
                        </div>

                        <div class="location-header-row">
                            <h3 class="product_header mb-0">{{ $t("Deliver To") }}</h3>
                            <router-link to="/" class="back-home-btn">
                                <span class="material-icons">arrow_back</span>
                                <span class="back-home-btn__label">{{ $t("Home") }}</span>
                            </router-link>
                        </div>

                    </div> -->

                    <div class="location-header-row">
                        <h3 class="product_header mb-0">{{ $t("Deliver To") }}</h3>
                        <router-link to="/" class="back-home-btn">
                            <span class="material-icons">arrow_back</span>
                            <span class="back-home-btn__label">{{ $t("Home") }}</span>
                        </router-link>
                    </div>


                    <!-- Selected location summary badge -->
                    <div v-if="guestShippingInfo.city && guestShippingInfo.city.id > 0"
                        class="selected-location-badge mb-3 mx-4">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            {{ guestShippingInfo.city.name }}
                            <span v-if="guestShippingInfo.state">, {{ guestShippingInfo.state.name }}</span>
                        </span>
                        <button class="change-location-btn" @click="clearCitySelection">
                            {{ $t("Change") }}
                        </button>
                    </div>

                    <!-- State accordion tiles -->
                    <div class="states-container px-2"
                        v-if="guestShippingInfo.states_options && guestShippingInfo.states_options.length > 0">
                        <div class="state-tile" v-for="state in guestShippingInfo.states_options" :key="state.id"
                            :class="{
                                'state-tile--open': expandedStateId === state.id,
                                'state-tile--selected': guestShippingInfo.state && guestShippingInfo.state.id === state.id && guestShippingInfo.city && guestShippingInfo.city.id > 0
                            }">
                            <!-- State header row -->

                            <div class="state-tile__header" @click="toggleState(state)">
                                <div class="state-tile__label">
                                    <span class="material-icons state-icon">place</span>
                                    <span>{{ state.name }}</span>

                                    <span
                                        v-if="guestShippingInfo.state && guestShippingInfo.state.id === state.id && guestShippingInfo.city && guestShippingInfo.city.id > 0"
                                        class="city-selected-chip">
                                        {{ guestShippingInfo.city.name }}
                                    </span>
                                </div>

                                <span class="material-icons state-tile__chevron">
                                    {{ expandedStateId === state.id ? 'expand_less' : 'expand_more' }}
                                </span>
                            </div>

                            <transition name="cities-expand">
                                <div class="cities-grid" v-if="expandedStateId === state.id">

                                    <div v-if="loadingCities" class="cities-loading">
                                        <span class="material-icons spin-animation">refresh</span>
                                        {{ $t("Loading cities...") }}
                                    </div>

                                    <div v-else class="city-tile" v-for="city in expandedStateCities" :key="city.id"
                                        :class="{ 'city-tile--selected': guestShippingInfo.city && guestShippingInfo.city.id === city.id }"
                                        @click="selectCity(state, city)">

                                        <span class="material-icons city-tile__icon">location_city</span>
                                        <span class="city-tile__name" :title="city.name">{{ city.name }}</span>

                                        <span v-if="guestShippingInfo.city && guestShippingInfo.city.id === city.id"
                                            class="material-icons city-tile__check">check</span>
                                    </div>
                                </div>
                            </transition>
                            <!-- 
                            <div class="state-tile__header" @click="toggleState(state)">
                                <div class="state-tile__label">
                                    <i class="fas fa-map-marker-alt state-icon"></i>
                                    <span>{{ state.name }}</span>
                                    <span
                                        v-if="guestShippingInfo.state && guestShippingInfo.state.id === state.id && guestShippingInfo.city && guestShippingInfo.city.id > 0"
                                        class="city-selected-chip">
                                        {{ guestShippingInfo.city.name }}
                                    </span>
                                </div>
                                <i class="fas state-tile__chevron"
                                    :class="expandedStateId === state.id ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        -->

                            <!-- City tiles (shown when state is expanded) -->
                            <!--
                            <transition name="cities-expand">
                                <div class="cities-grid" v-if="expandedStateId === state.id">
                                    <div v-if="loadingCities" class="cities-loading">
                                        <i class="fas fa-spinner fa-spin"></i> {{ $t("Loading cities...") }}
                                    </div>
                                    <div v-else class="city-tile" v-for="city in expandedStateCities" :key="city.id"
                                        :class="{ 'city-tile--selected': guestShippingInfo.city && guestShippingInfo.city.id === city.id }"
                                        @click="selectCity(state, city)">
                                        <i class="fas fa-city city-tile__icon"></i>
                                        <span>{{ city.name }}</span>
                                        <i v-if="guestShippingInfo.city && guestShippingInfo.city.id === city.id"
                                            class="fas fa-check city-tile__check"></i>
                                    </div>
                                </div>
                            </transition>
                        -->
                        </div>
                    </div>

                    <div v-else-if="!loadingStates" class="px-4">
                        <p class="text-muted small">{{ $t("No areas available.") }}</p>
                    </div>
                    <div v-if="loadingStates" class="px-4">
                        <p class="text-muted small"><i class="fas fa-spinner fa-spin"></i> {{ $t("Loading areas...") }}
                        </p>
                    </div>

                    <!-- Earliest Arrival row -->
                    <!-- <div class="mb-20 col-lg-12 mt-3">
                        <div class="d-flex align-items-center justify-content-between p-10"
                            style="background: #f8f9fa; border-radius: 6px;">
                            <div class="d-flex align-items-center">
                                <span class="material-icons mr-5"
                                    style="font-size: 18px; color: #6c757d;">schedule</span>
                                <span class="text-muted small">{{ $t('Earliest Arrival') }}</span>
                            </div>
                            <div v-if="guestShippingInfo.city && guestShippingInfo.city.id > 0"
                                style="margin-right: 5px;">
                                <strong>1 {{ $t('Hour') }}</strong>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
            <!-- End Guest Checkout -->

        </template>

    </div>
</template>

<script>
import axios from "axios";
import {
    CModal, CModalHeader, CModalTitle, CModalBody, CModalFooter,
} from "@coreui/vue";
import { mapState, mapGetters } from "vuex";

export default {
    name: "Location",

    components: {
        CModal, CModalHeader, CModalTitle, CModalBody, CModalFooter,
    },

    computed: {
        ...mapGetters('layout', ['isSplitScreen', 'isMobile']),

        forcedMobile() {
            if (this.isSplitScreen && !this.isMobile) return true;
            return this.isMobile;
        },

        mtClass() {
            return this.isSplitScreen && !this.isMobile ? 'mt-50' : 'mt-1';
        },

        config() {
            return this.$store.state.deliveryData.config;
        },

        enums() {
            return this.$store.state.deliveryData.enums;
        },

        customerAddress() {
            return this.$store.state.deliveryData.customerAddress || [];
        },

        pickupPoints() {
            return this.$store.state.deliveryData.pickupPoints || [];
        },

        isCustomerLogin() {
            return this.$store.state.deliveryData.isCustomerLogin || false;
        },

        customerShippingInfo() {
            return this.customerAddress.find(
                addr => addr.default_shipping === this.enums?.status?.ACTIVE
            ) || null;
        },

        customerBillingInfo() {
            return this.customerAddress.find(
                addr => addr.default_billing === this.enums?.status?.ACTIVE
            ) || null;
        },
    },

    data() {
        return {
            loading: true,
            loadingStates: false,
            loadingCities: false,
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
            customerShippingInfo: null,
            customerBillingInfo: null,
            errors: [],
            isRestoringData: false,

            // Accordion state
            expandedStateId: null,
            expandedStateCities: [],
        };
    },

    mounted() {
        // console.log("Full Store State:", this.$store.state);
        // console.log("Vuex state:", this.$store.state.deliveryData);
        // console.log("Customer Address:", this.customerAddress);
        // console.log("Enums:", this.enums);

        this.getCounties();
        this.$store.dispatch("setFinalShippingCost", 0);
    },

    watch: {
        customerAddress(newVal) {
            if (newVal.length) {
                this.$nextTick(() => {
                    this.getShippingOptions();
                });
            }
        },
    },

    methods: {

        /**
         * Toggle a state open/closed (accordion: only one open at a time)
         */
        toggleState(state) {
            if (this.expandedStateId === state.id) {
                // Close if already open
                this.expandedStateId = null;
                this.expandedStateCities = [];
            } else {
                // Open this state and load its cities
                this.expandedStateId = state.id;
                this.expandedStateCities = [];
                this.fetchCitiesForState(state);
            }
        },

        /**
         * Fetch cities for the expanded state tile
         */
        fetchCitiesForState(state) {
            this.loadingCities = true;
            axios
                .post("/api/v1/ecommerce-core/get-cities-of-state", {
                    state_id: state.id,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.expandedStateCities = response.data.data.cities;
                        // Also store on guestShippingInfo for compatibility with existing logic
                        this.guestShippingInfo.cities_options = response.data.data.cities;
                    }
                })
                .catch(() => {
                    this.expandedStateCities = [];
                })
                .finally(() => {
                    this.loadingCities = false;
                });
        },

        /**
         * Select a city tile: set state + city, save to Vuex, collapse accordion,
         * show success toast and redirect to homepage.
         */
        selectCity(state, city) {
            // Set the state & city
            this.guestShippingInfo = {
                ...this.guestShippingInfo,
                state: state,
                city: city,
            };

            // Collapse the accordion
            this.expandedStateId = null;

            // Save to Vuex immediately
            this.$store.dispatch("storeShippingDetails", { ...this.guestShippingInfo });

            // Trigger shipping options calculation
            this.getShippingOptions();

            // Success feedback + redirect home
            this.$toast.success(
                `${this.$t("Delivery location set to")} ${city.name}, ${state.name}`
            );
            this.$router.push("/");
        },

        /**
         * Clear the city selection so user can pick again
         */
        clearCitySelection() {
            this.guestShippingInfo = {
                ...this.guestShippingInfo,
                state: null,
                city: null,
                cities_options: [],
            };
            this.expandedStateId = null;
            this.expandedStateCities = [];
            this.$store.dispatch("storeShippingDetails", { ...this.guestShippingInfo });
        },

        /**
         * Will get previously saved data
         */
        getPreviousData() {
            let emptyShippingData = {
                id: "",
                name: "",
                email: "",
                phone_code: localStorage.getItem("country") != null
                    ? JSON.parse(localStorage.getItem("country")).phone_code : "",
                phone: "",
                postal_code: "",
                address: "",
                country: this.$t("Select Country"),
            };
            let emptyBillingData = { ...emptyShippingData };

            this.isActivePickupPoint = this.$store.state.isActivePickupPoint;
            this.isActiveHomeDelivery = this.$store.state.isActiveHomeDelivery;

            if (this.isCustomerLogin) {
                if (this.$store.state.shippingDetails != null) {
                    this.customerShippingInfo = this.$store.state.shippingDetails.name
                        ? this.$store.state.shippingDetails : emptyShippingData;
                }
                if (this.$store.state.billingDetails != null) {
                    this.customerBillingInfo = this.$store.state.billingDetails
                        ? this.$store.state.billingDetails : emptyShippingData;
                }
            } else {
                this.isActiveBillToDifferentAddress = this.$store.state.isActiveBillToDifferentAddress;
                this.isActiveCreateNewAccount = this.$store.state.isActiveCreateNewAccount;
                this.guestBillingInfo = this.$store.state.billingDetails != null
                    ? this.$store.state.billingDetails : emptyBillingData;
                this.isRestoringData = true;
                this.guestShippingInfo = this.$store.state.shippingDetails != null
                    ? this.$store.state.shippingDetails : emptyShippingData;
                this.guestCustomerInfo = this.$store.state.guestCustomerInfo != null
                    ? this.$store.state.guestCustomerInfo
                    : { name: "", email: "", password: "", confirm_password: "" };
            }

            this.selectedPickupPoints = this.$store.state.pickupPoint != null
                ? this.$store.state.pickupPoint
                : { id: "", name: this.$t("Select pickup point"), location: "", phone: "", zone_id: "", zone_name: "" };

            this.loading = false;
        },

        /**
         * Calculate shipping cost
         */
        getShippingOptions() {
            this.notAvailableProductsModal = false;
            this.shippingNotAvailableProducts = [];

            if (this.isActivePickupPoint && !this.isActiveHomeDelivery) {
                this.$store.dispatch("setFinalShippingCost", 0);
                this.deliveryNotAvailable = false;
            }

            if (!this.isActivePickupPoint && this.isActiveHomeDelivery) {
                let city_id = null;
                let post_code = null;

                if (this.isCustomerLogin && this.customerShippingInfo != null) {
                    post_code = this.customerShippingInfo.postal_code;
                    city_id = this.customerShippingInfo.city != null ? this.customerShippingInfo.city.id : null;
                }
                if (!this.isCustomerLogin && this.guestShippingInfo != null) {
                    post_code = this.guestShippingInfo.postal_code;
                    city_id = this.guestShippingInfo.city != null ? this.guestShippingInfo.city.id : null;
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
                    .catch(() => {
                        this.$store.dispatch("setFinalShippingCost", 0);
                        this.deliveryNotAvailable = true;
                    });
            }
        },

        saveDeliverylocationInState() {
            this.$store.dispatch("storeShippingDetails", { ...this.guestShippingInfo });
        },

        goNextStep() {
            if (!this.deliveryNotAvailable) {
                if (this.isActiveHomeDelivery && !this.isActivePickupPoint) {
                    let shippingAddress = {};
                    let billingAddress = {};
                    if (this.isCustomerLogin) {
                        shippingAddress = this.customerShippingInfo;
                        billingAddress = this.config?.use_shipping_address_as_billing_address == this.enums.status.ACTIVE
                            ? this.customerShippingInfo : this.customerBillingInfo;
                    } else {
                        shippingAddress = this.guestShippingInfo;
                        billingAddress = this.config?.use_shipping_address_as_billing_address == this.enums.status.ACTIVE
                            || !this.isActiveBillToDifferentAddress
                            ? this.guestShippingInfo : this.guestBillingInfo;
                        this.$store.dispatch("storeGuestCustomerDetails", this.guestCustomerInfo);
                        this.$store.dispatch("storeIsActiveBillToDifferentAddress", this.isActiveBillToDifferentAddress);
                        this.$store.dispatch("storeIsActiveCreateNewAccount", this.isActiveCreateNewAccount);
                    }
                    this.$store.dispatch("storeShippingDetails", shippingAddress);
                    this.$store.dispatch("storeBillingDetails", billingAddress);
                }
            } else {
                this.$toast.error(this.$t("Delivery not available in your location"));
            }
        },

        checkedAddress() {
            // No longer uses $refs.addressRadio — handled via :class binding
        },

        checkedBillingAddress(e) {
            this.$refs.billingAddressRadio.forEach((element) => {
                element.classList.remove("active");
            });
            e.target.parentElement.parentElement.classList.add("active");
        },

        /**
         * Get countries list — auto-selects single country and loads states
         */
        getCounties() {
            this.loadingStates = true;
            axios
                .post("/api/v1/ecommerce-core/get-countries", null)
                .then((response) => {
                    if (response.data.success) {
                        this.countries = response.data.data.countries;
                        // console.log("countries: ", this.countries);
                        if (this.countries.length >= 1) {
                            // Auto-select the first (or only) country
                            this.guestShippingInfo = {
                                ...this.guestShippingInfo,
                                country: this.countries[0],
                            };
                            this.getStates("shipping_info");
                        }
                    }
                })
                .catch(() => {
                    this.countries = [];
                    this.loadingStates = false;
                });
        },

        /**
         * Get states for selected country
         */
        getStates(origin) {
            // console.log("----getStates method called!!!");
            let country_id = "";
            if (origin === "billing_info") {
                country_id = this.guestBillingInfo.country ? this.guestBillingInfo.country.id : "";
            } else if (origin === "shipping_info") {
                country_id = this.guestShippingInfo.country ? this.guestShippingInfo.country.id : "";
            }

            // console.log("country_id: ", country_id);
            this.loadingStates = true;

            axios
                .post("/api/v1/ecommerce-core/get-states-of-countries", { country_id })
                .then((response) => {
                    if (response.data.success) {
                        // console.log("states_response: ", response.data);
                        if (origin === "billing_info") {
                            this.guestBillingInfo.states_options = response.data.data.states;
                        }
                        if (origin === "shipping_info") {
                            this.guestShippingInfo = {
                                ...this.guestShippingInfo,
                                states_options: response.data.data.states,
                            };
                        }
                    }
                })
                .catch(() => { })
                .finally(() => {
                    this.loadingStates = false;
                });
        },

        /**
         * Get cities for a state (kept for backward compatibility)
         */
        getCities(origin) {
            let state = "";
            if (origin === "billing_info") {
                state = this.guestBillingInfo.state ? this.guestBillingInfo.state.id : "";
            } else if (origin === "shipping_info") {
                state = this.guestShippingInfo.state ? this.guestShippingInfo.state.id : "";
            }
            axios
                .post("/api/v1/ecommerce-core/get-cities-of-state", { state_id: state })
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
                .catch(() => { });
        },
    },
};
</script>

<style scoped>
.location-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px 12px;
}

/* ── Back to Home Button ──────────────────────────────────────── */
.back-home-btn {
    /* position: fixed;*/
    top: 16px;
    left: 16px;
    /* z-index: 1050; */
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

/* ── Selected Location Badge ─────────────────────────────────── */
.selected-location-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ebf4ff;
    border: 1.5px solid #4a90e2;
    color: #2b6cb0;
    border-radius: 50px;
    padding: 6px 14px;
    font-size: 0.88rem;
    font-weight: 600;
}

.selected-location-badge i {
    color: #4a90e2;
}

.change-location-btn {
    background: none;
    border: none;
    color: #4a90e2;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    padding: 0 0 0 6px;
    text-decoration: underline;
    text-underline-offset: 2px;
}

.change-location-btn:hover {
    color: #2b6cb0;
}

/* ── States Container ────────────────────────────────────────── */
.states-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* ── State Tile ──────────────────────────────────────────────── */
.state-tile {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.state-tile--open {
    border-color: #4a90e2;
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.12);
}

.state-tile--selected {
    border-color: #48bb78;
    background: #f0fff4;
}

.state-tile__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    cursor: pointer;
    user-select: none;
    transition: background 0.15s ease;
}

.state-tile__header:hover {
    background: #f7fafc;
}

.state-tile--open .state-tile__header {
    background: #ebf4ff;
}

.state-tile--selected .state-tile__header {
    background: #f0fff4;
}

.state-tile__label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #2d3748;
}

.state-icon {
    color: #a0aec0;
    font-size: 0.85rem;
}

.state-tile--selected .state-icon {
    color: #48bb78;
}

.state-tile__chevron {
    font-size: 0.75rem;
    color: #a0aec0;
    transition: transform 0.2s ease;
}

.state-tile--open .state-tile__chevron {
    color: #4a90e2;
}

/* ── City selected chip (shown inside state header) ───────────── */
.city-selected-chip {
    display: inline-block;
    background: #48bb78;
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 50px;
    margin-left: 4px;
}

/* ── Cities Grid ─────────────────────────────────────────────── */
.cities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 8px;
    padding: 12px 16px 16px;
    border-top: 1px solid #e2e8f0;
    background: #fafbfc;
}

.cities-loading {
    grid-column: 1 / -1;
    text-align: center;
    color: #a0aec0;
    font-size: 0.85rem;
    padding: 10px 0;
}

/* ── City Tile ───────────────────────────────────────────────── */
.city-tile {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    color: #4a5568;
    background: #ffffff;
    transition: all 0.15s ease;
    position: relative;
    min-width: 0;
    overflow: hidden;
}

.city-tile:hover {
    border-color: #4a90e2;
    color: #2b6cb0;
    background: #ebf4ff;
}

.city-tile--selected {
    border-color: #48bb78;
    background: #f0fff4;
    color: #276749;
    font-weight: 700;
}

.city-tile__icon {
    font-size: 0.75rem;
    color: #a0aec0;
    flex-shrink: 0;
}

.city-tile--selected .city-tile__icon {
    color: #48bb78;
}

.city-tile__name {
    min-width: 0;
    flex: 1;
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.3;
}

.city-tile__check {
    font-size: 0.7rem;
    color: #48bb78;
    margin-left: auto;
    flex-shrink: 0;
}

/* ── Transition ──────────────────────────────────────────────── */
.cities-expand-enter-active,
.cities-expand-leave-active {
    transition: max-height 0.3s ease, opacity 0.25s ease;
    overflow: hidden;
    max-height: 600px;
}

.cities-expand-enter-from,
.cities-expand-leave-to {
    max-height: 0;
    opacity: 0;
}

/* ── Address cards (logged-in users) ─────────────────────────── */
.address-card {
    position: relative;
    padding: 1.25rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #ffffff;
    margin-bottom: 1rem;
}

.address-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.address-card--active {
    border-color: #4a90e2;
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

/* ── Mobile tweaks ───────────────────────────────────────────── */
@media screen and (max-width: 768px) {
    .cities-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .back-home-btn__label {
        display: none;
        /* icon-only on very small screens */
    }
}

.spin-animation {
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

/* Spacing fix for icons next to labels */
.state-icon,
.city-tile__icon {
    margin-right: 8px;
}
</style>