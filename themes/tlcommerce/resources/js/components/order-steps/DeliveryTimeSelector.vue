<template>
  <div
    class="delivery-time-section"
    :class="{
      'col-12 mb-20': !isHomepageVariant,
      'delivery-time-section--homepage': isHomepageVariant,
      'delivery-time-section--rtl': isRtl,
    }"
  >
    <template v-if="!isHomepageVariant">
      <h5 class="mb-3">{{ $t("Delivery Time") }}:</h5>

      <div class="delivery-time-options">
        <label v-if="!isSchedulingRequired" class="d-flex align-items-center gap-2 radio-label mb-2">
          <input
            type="radio"
            name="delivery-time-mode"
            value="now"
            :checked="deliveryMode === 'now'"
            @change="selectMode('now')"
          />
          <span class="radio-text">{{ $t("Now") }}</span>
        </label>

        <label class="d-flex align-items-center gap-2 radio-label mb-2">
          <input
            type="radio"
            name="delivery-time-mode"
            value="scheduled"
            :checked="deliveryMode === 'scheduled'"
            @change="selectMode('scheduled')"
          />
          <span class="radio-text">{{ $t("Choose Delivery Time") }}</span>
        </label>
      </div>

      <div
        v-if="deliveryMode === 'scheduled' && !selectedSlot"
        class="delivery-time-prompt"
        role="button"
        tabindex="0"
        @click="openSlotModal(false)"
        @keyup.enter="openSlotModal(false)"
      >
        <span class="material-icons delivery-time-prompt__icon">schedule</span>
        <span>{{ $t("Please select your preferred delivery time") }}</span>
      </div>

      <div v-if="deliveryMode === 'scheduled' && selectedSlot" class="delivery-time-summary">
        <div class="delivery-time-summary__content">
          <span class="material-icons delivery-time-summary__icon">schedule</span>
          <span>{{ formatSelectedSlot(selectedSlot) }}</span>
        </div>
        <button type="button" class="btn_underline" @click.prevent="openSlotModal(true)">
          {{ $t("Change") }}
        </button>
      </div>

      <p v-if="scheduleError" class="text-danger validation-error mb-0 mt-2">{{ scheduleError }}</p>
    </template>

    <CModal
      scrollable
      alignment="center"
      :visible="slotModalVisible"
      size="md"
      :class="['delivery-schedule-modal', { 'delivery-schedule-modal--rtl': isRtl }]"
      @close.prevent="closeSlotModal"
    >
      <CModalHeader>
        <div class="delivery-modal-header">
          <div class="delivery-modal-header__title-row">
            <button
              v-if="isHomepageVariant && modalStep === 'schedule'"
              type="button"
              class="delivery-modal-back"
              @click.prevent="backToOptionsStep"
            >
              <span class="material-icons">{{ isRtl ? 'arrow_forward' : 'arrow_back' }}</span>
            </button>
            <CModalTitle>{{ modalTitle }}</CModalTitle>
          </div>
          <button class="btn-circle bg-black size-35" @click.prevent="closeSlotModal">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </div>
      </CModalHeader>
      <CModalBody ref="modalBody">
        <div v-if="isHomepageVariant && modalStep === 'options'" class="delivery-time-options delivery-time-options--modal">
          <label v-if="!isSchedulingRequired" class="d-flex align-items-center gap-2 radio-label mb-2">
            <input
              type="radio"
              name="delivery-time-mode-homepage"
              value="now"
              :checked="deliveryMode === 'now'"
              @change="selectHomepageMode('now')"
            />
            <span class="radio-text">{{ $t("Now") }}</span>
          </label>

          <label class="d-flex align-items-center gap-2 radio-label mb-2">
            <input
              type="radio"
              name="delivery-time-mode-homepage"
              value="scheduled"
              :checked="deliveryMode === 'scheduled'"
              @change="selectHomepageMode('scheduled')"
            />
            <span class="radio-text">{{ $t("Choose Delivery Time") }}</span>
          </label>

          <button
            v-if="deliveryMode === 'scheduled'"
            type="button"
            class="btn_underline mt-2"
            @click.prevent="goToScheduleStep(true)"
          >
            {{ selectedSlot ? $t("Change") : $t("Please select your preferred delivery time") }}
          </button>
        </div>

        <template v-else>
          <div v-if="slotsLoading" class="p-3 text-center">
            {{ $t("Loading") }}...
          </div>
          <div v-else-if="availableDates.length === 0" class="p-3 text-center text-muted">
            {{ $t("No delivery times available") }}
          </div>
          <div v-else class="delivery-schedule-scroll">
            <div class="delivery-calendar">
              <div class="delivery-calendar__nav">
                <button type="button" class="delivery-calendar__nav-btn" @click.prevent="prevMonth">
                  <span class="material-icons">{{ isRtl ? 'chevron_right' : 'chevron_left' }}</span>
                </button>
                <span class="delivery-calendar__month">{{ calendarMonthLabel }}</span>
                <button type="button" class="delivery-calendar__nav-btn" @click.prevent="nextMonth">
                  <span class="material-icons">{{ isRtl ? 'chevron_left' : 'chevron_right' }}</span>
                </button>
              </div>
              <div class="delivery-calendar__weekdays">
                <span v-for="day in weekdayLabels" :key="day">{{ day }}</span>
              </div>
              <div class="delivery-calendar__grid">
                <button
                  v-for="(cell, index) in calendarCells"
                  :key="index"
                  type="button"
                  class="delivery-calendar__day"
                  :class="{
                    'delivery-calendar__day--outside': !cell.inMonth,
                    'delivery-calendar__day--selectable': cell.isSelectable,
                    'delivery-calendar__day--selected': cell.isSelected,
                    'delivery-calendar__day--today': cell.isToday,
                  }"
                  :disabled="!cell.isSelectable"
                  @click.prevent="selectCalendarDate(cell.date)"
                >
                  {{ cell.dayNumber }}
                </button>
              </div>
            </div>

            <div ref="slotsPanel" class="delivery-slots-panel">
              <h6 class="delivery-slots-panel__title">{{ $t("Select Delivery Time") }}</h6>
              <p v-if="pendingDate" class="delivery-slots-panel__date text-muted">{{ pendingDateLabel }}</p>
              <p v-else class="delivery-slots-panel__hint text-muted">{{ $t("Select a date above") }}</p>
              <div v-if="pendingDate && slotsForPendingDate.length === 0" class="text-center text-muted py-3">
                {{ $t("No delivery times available") }}
              </div>
              <div v-else-if="pendingDate" class="delivery-time-slots-grid">
                <button
                  v-for="slot in slotsForPendingDate"
                  :key="slot.id"
                  type="button"
                  class="delivery-time-slot-btn"
                  :class="{ active: pendingSlot && pendingSlot.id === slot.id }"
                  @click.prevent="selectPendingSlot(slot)"
                >
                  {{ formatSlotDisplay(slot) }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </CModalBody>
      <CModalFooter v-if="showConfirmFooter" class="delivery-modal-footer">
        <button
          type="button"
          class="btn btn_fill w-100"
          :disabled="!pendingSlot?.id"
          @click.prevent="confirmSelection"
        >
          {{ $t("Confirm") }}
        </button>
      </CModalFooter>
    </CModal>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import {
  CModal,
  CModalHeader,
  CModalTitle,
  CModalBody,
  CModalFooter,
} from "@coreui/vue";
import { formatSlotDisplay } from "@/utils/deliveryTimeFormat";

export default {
  name: "DeliveryTimeSelector",
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
    variant: {
      type: String,
      default: "checkout",
      validator: (value) => ["checkout", "homepage"].includes(value),
    },
  },
  data() {
    return {
      deliveryMode: null,
      selectedSlot: null,
      slotModalVisible: false,
      slotsLoading: false,
      availableDates: [],
      scheduleError: "",
      slotsLoaded: false,
      scrollToSlotsOnLoad: false,
      calendarMonth: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
      pendingDate: null,
      pendingSlot: null,
      modalStep: "schedule",
    };
  },
  computed: {
    ...mapGetters("layout", ["isRtl"]),
    isHomepageVariant() {
      return this.variant === "homepage";
    },
    isSchedulingRequired() {
      return this.config?.delivery_scheduling_required == this.enums.status.ACTIVE;
    },
    availableDateSet() {
      return new Set(this.availableDates.map((group) => group.date));
    },
    weekdayLabels() {
      return ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    },
    calendarMonthLabel() {
      return this.calendarMonth.toLocaleDateString(undefined, {
        month: "long",
        year: "numeric",
      });
    },
    calendarCells() {
      const year = this.calendarMonth.getFullYear();
      const month = this.calendarMonth.getMonth();
      const firstOfMonth = new Date(year, month, 1);
      const startOffset = (firstOfMonth.getDay() + 6) % 7;
      const gridStart = new Date(year, month, 1 - startOffset);
      const todayStr = this.formatDateKey(new Date());

      return Array.from({ length: 42 }, (_, index) => {
        const date = new Date(gridStart);
        date.setDate(gridStart.getDate() + index);
        const dateKey = this.formatDateKey(date);

        return {
          date: dateKey,
          dayNumber: date.getDate(),
          inMonth: date.getMonth() === month,
          isSelectable: this.availableDateSet.has(dateKey),
          isSelected: this.pendingDate === dateKey,
          isToday: dateKey === todayStr,
        };
      });
    },
    slotsForPendingDate() {
      if (!this.pendingDate) {
        return [];
      }
      const group = this.availableDates.find((item) => item.date === this.pendingDate);
      return group?.slots || [];
    },
    pendingDateLabel() {
      if (!this.pendingDate) {
        return "";
      }
      const group = this.availableDates.find((item) => item.date === this.pendingDate);
      if (group?.label) {
        return group.label;
      }
      return this.formatDateLabel(this.pendingDate);
    },
    modalTitle() {
      if (this.isHomepageVariant && this.modalStep === "options") {
        return this.$t("Delivery Time");
      }
      return this.$t("Schedule Delivery");
    },
    showConfirmFooter() {
      if (this.isHomepageVariant) {
        return this.modalStep === "schedule";
      }
      return true;
    },
  },
  mounted() {
    this.restoreFromStore();
    if (this.isHomepageVariant) {
      return;
    }
    if (this.isSchedulingRequired) {
      this.deliveryMode = "scheduled";
      if (!this.selectedSlot) {
        this.$nextTick(() => this.openSlotModal(false));
      }
    }
  },
  methods: {
    formatSlotDisplay,
    formatDateKey(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    },
    formatDateLabel(dateKey) {
      const parts = dateKey.split("-");
      if (parts.length !== 3) {
        return dateKey;
      }
      const date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
      return date.toLocaleDateString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
      });
    },
    restoreFromStore() {
      const stored = this.$store.state.deliverySchedule;
      if (!stored) {
        return;
      }
      this.deliveryMode = stored.mode || null;
      this.selectedSlot = stored.slot || null;
    },
    persistToStore() {
      this.$store.dispatch("storeDeliverySchedule", {
        mode: this.deliveryMode,
        slot: this.selectedSlot,
      });
    },
    selectMode(mode) {
      this.scheduleError = "";
      this.deliveryMode = mode;
      if (mode === "now") {
        this.selectedSlot = null;
        this.persistToStore();
        return;
      }
      this.persistToStore();
      this.openSlotModal(false);
    },
    selectHomepageMode(mode) {
      this.scheduleError = "";
      this.deliveryMode = mode;
      if (mode === "now") {
        this.selectedSlot = null;
        this.persistToStore();
        this.closeSlotModal();
        return;
      }
      this.persistToStore();
      this.goToScheduleStep(false);
    },
    goToScheduleStep(fromExistingSelection = false) {
      this.deliveryMode = "scheduled";
      this.modalStep = "schedule";
      this.scrollToSlotsOnLoad = fromExistingSelection;

      if (fromExistingSelection && this.selectedSlot?.schedule_date) {
        this.pendingDate = this.selectedSlot.schedule_date;
        this.pendingSlot = { ...this.selectedSlot };
        this.setCalendarMonthForDate(this.pendingDate);
      } else {
        this.pendingDate = null;
        this.pendingSlot = null;
        this.setInitialCalendarMonth();
      }

      if (!this.slotsLoaded) {
        this.fetchAvailableSlots();
      } else if (this.scrollToSlotsOnLoad) {
        this.$nextTick(() => this.scrollToSlotsPanel());
      }
    },
    openEditModal() {
      this.restoreFromStore();
      this.modalStep = "options";
      this.pendingDate = null;
      this.pendingSlot = null;
      this.scrollToSlotsOnLoad = false;
      this.slotModalVisible = true;
    },
    backToOptionsStep() {
      this.modalStep = "options";
      this.pendingDate = null;
      this.pendingSlot = null;
      this.scrollToSlotsOnLoad = false;
    },
    openSlotModal(fromExistingSelection = false) {
      this.slotModalVisible = true;
      this.goToScheduleStep(fromExistingSelection);
    },
    closeSlotModal() {
      this.slotModalVisible = false;
      this.pendingDate = null;
      this.pendingSlot = null;
      this.scrollToSlotsOnLoad = false;
      if (this.isHomepageVariant) {
        this.modalStep = "options";
      }
    },
    setInitialCalendarMonth() {
      if (this.availableDates.length > 0) {
        this.setCalendarMonthForDate(this.availableDates[0].date);
        return;
      }
      const now = new Date();
      this.calendarMonth = new Date(now.getFullYear(), now.getMonth(), 1);
    },
    setCalendarMonthForDate(dateKey) {
      const parts = dateKey.split("-");
      if (parts.length !== 3) {
        return;
      }
      this.calendarMonth = new Date(Number(parts[0]), Number(parts[1]) - 1, 1);
    },
    fetchAvailableSlots() {
      this.slotsLoading = true;
      axios
        .post("/api/v1/ecommerce-core/delivery-schedule/available-slots")
        .then((response) => {
          if (response.data.success) {
            this.availableDates = response.data.data?.dates || [];
            this.slotsLoaded = true;
            if (!this.pendingDate) {
              this.setInitialCalendarMonth();
            }
            if (this.scrollToSlotsOnLoad) {
              this.$nextTick(() => this.scrollToSlotsPanel());
            }
          } else {
            this.availableDates = [];
          }
        })
        .catch(() => {
          this.availableDates = [];
        })
        .finally(() => {
          this.slotsLoading = false;
        });
    },
    prevMonth() {
      this.calendarMonth = new Date(
        this.calendarMonth.getFullYear(),
        this.calendarMonth.getMonth() - 1,
        1
      );
    },
    nextMonth() {
      this.calendarMonth = new Date(
        this.calendarMonth.getFullYear(),
        this.calendarMonth.getMonth() + 1,
        1
      );
    },
    selectCalendarDate(dateKey) {
      if (!this.availableDateSet.has(dateKey)) {
        return;
      }
      this.pendingDate = dateKey;
      this.pendingSlot = null;
      this.$nextTick(() => this.scrollToSlotsPanel());
    },
    scrollToSlotsPanel() {
      const panel = this.$refs.slotsPanel;
      if (!panel) {
        return;
      }

      const modalBody = this.$refs.modalBody?.$el || this.$refs.modalBody;
      if (modalBody && typeof modalBody.scrollTop === "number") {
        modalBody.scrollTo({
          top: panel.offsetTop - 8,
          behavior: "smooth",
        });
        return;
      }

      panel.scrollIntoView({ behavior: "smooth", block: "start" });
    },
    selectPendingSlot(slot) {
      this.pendingSlot = {
        id: slot.id,
        schedule_date: slot.schedule_date,
        start_time: slot.start_time,
        end_time: slot.end_time,
        label: slot.label,
        display: slot.display,
      };
    },
    confirmSelection() {
      if (!this.pendingSlot?.id) {
        return;
      }
      this.selectedSlot = { ...this.pendingSlot };
      this.deliveryMode = "scheduled";
      this.scheduleError = "";
      this.persistToStore();
      this.closeSlotModal();
    },
    formatSelectedSlot(slot) {
      if (!slot) {
        return "";
      }
      const dateLabel = slot.schedule_date
        ? this.formatDateLabel(slot.schedule_date)
        : "";
      const timePart = formatSlotDisplay(slot);
      if (dateLabel && timePart) {
        return `${dateLabel} · ${timePart}`;
      }
      return timePart || dateLabel;
    },
    validateSchedule() {
      this.scheduleError = "";

      if (this.isSchedulingRequired || this.deliveryMode === "scheduled") {
        if (this.deliveryMode !== "scheduled" || !this.selectedSlot?.id) {
          this.scheduleError = this.$t("Please select a delivery time slot.");
          return false;
        }
        return true;
      }

      if (!this.deliveryMode) {
        this.scheduleError = this.$t("Please select a delivery time option.");
        return false;
      }

      if (this.deliveryMode === "scheduled" && !this.selectedSlot?.id) {
        this.scheduleError = this.$t("Please select a delivery time slot.");
        return false;
      }

      this.persistToStore();
      return true;
    },
  },
};
</script>

