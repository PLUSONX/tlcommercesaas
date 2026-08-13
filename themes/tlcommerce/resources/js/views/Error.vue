<template>
  <div class="d-flex align-items-center justify-content-center" :class="isFeaturePaneLayout
      ? 'error-page--feature-pane'
      : 'min-vh-100 vw-100 bg-light'
    ">
    <div class="container">
      <div :class="style404.custom_404 == 1
          ? 'content-404 bg-white text-center custom-style'
          : 'content-404 bg-white text-center'
        ">
        <h1 class="text-danger">
          <span v-if="style404.custom_404 != 1">Page Not Found</span>
          <span v-else>{{ style404.page_404_title }}</span>
        </h1>
        <div class="img-404 mt-30 mb-30" v-if="style404.custom_404 != 1">
          <!-- <img src="/public/images/404.png" alt="404 Image" /> -->
          <img src="images/404.png" alt="404 Image" />
        </div>
        <div class="img-404 mt-30 mb-30" v-else>
          <img :src="style404.image" alt="404 Image" />
        </div>

        <router-link to="/" :class="style404.custom_404 == 1 ? 'custom-button-style' : ''">
          <strong class="pr-2">Back to Home</strong>
          <i class="fa fa-arrow-right"></i>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
export default {
  layout: "empty",
  head() {
    return {
      title: "404",
    };
  },
  data() {
    return {
      style404: {},
      scTimer: 0,
      scY: 0,
    };
  },
  computed: {
    ...mapGetters("layout", ["isFeaturePaneLayout"]),
  },
  mounted() {
    this.ge404Style();
  },
  methods: {
    /**
     * Get back to top style
     */
    ge404Style() {
      axios
        .get("/api/theme/tlcommerce/v1/get-404-page-style")
        .then((response) => {
          if (response.data.success) {
            this.style404 = response.data.style;
          }
        })
        .catch((error) => {
          this.style404 = {};
        });
    },
  },
};
</script>

<style lang="scss">
.content-404 {
  padding: 100px;
}

.img-404 img {
  max-width: 100%;
  height: auto;
}

.error-page--feature-pane {
  width: 100%;
  min-height: 50vh;
  padding: 24px 16px;

  .content-404 {
    padding: 32px 16px;
    width: 100%;
  }
}

@media (max-width: 575px) {
  .content-404 {
    padding: 50px;
  }

  .error-page--feature-pane .content-404 {
    padding: 24px 12px;
  }
}
</style>
