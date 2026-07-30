import "./utils/assetVersionPurge";
import { createApp, defineAsyncComponent } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import BaseIconSvg from "./components/global/BaseIconSvg.vue";
import SectionTitle from "./components/global/SectionTitle.vue";
import TheLogo from "./components/global/TheLogo.vue";
import Currency from "./components/global/Currency.vue"
import Skeleton from "./components/skeleton/Skeleton.vue"
import BaseFileInput from "./components/global/BaseFileInput.vue"
import i18n from "./i18n_setup";
import ToastPlugin from 'vue-toast-notification';
import NotFound from "./components/global/NotFound.vue"
import { isChunkLoadError, reloadOnceForChunkError } from "./utils/chunkLoadRecovery";
import { loadLanguageAsync } from "./i18n_setup";
import { safeGetItem } from "./utils/safeStorage";

const VSelect = defineAsyncComponent(() =>
  import("vue-select").then((m) => m.default)
);

function showBootFallback(message) {
  const el = document.getElementById("app");
  if (!el || el.childElementCount > 0) {
    return;
  }
  el.innerHTML =
    '<div style="padding:24px;font-family:sans-serif;text-align:center;color:#333">' +
    "<p>Something went wrong loading this store.</p>" +
    '<p style="font-size:12px;color:#888">' +
    String(message || "Unknown error") +
    "</p>" +
    '<button type="button" onclick="location.reload()" style="margin-top:12px;padding:8px 16px">Reload</button>' +
    "</div>";
}

window.addEventListener("error", (event) => {
  if (isChunkLoadError(event.error || event.message)) {
    if (reloadOnceForChunkError()) {
      return;
    }
  }
  console.error("[tlcommerce] window.error", event.error || event.message);
});

window.addEventListener("unhandledrejection", (event) => {
  if (isChunkLoadError(event.reason)) {
    if (reloadOnceForChunkError()) {
      return;
    }
  }
  console.error("[tlcommerce] unhandledrejection", event.reason);
});

const app = createApp(App);

app.config.errorHandler = (err, instance, info) => {
  console.error("[tlcommerce] vue error", err, info);
  if (isChunkLoadError(err)) {
    reloadOnceForChunkError();
  }
};

try {
  app.use(store);
  app.use(router);
  app.use(i18n);
  app.use(ToastPlugin, {
    position: 'top-right',
    duration: 3500,
    dismissible: true,
    pauseOnHover: true,
  });
  app.component("base-icon-svg", BaseIconSvg);
  app.component("base-file-input", BaseFileInput);
  app.component("section-title", SectionTitle);
  app.component("the-logo", TheLogo);
  app.component("the-currency", Currency);
  app.component("v-select", VSelect);
  app.component('the-not-found', NotFound);
  app.component('skeleton', Skeleton);
  app.mount("#app");

  import("vue-social-sharing")
    .then(({ default: VueSocialSharing }) => {
      app.use(VueSocialSharing);
    })
    .catch((err) => {
      console.warn("[tlcommerce] vue-social-sharing load failed", err);
    });

  const bootLang = safeGetItem("locale") || "en";
  loadLanguageAsync(bootLang).catch(() => {});
} catch (err) {
  console.error("[tlcommerce] boot failed", err);
  showBootFallback(err && err.message);
}

// CSS loaded from master.blade.php — see themes/tlcommerce/public/css/