<style lang="scss" scoped>
.delivery-time-section {
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  padding-top: 20px;
}

.delivery-time-section--homepage {
  border-top: none;
  padding-top: 0;
  margin: 0;
}

.delivery-time-prompt {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  background: #eef4ff;
  color: #3b6fd8;
  cursor: pointer;
}

.delivery-time-prompt__icon {
  font-size: 20px;
}

.delivery-time-summary {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  background: #f7f7f7;
}

.delivery-time-summary__content {
  display: flex;
  align-items: center;
  gap: 10px;
}

.delivery-time-options--modal {
  padding: 8px 0 4px;
}

:deep(.delivery-schedule-modal .modal-dialog) {
  max-width: 420px;
}

@media (max-width: 575px) {
  :deep(.delivery-schedule-modal) {
    &.modal.show {
      display: flex !important;
      align-items: center;
      justify-content: center;
    }

    .modal-dialog {
      margin-left: auto;
      margin-right: auto;
      max-width: calc(100% - 24px);
      width: 100%;
    }
  }
}

.delivery-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  gap: 12px;
}

.delivery-modal-header__title-row {
  display: flex;
  align-items: center;
  gap: 8px;
  min-width: 0;
}

.delivery-modal-back {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border: none;
  background: transparent;
  color: rgba(0, 0, 0, 0.55);
  cursor: pointer;
  padding: 0;
  flex-shrink: 0;

  .material-icons {
    font-size: 20px;
  }
}

