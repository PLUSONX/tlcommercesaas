const SHARE_FA_CLASS_MAP = {
  facebook: "fa-brands fa-facebook-f",
  twitter: "fa-brands fa-twitter",
  linkedin: "fa-brands fa-linkedin-in",
  pinterest: "fa-brands fa-pinterest",
  whatsapp: "fa-brands fa-whatsapp",
  gmail: "fa-brands fa-google",
  google: "fa-brands fa-google",
  email: "fa-solid fa-envelope",
  reddit: "fa-brands fa-reddit",
  telegramme: "fa-brands fa-telegram",
  telegram: "fa-brands fa-telegram",
  tumblr: "fa-brands fa-tumblr",
  vk: "fa-brands fa-vk",
  digg: "fa-brands fa-digg",
  instagram: "fa-brands fa-instagram",
  youtube: "fa-brands fa-youtube",
  tiktok: "fa-brands fa-tiktok",
  snapchat: "fa-brands fa-snapchat",
};

const shareSvg = (viewBox, path) =>
  `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${viewBox}" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="${path}"/></svg>`;

const SHARE_NETWORK_ALIASES = {
  fb: "facebook",
  "facebook-f": "facebook",
  wa: "whatsapp",
  telegram: "telegramme",
  google: "gmail",
  "linkedin-in": "linkedin",
  "pinterest-p": "pinterest",
  "x-twitter": "twitter",
  envelope: "email",
  "envelope-o": "email",
  mail: "email",
};

