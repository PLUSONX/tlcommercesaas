function capitalizeFirst(str) {
  if (!str) {
    return "";
  }
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatValuePart(valuePart) {
  const values = valuePart
    .split(",")
    .map((value) => value.trim())
    .filter(Boolean);

  if (!values.length) {
    return "";
  }

  const counts = new Map();
  for (const value of values) {
    counts.set(value, (counts.get(value) || 0) + 1);
  }

  const seen = new Set();
  const parts = [];

  for (const value of values) {
    if (seen.has(value)) {
      continue;
    }
    seen.add(value);

    const count = counts.get(value);
    const display = capitalizeFirst(value);
    parts.push(count > 1 ? `${display} x${count}` : display);
  }

  return parts.join(", ");
}

/**
 * Format a cart variant label for display, collapsing repeated multi-select values.
 *
 * @param {string|number|null|undefined} variant
 * @returns {string}
 */
export function formatVariantDisplay(variant) {
  if (variant == null || variant === "") {
    return "";
  }

  const segments = String(variant).split("/").filter(Boolean);

  return segments
    .map((segment) => {
      const colonIndex = segment.indexOf(":");
      if (colonIndex < 0) {
        return capitalizeFirst(segment.trim());
      }

      const attrName = segment.slice(0, colonIndex).trim();
      const valuePart = segment.slice(colonIndex + 1);

      return `${capitalizeFirst(attrName)}: ${formatValuePart(valuePart)}`;
    })
    .join(" | ");
}
