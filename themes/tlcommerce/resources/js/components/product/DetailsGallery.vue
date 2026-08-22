<template>
  <div class="product-image-gallery" :class="{
    'is-loading': isLoading,
    'product-image-gallery--modern': modernLayout
  }">
    <div class="gallery-loader" v-show="isLoading">
      <div class="loader-spinner"></div>
    </div>

    <div class="gallery-content">
      <FsLightbox :toggler="toggler" :sources="zoomImages" :slide="slide" />
      <div class="carousel-wrapper">
        <!-- Modern desktop: main image + a visible rail containing every gallery image. -->
        <template v-if="modernLayout && !isActualMobile">
          <swiper
            :style="{
              '--swiper-navigation-color': '#6f4c3b',
              '--swiper-pagination-color': '#6f4c3b',
            }"
            :spaceBetween="10"
            :navigation="displayGalleryImages.length > 1"
            :pagination="displayGalleryImages.length > 1 ? { clickable: true } : false"
            :autoplay="displayGalleryImages.length > 1
              ? { delay: 2800, disableOnInteraction: true, pauseOnMouseEnter: true }
              : false"
            :rewind="displayGalleryImages.length > 1"
            :grabCursor="displayGalleryImages.length > 1"
            :observer="true"
            :observeParents="true"
            :modules="modules"
            class="mySwiper2 modern-desktop-main-swiper"
            @swiper="setMainSwiper"
            @slideChange="onMainSlideChange"
          >
            <swiper-slide
              v-for="(image, imageIndex) in displayGalleryImages"
              :key="`modern-main-${imageIndex}-${image.regular || image.video_link || image.thumbnail || ''}`"
              class="gallery-image"
            >
              <div v-if="image.type === 'image'" @click="openGalleryItem(imageIndex)">
                <v-lazy-image :src="cleanImage(image.regular)" />
              </div>

              <div
                v-else
                class="video-thumb modern-main-video"
                :style="{
                  backgroundImage: image.thumbnail ? `url(${cleanImage(image.thumbnail)})` : 'none',
                  backgroundColor: '#f7f8fa',
                }"
                @click="openGalleryItem(imageIndex)"
              ></div>
            </swiper-slide>
          </swiper>

          <!-- Desktop thumbnail rail: plain flex rail so every Gallery Image is rendered. -->
          <div
            v-if="displayGalleryImages.length > 1"
            class="modern-gallery-thumbs"
          >
            <button
              v-for="(image, imageIndex) in displayGalleryImages"
              :key="`modern-thumb-${imageIndex}-${image.regular || image.video_link || image.thumbnail || ''}`"
              type="button"
              class="modern-gallery-thumb"
              :class="{ 'modern-gallery-thumb--active': activeSlideIndex === imageIndex }"
              :aria-label="`${productName} ${imageIndex + 1}`"
              @click="goToGalleryImage(imageIndex)"
            >
              <img
                v-if="image.type === 'image'"
                :src="cleanImage(image.regular)"
                :alt="`${productName}-${imageIndex + 1}`"
              />
              <span
                v-else
                class="modern-gallery-thumb-video"
                :style="{
                  backgroundImage: image.thumbnail ? `url(${cleanImage(image.thumbnail)})` : 'none',
                }"
              >
                <span class="material-icons">play_arrow</span>
              </span>
            </button>
          </div>
        </template>

        <!-- Modern mobile: keep the working auto/manual Swiper behavior. -->
        <swiper
          v-else-if="modernLayout"
          :style="{
            '--swiper-navigation-color': '#fff',
            '--swiper-pagination-color': '#6f4c3b',
          }"
          :spaceBetween="10"
          :navigation="false"
          :pagination="{ clickable: true, dynamicBullets: displayGalleryImages.length > 7 }"
          :autoplay="displayGalleryImages.length > 1
            ? { delay: 2800, disableOnInteraction: true, pauseOnMouseEnter: true }
            : false"
          :rewind="displayGalleryImages.length > 1"
          :grabCursor="true"
          :observer="true"
          :observeParents="true"
          :modules="modules"
          class="mySwiper2"
          @swiper="setMainSwiper"
          @slideChange="onMainSlideChange"
        >
          <swiper-slide
            v-for="(image, imageIndex) in displayGalleryImages"
            :key="`${imageIndex}-${image.regular || image.video_link || image.thumbnail || ''}`"
            class="gallery-image"
          >
            <!--Image Thumbnail-->
            <div v-if="image.type === 'image'" @click="openGalleryItem(imageIndex)">
              <!-- <v-lazy-image :src="image.regular" /> -->
              <v-lazy-image :src="cleanImage(image.regular)" />
            </div>
            <!--End Image Thumbnail-->
            <!--Video Thumbnail-->
            <div
              v-if="image.type === 'video'"
              class="video-thumb"
              :style="{
                backgroundImage: `url(${image.thumbnail})`,
                backgroundColor: '#f7f8fa',
              }"
              :key="imageIndex"
              @click="openGalleryItem(imageIndex)"
            >
              <!-- <img
                src="themes/tlcommerce/assets/img/play-big.png"
                class="play-icon gallery-preview-panel__video-player"
                alt="video"
              /> -->
              <!-- <img
                src="/public/themes/tlcommerce/assets/img/play-big.png"
                class="play-icon gallery-preview-panel__video-player"
                alt="video"
              /> -->
            </div>
            <!--End Video thumbnail-->
          </swiper-slide>
        </swiper>

        <!-- Original gallery behavior for every non-Modern theme. -->
        <template v-else>
          <swiper
            :style="{
              '--swiper-navigation-color': '#fff',
              '--swiper-pagination-color': '#fff',
            }"
            :spaceBetween="10"
            :navigation="true"
            :thumbs="{ swiper: thumbsSwiper }"
            :modules="modules"
            class="mySwiper2"
          >
            <swiper-slide v-for="(image, imageIndex) in galleryImages" :key="imageIndex" class="gallery-image">
              <!--Image Thumbnail-->
              <div
                v-if="image.type === 'image'"
                @click="
                  () => {
                    slide = imageIndex + 1;
                    toggler = !toggler;
                  }
                "
              >
                <!-- <v-lazy-image :src="image.regular" /> -->
                <v-lazy-image :src="cleanImage(image.regular)" />
              </div>
              <!--End Image Thumbnail-->
              <!--Video Thumbnail-->
              <div
                v-if="image.type === 'video'"
                class="video-thumb"
                :style="{
                  backgroundImage: `url(${image.thumbnail})`,
                  backgroundColor: '#f7f8fa',
                }"
                :key="imageIndex"
                @click="
                  () => {
                    slide = imageIndex + 1;
                    toggler = !toggler;
                  }
                "
              >
                <!-- <img
                  src="themes/tlcommerce/assets/img/play-big.png"
                  class="play-icon gallery-preview-panel__video-player"
                  alt="video"
                /> -->
                <!-- <img
                  src="/public/themes/tlcommerce/assets/img/play-big.png"
                  class="play-icon gallery-preview-panel__video-player"
                  alt="video"
                /> -->
              </div>
              <!--End Video thumbnail-->
            </swiper-slide>
          </swiper>

          <swiper
            @swiper="setThumbsSwiper"
            :spaceBetween="10"
            :slidesPerView="5"
            :freeMode="true"
            :watchSlidesProgress="true"
            :modules="modules"
            class="mySwiper mt-10"
          >
            <swiper-slide v-for="(image, imageIndex) in galleryImages" :key="imageIndex" class="gallery-image">
              <img :src="cleanImage(image.regular)" v-if="image.type === 'image'" />
              <!-- <img :src="image.regular" v-if="image.type === 'image'" /> -->

              <div class="item-gallery__image-wrapper" v-else>
                <!-- <img
                  class="item-gallery__video-icon"
                  src="themes/tlcommerce/assets/img/play-show.png"
                  :alt="`product-gallery-image-${imageIndex}`"
                /> -->
                <!-- <img
                  class="item-gallery__video-icon"
                  src="/public/themes/tlcommerce/assets/img/play-show.png"
                  :alt="`product-gallery-image-${imageIndex}`"
                /> -->
              </div>
            </swiper-slide>
          </swiper>
        </template>
      </div>
      <!--Coupon collect area-->
      <div class="mt-30 d-flex flex-column align-items-center"
        v-if="!modernLayout && voucherList && voucherList.length > 0">
        <div class="coupon-wrap w-100">
          <div class="coupon p-2 text-center text-white" @click="showVoucherList = !showVoucherList">
            <h5 class="mb-0 text-white">
              {{ $t("Get The coupon Code Now") }}
            </h5>
            <p class="widget-collapse-toggle fz-12">
              <span>
                {{ $t("Available Offers") }}
              </span>
              <span class="material-icons fz-12"> expand_more </span>
            </p>
          </div>
          <ul v-if="showVoucherList" class="voucher-list list-unstyled">
            <li v-for="(voucher, index) in voucherList" :key="index">
              <div class="voucher-left">
                <div class="voucher-amount">
                  <sup v-if="voucher.discount_type == config.amount_type.flat">$</sup>{{ voucher.discount_amount
                  }}<sup v-if="voucher.discount_type == config.amount_type.percent">%</sup>
                </div>
                <div class="voucher-info">
                  <h6 v-if="voucher.minimum_spend_amount">
                    Orders over ${{ voucher.minimum_spend_amount }}
                  </h6>
                  <p>Expires: {{ voucher.expire_date }}</p>
                </div>
              </div>
              <div>
                <button class="btn btn-sm collect-btn" @click.prevent="collectCouponCode(voucher)">
                  {{ $t("Collect") }}
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!--End coupon collect area-->
      <!--Social media share options-->
      <ul v-if="!modernLayout" class="nav share-list justify-content-center my-3">
        <li class="social-link" v-for="network in networks" :key="network.network" :title="network.network">
          <ShareNetwork :network="network.network" :key="network.network" :url="url" :title="productName"
            :description="summary">
            <img v-if="isShareIconFile(network.icon) && !shareIconFailed[network.network]" class="rounded"
              :src="shareIconSrc(network.icon)" :alt="network.name" @error="onShareIconError(network.network)" />
            <span v-else-if="isShareIconHtml(network.icon)" v-html="network.icon"></span>
            <i v-else :class="shareIconFaClass(network)"></i>
          </ShareNetwork>
        </li>
      </ul>
      <!--End social media share options-->
    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import VLazyImage from "v-lazy-image";
