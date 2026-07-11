function parseHexColor(hex) {
  const raw = String(hex || "").trim().replace(/^#/, "");
  if (raw.length === 3) {
    return {
      r: parseInt(raw[0] + raw[0], 16),
      g: parseInt(raw[1] + raw[1], 16),
      b: parseInt(raw[2] + raw[2], 16),
    };
  }
  if (raw.length === 6) {
    return {
      r: parseInt(raw.slice(0, 2), 16),
      g: parseInt(raw.slice(2, 4), 16),
      b: parseInt(raw.slice(4, 6), 16),
    };
  }
  return null;
}

function relativeLuminance({ r, g, b }) {
  const toLinear = (c) => {
    const s = c / 255;
    return s <= 0.03928 ? s / 12.92 : ((s + 0.055) / 1.055) ** 2.4;
  };
  return 0.2126 * toLinear(r) + 0.7152 * toLinear(g) + 0.0722 * toLinear(b);
}

function colorsTooSimilar(a, b) {
  const c1 = parseHexColor(a);
  const c2 = parseHexColor(b);
  if (!c1 || !c2) return false;
  if (a.toLowerCase() === b.toLowerCase()) return true;
  const l1 = relativeLuminance(c1);
  const l2 = relativeLuminance(c2);
  return Math.abs(l1 - l2) < 0.08;
}

/**
 * Return textColor when it contrasts with background; otherwise fallback.
 */
export function readableColor(textColor, backgroundColor, fallback) {
  const text = String(textColor || "").trim();
  const bg = String(backgroundColor || "").trim();
  const fb = String(fallback || "#111111").trim();
  if (!text || !bg) return text || fb;
  if (colorsTooSimilar(text, bg)) return fb;
  return text;
}