.delivery-schedule-scroll {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.delivery-calendar__nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
}

.delivery-calendar__nav-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border: none;
  background: transparent;
  color: rgba(0, 0, 0, 0.45);
  cursor: pointer;
  padding: 0;

  .material-icons {
    font-size: 20px;
  }
}

.delivery-calendar__month {
  font-size: 16px;
  font-weight: 700;
  color: #111;
}

.delivery-calendar__weekdays {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
  margin-bottom: 4px;
  text-align: center;
  font-size: 12px;
  color: rgba(0, 0, 0, 0.45);
}

.delivery-calendar__grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
}

.delivery-calendar__day {
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  background: transparent;
  border-radius: 6px;
  font-size: 13px;
  color: rgba(0, 0, 0, 0.25);
  cursor: default;
  padding: 0;
}

.delivery-calendar__day--outside {
  color: rgba(0, 0, 0, 0.2);
}

.delivery-calendar__day--selectable {
  color: #111;
  cursor: pointer;
}

.delivery-calendar__day--selectable:hover {
  background: rgba(0, 0, 0, 0.04);
}

.delivery-calendar__day--selected {
  background: rgba(147, 130, 220, 0.15);
  border: 1px solid rgba(147, 130, 220, 0.6);
  color: #111;
  font-weight: 600;
}

