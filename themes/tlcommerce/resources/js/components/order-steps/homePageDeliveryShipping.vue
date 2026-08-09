<template>
    <!-- <div class="p-1"> -->
    <div :class="{ 'delivery-page--rtl': isRtl }">
        <div class="delivery-container">

            <div v-if="!isCustomerLogin" class="delivery-card">

                <div class="delivery-field" @click="goToLocation">
                    <div class="delivery-field__left">
                        <span class="material-icons delivery-field__icon">motorcycle</span>
                        <span class="delivery-field__label">{{ selectDeliveryLocationLabel }}</span>
                    </div>

                    <div class="delivery-field__location" v-if="selectedCity">
                        <span class="material-icons delivery-field__pin">place</span>
                        <span class="delivery-field__city">
                            {{ selectedCity.name }}<template v-if="selectedState">, {{ selectedState.name }}</template>
                        </span>
                        <span class="material-icons delivery-field__chevron">{{ isRtl ? 'chevron_left' : 'chevron_right'
                            }}</span>
                    </div>

                    <div class="delivery-field__location delivery-field__location--empty" v-else>
                        <span class="delivery-field__prompt">{{ chooseLocationLabel }}</span>
                        <!-- <span class="delivery-field__prompt">{{ tapToChooseLabel }}</span> -->
                        <span class="material-icons delivery-field__chevron">{{ isRtl ? 'chevron_left' : 'chevron_right'
                            }}</span>
                    </div>
                </div>

                <hr class="delivery-card__divider">

                <div class="delivery-arrival">
                    <div class="d-flex align-items-center">
                        <span class="material-icons mr-2">schedule</span>
                        <span class="text-muted small">{{ earliestArrivalLabel }}</span>
                    </div>
                    <div v-if="earliestArrival" class="arrival-time">
                        <strong>{{ earliestArrival }}</strong>
                    </div>
                    <!-- <div v-if="selectedCity" class="arrival-time">
                        <div v-if="['raneem', 'raneemjewelers', 'RANEEM JEWELERS'].includes(tenant)">
                            <strong>12-24 {{ $t('Hours') }}</strong>
                        </div>

                        <div v-if="['kfc', 'tryguardi', 'TryGuardi', 'Guardi', 'TRYGUARDI'].includes(tenant)">
                            <strong>3-6 {{ $t('Hours') }}</strong>
                        </div>

                        <div v-if="['Tasty', 'tasty'].includes(tenant)">
                            <strong>12-24 {{ $t('Hours') }}</strong>
                        </div>
                    </div> -->
                </div>

                <hr class="delivery-card__divider">

                <div class="delivery-time-row" role="button" tabindex="0" @click="openDeliveryTimeEdit"
                    @keyup.enter="openDeliveryTimeEdit">
                    <div class="d-flex align-items-center">
                        <span class="material-icons mr-2">event</span>
                        <span class="text-muted small">{{ deliveryTimeLabel }}</span>
                    </div>
                    <div class="arrival-time">
                        <strong v-if="deliveryTimeValue" class="delivery-time-row__value">{{ deliveryTimeValue
                            }}</strong>
                        <span v-else class="delivery-field__prompt">{{ chooseDeliveryTimeLabel }}</span>
                        <span class="material-icons delivery-field__chevron">{{ isRtl ? 'chevron_left' : 'chevron_right'
                            }}</span>
                    </div>
                </div>

            </div>

        </div>

        <delivery-time-selector v-if="config && enums" ref="deliveryTimeSelector" variant="homepage" :config="config"
            :enums="enums" />
    </div>
</template>

<!-- <template>

    <div class="mb-0"> -->

<!-- <div v-if="!isCustomerLogin" class="delivery-card">

            <div class="delivery-field" @click="goToLocation">
                <div class="delivery-field__left">
                    <span class="material-icons delivery-field__icon">motorcycle</span>
                    <span class="delivery-field__label">{{ $t("Select Delivery Location") }}</span>
                </div>

                <div class="delivery-field__location" v-if="selectedCity">
                    <span class="material-icons delivery-field__pin">place</span>
                    <span class="delivery-field__city">
                        {{ selectedCity.name }}<template v-if="selectedState">, {{ selectedState.name }}</template>
                    </span>
                    <span class="material-icons delivery-field__chevron">chevron_right</span>
                </div>

                <div class="delivery-field__location delivery-field__location--empty" v-else>
                    <span class="delivery-field__prompt">{{ $t("Tap to choose") }}</span>
                    <span class="material-icons delivery-field__chevron">chevron_right</span>
                </div>
            </div>

            <hr class="delivery-card__divider">

            <div class="delivery-arrival">
                <div class="d-flex align-items-center">
                    <span class="material-icons mr-5">schedule</span>
                    <span class="text-muted small">{{ $t('Earliest Arrival') }}</span>
                </div>
                <div v-if="selectedCity" class="arrival-time">
                    <strong>1 {{ $t('Hour') }}</strong>
                </div>
            </div>

        </div> -->

