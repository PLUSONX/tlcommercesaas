<template>
  <div
    class="product-banner-item d-flex align-items-center justify-content-center"
    @wheel="scrubVideo"
  >
    <div class="slide-img">
      <!-- YouTube or Vimeo -->
      <template v-if="mediaType === 'embed'">
        <iframe
          ref="videoIframe"
          class="banner-media"
          :src="embedUrl"
          :title="content.title || 'Video banner'"
          frameborder="0"
          allow="autoplay; encrypted-media; picture-in-picture"
          allowfullscreen
          @load="pauseVideo"
        ></iframe>

        <!-- Iframes do not pass wheel events to Vue, so this layer captures them. -->
        <div class="embed-motion-layer"></div>
      </template>

      <!-- Direct MP4, WebM, OGG, MOV or M3U8 -->
      <video
        v-else-if="mediaType === 'video'"
        ref="video"
        class="banner-media"
        :src="mediaUrl"
        muted
        playsinline
        preload="metadata"
        @loadedmetadata="prepareDirectVideo"
      ></video>

      <!-- External image URL or uploaded desktop/mobile image -->
      <img
        v-else
        class="banner-media"
        :src="activeImage"
        :alt="content.title || 'Banner image'"
        :width="imageWidth"
        :height="imageHeight"
        :fetchpriority="priority ? 'high' : 'auto'"
        :loading="priority ? 'eager' : 'lazy'"
        decoding="async"
      />
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "ProductBanner",

  props: {
    content: {
      type: Object,
      required: true,
    },
    priority: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      embedCurrentTime: 0,
      embedDurationLimit: 3,
    };
  },

  computed: {
    ...mapGetters("layout", ["isMobile"]),

    mediaUrl() {
      const url = String(this.content?.url || "").trim();
      return !url || url === "/" ? "" : url;
    },

    mediaType() {
      const url = this.mediaUrl;

      if (!url) return "uploaded-image";
      if (/youtube\.com|youtu\.be|vimeo\.com/i.test(url)) return "embed";
      if (/\.(mp4|webm|ogg|mov|m3u8)(\?.*)?$/i.test(url)) return "video";
      if (/\.(jpg|jpeg|png|webp|gif|avif|svg)(\?.*)?$/i.test(url)) {
        return "external-image";
      }

      return "uploaded-image";
    },

    embedUrl() {
      if (/youtube\.com|youtu\.be/i.test(this.mediaUrl)) {
        const match = this.mediaUrl.match(
          /(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([^?&/]+)/
        );
        const videoId = match?.[1];

        if (!videoId) return "";

        return (
          `https://www.youtube.com/embed/${videoId}` +
          `?autoplay=0` +
          `&mute=1` +
          `&loop=0` +
          `&controls=0` +
          `&rel=0` +
          `&playsinline=1` +
          `&enablejsapi=1`
        );
      }

      if (/vimeo\.com/i.test(this.mediaUrl)) {
        const match = this.mediaUrl.match(/vimeo\.com\/(?:video\/)?(\d+)/);
        const videoId = match?.[1];

        if (!videoId) return "";

        return (
          `https://player.vimeo.com/video/${videoId}` +
          `?autoplay=0` +
          `&muted=1` +
          `&loop=0` +
          `&controls=0` +
          `&api=1`
        );
      }

      return "";
    },

    cleanDesktopImage() {
      return this.cleanImagePath(this.content?.desktop);
    },

    cleanMobileImage() {
      return this.cleanImagePath(this.content?.mobile);
    },

    activeImage() {
      if (this.mediaType === "external-image") return this.mediaUrl;

      if (this.isMobile) {
        return this.cleanMobileImage || this.cleanDesktopImage;
      }

      return this.cleanDesktopImage || this.cleanMobileImage;
    },

    imageWidth() {
      return this.isMobile ? 800 : 1600;
    },

    imageHeight() {
      return 500;
    },
  },

  watch: {
    mediaUrl() {
      this.$nextTick(() => this.resetVideoPosition());
    },
  },

  mounted() {
    this.$nextTick(() => this.pauseVideo());
  },

  beforeUnmount() {
    this.pauseVideo();
  },

  methods: {
    cleanImagePath(image) {
      if (!image) return "";

      const path = String(image);
      return path.startsWith("/public") ? path.slice(7) : path;
    },

    prepareDirectVideo() {
      const video = this.$refs.video;
      if (!video) return;

      video.pause();
      video.currentTime = 0;
    },

    scrubVideo(event) {
      if (this.mediaType !== "video" && this.mediaType !== "embed") return;
      if (event.deltaY === 0) return;

      const direction = event.deltaY > 0 ? 1 : -1;
      const step = Math.min(Math.max(Math.abs(event.deltaY) * 0.003, 0.08), 0.35);
      const edgeTolerance = 0.02;

      if (this.mediaType === "video") {
        const video = this.$refs.video;
        if (!video) return;

        video.pause();

        // The requested hero motion is limited to its first 3 seconds.
        const duration = Number.isFinite(video.duration)
          ? Math.min(video.duration, 3)
          : 3;

        // Once an edge is reached, do not consume the wheel event. This lets
        // the browser continue scrolling the page without looping the video.
        if (direction > 0 && video.currentTime >= duration - edgeTolerance) {
          video.currentTime = duration;
          return;
        }

        if (direction < 0 && video.currentTime <= edgeTolerance) {
          video.currentTime = 0;
          return;
        }

        const nextTime = Math.min(
          Math.max(video.currentTime + direction * step, 0),
          duration
        );

        event.preventDefault();
        video.currentTime = nextTime;
        return;
      }

      if (
        direction > 0 &&
        this.embedCurrentTime >= this.embedDurationLimit - edgeTolerance
      ) {
        this.embedCurrentTime = this.embedDurationLimit;
        return;
      }

      if (direction < 0 && this.embedCurrentTime <= edgeTolerance) {
        this.embedCurrentTime = 0;
        return;
      }

      const nextTime = Math.min(
        Math.max(this.embedCurrentTime + direction * step, 0),
        this.embedDurationLimit
      );

      event.preventDefault();
      this.embedCurrentTime = nextTime;
      this.seekEmbedVideo(nextTime);
    },

    resetVideoPosition() {
      this.pauseVideo();
      this.embedCurrentTime = 0;

      if (this.mediaType === "video" && this.$refs.video) {
        this.$refs.video.currentTime = 0;
      } else if (this.mediaType === "embed") {
        this.seekEmbedVideo(0);
      }
    },

    pauseVideo() {
      if (this.mediaType === "video") {
        this.$refs.video?.pause();
      } else if (this.mediaType === "embed") {
        this.sendEmbedCommand("pause");
      }
    },

    seekEmbedVideo(seconds) {
      const iframe = this.$refs.videoIframe;
      if (!iframe?.contentWindow) return;

      if (/youtube\.com|youtu\.be/i.test(this.mediaUrl)) {
        iframe.contentWindow.postMessage(
          JSON.stringify({
            event: "command",
            func: "seekTo",
            args: [seconds, true],
          }),
          "*"
        );
      } else if (/vimeo\.com/i.test(this.mediaUrl)) {
        iframe.contentWindow.postMessage(
          { method: "setCurrentTime", value: seconds },
          "*"
        );
      }

      this.$nextTick(() => this.pauseVideo());
    },

    sendEmbedCommand(action) {
      const iframe = this.$refs.videoIframe;
      if (!iframe?.contentWindow) return;

      if (/youtube\.com|youtu\.be/i.test(this.mediaUrl)) {
        iframe.contentWindow.postMessage(
          JSON.stringify({ event: "command", func: `${action}Video`, args: [] }),
          "*"
        );
      } else if (/vimeo\.com/i.test(this.mediaUrl)) {
        iframe.contentWindow.postMessage({ method: action }, "*");
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.product-banner-item,
.slide-img {
  width: 100%;
  height: 100%;
}

.slide-img {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  background: #000;
}

.banner-media {
  display: block;
  width: 100%;
  height: 450px;
  border: 0;
  object-fit: cover;
}

.embed-motion-layer {
  position: absolute;
  inset: 0;
  z-index: 2;
  background: transparent;
}

@media (max-width: 767px) {
  .banner-media {
    height: 300px;
  }
}
</style>