.delivery-calendar__day--today:not(.delivery-calendar__day--selected) {
  font-weight: 600;
}

.delivery-slots-panel {
  padding-top: 4px;
}

.delivery-slots-panel__title {
  font-weight: 700;
  font-size: 15px;
  margin-bottom: 6px;
}

.delivery-slots-panel__date,
.delivery-slots-panel__hint {
  font-size: 13px;
  margin-bottom: 12px;
}

.delivery-time-slots-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.delivery-time-slot-btn {
  display: block;
  width: 100%;
  text-align: center;
  padding: 12px 10px;
  border: 1px solid rgba(0, 0, 0, 0.12);
  border-radius: 10px;
  background: #f5f5f5;
  cursor: pointer;
  font-size: 13px;
  line-height: 1.3;
}

.delivery-time-slot-btn.active,
.delivery-time-slot-btn:hover {
  border-color: rgba(147, 130, 220, 0.8);
  background: rgba(147, 130, 220, 0.12);
}

.delivery-modal-footer {
  border-top: none;
  padding-top: 0;
}

.delivery-modal-footer .btn {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.delivery-modal-footer .btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

@media (max-width: 575px) {
  .delivery-time-slots-grid {
    grid-template-columns: 1fr;
  }
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.delivery-time-section--rtl > h5,
.delivery-time-section--rtl .validation-error {
  direction: rtl;
  text-align: right;
}

.delivery-time-section--rtl .radio-label.d-flex {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-start;
  width: 100%;
}

.delivery-time-section--rtl .radio-text {
  direction: rtl;
  text-align: right;
  flex: 1 1 auto;
  min-width: 0;
}

.delivery-time-section--rtl .delivery-time-prompt {
  flex-direction: row-reverse;
  direction: ltr;
}

.delivery-time-section--rtl .delivery-time-prompt > span:not(.material-icons) {
  direction: rtl;
  text-align: right;
  flex: 1 1 auto;
}

.delivery-time-section--rtl .delivery-time-summary {
  flex-direction: row-reverse;
  direction: ltr;
}

.delivery-time-section--rtl .delivery-time-summary__content {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-end;
  min-width: 0;
}

.delivery-time-section--rtl .delivery-time-summary__content > span:not(.material-icons) {
  direction: rtl;
  text-align: right;
}

.delivery-time-section--rtl .delivery-time-summary .btn_underline {
  direction: rtl;
  unicode-bidi: plaintext;
}

.delivery-schedule-modal--rtl :deep(.modal-header),
.delivery-schedule-modal--rtl .delivery-modal-header {
  flex-direction: row-reverse;
  direction: ltr;
}

.delivery-schedule-modal--rtl .delivery-modal-header__title-row {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-end;
}

.delivery-schedule-modal--rtl :deep(.modal-title) {
  direction: rtl;
  text-align: right;
}

.delivery-schedule-modal--rtl .delivery-time-options--modal .radio-label.d-flex {
  flex-direction: row-reverse;
  direction: ltr;
  justify-content: flex-start;
  width: 100%;
}

.delivery-schedule-modal--rtl .delivery-time-options--modal .radio-text {
  direction: rtl;
  text-align: right;
  flex: 1 1 auto;
  min-width: 0;
}

.delivery-schedule-modal--rtl .delivery-time-options--modal .btn_underline {
  direction: rtl;
  text-align: right;
  display: block;
  width: 100%;
}

.delivery-schedule-modal--rtl .delivery-calendar__month,
.delivery-schedule-modal--rtl .delivery-slots-panel__title,
.delivery-schedule-modal--rtl .delivery-slots-panel__date,
.delivery-schedule-modal--rtl .delivery-slots-panel__hint {
  direction: rtl;
  text-align: right;
}

.delivery-schedule-modal--rtl .delivery-calendar__nav {
  flex-direction: row-reverse;
}

.delivery-schedule-modal--rtl .delivery-calendar__weekdays,
.delivery-schedule-modal--rtl .delivery-calendar__grid {
  direction: ltr;
}

.delivery-schedule-modal--rtl .p-3.text-center,
.delivery-schedule-modal--rtl .text-center.text-muted {
  direction: rtl;
}
</style>
