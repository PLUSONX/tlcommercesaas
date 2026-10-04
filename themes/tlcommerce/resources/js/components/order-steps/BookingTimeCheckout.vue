<template>
  <div v-if="enabled" class="booking-checkout col-12 mb-20" :class="{ 'booking-checkout--rtl': isRtl }">
    <h5>{{ title }}:</h5>

    <div class="booking-checkout__info">
      <span class="material-icons booking-checkout__info-icon">info</span>
      <span>{{ infoText }}</span>
    </div>

    <label class="booking-checkout__option" @click.prevent="openEdit">
      <input type="radio" name="booking-time-option" :checked="true" />
      <span>{{ chooseLabel }}</span>
    </label>

    <div v-if="booking" class="booking-time-summary">
      <div class="booking-time-summary__content">
        <span class="material-icons booking-time-summary__icon">event_available</span>
        <span>{{ summaryText }}</span>
      </div>
      <button type="button" class="btn_underline" @click.prevent="openEdit">
        {{ changeLabel }}
      </button>
    </div>

    <div
      v-else
      class="booking-time-prompt"
      role="button"
      tabindex="0"
      @click="openEdit"
      @keyup.enter="openEdit"
    >
      <span class="material-icons">schedule</span>
      <span>{{ selectLabel }}</span>
    </div>

    <booking-time-selector
      ref="selector"
      variant="checkout"
      :config="config"
      :enums="enums"
      @confirmed="onConfirmed"
      @released="onReleased"
      @cleared="onCleared"
    />
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import BookingTimeSelector from "./BookingTimeSelector.vue";

const STORAGE_KEY = "tlcommerce_studio_booking_hold";
const COOKIE_NAME = "studio_booking_token";