// import required modules
import "swiper/css/pagination";
import { FreeMode, Navigation, Thumbs, Pagination, Autoplay } from "swiper";
import config from "../../config.js";
// Import Swiper styles
import "swiper/css";
import FsLightbox from "fslightbox-vue/v3";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/thumbs";
export default {
  name: "DetailsGallery",
  emits: ["ready"],
  components: {
    "v-lazy-image": VLazyImage,
    FsLightbox,
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [FreeMode, Navigation, Thumbs, Pagination, Autoplay],
    };
  },
  props: {
    galleryImages: {
      type: Array,
      required: true,
    },

    thumbnailImage: {
      type: [String, Object, Array],
      default: null,
    },

    voucherList: {
      type: Array,
      required: false,
    },

    productName: {
      type: String,
      default: "",
    },

    summary: {
      type: String,
      default: "",
    },

    url: {
      type: String,
      default: "",
    },

    networks: {
      type: Array,
      required: false,
    },

    modernLayout: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    isActualMobile() {
      return this.viewportWidth <= 768;
    },

    normalizedThumbnailUrl() {
      return this.resolveImageUrl(this.thumbnailImage);
    },

    displayGalleryImages() {
      // The saved product Thumbnail Image is shown in the Modern top header.
      // The gallery below contains Gallery Images only.
      return Array.isArray(this.galleryImages)
        ? this.galleryImages.filter(Boolean)
        : [];
    },

    zoomImages() {
      const images = [];

      for (let i = 0; i < this.displayGalleryImages.length; i++) {
        const item = this.displayGalleryImages[i];

        if (!item) continue;

        if (item.type === "image") {
          const imageUrl = this.resolveImageUrl(item.zoom || item.regular);
          if (imageUrl) {
            images.push(this.cleanImage(imageUrl));
          }
        } else {
          const videoUrl = this.resolveImageUrl(item.video_link);
          if (videoUrl) {
            images.push(this.cleanImage(videoUrl));
          }
        }
      }

      return images;
    },
  },
  // computed: {
  //   zoomImages() {
  //     let images = [];
  //     for (let i = 0; i < this.galleryImages.length; i++) {
  //       if (this.galleryImages[i].type == "image") {
  //         images.push(this.galleryImages[i].zoom);
  //       } else {
  //         images.push(this.galleryImages[i].video_link);
  //       }
  //     }
  //     return images;
  //   },
  // },
  data() {
    return {
      slide: 1,
      toggler: false,
      config,
      index: null,
      showVoucherList: false,
      thumbsSwiper: null,
      shareIconFailed: {},
      isLoading: true,
      viewportWidth: typeof window !== "undefined" ? window.innerWidth : 0,
      mainSwiper: null,
      activeSlideIndex: 0,
    };
  },
  mounted() {
    if (typeof window !== "undefined") {
      this.viewportWidth = window.innerWidth;
      window.addEventListener("resize", this.handleViewportResize);
    }
    this.startLoading();
  },
  beforeUnmount() {
    if (typeof window !== "undefined") {
      window.removeEventListener("resize", this.handleViewportResize);
    }
  },
  methods: {
    handleViewportResize() {
      this.viewportWidth = window.innerWidth;
    },

    openGalleryItem(index) {
      this.slide = index + 1;
      this.toggler = !this.toggler;
    },

    setMainSwiper(swiper) {
      this.mainSwiper = swiper;
      this.activeSlideIndex = swiper?.realIndex ?? swiper?.activeIndex ?? 0;
    },

    onMainSlideChange(swiper) {
      this.activeSlideIndex = swiper?.realIndex ?? swiper?.activeIndex ?? 0;
    },

    goToGalleryImage(index) {
      if (!this.mainSwiper) return;
      this.mainSwiper.slideTo(index);
      this.activeSlideIndex = index;
    },

    resolveImageUrl(value, depth = 0) {
      if (value == null || depth > 5) {
        return null;
      }

      if (typeof value === "string") {
        const trimmed = value.trim();
        if (!trimmed || /^\d+$/.test(trimmed)) {
          return null;
        }
        return trimmed;
      }

      if (Array.isArray(value)) {
        for (const item of value) {
          const resolved = this.resolveImageUrl(item, depth + 1);
          if (resolved) return resolved;
        }
        return null;
      }

      if (typeof value === "object") {
        const preferredKeys = [
          "regular",
          "zoom",
          "original",
          "large",
          "medium",
          "url",
          "path",
          "src",
          "image_path",
          "imagePath",
          "thumbnail_image_path",
          "thumbnailImagePath",
          "thumbnail_path",
          "thumbnailPath",
          "thumbnail_image_url",
          "thumbnailImageUrl",
          "thumbnail_url",
          "thumbnailUrl",
          "featured_image_path",
          "featuredImagePath",
          "image",
          "thumbnail_image",
          "thumbnailImage",
          "thumbnail",
          "featured_image",
          "featuredImage",
        ];

        for (const key of preferredKeys) {
          if (!Object.prototype.hasOwnProperty.call(value, key)) continue;
          const resolved = this.resolveImageUrl(value[key], depth + 1);
          if (resolved) return resolved;
        }
      }

      return null;
    },

    startLoading() {
      const minTime = new Promise((resolve) =>
        setTimeout(resolve, 600)
      );

      const firstImageReady = new Promise((resolve) => {
        const first =
          this.displayGalleryImages &&
          this.displayGalleryImages[0];

        if (!first || first.type !== "image") {
          return resolve();
        }

        this.$nextTick(() => {
          const img = this.$el.querySelector(".mySwiper2 img");

          if (!img) return resolve();

          if (img.complete && img.naturalWidth > 0) {
            return resolve();
          }

          img.addEventListener("load", resolve, {
            once: true,
          });

          img.addEventListener("error", resolve, {
            once: true,
          });
        });
      });

      const hardTimeout = new Promise((resolve) =>
        setTimeout(resolve, 4000)
      );

      Promise.race([
        Promise.all([minTime, firstImageReady]),
        hardTimeout,
      ]).then(() => {
        this.isLoading = false;
        this.$emit("ready");
      });
    },
    cleanImage(img) {
      const resolved = this.resolveImageUrl(img);
      if (!resolved) return '';
      return resolved.startsWith('/public') ? resolved.slice(7) : resolved;
    },

    isShareIconHtml(icon) {
      return icon && String(icon).trim().startsWith('<');
    },

    isShareIconFile(icon) {
      return icon && /\.(svg|png|jpe?g|gif|webp)$/i.test(String(icon).trim());
    },

    shareIconSrc(icon) {
      const filename = String(icon).trim().replace(/^\/?public\//, '');
      if (filename.startsWith('/') || filename.startsWith('http')) return filename;
      return `/themes/default/public/assets/social/${filename}`;
    },

    shareIconFaClass(network) {
      const map = {
        facebook: 'fa fa-facebook',
        twitter: 'fa fa-twitter',
        linkedin: 'fa fa-linkedin',
        pinterest: 'fa fa-pinterest',
        whatsapp: 'fa fa-whatsapp',
        gmail: 'fa fa-google',
        email: 'fa fa-envelope',
        reddit: 'fa fa-reddit',
        telegramMe: 'fa fa-telegram',
        tumblr: 'fa fa-tumblr',
        vk: 'fa fa-vk',
        digg: 'fa fa-digg',
      };
      return map[network.network] || 'fa fa-share-alt';
    },

    onShareIconError(networkKey) {
      this.shareIconFailed = { ...this.shareIconFailed, [networkKey]: true };
    },

    /**
     * Collect coupon code
     */
    async collectCouponCode(item) {
      let text = item.code;
      var input = document.createElement("input");
      input.setAttribute("value", text);
      document.body.appendChild(input);
      input.select();
      var result = document.execCommand("copy");
      document.body.removeChild(input);
      this.$toast.success(this.$t("Coupon collected successfully"));
      return result;
    },

    /**
     * downloads gallery image of a product
     */
    setThumbsSwiper(swiper) {
      this.thumbsSwiper = swiper;
    },
  },

  // methods: {
  //   /**
  //    * Collect coupon code
  //    */
  //   async collectCouponCode(item) {
  //     let text = item.code;
  //     var input = document.createElement("input");
  //     input.setAttribute("value", text);
  //     document.body.appendChild(input);
  //     input.select();
  //     var result = document.execCommand("copy");
  //     document.body.removeChild(input);
  //     this.$toast.success(this.$t("Coupon collected successfully"));
  //     return result;
  //   },
  //   /**
  //    * downloads gallery image of a product
  //    */
  //   setThumbsSwiper(swiper) {
  //     this.thumbsSwiper = swiper;
  //   },
  // },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.navSlider {
  padding: 0 10px;

  .gallery-image {
    padding: 5px;

    img {
      margin: auto;
      width: 60px;
      height: 70px;
      background-image: linear-gradient(gray 100%, transparent 0);
      object-fit: cover;
    }
  }
}

