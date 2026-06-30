const STYLE_TAG_REGEX = /<style[^>]*>([\s\S]*?)<\/style>/gi;
const FIRST_HTML_TAG_REGEX = /<([a-z][\w-]*)[\s>/]/i;
const CSS_SELECTOR_REGEX = /[\.\#\@][\w-]/;

function looksLikeCss(text) {
  if (!text || !text.includes("{")) {
    return false;
  }

  return CSS_SELECTOR_REGEX.test(text);
}

/**
 * Summernote may strip <style> tags but leave the CSS body as plain text
 * before the first HTML element.
 */
export function extractOrphanedLeadingCss(html) {
  if (!html || typeof html !== "string") {
    return null;
  }

  const trimmed = html.trim();
  if (!trimmed || trimmed.startsWith("<")) {
    return null;
  }

  const firstTagMatch = trimmed.match(FIRST_HTML_TAG_REGEX);
  if (!firstTagMatch || firstTagMatch.index === undefined || firstTagMatch.index === 0) {
    return null;
  }

  const leading = trimmed.slice(0, firstTagMatch.index).trim();
  const rest = trimmed.slice(firstTagMatch.index).trim();

  if (!looksLikeCss(leading)) {
    return null;
  }

  return {
    cssText: leading,
    htmlWithoutStyles: rest,
  };
}

/**
 * Extract embedded <style> blocks from CMS HTML content.
 * Browsers do not apply <style> inserted via v-html / innerHTML.
 */
export function extractPageContentStyles(html) {
  if (!html || typeof html !== "string") {
    return { htmlWithoutStyles: html || "", cssText: "" };
  }

  const cssChunks = [];
  let match;

  while ((match = STYLE_TAG_REGEX.exec(html)) !== null) {
    if (match[1]) {
      cssChunks.push(match[1].trim());
    }
  }

  let htmlWithoutStyles = html.replace(STYLE_TAG_REGEX, "").trim();
  let cssText = cssChunks.join("\n\n");

  if (!cssText) {
    const orphaned = extractOrphanedLeadingCss(htmlWithoutStyles);
    if (orphaned) {
      cssText = orphaned.cssText;
      htmlWithoutStyles = orphaned.htmlWithoutStyles;
    }
  }

  return {
    htmlWithoutStyles,
    cssText,
  };
}

/**
 * Inject page content CSS into document.head (or update existing node).
 */
export function applyPageContentStyles(styleId, cssText) {
  if (!styleId) {
    return;
  }

  const existing = document.getElementById(styleId);

  if (!cssText) {
    if (existing) {
      existing.remove();
    }
    return;
  }

  const styleEl = existing || document.createElement("style");
  styleEl.id = styleId;
  styleEl.type = "text/css";
  styleEl.textContent = cssText;

  if (!existing) {
    document.head.appendChild(styleEl);
  }
}

/**
 * Remove injected page content CSS (SPA route change cleanup).
 */
export function removePageContentStyles(styleId) {
  if (!styleId) {
    return;
  }

  const existing = document.getElementById(styleId);
  if (existing) {
    existing.remove();
  }
}

/**
 * Split styles from HTML and inject them under a stable element id.
 */
export function preparePageContentForRender(html, styleId) {
  const { htmlWithoutStyles, cssText } = extractPageContentStyles(html);
  applyPageContentStyles(styleId, cssText);

  return {
    htmlWithoutStyles,
    cssText,
    hasCustomStyles: cssText.length > 0,
  };
}
