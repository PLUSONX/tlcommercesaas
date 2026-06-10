<template>
    <template v-if="isSplitScreen && !isMobile">
        <!-- Featured Product — split-screen layout -->
        <section
            class="cta-section cta-section--split home-page-section"
            :class="{ 'cta-section--has-video': properties.video_url }"
            :style="splitScreenStyleObject"
        >
            <div class="px-3">
                <div class="row align-items-center">
                    <div v-if="properties.video_url" class="col-12 d-flex justify-content-center mb-30">
                        <video-card :src="properties.video_url"
                            :btn-border-color="properties.play_button_border_color_c_"
                            :btn-icon-color="properties.play_button_color_c_" />
                    </div>
                    <div v-if="properties.cta_image" class="col-12 mb-30">
                        <div class="cta-image position-relative text-center">
                            <img :src="cleanImage" alt="Featured Image" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="cta-content position-relative text-white text-center">
                            <span class="d-inline-block section_title" v-if="properties.meta_title_t_">
                                {{ properties.meta_title_t_ }}
                            </span>
                            <h2 class="text-white section_title" v-if="properties.title_t_">
                                {{ properties.title_t_ }}
                            </h2>
                            <p class="mb-3 section_title" v-if="properties.paragraph_t_">
                                {{ properties.paragraph_t_ }}
                            </p>
                            <router-link v-if="properties.button_text_t_" :to="`/products/${properties.permalink}`"
                                class="text-white btn-underline section_btn">
                                {{ properties.button_text_t_ }}
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </template>

    <template v-else>
        <!-- Featured Product — default layout (original) -->
        <section class="cta-section home-page-section container" :style="styleObject">
            <div class="row align-items-center">
                <div v-if="properties.video_url" class="col-lg-4 d-flex justify-content-center">
                    <video-card :src="properties.video_url"
                        :btn-border-color="properties.play_button_border_color_c_"
                        :btn-icon-color="properties.play_button_color_c_" />
                </div>
                <div v-if="properties.cta_image" class="col-lg-4">
                    <div class="cta-image position-relative text-center my-50 my-lg-0">
                        <img :src="cleanImage" alt="Featured Image" />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cta-content position-relative text-white text-center text-lg-start">
                        <span class="d-inline-block section_title" v-if="properties.meta_title_t_">
                            {{ properties.meta_title_t_ }}
                        </span>
                        <h2 class="text-white section_title" v-if="properties.title_t_">
                            {{ properties.title_t_ }}
                        </h2>
                        <p class="mb-3 section_title" v-if="properties.paragraph_t_">
                            {{ properties.paragraph_t_ }}
                        </p>
                        <router-link v-if="properties.button_text_t_" :to="`/products/${properties.permalink}`"
                            class="text-white btn-underline section_btn">
                            {{ properties.button_text_t_ }}
                        </router-link>
                    </div>
                </div>
            </div>
        </section>
    </template>
</template>
<script>
import { defineAsyncComponent } from "vue";
import { mapGetters } from "vuex";
const VideoCard = defineAsyncComponent(() => import("../../ui/VideoCard.vue"));
export default {
    name: "FeaturedProduct",
    components: {
        VideoCard,
    },
    props: {
        properties: {
            type: Object,
            default: () => ({}),
        },
    },
    computed: {
        ...mapGetters("layout", ["isSplitScreen", "isMobile"]),

        cleanImage() {
            const img = this.properties?.cta_image;
            if (!img) return "";
            return img.startsWith("/public") ? img.slice(7) : img;
        },

        cleanBgImage() {
            const img = this.properties?.background_image;
            if (!img) return "";
            return img.startsWith("/public") ? img.slice(7) : img;
        },

        styleObject() {
            const p = this.properties || {};
            const styles = {};

            if (p.background_color) {
                styles["--section-background-color"] = p.background_color;
            }
            if (this.cleanBgImage) {
                styles["--section-background-image"] = `url(${this.cleanBgImage})`;
            }
            if (p.background_position) {
                styles["--section-background-image-position"] = p.background_position;
            }
            if (p.background_size) {
                styles["--section-background-image-size"] = p.background_size;
            }
            if (p.background_repeat) {
                styles["--section-background-image-repeat"] = p.background_repeat;
            }

            const paddingKeys = [
                "padding_top",
                "padding_right",
                "padding_bottom",
                "padding_left",
            ];
            if (paddingKeys.some((key) => p[key] != null && p[key] !== "")) {
                const px = (val) =>
                    val != null && val !== "" ? `${val}px` : "0px";
                styles["--section-padding"] = `${px(p.padding_top)} ${px(
                    p.padding_right
                )} ${px(p.padding_bottom)} ${px(p.padding_left)}`;
            }

            const marginKeys = [
                "margin_top",
                "margin_right",
                "margin_bottom",
                "margin_left",
            ];
            if (marginKeys.some((key) => p[key] != null && p[key] !== "")) {
                const px = (val) =>
                    val != null && val !== "" ? `${val}px` : "0px";
                styles["--section-margin"] = `${px(p.margin_top)} ${px(
                    p.margin_right
                )} ${px(p.margin_bottom)} ${px(p.margin_left)}`;
            }

            if (p.text_color_c_) {
                styles["--title-color"] = p.text_color_c_;
            }
            if (p.button_color_c_) {
                styles["--button-color"] = p.button_color_c_;
            }
            if (p.button_hover_color_c_) {
                styles["--button-hover-color"] = p.button_hover_color_c_;
            }

            return styles;
        },

        splitScreenStyleObject() {
            return {
                ...this.styleObject,
                "--section-background-color": "transparent",
            };
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../assets/sass/00-abstracts/01-variables";

.section_title {
    color: var(--title-color) !important;
}

.section_btn {
    color: var(--button-color) !important;
    background-color: var(--button-background-color) !important;
    border: var(--button-border) !important;
    border-color: var(--button-border-color) !important;
}

.section_btn:hover {
    color: var(--button-hover-color);
    background-color: var(--button-hover-bg-color);
    border-color: var(--button-hover-border-color);
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

/* Split-screen layout */
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

/* Default layout — original FeaturedProduct fallbacks */
.cta {
    &-section {
        padding: 80px 0;
        background-repeat: no-repeat;
        z-index: 99;

        @media (max-width: 991px) {
            padding: 60px 10px;
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
