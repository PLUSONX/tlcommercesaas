<template>
    <footer class="company-footer" :class="hasCartItems ? 'company-footer--checkout' : 'company-footer--designer'">
        <template v-if="!hasCartItems">
            <span class="c1">Designed by <a :href="designerUrl" target="_blank" rel="noopener">{{ designerName }}</a></span>
        </template>

        <template v-else>
            <div class="company-footer__checkout-inner">
                <div class="company-footer__cart-icon-wrap" aria-hidden="true">
                    <span class="material-icons company-footer__cart-icon">shopping_cart</span>
                    <span class="company-footer__cart-badge">{{ cartItemCount }}</span>
                </div>

                <div class="company-footer__totals">
                    <div class="company-footer__subtotal">
                        <the-currency :amount="cartSubtotal" tag="span" />
                    </div>
                    <div v-if="showDeliveryFee" class="company-footer__delivery-fee">
                        {{ $t("Delivery fee") }}
                        <the-currency :amount="deliveryFeeAmount" tag="span" />
                    </div>
                </div>

                <button v-if="showFooterCheckoutButton" type="button"
                    class="btn btn_fill rounded company-footer__checkout-btn"
                    :disabled="isCheckoutRoute && checkoutOrderCreating"
                    @click="onFooterCheckoutClick">
                    <span v-if="isCheckoutRoute && checkoutOrderCreating">
                        <CSpinner component="span" size="sm" aria-hidden="true" />
                        {{ $t("Please wait") }}
                    </span>
                    <span v-else>
                        {{ isCheckoutRoute ? $t("Checkout") : $t("Check out") }}
                    </span>
                </button>
            </div>
        </template>
    </footer>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { CSpinner } from "@coreui/vue";

/** Flat rate shipping option id (tlecommercecore shipping_cost_options.flat_rate) */
const FLAT_RATE_SHIPPING_OPTION = 1;

const COMPANY_FOOTER_HEIGHT_VAR = "--tl-company-footer-height";

export default {
    name: "CompanyFooter",

    components: {
        CSpinner,
    },

    computed: {
        ...mapState({
            siteSettings: (state) => state.siteSettings,
            cart: (state) => state.cart,
            shippingCost: (state) => (state.shippingCost ? state.shippingCost : 0),
            checkoutOrderCreating: (state) => state.checkoutOrderCreating,
        }),
        ...mapGetters(["cartItemCount"]),

        isCheckoutRoute() {
            return this.$route?.name === "Checkout";
        },

        showFooterCheckoutButton() {
            return this.hasCartItems;
        },

        appliedShippingCost() {
            const cost = parseFloat(this.shippingCost);
            return Number.isFinite(cost) && cost > 0 ? cost : 0;
        },

        designerName() {
            return this.siteSettings?.designer_name || "Platepilots.com";
        },

        designerUrl() {
            return this.siteSettings?.designer_url || "https://platepilots.com";
        },

        hasCartItems() {
            return this.cartItemCount > 0;
        },

        cartSubtotal() {
            if (!Array.isArray(this.cart) || !this.cart.length) {
                return 0;
            }
            return this.cart.reduce(
                (accum, item) =>
                    parseFloat(accum) + parseFloat(item.unitPrice * item.quantity),
                0
            );
        },

        isFlatRateShipping() {
            const option = this.siteSettings?.shipping_option;
            return Number(option) === FLAT_RATE_SHIPPING_OPTION;
        },

        flatRateDeliveryFee() {
            const cost = this.siteSettings?.flat_rate_shipping_cost;
            return cost != null && cost !== "" ? parseFloat(cost) : 0;
        },

        deliveryFeeAmount() {
            if (this.isCheckoutRoute) {
                return this.appliedShippingCost;
            }
            if (this.appliedShippingCost > 0) {
                return this.appliedShippingCost;
            }
            if (this.isFlatRateShipping && this.flatRateDeliveryFee > 0) {
                return this.flatRateDeliveryFee;
            }
            return 0;
        },

        showDeliveryFee() {
            return this.deliveryFeeAmount > 0;
        },
    },

    mounted() {
        this.syncCompanyFooterHeightVar();
    },

    updated() {
        this.syncCompanyFooterHeightVar();
    },

    beforeUnmount() {
        this.clearCompanyFooterHeightVar();
    },

    methods: {
        syncCompanyFooterHeightVar() {
            this.$nextTick(() => {
                const el = this.$el;
                if (!el || typeof el.offsetHeight !== "number") {
                    return;
                }
                document.documentElement.style.setProperty(
                    COMPANY_FOOTER_HEIGHT_VAR,
                    `${el.offsetHeight}px`
                );
            });
        },

        clearCompanyFooterHeightVar() {
            document.documentElement.style.removeProperty(COMPANY_FOOTER_HEIGHT_VAR);
        },

        goToCheckout() {
            this.$router.push("/checkout");
        },

        onFooterCheckoutClick() {
            if (this.isCheckoutRoute) {
                this.$store.dispatch("requestCheckoutPlaceOrder");
                return;
            }
            this.goToCheckout();
        },
    },
};
</script>

<style scoped>
.company-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 100%;
    z-index: 100;
}

.company-footer--designer {
    padding: 10px 24px;
    font-size: 12px;
    letter-spacing: 0.03em;
    background: #fff;
    border-top: 1px solid #e8e8e8;
    box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.06);
}

.company-footer--designer a {
    color: inherit;
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.2s ease;
}

.company-footer--designer a:hover {
    opacity: 0.85;
}

.company-footer--checkout {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid #e8e8e8;
    box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.06);
}

.company-footer__checkout-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    max-width: 100%;
}

.company-footer__cart-icon-wrap {
    position: relative;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
}

.company-footer__cart-icon {
    font-size: 28px;
    color: #2d2d2d;
}

.company-footer__cart-badge {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    border-radius: 999px;
    background: #e53935;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    line-height: 18px;
    text-align: center;
}

.company-footer__totals {
    flex: 1 1 auto;
    min-width: 0;
}

.company-footer__subtotal {
    font-size: 18px;
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.2;
}

.company-footer__delivery-fee {
    margin-top: 2px;
    font-size: 12px;
    color: #888;
    line-height: 1.3;
}

.company-footer__checkout-btn {
    flex-shrink: 0;
    border-radius: 999px;
    padding: 12px 22px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}

.company-footer__checkout-btn:active {
    opacity: 0.92;
}
</style>
