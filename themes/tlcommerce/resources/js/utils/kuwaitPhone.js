/**
 * Kuwait mobile numbers: 8 digits starting with 5, 6, or 9.
 * Accepts optional +965 / 965 / 00965 and strips spaces or dashes.
 */

export const KUWAIT_MOBILE_HINT = "5XXXXXXX";
export const KUWAIT_MOBILE_ERROR =
  "Please enter a valid Kuwait mobile number (8 digits starting with 5, 6, or 9).";

export function normalizeKuwaitMobile(value) {
  if (value == null) {
    return null;
  }

  let digits = String(value).replace(/\D/g, "");
  if (!digits) {
    return null;
  }

  if (digits.startsWith("00965")) {
    digits = digits.slice(5);
  } else if (digits.startsWith("965")) {
    digits = digits.slice(3);
  }

  if (digits.length === 8 && /^[569]/.test(digits)) {
    return digits;
  }

  return null;
}

export function isValidKuwaitMobile(value) {
  return normalizeKuwaitMobile(value) !== null;
}