.slider-custom-nav {
  button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 24px;
    width: 24px;
    opacity: 0.5;
    transition: 0.3s ease-in;

    &:hover {
      opacity: 1;
    }

    +button {
      left: auto;
      right: 0;
    }
  }
}

//Coupon Area
.coupon-wrap {
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  position: relative;
}

.coupon {
  cursor: pointer;
  width: 100%;
  height: 60px;
  background-color: $c1;
}

.voucher-list {
  padding: 5px 30px;
  border: 1px solid #f7f8fa;
  position: absolute;
  left: 0;
  background: #fff;
  width: 100%;
  z-index: 9;

  li {
    padding: 8px 0 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;

    &:not(:last-child) {
      border-bottom: 1px solid #e6e6e6;
    }
  }

  .voucher-left {
    display: flex;
    align-items: center;
  }

  .voucher-amount {
    margin-right: 20px;
    font-size: 30px;
    font-weight: 800;
    font-family: $title-font;
    color: #3b3b3b;

    sup {
      font-weight: 300;
    }
  }

  .voucher-info {
    h6 {
      font-weight: 500;
      font-size: 16px;
      margin-bottom: 0px;
    }

    p {
      font-size: 13px;
      color: #666666;
    }
  }
}

.mySwiper2 img {
  width: 100%;
}

