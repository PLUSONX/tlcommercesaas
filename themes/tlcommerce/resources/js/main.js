import { createApp } from "vue";
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
import vSelect from "vue-select";
import ToastPlugin from 'vue-toast-notification';
import NotFound from "./components/global/NotFound.vue"
import VueSocialSharing from 'vue-social-sharing'

const CHUNK_RELOAD_KEY = "tlc_chunk_reload_v219";

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

function isChunkLoadError(err) {
  const msg = err && (err.message || String(err));
  return /Loading chunk|ChunkLoadError|Failed to fetch dynamically imported module|Importing a module script failed/i.test(
    msg || ""
  );
}

function reloadOnceForChunkError() {
  try {
    if (!sessionStorage.getItem(CHUNK_RELOAD_KEY)) {
      sessionStorage.setItem(CHUNK_RELOAD_KEY, "1");
      window.location.reload();
      return true;
    }
  } catch (e) {
    // sessionStorage may be blocked
  }
  return false;
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
  app.use(VueSocialSharing);
  app.component("base-icon-svg", BaseIconSvg);
  app.component("base-file-input", BaseFileInput);
  app.component("section-title", SectionTitle);
  app.component("the-logo", TheLogo);
  app.component("the-currency", Currency);
  app.component("v-select", vSelect);
  app.component('the-not-found', NotFound);
  app.component('skeleton', Skeleton);
  app.mount("#app");
} catch (err) {
  console.error("[tlcommerce] boot failed", err);
  showBootFallback(err && err.message);
}

//Import coreui Styles
import 'bootstrap/dist/css/bootstrap.css'
import "@coreui/coreui/dist/css/coreui.min.css";

//Toast notification
import 'vue-toast-notification/dist/theme-sugar.css';


//Import Google Icons Style
import "./assets/css/google-icons.css";

//Import Main Style
import "./assets/sass/app.scss";
