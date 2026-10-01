<template>
    <div class="studio-booking-selector">
        <div v-if="modalVisible" class="studio-modal-backdrop" @click.self="closeModal">
            <div class="studio-modal" :class="{ 'studio-modal--rtl': isRtl }" role="dialog" aria-modal="true">
                <div class="studio-modal__header">
                    <div>
                        <h4>{{ bookingTitle }}</h4>
                        <small>{{ bookingHint }}</small>
                    </div>
                    <button type="button" class="studio-modal__close" @click="closeModal">×</button>
                </div>

                <div class="studio-modal__body">
                    <div v-if="loading" class="studio-state">{{ loadingText }}</div>
                    <div v-else-if="errorMessage && !availableDates.length" class="studio-state studio-state--error">{{ errorMessage }}</div>
                    <template v-else>
                        <div v-if="errorMessage" class="studio-state studio-state--error">{{ errorMessage }}</div>
                        <div class="studio-calendar__nav">
                            <button type="button" @click="previousMonth">‹</button>
                            <strong>{{ monthLabel }}</strong>
                            <button type="button" @click="nextMonth">›</button>
                        </div>
                        <div class="studio-calendar__weekdays">
                            <span v-for="day in weekdays" :key="day">{{ day }}</span>
                        </div>
                        <div class="studio-calendar__grid">
                            <button
                                v-for="day in calendarDays"
                                :key="day.key"
                                type="button"
                                :class="dayClasses(day)"
                                :disabled="!day.selectable"
                                @click="selectDate(day.date)"
                            >{{ day.day }}</button>
                        </div>

                        <div v-if="selectedDate" class="studio-slots">
                            <div class="studio-slots__title">{{ selectedDateLabel }}</div>
                            <div v-if="selectedDateSlots.length" class="studio-slots__grid">
                                <button
                                    v-for="slot in selectedDateSlots"
                                    :key="slot.id"
                                    type="button"
                                    class="studio-slot"
                                    :class="{ active: isSlotSelected(slot) }"
                                    @click="toggleSlot(slot)"
                                >{{ slot.display || slot.label }}</button>
                            </div>
                            <div v-else class="studio-state">{{ noHoursText }}</div>
                        </div>

                        <div v-if="selectedSlots.length" class="studio-summary">
                            <div>
                                <strong>{{ summaryLabel }}</strong>
                                <span>{{ selectedDateLabel }} · {{ selectedStartLabel }} - {{ selectedEndLabel }}</span>
                            </div>
                            <strong>{{ selectedSlots.length }} {{ selectedSlots.length === 1 ? hourLabel : hoursLabel }}</strong>
                        </div>
                    </template>
                </div>

                <div class="studio-modal__footer">
                    <button type="button" class="btn btn-light" @click="closeModal">{{ cancelLabel }}</button>
                    <button type="button" class="btn long" :disabled="!selectedSlots.length || confirming" @click="confirmSelection">
                        {{ confirming ? confirmingLabel : confirmLabel }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

const STORAGE_KEY = 'tlcommerce_studio_booking_hold';
// Read by the checkout request (OrderRepository::attachStudioBookingForOrder).
const COOKIE_NAME = 'studio_booking_token';

export default {
    name: 'BookingTimeSelector',
    props: {
        config: Object,
        enums: Object,
        variant: { type: String, default: 'homepage' },
    },
    emits: ['confirmed', 'released'],
    data() {
        return {
            modalVisible: false,
            loading: false,
            confirming: false,
            errorMessage: '',
            availableDates: [],
            selectedDate: null,
            selectedSlots: [],
            currentBooking: null,
            calendarMonth: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
        };
    },
    computed: {
        ...mapGetters('layout', ['isRtl']),
        bookingTitle() { return this.localized('Book Now', 'احجز الحين'); },
        bookingHint() { return this.localized('Select a date and consecutive hourly slots.', 'اختر التاريخ والساعات المتتالية.'); },
        loadingText() { return this.localized('Loading available hours…', 'جاري تحميل الساعات المتاحة…'); },
        noHoursText() { return this.localized('No available hours for this date.', 'ماكو ساعات متاحة لهذا التاريخ.'); },
        summaryLabel() { return this.localized('Selected time', 'الوقت المختار'); },
        cancelLabel() { return this.localized('Cancel', 'إلغاء'); },
        confirmLabel() { return this.localized('Confirm', 'تأكيد'); },
        confirmingLabel() { return this.localized('Holding…', 'جاري الحجز المؤقت…'); },
        hourLabel() { return this.localized('Hour', 'ساعة'); },
        hoursLabel() { return this.localized('Hours', 'ساعات'); },
        monthLabel() {
            return this.calendarMonth.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
        },
        weekdays() {
            const base = new Date(2024, 0, 7);
            return Array.from({ length: 7 }, (_, i) => new Date(base.getTime() + i * 86400000).toLocaleDateString(undefined, { weekday: 'short' }));
        },
        dateMap() {
            return new Map(this.availableDates.map(item => [item.date, item]));
        },
        calendarDays() {
            const first = new Date(this.calendarMonth.getFullYear(), this.calendarMonth.getMonth(), 1);
            const last = new Date(this.calendarMonth.getFullYear(), this.calendarMonth.getMonth() + 1, 0);
            const offset = first.getDay();
            const total = Math.ceil((offset + last.getDate()) / 7) * 7;
            const days = [];
            for (let i = 0; i < total; i++) {
                const date = new Date(this.calendarMonth.getFullYear(), this.calendarMonth.getMonth(), i - offset + 1);
                const key = this.toDateKey(date);
                const currentMonth = date.getMonth() === this.calendarMonth.getMonth();
                days.push({ key, date: key, day: date.getDate(), currentMonth, selectable: currentMonth && this.dateMap.has(key) });
            }
            return days;
        },
        selectedDateSlots() {
            return this.dateMap.get(this.selectedDate)?.slots || [];
        },
        selectedDateLabel() {
            const entry = this.dateMap.get(this.selectedDate);
            return entry?.label || this.selectedDate || '';
        },
        selectedStartLabel() { return this.selectedSlots[0]?.start_time ? this.formatTime(this.selectedSlots[0].start_time) : ''; },
        selectedEndLabel() { return this.selectedSlots[this.selectedSlots.length - 1]?.end_time ? this.formatTime(this.selectedSlots[this.selectedSlots.length - 1].end_time) : ''; },
    },
    methods: {
        localized(en, ar) {
            const translated = this.$t(en);
            if (!this.isRtl) return translated;
            return /[\u0600-\u06FF]/.test(translated) ? translated : ar;
        },
        /* ---- storage + cookie (the cookie carries the hold token to checkout) ---- */
        persistBooking(booking) {
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(booking)); } catch (e) { /* ignore */ }
            try {
                let maxAge = 86400; // selection only; nothing is reserved until the order is placed
                if (booking.expires_at) {
                    const left = Math.floor((new Date(booking.expires_at).getTime() - Date.now()) / 1000);
                    if (!Number.isNaN(left)) maxAge = Math.max(left, 1);
                }
                document.cookie = `${COOKIE_NAME}=${encodeURIComponent(booking.booking_token)}; path=/; max-age=${maxAge}; SameSite=Lax`;
            } catch (e) { /* ignore */ }
        },
        clearBooking() {
            try { localStorage.removeItem(STORAGE_KEY); } catch (e) { /* ignore */ }
            try { document.cookie = `${COOKIE_NAME}=; path=/; max-age=0; SameSite=Lax`; } catch (e) { /* ignore */ }
        },
        /**
         * Opens the booking modal. The modal now ALWAYS stays open: if booking is
         * disabled or hours cannot be loaded, the reason is shown inside the modal.
         */
        async openEditModal() {
            this.modalVisible = true;
            this.errorMessage = '';
            await this.fetchAvailableSlots();
        },
        closeModal() {
            if (this.confirming) return;
            this.modalVisible = false;
            this.errorMessage = '';
            this.selectedSlots = [];
        },
        readStoredBooking() {
            try {
                const stored = localStorage.getItem(STORAGE_KEY);
                if (!stored) return null;

                const booking = JSON.parse(stored);
                if (!booking?.booking_token) return null;

                if (booking.expires_at) {
                    const expiresAt = new Date(booking.expires_at).getTime();
                    if (!Number.isNaN(expiresAt) && expiresAt <= Date.now()) {
                        this.clearBooking();
                        return null;
                    }
                }

                return booking;
            } catch (error) {
                this.clearBooking();
                return null;
            }
        },
        restoreCurrentBooking(booking) {
            this.currentBooking = booking || null;

            if (!booking?.schedule_date) {
                this.selectedDate = null;
                this.selectedSlots = [];
                return;
            }

            const dateEntry = this.availableDates.find(item => item.date === booking.schedule_date);
            if (!dateEntry) {
                this.selectedDate = null;
                this.selectedSlots = [];
                return;
            }

            this.setMonthForDate(booking.schedule_date);
            this.selectedDate = booking.schedule_date;

            this.selectedSlots = (dateEntry.slots || []).filter(slot =>
                slot.start_time < booking.end_time &&
                slot.end_time > booking.start_time
            );
        },
        async fetchAvailableSlots() {
            this.loading = true;
            this.errorMessage = '';

            try {
                const storedBooking = this.readStoredBooking();
                const response = await axios.post('/api/v1/ecommerce-core/studio-booking/available-slots', {
                    booking_token: storedBooking?.booking_token || null,
                }, { headers: { Accept: 'application/json' } });

                if (response.data?.success === false || response.data?.enabled === false) {
                    this.availableDates = [];
                    this.currentBooking = null;
                    this.selectedDate = null;
                    this.selectedSlots = [];
                    if (storedBooking) {
                        this.clearBooking();
                        this.$emit('released');
                    }
                    this.errorMessage = this.localized('Studio booking is currently unavailable.', 'حجز الاستوديو غير متاح حالياً.');
                    return;
                }

                this.availableDates = response.data?.data?.dates || [];

                const currentBooking = response.data?.current_booking || null;

                if (currentBooking) {
                    this.persistBooking(currentBooking);
                    this.restoreCurrentBooking(currentBooking);
                } else {
                    if (storedBooking) {
                        // The hold no longer exists on the server.
                        this.clearBooking();
                        this.$emit('released');
                    }
                    this.currentBooking = null;
                    this.selectedDate = null;
                    this.selectedSlots = [];

                    if (this.availableDates.length) {
                        this.setMonthForDate(this.availableDates[0].date);
                        this.selectDate(this.availableDates[0].date);
                    }
                }

                if (!this.availableDates.length) {
                    this.errorMessage = this.localized('No studio hours are available right now.', 'ما فيه ساعات استوديو متاحة حالياً.');
                }
            } catch (error) {
                this.availableDates = [];
                this.currentBooking = null;
                this.errorMessage = this.localized('Unable to load studio hours.', 'تعذر تحميل ساعات الاستوديو.');
            } finally {
                this.loading = false;
            }
        },
        setMonthForDate(key) {
            const parts = String(key).split('-');
            if (parts.length === 3) this.calendarMonth = new Date(Number(parts[0]), Number(parts[1]) - 1, 1);
        },
        previousMonth() { this.calendarMonth = new Date(this.calendarMonth.getFullYear(), this.calendarMonth.getMonth() - 1, 1); },
        nextMonth() { this.calendarMonth = new Date(this.calendarMonth.getFullYear(), this.calendarMonth.getMonth() + 1, 1); },
        selectDate(date) {
            if (!this.dateMap.has(date)) return;
            this.selectedDate = date;
            this.selectedSlots = [];
        },
        toggleSlot(slot) {
            if (!this.selectedSlots.length) {
                this.selectedSlots = [slot];
                return;
            }

            const index = this.selectedSlots.findIndex(item => item.id === slot.id);
            if (index >= 0) {
                // Clicking a selected slot trims the range at that slot.
                // Clicking the first slot therefore clears the selection.
                this.selectedSlots = this.selectedSlots.slice(0, index);
                return;
            }

            const last = this.selectedSlots[this.selectedSlots.length - 1];
            if (slot.start_time === last.end_time) {
                this.selectedSlots = [...this.selectedSlots, slot];
                return;
            }

            const first = this.selectedSlots[0];
            if (slot.end_time === first.start_time) {
                this.selectedSlots = [slot, ...this.selectedSlots];
                return;
            }

            this.selectedSlots = [slot];
        },
        isSlotSelected(slot) { return this.selectedSlots.some(item => item.id === slot.id); },
        dayClasses(day) {
            return {
                'studio-calendar__day': true,
                'outside': !day.currentMonth,
                'selectable': day.selectable,
                'selected': day.date === this.selectedDate,
            };
        },
        async confirmSelection() {
            if (!this.selectedSlots.length) return;
            this.confirming = true;
            this.errorMessage = '';

            try {
                const first = this.selectedSlots[0];
                const last = this.selectedSlots[this.selectedSlots.length - 1];
                const bookingToken = this.currentBooking?.booking_token || this.readStoredBooking()?.booking_token || null;

                const response = await axios.post('/api/v1/ecommerce-core/studio-booking/hold', {
                    schedule_date: this.selectedDate,
                    start_time: first.start_time,
                    end_time: last.end_time,
                    booking_token: bookingToken,
                }, { headers: { Accept: 'application/json' } });

                if (!response.data?.success) {
                    throw new Error(response.data?.message || 'Unable to hold the selected hours.');
                }

                const booking = response.data.data;
                this.currentBooking = booking;
                this.persistBooking(booking);
                this.$emit('confirmed', booking);
                this.modalVisible = false;
                this.selectedSlots = [];
            } catch (error) {
                const message = error?.response?.data?.message || error?.message || this.localized('The selected hours are no longer available.', 'الساعات المختارة لم تعد متاحة.');
                await this.fetchAvailableSlots();
                // fetchAvailableSlots resets the message, so show the hold error afterwards.
                this.errorMessage = message;
            } finally {
                this.confirming = false;
            }
        },
        formatTime(value) {
            const match = String(value || '').match(/^(\d{1,2}):(\d{2})/);
            if (!match) return value || '';
            let h = Number(match[1]);
            const m = match[2];
            const suffix = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return `${h}:${m} ${suffix}`;
        },
        toDateKey(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        },
    },
};
</script>