export default {
  name: "BookingTimeCheckout",
  components: { BookingTimeSelector },
  props: {
    config: { type: Object, default: () => ({}) },
    enums: { type: Object, default: () => ({}) },
    emits: ["hours-selected"],
  },
  data() {
    return {
      enabled: false,
      booking: null,
    };
  },
  computed: {
    ...mapGetters("layout", ["isRtl"]),
    title() { return this.localized("Booking Time", "وقت الحجز"); },
    infoText() {
      return this.localized(
        "Choose your studio hours. They are reserved when you complete the order.",
        "اختر ساعات الاستوديو. يتم حجزها عند إكمال الطلب."
      );
    },
    chooseLabel() { return this.localized("Choose Booking Time", "اختر وقت الحجز"); },
    selectLabel() { return this.localized("Select studio date & time", "اختر تاريخ ووقت الاستوديو"); },
    changeLabel() { return this.localized("Change", "تغيير"); },
    summaryText() {
      const b = this.booking;
      if (!b?.schedule_date || !b?.start_time || !b?.end_time) return "";
      const parts = String(b.schedule_date).split("-");
      const date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
      const dateLabel = date.toLocaleDateString(undefined, {
        weekday: "short", month: "short", day: "numeric", year: "numeric",
      });
      const hours = Math.round(Number(b.duration_minutes || 0) / 60);
      const hourText = hours === 1 ? this.localized("Hour", "ساعة") : this.localized("Hours", "ساعات");
      const tail = hours > 0 ? ` · ${hours} ${hourText}` : "";
      return `${dateLabel} · ${this.formatTime(b.start_time)} - ${this.formatTime(b.end_time)}${tail}`;
    },
  },
  mounted() {
    this.init();
    this._visibilityHandler = () => {
      if (document.visibilityState === "visible") this.verify();
    };
    document.addEventListener("visibilitychange", this._visibilityHandler);
  },
  beforeUnmount() {
    document.removeEventListener("visibilitychange", this._visibilityHandler);
  },
  methods: {
    localized(en, ar) {
      const translated = this.$t(en);
      if (!this.isRtl) return translated;
      return /[\u0600-\u06FF]/.test(translated) ? translated : ar;
    },
    flagEnabled(value) {
      return value === true || value === 1 || value === "1" || value === "true" || value === "enabled";
    },
    async init() {
      // Same source Deliver Now uses (earliest-arrival), with the config endpoint as fallback.
      let arrivalFlag;
      try {
        const cityId = this.$store.state.shippingDetails?.city?.id || null;
        const stateId = this.$store.state.shippingDetails?.state?.id || null;
        const res = await axios.post("/api/v1/ecommerce-core/earliest-arrival", {
          city_id: cityId,
          state_id: stateId,
        });
        arrivalFlag = res.data?.booking_now_enabled;
      } catch (e) {
        arrivalFlag = undefined;
      }

      let configFlag;
      try {
        const res = await axios.get("/api/v1/ecommerce-core/studio-booking/config", {
          headers: { Accept: "application/json" },
        });
        const payload = res.data || {};
        configFlag = payload.booking_now_enabled ?? payload.enabled;
      } catch (e) {
        configFlag = undefined;
      }

      const raw = arrivalFlag !== undefined && arrivalFlag !== null ? arrivalFlag : configFlag;
      this.enabled = this.flagEnabled(raw);

      if (!this.enabled) {
        this.booking = null;
        this.clearStored();
        return;
      }

      this.booking = this.readStored();
      await this.verify();
    },
    readStored() {
      try {
        const raw = localStorage.getItem(STORAGE_KEY);
        const b = raw ? JSON.parse(raw) : null;
        return b?.booking_token ? b : null;
      } catch (e) {
        return null;
      }
    },
    clearStored() {
      try { localStorage.removeItem(STORAGE_KEY); } catch (e) { /* ignore */ }
      try { document.cookie = `${COOKIE_NAME}=; path=/; max-age=0; SameSite=Lax`; } catch (e) { /* ignore */ }
    },
    /** Make sure the saved selection is still free; otherwise ask the customer to choose again. */
    async verify() {
      if (!this.enabled) return;
      const stored = this.readStored();
      if (!stored) {
        this.booking = null;
        return;
      }
      try {
        const res = await axios.post(
          "/api/v1/ecommerce-core/studio-booking/available-slots",
          { booking_token: stored.booking_token },
          { headers: { Accept: "application/json" } }
        );
        if (res.data?.enabled === false) {
          this.enabled = false;
          this.booking = null;
          this.clearStored();
          return;
        }
        if (res.data?.current_booking) {
          this.booking = res.data.current_booking;
        } else {
          this.booking = null;
          this.clearStored();
        }
      } catch (e) {
        // Network problem: keep what the customer chose; the server re-checks when the order is placed.
        this.booking = stored;
      }
    },
    openEdit() {
      this.$refs.selector?.openEditModal();
    },
    onConfirmed(booking) {
      this.booking = booking || null;
      if (!booking) return;

      // 1 slot = 1 hour. Quantity always follows the number of hours chosen
      // (more hours -> quantity goes up, fewer hours -> quantity goes down).
      const hours = Math.max(1, Math.round(Number(booking.duration_minutes || 0) / 60));
      this.syncCartQuantity(hours);
    },
    syncCartQuantity(hours) {
      // Parent (DeliveryShipping / checkout page) updates the cart items.
      this.$emit("hours-selected", hours);
    },
    onReleased() {
      this.booking = null;
    },
        onCleared() {
      this.booking = null;
      // quantity goes back to 1 (CartStep.setQuantityFromHours is already wired through DeliveryShipping)
      this.$emit("hours-selected", 1);
    },
    formatTime(value) {
      const match = String(value || "").match(/^(\d{1,2}):(\d{2})/);
      if (!match) return value || "";
      let hour = Number(match[1]);
      const minute = match[2];
      const suffix = hour >= 12 ? "pm" : "am";
      hour = hour % 12 || 12;
      return `${hour}:${minute} ${suffix}`;
    },
  },
};
</script>

<style scoped>
.booking-checkout {
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  padding-top: 20px;
}

.booking-checkout__info {
  align-items: flex-start;
  display: flex;
  font-size: 13px;
  gap: 10px;
  margin-top: 10px;
}

.booking-checkout__info-icon {
  color: #3b82f6;
  flex-shrink: 0;
  font-size: 18px;
}

.booking-checkout__option {
  align-items: center;
  cursor: pointer;
  display: flex;
  gap: 8px;
  margin: 14px 0 0;
}

.booking-checkout__option input {
  cursor: pointer;
  margin: 0;
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

.booking-checkout--rtl {
  direction: rtl;
  text-align: right;
}

.booking-checkout--rtl .booking-checkout__info,
.booking-checkout--rtl .booking-checkout__option,
.booking-checkout--rtl .booking-time-summary,
.booking-checkout--rtl .booking-time-summary__content,
.booking-checkout--rtl .booking-time-prompt {
  flex-direction: row-reverse;
  direction: ltr;
}

.booking-checkout--rtl .booking-checkout__info > span:last-child,
.booking-checkout--rtl .booking-checkout__option > span:last-child,
.booking-checkout--rtl .booking-time-summary__content > span:last-child,
.booking-checkout--rtl .booking-time-prompt > span:last-child {
  direction: rtl;
  text-align: right;
}
</style>