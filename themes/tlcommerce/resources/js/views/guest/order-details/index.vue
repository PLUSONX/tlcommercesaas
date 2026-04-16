<template>
    <div class="pt-4" style="max-width: 600px; margin: 40px auto; padding: 0 16px; font-family: sans-serif;">

        <!-- Loading -->
        <div v-if="loading" style="text-align: center; padding: 60px 0; color: #888;">
            Loading order details...
        </div>

        <!-- Error -->
        <div v-else-if="!success" style="text-align: center; padding: 60px 0; color: #e74c3c;">
            Order not found or an error occurred.
        </div>

        <!-- Order Details -->
        <div v-else-if="orderDetails">
            <h2 style="margin-bottom: 4px;">Order Status</h2>
            <p style="color: #888; margin-top: 0; margin-bottom: 24px;">
                Order #{{ orderDetails.order_code }}
            </p>

            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; width: 40%; border-bottom: 1px solid #e0e0e0;">
                            Order Date</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{ orderDetails.order_date }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Payment Method</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{ orderDetails.payment_method
                            }}</td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Payment Status</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">
                            <span :style="paymentStatusStyle">
                                {{ orderDetails.payment_status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Delivery Status</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">
                            <span :style="deliveryStatusStyle">
                                {{ orderDetails.delivery_status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Subtotal</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{ orderDetails.sub_total }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Delivery Cost</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{
                            orderDetails.total_delivery_cost }}</td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Discount</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{ orderDetails.total_discount
                            }}</td>
                    </tr>
                    <tr>
                        <!-- <td
                            style="padding: 12px 16px; background: #f5f5f5; font-weight: 600; border-bottom: 1px solid #e0e0e0;">
                            Tax</td>
                        <td style="padding: 12px 16px; border-bottom: 1px solid #e0e0e0;">{{ orderDetails.total_tax }}
                        </td> -->
                    </tr>
                    <tr>
                        <td style="padding: 12px 16px; background: #f5f5f5; font-weight: 700; font-size: 1.05em;">Total
                        </td>
                        <td style="padding: 12px 16px; font-weight: 700; font-size: 1.05em;">{{
                            orderDetails.total_payable_amount }}</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="orderDetails.note"
                style="margin-top: 20px; padding: 12px 16px; background: #fffbe6; border-left: 4px solid #f0c040; border-radius: 4px;">
                <strong>Note:</strong> {{ orderDetails.note }}
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "GuestOrderDetails",
    data() {
        return {
            loading: false,
            success: false,
            orderDetails: null,
        }
    },
    computed: {
        paymentStatusStyle() {
            const paid = this.orderDetails?.payment_status === 1;
            return {
                display: 'inline-block',
                padding: '2px 10px',
                borderRadius: '12px',
                fontSize: '0.85em',
                fontWeight: '600',
                background: paid ? '#e6f9f0' : '#fff0f0',
                color: paid ? '#27ae60' : '#e74c3c',
                textTransform: 'capitalize',
            };
        },
        deliveryStatusStyle() {
            const delivered = this.orderDetails?.delivery_status === 1;
            return {
                display: 'inline-block',
                padding: '2px 10px',
                borderRadius: '12px',
                fontSize: '0.85em',
                fontWeight: '600',
                background: delivered ? '#e6f9f0' : '#fff8e6',
                color: delivered ? '#27ae60' : '#e67e22',
                textTransform: 'capitalize',
            };
        },
    },
    mounted() {
        this.getGuestOrderDetails();
    },
    methods: {
        getGuestOrderDetails() {
            this.loading = true;
            axios
                .post("/api/v1/ecommerce-core/guest/order/details", {
                    order_id: this.$route.params.id,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.orderDetails = response.data.data;
                        this.success = true;
                    }
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },
    }
}
</script>