<style scoped>
.studio-modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1080;display:flex;align-items:center;justify-content:center;padding:12px}.studio-modal{width:100%;max-width:430px;max-height:90vh;overflow:auto;background:#fff;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.2)}.studio-modal__header{display:flex;justify-content:space-between;gap:14px;padding:18px 20px 12px;border-bottom:0}.studio-modal__header h4{margin:0;font-size:16px;font-weight:700;color:#222}.studio-modal__header small{display:block;margin-top:6px;color:rgba(0,0,0,.55);font-size:11px;line-height:1.45}.studio-modal__close{border:0;background:#20212c;color:#fff;border-radius:50%;width:34px;height:34px;line-height:30px;font-size:24px;cursor:pointer;flex:0 0 auto}.studio-modal__body{padding:10px 20px 18px}.studio-modal__footer{display:flex;justify-content:flex-end;gap:10px;padding:12px 20px 18px;border-top:1px solid rgba(0,0,0,.06)}.studio-calendar__nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}.studio-calendar__nav button{border:0;background:transparent;width:30px;height:30px;font-size:24px;color:#555}.studio-calendar__weekdays,.studio-calendar__grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px}.studio-calendar__weekdays{color:rgba(0,0,0,.45);font-size:11px;text-align:center;margin-bottom:4px}.studio-calendar__day{height:34px;border:0;border-radius:6px;background:transparent;color:rgba(0,0,0,.2);font-size:13px}.studio-calendar__day.selectable{color:#111;cursor:pointer}.studio-calendar__day.selectable:hover{background:rgba(0,0,0,.04)}.studio-calendar__day.selected{background:rgba(147,130,220,.15);border:1px solid rgba(147,130,220,.6);font-weight:600}.studio-slots{padding-top:14px}.studio-slots__title{font-size:14px;font-weight:700;margin-bottom:8px}.studio-slots__grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.studio-slot{border:1px solid rgba(0,0,0,.12);background:#f5f5f5;border-radius:9px;padding:11px 8px;font-size:12px;cursor:pointer}.studio-slot.active{background:rgba(147,130,220,.12);border-color:rgba(147,130,220,.8)}.studio-summary{display:flex;justify-content:space-between;gap:10px;margin-top:14px;padding:12px;border-radius:9px;background:#f7f7f7;font-size:12px}.studio-summary span{display:block;margin-top:3px;color:#666}.studio-state{text-align:center;padding:24px 8px;color:#666;font-size:13px}.studio-state--error{color:#b42318}.studio-modal--rtl{direction:rtl}.studio-modal--rtl .studio-modal__header{flex-direction:row-reverse}.studio-modal--rtl .studio-modal__footer{justify-content:flex-start}.studio-modal--rtl .studio-calendar__grid,.studio-modal--rtl .studio-calendar__weekdays{direction:ltr}@media(max-width:575px){.studio-modal{max-width:calc(100% - 8px)}}
</style>
