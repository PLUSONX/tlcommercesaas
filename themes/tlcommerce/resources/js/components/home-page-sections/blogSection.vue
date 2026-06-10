<template>
  <template v-if="isSplitScreen && !isMobile">
    <!-- Blog — split-screen -->
    <section
      :style="splitScreenCssVars"
      class="pb-15 pt-15 blog-section blog-section--split home-page-section force-mobile-layout mobile-content-wrapper"
      v-if="success"
    >
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title
              class="mb-30"
              :title="section_title"
              :titleColor="sectionStyleProps.title_color"
            />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link
              class="btn btn-sm rounded-0 mb-30 blog-section-btn"
              to="/blog"
            >
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <div class="row section-content-row">
          <div
            class="col-6"
            v-for="(blog, index) in blogs"
            :key="`blog-${index}`"
          >
            <blog-card :blog="blog" class="mb-30" />
          </div>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <!-- Blog -->
    <section
      :style="cssVars"
      class="pb-15 pt-15 blog-section home-page-section"
      v-if="success"
    >
      <div class="custom-container2">
        <div class="row align-items-center section-header-row">
          <div class="col-md-6">
            <section-title
              class="mb-30"
              :title="section_title"
              :titleColor="sectionStyleProps.title_color"
            />
          </div>
          <div class="col-md-6 text-md-end">
            <router-link
              class="btn btn-sm rounded-0 mb-30 blog-section-btn"
              to="/blog"
            >
              {{ viewAllLabel }}
            </router-link>
          </div>
        </div>

        <div class="row">
          <div
            class="col-lg-3 col-6"
            v-for="(blog, index) in blogs"
            :key="`blog-${index}`"
          >
            <blog-card :blog="blog" class="mb-30" />
          </div>
        </div>
      </div>
    </section>
  </template>
</template>
<script>
import { defineAsyncComponent } from "vue";
const BlogCard = defineAsyncComponent(() => import("../ui/BlogCard.vue"));
import axios from "axios";
import { normalizeSectionProps, sectionBgImageUrl, sectionViewAllLabel } from "@/utils/sectionProps";
import { mapGetters } from "vuex";
export default {
  name: "BlogSection",
  components: {
    BlogCard,
  },
  props: {
    content: {
      type: String,
      required: false,
    },
    properties: {
      type: Array,
      required: false,
    },
  },
  computed: {
    sectionStyleProps() {
      return normalizeSectionProps(this.properties);
    },

    viewAllLabel() {
      return sectionViewAllLabel(this.properties, this.$t);
    },

    cssVars() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": sectionBgImageUrl(p.bg_image),
        "--section-background-image-position": p.background_position,
        "--section-background-image-size": p.background_size,
        "--section-background-image-repeat": p.background_repeat,
        "--section-padding": `${
          p.padding_top +
          "px " +
          p.padding_right +
          "px " +
          p.padding_bottom +
          "px " +
          p.padding_left +
          "px"
        }`,
        "--section-margin": `${
          p.margin_top +
          "px " +
          p.margin_right +
          "px " +
          p.margin_bottom +
          "px " +
          p.margin_left +
          "px"
        }`,
        "--button-color": p.btn_color,
        "--button-background-color": p.btn_bg_color,
        "--button-border":
          p.btn_border != null ? p.btn_border + "px solid" : 0 + "px",
        "--button-border-color": p.btn_border_color,
        "--button-hover-border-color": p.btn_border_hover_color,
        "--button-hover-bg-color": p.btn_bg_hover_color,
        "--button-hover-color": p.btn_hover_color,
      };
    },

    ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

    splitScreenCssVars() {
      return {
        ...this.cssVars,
        "--section-background-color": "transparent",
        "--section-background-image": "none",
      };
    },
  },
  data() {
    const props = normalizeSectionProps(this.properties);
    return {
      blogs: [],
      success: false,
      section_title: props.title ? this.$t(props.title) : "",
    };
  },
  mounted() {
    this.getSectionBlogs();
  },
  methods: {
    getSectionBlogs() {
      const p = this.sectionStyleProps;
      axios
        .post("/api/theme/tlcommerce/v1/home-page-blogs-list", {
          quantity: p.number_of_blogs,
          content: p.content,
          category: p.category,
        })
        .then((response) => {
          if (response.data.success) {
            this.blogs = response.data.data ?? [];
            this.success = true;
          } else {
            this.success = false;
          }
        })
        .catch((error) => {
          this.success = false;
        });
    },
  },
};
</script>
<style scoped>
.blog-section-btn {
  color: var(--button-color, #fff);
  background-color: var(--button-background-color, var(--c1, #e62d04));
  border: var(--button-border, 0);
  border-color: var(--button-border-color, transparent);
  min-height: 32px;
  min-width: 80px;
}
.blog-section-btn:hover {
  color: var(--button-hover-color, #fff);
  background-color: var(--button-hover-bg-color, var(--c1, #e62d04));
  border-color: var(--button-hover-border-color, transparent);
}
.blog-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.blog-section--split {
  background-color: transparent !important;
  background-image: none !important;
}

.force-mobile-layout .d-none.d-lg-block,
.force-mobile-layout .d-lg-block {
  display: none !important;
}

.force-mobile-layout .d-block.d-lg-none,
.force-mobile-layout .d-lg-none {
  display: block !important;
}
</style>