.product-image-gallery {
  position: relative;

  &.is-loading .gallery-content {
    visibility: hidden; // NOT display:none — v-lazy-image needs layout/geometry to detect intersection
  }
}

.gallery-loader {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  z-index: 5;
  background: #fff;

  .loader-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba($c1, 0.2);
    border-top-color: $c1;
    border-radius: 50%;
    animation: gallery-spin 0.7s linear infinite;
  }
}

@keyframes gallery-spin {
  to {
    transform: rotate(360deg);
  }
}

.video-thumb {
  position: relative;
  min-height: 380px;
  width: auto;
  background-size: cover;
  background-position: center;
}

.gallery-preview-panel__video-player {
  position: absolute;
  width: 63px !important;
  height: 48px;
  font-size: 42px;
  top: calc(50% - 21px);
  left: calc(50% - 21px);
  color: #fff;
  cursor: pointer;
}

.item-gallery__image-wrapper {
  width: 90px;
  height: 90px;
  display: table-cell;
  vertical-align: middle;
  margin: auto;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border: 1px solid #dadada;
  border-radius: 2px;
  text-align: center;

  @media (max-width: 767px) {
    height: 48px;
    width: 48px;
  }
}

.item-gallery__video-icon {
  display: inline-block !important;
  width: 40px;
  height: 29px;
}