<!-- <div v-if="!isCustomerLogin">

            <div class="delivery-field" @click="goToLocation">

                <div class="delivery-field__left">
                    <span class="material-icons delivery-field__icon"
                        style="font-size: 18px; vertical-align: middle;">motorcycle</span>
                    <span class="delivery-field__label">{{ $t("Select Delivery Location") }}</span>
                </div>

                <div class="delivery-field__location" v-if="selectedCity">
                    <span class="material-icons delivery-field__pin"
                        style="font-size: 18px; vertical-align: middle;">place</span>
                    <span class="delivery-field__city">
                        {{ selectedCity.name }}<template v-if="selectedState">, {{ selectedState.name }}</template>
                    </span>
                    <span class="material-icons delivery-field__chevron"
                        style="font-size: 18px; vertical-align: middle;">chevron_right</span>
                </div>

                <div class="delivery-field__location delivery-field__location--empty" v-else>
                    <span class="delivery-field__prompt">{{ $t("Tap to choose") }}</span>
                    <span class="material-icons delivery-field__chevron"
                        style="font-size: 18px; vertical-align: middle;">chevron_right</span>
                </div>

            </div>

            <div class="mb-10 col-lg-12 mt-1">
                <div class="d-flex align-items-center justify-content-between p-10"
                    style="background: #f8f9fa; border-radius: 6px;">
                    <div class="d-flex align-items-center">
                        <span class="material-icons mr-5" style="font-size: 18px; color: #6c757d;">schedule</span>
                        <span class="text-muted small">{{ $t('Earliest Arrival') }}</span>
                    </div>
                    <div v-if="selectedCity" style="margin-right: 5px;">
                        <strong>1 {{ $t('Hour') }}</strong>
                    </div>
                </div>
            </div>

        </div> -->
<!-- </div>

</template> -->

<script>

import axios from "axios";
import { mapGetters } from "vuex";
import DeliveryTimeSelector from "./DeliveryTimeSelector.vue";
import { formatSlotDisplay } from "@/utils/deliveryTimeFormat";

