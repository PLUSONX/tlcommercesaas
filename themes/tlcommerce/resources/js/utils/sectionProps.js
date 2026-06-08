export function normalizeSectionProps(properties) {
  if (properties == null) return {};
  if (Array.isArray(properties)) return properties[0] || {};
  return properties;
}

export function sectionViewAllLabel(properties, t) {
  const title = normalizeSectionProps(properties).btn_title;
  const trimmed = typeof title === "string" ? title.trim() : title;
  return trimmed || t("View All");
}
