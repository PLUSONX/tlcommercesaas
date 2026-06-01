export function normalizeSectionProps(properties) {
  if (properties == null) return {};
  if (Array.isArray(properties)) return properties[0] || {};
  return properties;
}
