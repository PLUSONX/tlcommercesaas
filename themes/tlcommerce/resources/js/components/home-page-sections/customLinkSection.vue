<template>
  <template v-if="isSplitScreen && !isMobile">
    <section
      class="cta-section cta-section--split home-page-section"
      :class="{ 'cta-section--has-video': sectionStyleProps.video_url }"
      :style="splitScreenStyleObject"
    >
      <div class="px-3">
        <div class="row align-items-center">
          <div v-if="sectionStyleProps.video_url" class="col-12 d-flex justify-content-center mb-30">
            <video-card :src="sectionStyleProps.video_url"
              :btn-border-color="sectionStyleProps.play_btn_border_color"
              :btn-icon-color="sectionStyleProps.play_btn_color" />
          </div>
          <div v-if="sectionStyleProps.cta_image" class="col-12 mb-30">
            <div class="cta-image position-relative text-center">
              <img :src="cleanImage" alt="" />
            </div>
          </div>
          <div class="col-12">
            <div class="cta-content position-relative text-white text-center">
              <span class="d-inline-block section_title" v-if="sectionStyleProps.meta_title">
                {{ sectionStyleProps.meta_title }}
              </span>
              <h2 class="text-white section_title">{{ sectionStyleProps.title }}</h2>
              <p class="mb-3 section_title" v-if="sectionStyleProps.featured_title">
                {{ $t(sectionStyleProps.featured_title) }}
              </p>
              <router-link
                v-if="ctaLink?.kind === 'router'"
                :to="ctaLink.to"
                class="text-white btn-underline section_btn"
              >
                {{ viewAllLabel }}
              </router-link>
              <a
                v-else-if="ctaLink?.kind === 'external'"
                :href="ctaLink.href"
                target="_blank"
                rel="noopener noreferrer"
                class="text-white btn-underline section_btn"
              >
                {{ viewAllLabel }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>

  <template v-else>
    <section class="cta-section home-page-section" :style="styleObject">
      <div class="custom-container2">
        <div class="row align-items-center">
          <div v-if="sectionStyleProps.video_url" class="col-lg-4 d-flex justify-content-center">
            <video-card :src="sectionStyleProps.video_url"
              :btn-border-color="sectionStyleProps.play_btn_border_color"
              :btn-icon-color="sectionStyleProps.play_btn_color" />
          </div>
          <div v-if="sectionStyleProps.cta_image" class="col-lg-4">
            <div class="cta-image position-relative text-center my-50 my-lg-0">
              <img :src="cleanImage" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="cta-content position-relative text-white text-center text-lg-start">
              <span class="d-inline-block section_title" v-if="sectionStyleProps.meta_title">
                {{ sectionStyleProps.meta_title }}
              </span>
              <h2 class="text-white section_title">{{ sectionStyleProps.title }}</h2>
              <p class="mb-3 section_title" v-if="sectionStyleProps.featured_title">
                {{ $t(sectionStyleProps.featured_title) }}
              </p>
              <router-link
                v-if="ctaLink?.kind === 'router'"
                :to="ctaLink.to"
                class="text-white btn-underline section_btn"
              >
                {{ viewAllLabel }}
              </router-link>
              <a
                v-else-if="ctaLink?.kind === 'external'"
                :href="ctaLink.href"
                target="_blank"
                rel="noopener noreferrer"
                class="text-white btn-underline section_btn"
              >
                {{ viewAllLabel }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
</template>
<script>
import { defineAsyncComponent } from "vue";
import { cleanMediaPath, normalizeSectionProps, sectionBgImageUrl, sectionViewAllLabel } from "@/utils/sectionProps";
import { resolveActionLink } from "@/utils/quizResultTokens";
import { mapGetters } from "vuex";
const VideoCard = defineAsyncComponent(() => import("../ui/VideoCard.vue"));
export default {
  name: "customLinkSection",
  components: {
    VideoCard,
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

    ctaLink() {
      return resolveActionLink(this.sectionStyleProps.link_url);
    },

    styleObject() {
      const p = this.sectionStyleProps;
      return {
        "--section-background-color": p.bg_color,
        "--section-background-image": sectionBgImageUrl(p.bg_image),
        "--section-background-image-position": p.background_position,
        "--section-background-image-size": p.background_size,
        "--section-background-image-repeat": p.background_repeat,
        "--section-padding": `${p.padding_top +
          "px " +
          p.padding_right +
          "px " +
          p.padding_bottom +
          "px " +
          p.padding_left +
          "px"
          }`,
        "--section-margin": `${p.margin_top +
          "px " +
          p.margin_right +
          "px " +
          p.margin_bottom +
          "px " +
          p.margin_left +
          "px"
          }`,
        "--title-color": p.title_color,
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

    splitScreenStyleObject() {
      return {
        ...this.styleObject,
        "--section-background-color": "transparent",
      };
    },

    cleanImage() {
      return cleanMediaPath(this.sectionStyleProps?.cta_image);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.section_title {
  color: var(--title-color) !important;
}

.section_btn {
  color: var(--button-color, #fff) !important;
  background-color: var(--button-background-color, var(--c1, #e62d04)) !important;
  border: var(--button-border, 0) !important;
  border-color: var(--button-border-color, transparent) !important;
  min-height: 32px;
  min-width: 80px;
}

.section_btn:hover {
  color: var(--button-hover-color, #fff);
  background-color: var(--button-hover-bg-color, var(--c1, #e62d04));
  border-color: var(--button-hover-border-color, transparent);
}

.cta-section {
  background-image: var(--section-background-image);
  background-color: var(--section-background-color);
  padding: var(--section-padding) !important;
  margin: var(--section-margin) !important;
  background-position: var(--section-background-image-position);
  background-size: var(--section-background-image-size);
  background-repeat: var(--section-background-image-repeat);
}

.cta-section--split {
  background-color: transparent !important;
  padding: 30px 0 !important;

  &:not(.cta-section--has-video) {
    background-image: none !important;
  }

  &.cta-section--has-video {
    background-size: cover;
    background-repeat: no-repeat;
  }

  &::before {
    display: none !important;
  }

  .cta-content {

    span,
    p {
      font-size: 14px;
      line-height: 20px;
    }

    h2 {
      font-size: 24px;
      line-height: 1.2;
      margin: 6px 0;
    }
  }

  .btn-underline {
    font-size: 18px;
  }

  .cta-image img {
    max-width: 100%;
    height: auto;
  }
}

.cta {
  &-section {
    padding: 134px 0;
    background-repeat: no-repeat;
    z-index: 99;

    @media (max-width: 991px) {
      padding: 50px 0;
    }

    @media (min-width: 992px) {
      background-size: cover;

      &::before {
        width: 50%;
        height: 100%;
        clip-path: polygon(0 0, 100% 0%, 100% 100%, 20% 100%);
      }
    }
  }

  &-content {

    span,
    p {
      font-size: 18px;
      line-height: 24px;
      font-weight: 500;
    }

    h2 {
      font-size: 42px;
      $lh: 1.19;
      line-height: $lh;
      margin: 6px 0;
    }

    .slide-price {
      margin-bottom: 6px;
    }
  }

  &-shape {
    opacity: 0.6;

    &.top {
      left: 55%;
      top: 0;
    }

    &.bottom {
      right: 0;
      bottom: 0;
    }
  }
}

.btn-underline {
  font-size: 24px;
  font-weight: bold;
  font-family: $title-font;

  &:hover {
    text-decoration: underline;
    opacity: 0.9;
  }
}
</style>
