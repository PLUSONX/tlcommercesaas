export function normalizeMultilineText(raw) {
  if (raw == null || raw === "") return "";
  let text = String(raw);
  if (typeof document !== "undefined") {
    const el = document.createElement("textarea");
    el.innerHTML = text;
    text = el.value;
  } else {
    text = text
      .replace(/&nbsp;/gi, " ")
      .replace(/&#160;/g, " ")
      .replace(/&amp;/g, "&")
      .replace(/&lt;/g, "<")
      .replace(/&gt;/g, ">")
      .replace(/&quot;/g, '"');
  }
  text = text.replace(/<\s*br\s*\/?>/gi, "\n");
  text = text.replace(/<\/\s*p\s*>/gi, "\n\n");
  text = text.replace(/<\/\s*div\s*>/gi, "\n\n");
  text = text.replace(/<[^>]+>/g, "");
  const lines = text
    .split(/\r\n|\r|\n/)
    .map((line) => line.replace(/[ \t\u00a0]+/g, " ").trim())
    .filter((line) => line !== "");
  return lines.join("\n\n").trim();
}

export function escapeHtml(text) {
  return String(text ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

export function formatMultilineHtml(text) {
  const escaped = escapeHtml(String(text ?? ""));
  return escaped.replace(/\n\n/g, "<br>").replace(/\n/g, "<br>");
}

export function formatAnswerDescription(raw) {
  if (raw == null || raw === "") return "";
  return formatMultilineHtml(normalizeMultilineText(raw));
}