.social-link {
  cursor: pointer;
  border-color: red($color: #000000);
}

.share-list {
  a {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #aaaaaa;
    transition: 0.3s ease-in;
    margin: 2px;
    border: 1px solid #8b8b8b;

    &:hover {
      border-color: $c1;
    }

    img {
      width: 14px;
      height: 14px;
    }

    i,
    .fa {
      font-size: 14px;
    }
  }
}

/* ========================================
   MODERN THEME PRODUCT GALLERY
======================================== */

/* Modern desktop gallery: one hero image with every Gallery Image visible below. */
.modern-desktop-main-swiper {
  width: 100%;
}

.modern-gallery-thumbs {
  width: 100%;
  padding: 0 14px 14px;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  gap: 8px;
  overflow-x: auto;
  overflow-y: hidden;
  scrollbar-width: thin;
}

.modern-gallery-thumb {
  flex: 0 0 68px;
  width: 68px;
  height: 68px;
  padding: 4px;
  border: 1px solid #e5ded9;
  border-radius: 10px;
  background: #fff;
  overflow: hidden;
  box-sizing: border-box;
  cursor: pointer;
  opacity: 0.62;
  transition: opacity 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.modern-gallery-thumb img {
  display: block;
  width: 100%;
  height: 100%;
  padding: 0;
  object-fit: contain;
}

.modern-gallery-thumb--active {
  opacity: 1;
  border-color: #6f4c3b;
  box-shadow: 0 0 0 1px #6f4c3b;
}

.modern-gallery-thumb-video {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f7f8fa;
  background-size: cover;
  background-position: center;
}

.modern-gallery-thumb-video .material-icons {
  font-size: 22px;
  color: #6f4c3b;
}

.product-image-gallery--modern {
  width: 100%;
  background: #f8f5f3;
  border-radius: 0 0 28px 28px;
  overflow: hidden;

  .gallery-content {
    width: 100%;
  }

  .carousel-wrapper {
    position: relative;
    width: 100%;
  }

  :deep(.mySwiper2) {
    width: 100%;
    padding-bottom: 34px;
  }

  :deep(.mySwiper2 .swiper-slide) {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 245px;
  }

  :deep(.mySwiper2 .gallery-image > div) {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  :deep(.mySwiper2 img) {
    display: block;
    width: 100%;
    max-width: 310px;
    height: 225px;
    margin: auto;
    padding: 10px 18px 4px;
    object-fit: contain;
  }

  :deep(.swiper-pagination) {
    bottom: 12px !important;
  }

  :deep(.swiper-pagination-bullet) {
    width: 6px;
    height: 6px;
    opacity: 0.35;
    background: #6f4c3b;
  }

  :deep(.swiper-pagination-bullet-active) {
    width: 7px;
    height: 7px;
    opacity: 1;
    background: #6f4c3b;
  }

  .gallery-loader {
    min-height: 300px;
    background: #f8f5f3;
  }
}

@media (max-width: 575px) {
  .product-image-gallery--modern {
    :deep(.mySwiper2 .swiper-slide) {
      min-height: 260px;
    }

    :deep(.mySwiper2 img) {
      max-width: 280px;
      height: 245px;
      padding: 12px 20px 4px;
    }

    :deep(.swiper-button-prev),
    :deep(.swiper-button-next) {
      display: none !important;
    }
  }
}
</style>
