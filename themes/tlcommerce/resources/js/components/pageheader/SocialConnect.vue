<template>
  <ul v-if="socialLinks.length" class="social-connect nav share-list gap-1">
    <li
      v-for="(social, index) in socialLinks"
      :key="`social-connect-${index}`"
      class="social-link"
    >
      <a
        :class="linkClass"
        :href="social.social_icon_url"
        :title="social.social_icon_title || ''"
        target="_blank"
        rel="noopener noreferrer"
      >
        <span
          v-if="getSocialIconSvg(social)"
          class="share-icon-svg"
          v-html="getSocialIconSvg(social)"
          aria-hidden="true"
        />
        <span
          v-else-if="isSocialIconHtml(social.social_icon)"
          v-html="normalizeSocialIconHtml(social.social_icon)"
        />
        <i v-else :class="resolveSocialIconClass(social)" />
      </a>
    </li>
  </ul>
</template>

<script>
import axios from "axios";
import {
  getSocialIconSvg,
  isSocialIconHtml,
  normalizeSocialIconHtml,
  resolveSocialIconClass,
} from "@/utils/socialIconHelpers.js";

export default {
  name: "SocialConnect",
  data() {
    return {
      socialLinks: [],
      socialStyle: {},
    };
  },
  computed: {
    linkClass() {
      return this.socialStyle.custom_social == 1
        ? "custom-icon-style btn-circle size-35"
        : "btn-circle bg-transparent size-35";
    },
  },
  mounted() {
    this.fetchSocialLinks();
  },
  methods: {
    getSocialIconSvg,
    isSocialIconHtml,
    normalizeSocialIconHtml,
    resolveSocialIconClass,
    fetchSocialLinks() {
      axios
        .post("/api/theme/tlcommerce/v1/get-social-links")
        .then((response) => {
          if (response.data.success) {
            this.socialLinks = response.data.data || [];
            this.socialStyle = response.data.socialStyle || {};
          }
        })
        .catch(() => {
          this.socialLinks = [];
        });
    },
  },
};
</script>

<style scoped>
.social-connect {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 8px;
  list-style: none;
  margin: 0;
  padding: 0;
  width: 100%;
}

.social-connect :deep(a) {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.social-connect :deep(.share-icon-svg) {
  display: flex;
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

.social-connect :deep(.share-icon-svg svg) {
  width: 14px;
  height: 14px;
  display: block;
  fill: currentColor;
}

.social-connect :deep(i),
.social-connect :deep(.fa-brands),
.social-connect :deep(.fa-solid) {
  font-size: 14px;
  line-height: 1;
}
</style>