const SHARE_ICON_SVG_MAP = {
  facebook: shareSvg(
    "0 0 320 512",
    "M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.2 80 159.4v42.1H14v97.8H80z"
  ),
  twitter: shareSvg(
    "0 0 512 512",
    "M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
  ),
  linkedin: shareSvg(
    "0 0 448 512",
    "M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"
  ),
  pinterest: shareSvg(
    "0 0 496 512",
    "M496 256c0 137.991-111.043 248-248 248-25.876 0-50.463-3.858-73.925-11.123 10.154-16.41 22.546-41.94 27.77-59.324 3.446-11.497 17.68-60.017 17.68-60.017 9.299 17.736 36.451 33.364 65.271 33.364 85.981 0 144.311-78.466 144.311-176.64 0-76.416-64.672-146.56-163.2-146.56-114.592 0-172.16 82.176-172.16 151.168 0 41.6 22.208 93.312 57.728 109.76 5.376 2.496 8.192 1.376 9.408-3.776.896-3.904 5.632-22.784 7.808-31.616 2.496-9.6 1.536-13.056-5.44-21.504-15.232-18.432-24.768-41.984-24.768-67.328 0-54.656 41.472-107.392 111.872-107.392 60.992 0 94.464 37.184 94.464 90.048 0 67.712-30.464 114.816-70.272 114.816-21.888 0-38.208-18.112-32.96-40.32 6.272-26.496 18.432-55.104 18.432-74.304 0-17.152-9.216-31.488-28.288-31.488-22.4 0-40.448 23.168-40.448 54.272 0 19.776 6.656 33.184 6.656 33.184s-22.784 96.64-26.752 113.536c-4.48 19.008-2.624 45.568-.896 62.848C91.481 470.205 0 372.581 0 256 0 114.615 111.043 4 248 4s248 110.615 248 252z"
  ),
  whatsapp: shareSvg(
    "0 0 448 512",
    "M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 339.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.7-32.8-16.2-37.9-18-5.1-1.9-8.8-2.7-12.5 2.7-3.7 5.5-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.1 1.8-3.7.9-6.9-.5-9.7-1.4-2.7-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
  ),
  gmail: shareSvg(
    "0 0 488 512",
    "M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"
  ),
  email: shareSvg(
    "0 0 512 512",
    "M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"
  ),
  reddit: shareSvg(
    "0 0 512 512",
    "M201.5 305.5c-13.8 0-24.9-11.1-24.9-24.6 0-13.8 11.1-24.9 24.9-24.9 13.6 0 24.6 11.1 24.6 24.9 0 13.6-11.1 24.6-24.6 24.6zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-132.3-41.2c-9.4 0-17.7 3.9-23.8 10-22.4-15.5-52.6-25.5-86.1-26.6l17.4-80.3 55.4 11.8c.8 18.5 16.2 33.3 34.9 33.3 19.4 0 35.1-15.7 35.1-35.1s-15.7-35.1-35.1-35.1c-14.9 0-27.8 9.2-33.4 22.3l-61.2-13.1c-3-.8-6.1 1.3-6.9 4.4l-19.4 89.1c-33 1.2-62.8 11.1-85.1 26.5-6.1-6.3-14.7-10.2-24.1-10.2-34.9 0-46.3 46.9-14.4 62.8-1.1 5.2-1.7 10.6-1.7 16.2 0 56.6 64 102.5 142.8 102.5 79.1 0 143.1-45.9 143.1-102.5 0-5.5-.6-10.8-1.6-16.1 31.5-15.9 20-62.6-14.9-62.6zM310.4 305.5c-13.6 0-24.6-11.1-24.6-24.6 0-13.8 11-24.9 24.6-24.9 13.8 0 24.9 11.1 24.9 24.9 0 13.6-11.1 24.6-24.9 24.6z"
  ),
  telegramme: shareSvg(
    "0 0 496 512",
    "M248 8C111 8 0 119 0 256S111 504 248 504 496 393 496 256 385 8 248 8zm121.8 169.3l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"
  ),
  tumblr: shareSvg(
    "0 0 320 512",
    "M309.8 480.3c-13.6 14.5-50 31.7-97.4 31.7-120.8 0-147-88.8-147-140.6v-144H17.9c-5.5 0-10-4.5-10-10v-68c0-7.2 4.5-13.6 11.3-16 62.6-21.8 81.3-76 84.3-117.1.8-11 6.5-16.3 16.1-16.3h70.9c5.5 0 10 4.5 10 10v115.2h83c5.5 0 10 4.4 10 9.9v81.7c0 5.5-4.5 10-10 10h-83.4V360c0 34.2 15.7 50.8 47.8 50.8 12.9 0 27.8-2.3 38.8-7.8 6.2-3.1 12.9.3 14.9 6.8l22.1 62.5c1.8 5.2-.2 11.1-5.4 14z"
  ),
  vk: shareSvg(
    "0 0 448 512",
    "M31.5 63.5C0 95 0 145.7 0 247V265c0 101.3 0 152 31.5 183.5C63 480 113.7 480 215 480H233c101.3 0 152 0 183.5-31.5C448 417 448 366.3 448 265V247c0-101.3 0-152-31.5-183.5C385 32 334.3 32 233 32H215C113.7 32 63 32 31.5 63.5zM75.6 168.3h21.1c2 36.7 16.7 67.9 48.4 88.5c-12.3 6.4-26.5 21.9-32.7 33.4c-9.2 17-10.6 24.3-10.6 37.8c0 21.6 10.4 43.8 29.4 55.4c19.7 12 48.1 13.3 71.2 7.3c18.8-4.9 40.7-16.7 55.4-35.8c7.4 18.4 20.3 28.8 31.8 33.4c13.6 5.4 30.3 5.8 46.8 1.4c16.1-4.3 32.9-14.8 44.4-32.5c11.8-18.2 18.5-43.1 18.5-73.8c0-8.2-.1-16.3-.4-24.4h21.1V168.3H264.8v117.3c0 12.3-1.1 19.4-3.4 23.8c-2.2 4.2-6.3 6.5-12.1 6.5c-8.6 0-14.3-6.5-16.1-12.3c-1.5-4.9-1.8-11.4-1.8-19.6V168.3h-63.4c.2 8.4 .4 18.1 .4 30.6c0 12.9-.3 27.3-.9 37.6c-.6 10.3-1.5 18.4-2.8 24.3c-2.4 10.8-7.8 19.5-16.2 19.5c-11.1 0-18.1-9.9-18.1-24.9c0-8.1 1.5-16.7 4.8-27.9c9.8-32.9 26.8-69.9 32.4-85.1H75.6z"
  ),
  digg: shareSvg(
    "0 0 512 512",
    "M81.7 172.3H0v174.4h132.7V96h-51v76.3zm0 133.4H50.9v-89.4h30.8v89.4zm297.2-133.4v174.4h81.8v28.5h-81.8V416H512V172.3H378.9zm81.8 133.4h-30.8v-89.4h30.8v89.4zm-235.6 41h82.1v28.5h-82.1V416h133.3V172.3H225.1v174.4zm51.2-133.3h30.8v89.4h-30.8v-89.4zM153.3 96h51.3v51h-51.3V96zm0 76.3h51.3v174.4h-51.3V172.3z"
  ),
  instagram: shareSvg(
    "0 0 448 512",
    "M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
  ),
};

const LEGACY_ICON_CLASS_MAP = {
  "fa-facebook": "fa-brands fa-facebook-f",
  "fa-facebook-f": "fa-brands fa-facebook-f",
  "fa-facebook-square": "fa-brands fa-facebook-square",
  "fa-twitter": "fa-brands fa-twitter",
  "fa-x-twitter": "fa-brands fa-x-twitter",
  "fa-linkedin": "fa-brands fa-linkedin-in",
  "fa-linkedin-in": "fa-brands fa-linkedin-in",
  "fa-pinterest": "fa-brands fa-pinterest",
  "fa-pinterest-p": "fa-brands fa-pinterest-p",
  "fa-whatsapp": "fa-brands fa-whatsapp",
  "fa-google": "fa-brands fa-google",
  "fa-instagram": "fa-brands fa-instagram",
  "fa-youtube": "fa-brands fa-youtube",
  "fa-tiktok": "fa-brands fa-tiktok",
  "fa-snapchat": "fa-brands fa-snapchat",
  "fa-telegram": "fa-brands fa-telegram",
  "fa-reddit": "fa-brands fa-reddit",
  "fa-tumblr": "fa-brands fa-tumblr",
  "fa-vk": "fa-brands fa-vk",
  "fa-digg": "fa-brands fa-digg",
  "fa-envelope": "fa-solid fa-envelope",
  "fa-envelope-o": "fa-solid fa-envelope",
};

