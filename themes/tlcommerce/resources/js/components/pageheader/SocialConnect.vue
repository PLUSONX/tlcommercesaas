<template>
  <ul v-if="socialLinks.length" class="social-connect nav share-list gap-1">
    <li v-for="(social, index) in socialLinks" :key="`social-connect-${index}`">
      <a
        :class="
          socialStyle.custom_social == 1
            ? 'custom-icon-style btn-circle size-35'
            : 'btn-circle bg-transparent size-35'
        "
        :href="social.social_icon_url"
        :title="social.social_icon_title || ''"
        target="_blank"
        rel="noopener noreferrer"
      >
        <i :class="'fa ' + social.social_icon"></i>
      </a>
    </li>
  </ul>
</template>

<script>
import axios from "axios";

export default {
  name: "SocialConnect",
  data() {
    return {
      socialLinks: [],
      socialStyle: {},
    };
  },
  mounted() {
    this.fetchSocialLinks();
  },
  methods: {
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
</style>
