<template>
  <div :class="mtClass">
    <div class="light-bg" v-if="pageLoading">
      <skeleton style="height: 100vh;" class="w-100 pt-20"></skeleton>
      <!-- <skeleton height="100vh" class="w-100 pt-20"></skeleton> -->
    </div>

    <!-- <page-header :items="bItems" v-if="is_breadcrumb" :title="page.title" /> -->

    <builder-section :page="page" :sections="page_section" :widgets="page_builder_widgets" @section-loaded="loaded"
      v-if="active_pagebuilder && page.page_type == 'builder'" />

    <div class="pt-30 pt-lg-60 pb-60 light-bg" v-else :class="{
      'force-mobile-layout': forcedMobile,
      'mobile-content-wrapper': forcedMobile,
      'page-details--split': isSplitScreen && !isMobile,
    }">
      <div class="custom-container2">
        <div class="row">
          <div class="col-lg-12">
            <!-- Page Details -->
            <article class="post-details">
              <!-- Page Header -->
              <header class="entry-header mb-40" v-if="page.page_image != null">
                <div class="entry-thumbnail">
                  <img :src="cleanImage(page.page_image)" :alt="page.title" />
                </div>
              </header>
              <!-- End Page Header -->

              <!-- Page Content -->
              <div class="entry-content" v-html="page.content"></div>
              <!-- End Page Content -->
            </article>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { mapGetters } from "vuex";

const PageHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/PageHeader.vue")
);

const BuilderSection = defineAsyncComponent(() =>
  import("@/components/page-builder/BuilderSection.vue")
);

const axios = require("axios").default;
export default {
  name: "Products",
  components: {
    PageHeader,
    BuilderSection,
  },

  computed: {
    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    forcedMobile() {
      if (this.isSplitScreen && !this.isMobile) {
        return true;
      }
      return this.isMobile;
    },

    mtClass() {
      if (this.isSplitScreen) {
        return "mt-50";
      }
      return "mt-1";
    },
  },

  data() {
    return {
      pageTitle: "Page Details",
      bItems: [
        {
          text: "Home",
          href: "/",
        },
      ],
      pageLoading: true,
      is_breadcrumb: false,
      page: {},
      page_section: {},
      active_pagebuilder: false,
      page_builder_widgets: {},
    };
  },
  mounted() {
    this.getPageDetails();

  },
  methods: {
    cleanImage(img) {
      if (!img) return '';
      return img.replace(/^\/public/, '');
    },
    /**
     * Get page details
     */
    getPageDetails() {
      let fullUrl = this.$route.params.id;
      let slug = fullUrl;
      let splittedFullUrl = fullUrl.split("/");
      if (splittedFullUrl.length > 0) {
        slug = splittedFullUrl[splittedFullUrl.length - 1];
      }

      if (this.$route.params.child != undefined) {
        slug = this.$route.params.child;
      }

      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };
      axios
        .get("/api/theme/tlcommerce/v1/page/" + slug, {
          headers: headers,
        })
        .then((response) => {
          if (response.data.success) {
            this.page = response.data.page;
            this.bItems = response.data.breadCrumbs;
            if (this.page == null) {
              this.$router.push({ path: "/404" });
            }
            this.page_section = response.data.page_sections ?? {};
            this.active_pagebuilder = response.data.active_pagebuilder;
            this.page_builder_widgets =
              response.data.page_builder_widgets ?? {};
            this.is_breadcrumb = !(
              this.active_pagebuilder && this.page.page_type == "builder"
            );
            this.loaded();
          } else {
            this.$router.push({ path: "/404" });
          }
        })
        .catch((error) => {
          this.loaded();
        });
    },
    /**
     * Make Preloader False
     */
    loaded() {
      this.pageLoading = false;
    },
  },
};
</script>

<style scoped>
.force-mobile-layout .row > [class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.page-details--split {
  padding-top: 30px !important;
  padding-bottom: 30px !important;
}

.page-details--split .entry-thumbnail img {
  max-width: 100%;
  height: auto;
}

.page-details--split .entry-content :deep(img) {
  max-width: 100%;
  height: auto;
}
</style>
