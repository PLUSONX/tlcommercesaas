/**
 * Multi-select variant segment helpers (duplicate option IDs allowed).
 */

export function getSelectedOptionIds(attr, selectedVariant) {
  const key = String(attr.id);
  const segment = (selectedVariant || "")
    .split("/")
    .filter(Boolean)
    .find((part) => part.split(":")[0] === key);

  if (!segment) {
    return [];
  }

  return (segment.split(":")[1] || "").split(",").filter(Boolean);
}

export function getOptionSelectionCount(attr, option, selectedVariant) {
  const optionId = String(option.id);
  return getSelectedOptionIds(attr, selectedVariant).filter((id) => id === optionId)
    .length;
}

export function selectedOptionCount(attr, selectedVariant) {
  return getSelectedOptionIds(attr, selectedVariant).length;
}

export function canAddOptionSelection(attr, selectedVariant) {
  const limit = parseInt(attr.multi_select_limit, 10);
  if (!limit || limit <= 0) {
    return true;
  }
  return selectedOptionCount(attr, selectedVariant) < limit;
}

function setSelectedOptionIds(attr, selectedIds, selectedVariant) {
  const key = String(attr.id);
  const parts = (selectedVariant || "")
    .split("/")
    .filter(Boolean)
    .filter((part) => part.split(":")[0] !== key);

  if (selectedIds.length > 0) {
    parts.push(`${key}:${selectedIds.join(",")}`);
  }

  return parts.join("/");
}

export function addOptionSelection(attr, option, selectedVariant) {
  const limit = parseInt(attr.multi_select_limit, 10);
  const selectedIds = getSelectedOptionIds(attr, selectedVariant);
  if (limit > 0 && selectedIds.length >= limit) {
    return { selectedVariant, added: false };
  }

  selectedIds.push(String(option.id));
  return {
    selectedVariant: setSelectedOptionIds(attr, selectedIds, selectedVariant),
    added: true,
  };
}

export function removeOneOptionSelection(attr, option, selectedVariant) {
  const selectedIds = getSelectedOptionIds(attr, selectedVariant);
  const optionId = String(option.id);
  const index = selectedIds.indexOf(optionId);
  if (index < 0) {
    return { selectedVariant, removed: false };
  }

  selectedIds.splice(index, 1);
  return {
    selectedVariant: setSelectedOptionIds(attr, selectedIds, selectedVariant),
    removed: true,
  };
}

export function findInvalidMultiSelectAttribute(attributes, selectedVariant) {
  return (
    (attributes || []).find((attr) => {
      return (
        attr.multi_select &&
        selectedOptionCount(attr, selectedVariant) !==
          parseInt(attr.multi_select_limit, 10)
      );
    }) || null
  );
}

export function validateMultiSelectSelections(attributes, selectedVariant) {
  return findInvalidMultiSelectAttribute(attributes, selectedVariant) === null;
}

export function stripMultiSelectSegments(attributes, selectedVariant) {
  const multiSelectIds = (attributes || [])
    .filter((attr) => attr.multi_select)
    .map((attr) => String(attr.id));

  return (selectedVariant || "")
    .split("/")
    .filter(Boolean)
    .filter((segment) => !multiSelectIds.includes(segment.split(":")[0]))
    .join("/");
}

export function applySingleOptionSelection(attr, option, selectedVariant) {
  const key = String(attr.id);
  const parts = (selectedVariant || "")
    .split("/")
    .filter(Boolean);

  let found = false;
  const next = parts.map((part) => {
    const [choiceId] = part.split(":");
    if (String(choiceId) === key) {
      found = true;
      return `${choiceId}:${option.id}`;
    }
    return part;
  });

  if (!found) {
    next.push(`${key}:${option.id}`);
  }

  return next.join("/");
}
