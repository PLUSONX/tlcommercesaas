import DefaultLayoutShell from "./DefaultLayoutShell.vue";
import SplitScreenLayoutShell from "./SplitScreenLayoutShell.vue";
import ModernLayoutShell from "./ModernLayoutShell.vue";

/** Map of activeLayout.type → shell component. Unknown types fall back to default. */
export const LAYOUT_SHELLS = {
  default: DefaultLayoutShell,
  split_screen: SplitScreenLayoutShell,
  modern: ModernLayoutShell,
};

export function resolveLayoutShell(layoutType) {
  return LAYOUT_SHELLS[layoutType] || LAYOUT_SHELLS.default;
}