function normalizeNetworkToken(token) {
  if (!token) {
    return "";
  }

  let key = String(token).trim().toLowerCase();
  if (key.startsWith("fa-")) {
    key = key.slice(3);
  }

  return SHARE_NETWORK_ALIASES[key] || key;
}

export function shareNetworkKey(source) {
  if (typeof source === "string") {
    const key = String(source || "").trim().toLowerCase();
    return SHARE_NETWORK_ALIASES[key] || key;
  }

  const icon = String(source?.social_icon || "").trim();
  const title = String(source?.social_icon_title || "").trim();

  if (icon && isSocialIconHtml(icon)) {
    const classMatch = icon.match(/class=["']([^"']+)["']/i);
    if (classMatch) {
      return socialNetworkKey({ social_icon: classMatch[1], social_icon_title: title });
    }
  }

  if (icon) {
    const tokens = icon.split(/\s+/).filter(Boolean);
    const legacyPrefixes = new Set(["fa", "fab", "fas", "far", "fal", "fad"]);
    const iconToken =
      tokens.find((token) => token.startsWith("fa-") && !legacyPrefixes.has(token)) ||
      tokens.find((token) => !legacyPrefixes.has(token));

    if (iconToken) {
      return normalizeNetworkToken(iconToken);
    }
  }

  if (title) {
    return normalizeNetworkToken(title.replace(/\s+/g, "-"));
  }

  return "";
}

export function getSocialIconSvg(social) {
  const key = shareNetworkKey(social);
  return SHARE_ICON_SVG_MAP[key] || "";
}

export function isSocialIconHtml(icon) {
  return icon && String(icon).trim().startsWith("<");
}

export function normalizeSocialIconHtml(icon) {
  if (!icon) {
    return "";
  }

  return String(icon)
    .replace(/class="fa fa-facebook"/g, 'class="fa-brands fa-facebook-f"')
    .replace(/class="fa fa-whatsapp"/g, 'class="fa-brands fa-whatsapp"')
    .replace(/class="fa fa-twitter"/g, 'class="fa-brands fa-twitter"')
    .replace(/class="fa fa-linkedin"/g, 'class="fa-brands fa-linkedin-in"')
    .replace(/class="fa fa-pinterest"/g, 'class="fa-brands fa-pinterest"')
    .replace(/class="fa fa-google"/g, 'class="fa-brands fa-google"')
    .replace(/class="fa fa-instagram"/g, 'class="fa-brands fa-instagram"')
    .replace(/class="fa fa-youtube"/g, 'class="fa-brands fa-youtube"')
    .replace(/class="fa fa-envelope"/g, 'class="fa-solid fa-envelope"')
    .replace(/class="fa fa-reddit"/g, 'class="fa-brands fa-reddit"')
    .replace(/class="fa fa-telegram"/g, 'class="fa-brands fa-telegram"')
    .replace(/class="fa fa-tumblr"/g, 'class="fa-brands fa-tumblr"')
    .replace(/class="fa fa-vk"/g, 'class="fa-brands fa-vk"')
    .replace(/class="fa fa-digg"/g, 'class="fa-brands fa-digg"');
}

export function resolveSocialIconClass(social) {
  const icon = social?.social_icon;
  const networkFaClass = SHARE_FA_CLASS_MAP[shareNetworkKey(social)];

  if (!icon) {
    return networkFaClass || "fa-solid fa-share-nodes";
  }

  const raw = String(icon).trim();

  if (/fa-(brands|solid|regular|light|thin|duotone)\s/.test(raw)) {
    return raw;
  }

  if (networkFaClass) {
    return networkFaClass;
  }

  const tokens = raw.split(/\s+/).filter(Boolean);
  const legacyPrefixes = new Set(["fa", "fab", "fas", "far", "fal", "fad"]);
  const iconToken =
    tokens.find((token) => token.startsWith("fa-") && !legacyPrefixes.has(token)) ||
    tokens[tokens.length - 1];
  const key = iconToken.startsWith("fa-") ? iconToken : `fa-${iconToken}`;

  return LEGACY_ICON_CLASS_MAP[key] || "fa-solid fa-share-nodes";
}