export default {
    components: {
        DeliveryTimeSelector,
    },

    props: {
        enums: Object,
        config: Object,
        customerAddress: Object,
        isCustomerLogin: Boolean,
        pickupPoints: Array,
        tenant: String,
    },

    data() {
        return {
            earliestArrival: null,
            loadingEarliestArrival: false,
        };
    },

    computed: {
        ...mapGetters('layout', ['isRtl']),
        selectedCity() {
            return this.$store.state.shippingDetails?.city || null;
        },
        selectedState() {
            return this.$store.state.shippingDetails?.state || null;
        },
        tenant() {
            return this.$store.state.siteProperties?.site_name;
        },
        locationKey() {
            const cityId = this.selectedCity?.id || "";
            const stateId = this.selectedState?.id || "";
            return `${cityId}-${stateId}`;
        },
        deliverySchedule() {
            return this.$store.state.deliverySchedule || null;
        },
        scheduledSummary() {
            const schedule = this.deliverySchedule;
            if (schedule?.mode !== "scheduled" || !schedule?.slot) {
                return null;
            }
            return this.formatScheduledSlot(schedule.slot);
        },
        deliveryTimeValue() {
            if (this.scheduledSummary) {
                return this.scheduledSummary;
            }
            if (this.deliverySchedule?.mode === "now") {
                return this.nowLabel;
            }
            return null;
        },
        selectDeliveryLocationLabel() {
            return this.localizedLabel("Select Delivery Location", "اختر موقع التوصيل");
        },
        tapToChooseLabel() {
            return this.localizedLabel("Tap to choose", "اضغط للاختيار");
        },
        chooseLocationLabel() {
            return this.localizedLabel("Choose location", "اختر الموقع");
        },
        earliestArrivalLabel() {
            return this.localizedLabel("Order Now", "اطلب الحين");
            //return this.localizedLabel("Earliest Arrival", "أقرب وقت للوصول");
        },
        deliveryTimeLabel() {
            return this.localizedLabel("Deliver Now", "التوصيل الآن");
        },
        chooseDeliveryTimeLabel() {
            return this.localizedLabel("Choose delivery time", "اختر وقت التوصيل");
        },
        nowLabel() {
            return this.localizedLabel("Now", "الآن");
        },
    },

    watch: {
        locationKey: {
            immediate: true,
            handler() {
                this.fetchEarliestArrival();
            },
        },
    },

    mounted() {
        // console.log('siteSettings:', this.$store.state.siteSettings);
        // console.log('siteProperties:', this.$store.state.siteProperties);
        // console.log('site_name:', this.$store.state.siteProperties.site_name);
        // this.tenant = this.$store.state.siteProperties.site_name;
    },

    methods: {
        localizedLabel(key, arFallback) {
            const translated = this.$t(key);
            if (!this.isRtl) return translated;
            return /[\u0600-\u06FF]/.test(translated) ? translated : arFallback;
        },

        formatDateLabel(dateKey) {
            const parts = String(dateKey || "").split("-");
            if (parts.length !== 3) {
                return dateKey || "";
            }
            const date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            return date.toLocaleDateString(undefined, {
                weekday: "short",
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        formatScheduledSlot(slot) {
            if (!slot) {
                return null;
            }
            const dateLabel = slot.schedule_date
                ? this.formatDateLabel(slot.schedule_date)
                : "";
            const timePart = formatSlotDisplay(slot);
            if (dateLabel && timePart) {
                return `${dateLabel} · ${timePart}`;
            }
            return timePart || dateLabel || null;
        },

        openDeliveryTimeEdit() {
            this.$refs.deliveryTimeSelector?.openEditModal();
        },

        fetchEarliestArrival() {
            this.loadingEarliestArrival = true;
            axios
                .post("/api/v1/ecommerce-core/earliest-arrival", {
                    city_id: this.selectedCity?.id || null,
                    state_id: this.selectedState?.id || null,
                })
                .then((response) => {
                    if (response.data?.success && response.data?.shipping_time) {
                        this.earliestArrival = response.data.shipping_time;
                    } else {
                        this.earliestArrival = null;
                    }
                })
                .catch(() => {
                    this.earliestArrival = null;
                })
                .finally(() => {
                    this.loadingEarliestArrival = false;
                });
        },

        goToLocation() {

            this.$store.dispatch("setDeliveryData", {
                enums: this.enums,
                config: this.config,
                customerAddress: this.customerAddress,
                isCustomerLogin: this.isCustomerLogin,
                pickupPoints: this.pickupPoints
            });

            console.log("dispatching delivery data", {
                isCustomerLogin: this.isCustomerLogin,
                enums: this.enums,
                config: this.config,
                customerAddress: this.customerAddress
            });

            this.$router.push('/select/location');
        }
    }
}
</script>

<style scoped>
.delivery-container {
    margin: 0;
    padding: 0;
}

/* The main wrapper - handles the "Card" look */
.delivery-card {
    background: #f8f9fa;
    border: 1px solid #e2e2e2;
    /* border-radius: 8px; */
    overflow: hidden;
    /* Important: keeps child backgrounds inside the rounded corners */
    width: 100%;
    transition: all 0.2s ease;
}

/* Internal shared styles */
.delivery-field,
.delivery-arrival,
.delivery-time-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    width: 100%;
}

/* Specific styling for the clickable top area */
.delivery-field {
    cursor: pointer;
    background: transparent;
    /* Changed from #f8f9fa */
    border: none;
    /* Removed individual border */
}

.delivery-field:hover,
.delivery-time-row:hover {
    background: #f1f1f1;
}

/* Middle area — read-only estimate */
.delivery-arrival {
    background: rgba(0, 0, 0, 0.03);
    /* Subtle contrast from top section */
}

/* Bottom area — tappable delivery time choice */
.delivery-time-row {
    cursor: pointer;
    background: transparent;
    border: none;
}

.delivery-time-row:focus {
    outline: none;
    background: #f1f1f1;
}

.delivery-time-row__value {
    font-size: 0.85rem;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.delivery-field__chevron {
    font-size: 18px;
    color: #a0aec0;
    flex-shrink: 0;
}

.delivery-field__location--empty .delivery-field__prompt,
.delivery-time-row .delivery-field__prompt {
    font-size: 0.8rem;
    font-weight: 400;
    color: #a0aec0;
}

.delivery-card__divider {
    margin: 0;
    border: 0;
    border-top: 1px solid #e2e2e2;
}

/* Icon & Text logic */
.delivery-field__left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.delivery-field__location {
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 0;
}

.delivery-field__city {
    font-size: 0.85rem;
    font-weight: 700;
    color: #4a90e2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}

.mr-2 {
    margin-inline-end: 8px;
}

.material-icons {
    font-size: 18px;
    vertical-align: middle;
    color: #6c757d;
}

.delivery-field__pin {
    color: #4a90e2;
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.delivery-page--rtl .delivery-field,
.delivery-page--rtl .delivery-arrival,
.delivery-page--rtl .delivery-time-row {
    flex-direction: row-reverse;
    direction: ltr;
}

.delivery-page--rtl .delivery-field__left {
    flex: 0 1 auto;
    flex-direction: row-reverse;
    direction: ltr;
    justify-content: flex-end;
}

.delivery-page--rtl .delivery-field__location {
    flex: 0 1 auto;
    min-width: 0;
    max-width: none;
    flex-direction: row-reverse;
    direction: ltr;
    justify-content: flex-end;
}

.delivery-page--rtl .delivery-field__label,
.delivery-page--rtl .delivery-field__prompt,
.delivery-page--rtl .delivery-arrival .text-muted,
.delivery-page--rtl .delivery-time-row .text-muted {
    direction: rtl;
    text-align: right;
    white-space: normal;
    overflow: visible;
    unicode-bidi: plaintext;
}

.delivery-page--rtl .delivery-field__city {
    direction: rtl;
    text-align: right;
}

.delivery-page--rtl .arrival-time {
    direction: ltr;
    text-align: left;
    flex-shrink: 0;
}

.delivery-page--rtl .delivery-time-row .arrival-time {
    flex-direction: row-reverse;
}

.arrival-time {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.arrival-time strong {
    font-weight: 700;
    color: #111;
}

.arrival-time__edit {
    border: none;
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: inherit;
    font-weight: 400;
    color: #6c757d;
    cursor: pointer;
    line-height: 1;
}

.arrival-time__edit:hover {
    color: #343a40;
    text-decoration: underline;
}

.delivery-page--rtl .arrival-time__edit {
    direction: rtl;
    unicode-bidi: plaintext;
}

.delivery-page--rtl .delivery-arrival>.d-flex,
.delivery-page--rtl .delivery-time-row>.d-flex {
    flex-direction: row-reverse;
    direction: ltr;
}
</style>

<!-- <style scoped>
.delivery-field {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 16px;
    border-radius: 8px;

    background: #f8f9fa;
    border: 1px solid #e2e2e2;

    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
}

.delivery-field:hover {
    background: #f1f1f1;
}

/* ── Left side ───────────────────────────────────────────────── */
.delivery-field__left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.delivery-field__icon {
    color: #4a5568;
    font-size: 0.95rem;
}

.delivery-field__label {
    color: #2d3748;
    font-size: 0.92rem;
}

/* ── Right side ──────────────────────────────────────────────── */
.delivery-field__location {
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 0;
    /* allow text truncation */
}

.delivery-field__pin {
    font-size: 0.8rem;
    color: #4a90e2;
    flex-shrink: 0;
}

.delivery-field__city {
    font-size: 0.85rem;
    font-weight: 700;
    color: #4a90e2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}

.delivery-field__chevron {
    font-size: 0.7rem;
    color: #a0aec0;
    flex-shrink: 0;
}

/* Empty state prompt */
.delivery-field__location--empty .delivery-field__prompt {
    font-size: 0.8rem;
    font-weight: 400;
    color: #a0aec0;
}

/* The main wrapper */
.delivery-card {
    background: #f8f9fa;
    /* Light grey background */
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    /* margin: 10px 0; */
    /* Vertical spacing only */
    overflow: hidden;
    /* Ensures no content bleeds out */
    width: 100%;
}

/* Location Section */
.delivery-field {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    cursor: pointer;
}

/* Arrival Section */
.delivery-arrival {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: rgba(0, 0, 0, 0.02);
    /* Slight contrast for the bottom half */
}

/* Subtle divider between the two sections */
.delivery-card__divider {
    margin: 0;
    border: 0;
    border-top: 1px solid #eee;
}

/* Icon consistency */
.material-icons {
    font-size: 18px;
    vertical-align: middle;
    color: #6c757d;
}
</style> -->