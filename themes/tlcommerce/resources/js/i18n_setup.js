
import { createI18n } from 'vue-i18n'
import axios from "axios";
import { safeGetItem } from "./utils/safeStorage";
import store from "./store";

const LOCALE_TIMEOUT_MS = 8000;
const bootLocale = safeGetItem("locale") || "en";

// Set immediately so API calls on first paint use the stored locale
// (loadLanguageAsync may still be fetching translation messages).
axios.defaults.headers.common["Accept-Language"] = bootLocale;

const i18n = createI18n({
    locale: bootLocale,
    fallbackLocale: "en",
    missingWarn: false, // This hides the "Not found" warnings
    fallbackWarn: false, // This hides warnings when it falls back to another language
    legacy: false,
    messages: {}

})

const loadedLanguages = [];
const languageMeta = {};

function applyDocumentDirection(language, langCode) {
    const html = document.documentElement;
    const isRtl = language?.is_rtl == 1;

    html.classList.toggle("rtl", isRtl);
    html.setAttribute("dir", isRtl ? "rtl" : "ltr");

    if (langCode) {
        html.setAttribute("lang", langCode);
    }

    store.commit("layout/SET_IS_RTL", isRtl);
}

function setI18nLanguage(lang) {
    i18n.global.locale.value = lang;
    axios.defaults.headers.common["Accept-Language"] = lang;
    document.documentElement.setAttribute("lang", lang);
    return lang;
}

export async function loadLanguageAsync(lang) {
    if (loadedLanguages.includes(lang)) {
        applyDocumentDirection(languageMeta[lang], lang);
        if (i18n.global.locale.value !== lang) {
            return Promise.resolve(setI18nLanguage(lang));
        }
        return Promise.resolve();
    }

    return axios.get(`/api/v1/locale/${lang}`, { timeout: LOCALE_TIMEOUT_MS })
        .then(response => {

            let messages = response.data.data;
            let language = response.data.language;

            if (language != null) {
                languageMeta[lang] = language;
                applyDocumentDirection(language, lang);
            }

            loadedLanguages.push(lang);
            i18n.global.setLocaleMessage(lang, messages);
            return Promise.resolve(setI18nLanguage(lang));
        })
        .catch(error => {
            console.error('=== Locale API Failed ===', error);
            applyDocumentDirection(null, lang);
            // Fail open so router navigation is never blocked forever
            return Promise.resolve(setI18nLanguage(lang));
        });
}

// export async function loadLanguageAsync(lang) {
//     console.log('---i18n');

//     if (loadedLanguages.includes(lang)) {
//         if (i18n.locale !== lang) {
//             return Promise.resolve(setI18nLanguage(lang));
//         }
//     }
//     return axios.get(`/api/v1/locale/${lang}`).then(response => {
//         let messages = response.data.data;
//         let language = response.data.language;
//         var html = document.querySelector("html");
//         html.className = language?.is_rtl == 1 ? "rtl" : "";
//         loadedLanguages.push(lang);
//         i18n.global.setLocaleMessage(lang, messages);
//         return Promise.resolve(setI18nLanguage(lang));
//     });
// }
export default i18n;