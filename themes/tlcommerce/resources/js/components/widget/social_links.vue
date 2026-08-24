<template>
  <ul v-if="social_links.length" class="widget nav share-list gap-1">
    <li
      v-for="(social, index) in social_links"
      :key="`social-${index}`"
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
import {
  getSocialIconSvg,
  isSocialIconHtml,
  normalizeSocialIconHtml,
  resolveSocialIconClass,
} from "@/utils/socialIconHelpers.js";

export default {
  name: "social_links",
  props: {
    social_links: {
      type: Array,
      default: () => [],
    },
    styleTwo: {
      type: Boolean,
      default: false,
    },
    socialStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  computed: {
    linkClass() {
      return this.socialStyle.custom_social == 1
        ? "custom-icon-style btn-circle size-35"
        : "btn-circle bg-transparent size-35";
    },
  },
  methods: {
    getSocialIconSvg,
    isSocialIconHtml,
    normalizeSocialIconHtml,
    resolveSocialIconClass,
  },
};
</script>

<style scoped>
.share-list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  list-style: none;
  margin: 0;
  padding: 0;
}

.share-list :deep(a) {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.share-list :deep(.share-icon-svg) {
  display: flex;
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

.share-list :deep(.share-icon-svg svg) {
  width: 14px;
  height: 14px;
  display: block;
  fill: currentColor;
}

.share-list :deep(i),
.share-list :deep(.fa-brands),
.share-list :deep(.fa-solid) {
  font-size: 14px;
  line-height: 1;
}
</style>
