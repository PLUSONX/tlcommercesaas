/**
 * Format delivery slot times for storefront time-slot / shipping UI.
 * Example: 15:45 → "3:45 pm", range → "3:45 pm - 6:45 pm"
 */

function parseTimeParts(time) {
  if (time == null || time === "") {
    return null;
  }

  const match = String(time).trim().match(/^(\d{1,2}):(\d{2})(?::\d{2})?/);
  if (!match) {
    return null;
  }

  const hours = Number(match[1]);
  const minutes = Number(match[2]);
  if (Number.isNaN(hours) || Number.isNaN(minutes) || hours > 23 || minutes > 59) {
    return null;
  }

  return { hours, minutes };
}

export function formatTime12h(time) {
  const parts = parseTimeParts(time);
  if (!parts) {
    return time == null ? "" : String(time);
  }

  const period = parts.hours >= 12 ? "pm" : "am";
  let hour12 = parts.hours % 12;
  if (hour12 === 0) {
    hour12 = 12;
  }

  const minutes = String(parts.minutes).padStart(2, "0");
  return `${hour12}:${minutes} ${period}`;
}

export function formatTimeRange12h(start, end) {
  const startLabel = formatTime12h(start);
  const endLabel = formatTime12h(end);
  if (!startLabel && !endLabel) {
    return "";
  }
  if (!startLabel) {
    return endLabel;
  }
  if (!endLabel) {
    return startLabel;
  }
  return `${startLabel} - ${endLabel}`;
}

export function formatSlotDisplay(slot) {
  if (!slot) {
    return "";
  }

  const range = formatTimeRange12h(slot.start_time, slot.end_time);
  if (!range) {
    return slot.display || "";
  }

  if (slot.label) {
    return `${slot.label} · ${range}`;
  }

  return range;
}
