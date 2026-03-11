<template>

    <div class="mb-2">

        <div v-if="!isCustomerLogin">

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

        </div>
    </div>

</template>

<script>

export default {

    props: {
        enums: Object,
        config: Object,
        customerAddress: Object,
        isCustomerLogin: Boolean,
        pickupPoints: Array
    },

    computed: {
        selectedCity() {
            return this.$store.state.shippingDetails?.city || null;
        },
        selectedState() {
            return this.$store.state.shippingDetails?.state || null;
        },
    },

    methods: {

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
</style>