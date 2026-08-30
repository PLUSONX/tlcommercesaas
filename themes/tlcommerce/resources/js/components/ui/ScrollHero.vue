<template>
  <section
    v-if="hasFrames"
    ref="hero"
    class="scroll-hero"
    :style="{
      '--scroll-hero-height': `${scrollHeight}vh`,
    }"
  >
    <div class="scroll-hero__sticky">
      <canvas
        ref="canvas"
        class="scroll-hero__canvas"
        aria-label="Scroll hero animation"
      ></canvas>

      <div
        v-if="loading"
        class="scroll-hero__loader"
      >
        <span></span>
      </div>

    </div>
  </section>
</template>

<script>
export default {
  name: "ScrollHero",

  props: {
    config: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      images: [],
      loading: true,
      loadedCount: 0,
      currentFrame: 0,
      targetFrame: 0,
      animationRequest: null,
      canvasWidth: 0,
      canvasHeight: 0,
      resizeObserver: null,
      destroyed: false,
      isMobileScreen: false,
    };
  },

  computed: {
  desktopFrames() {
    const frames = Array.isArray(this.config?.desktop)
      ? this.config.desktop
      : [];

    return frames
      .map((frame) => this.cleanFrameUrl(frame))
      .filter(Boolean);
  },

  mobileFrames() {
    const frames = Array.isArray(this.config?.mobile)
      ? this.config.mobile
      : [];

    return frames
      .map((frame) => this.cleanFrameUrl(frame))
      .filter(Boolean);
  },

  activeFrames() {
    if (this.isMobileScreen && this.mobileFrames.length) {
      return this.mobileFrames;
    }

    return this.desktopFrames;
  },

  frames() {
    return this.activeFrames;
  },

  hasFrames() {
    return this.frames.length > 0;
  },

  loadProgress() {
    if (!this.frames.length) return 0;
    return Math.min(100, Math.round((this.loadedCount / this.frames.length) * 100));
  },

  scrollHeight() {
    const height = Number(this.config?.scroll_height || 300);
    return Math.min(600, Math.max(150, height));
  },
  },

  watch: {
    activeFrames: {
      handler() {
        if (this.$refs.canvas) {
          this.resetAndLoadFrames();
        }
      },
    },
  },

  mounted() {
    this.updateScreenSize();

    if (!this.hasFrames) {
      return;
    }

    this.$nextTick(() => {
      this.setupCanvas();
      this.resetAndLoadFrames();

      document.addEventListener(
        "scroll",
        this.handleScroll,
        { passive: true, capture: true }
      );

      window.addEventListener("wheel", this.handleScroll, { passive: true });
      window.addEventListener("touchmove", this.handleScroll, { passive: true });

      window.addEventListener(
        "resize",
        this.handleResize,
        { passive: true }
      );
    });
  },

  beforeUnmount() {
    this.destroyed = true;

    document.removeEventListener(
      "scroll",
      this.handleScroll,
      true
    );

    window.removeEventListener("wheel", this.handleScroll);
    window.removeEventListener("touchmove", this.handleScroll);

    window.removeEventListener(
      "resize",
      this.handleResize
    );

    if (this.animationRequest) {
      cancelAnimationFrame(this.animationRequest);
    }

    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
    }

    this.images = [];
  },

  methods: {
      cleanFrameUrl(frame) {
    if (!frame) return "";

    return String(frame)
      .replace("/public/tlcommerce/", "/tlcommerce/")
      .replace("/storage/tlcommerce/", "/tlcommerce/");
  },

  updateScreenSize() {
    this.isMobileScreen = window.innerWidth <= 767;
  },

    setupCanvas() {
      const canvas = this.$refs.canvas;

      if (!canvas) {
        return;
      }

      this.resizeCanvas();

      if ("ResizeObserver" in window) {
        this.resizeObserver = new ResizeObserver(
          this.resizeCanvas
        );

        this.resizeObserver.observe(canvas);
      }
    },

    resetAndLoadFrames() {
      if (!this.hasFrames) {
        return;
      }

      this.images = new Array(this.frames.length);
      this.loadedCount = 0;
      this.currentFrame = 0;
      this.targetFrame = 0;
      this.loading = true;

      // Load only frame 1 first. Other frames must not compete with it.
      this.loadFrame(0, true);
    },

    loadFrame(index, priority = false) {
      if (
        this.destroyed ||
        !this.frames[index] ||
        this.images[index]
      ) {
        return;
      }

      const image = new Image();

      image.decoding = "async";

      if (priority && "fetchPriority" in image) {
        image.fetchPriority = "high";
      }

      image.onload = () => {
        if (this.destroyed) {
          return;
        }

        this.loadedCount++;

        if (index === 0) {
          this.loading = false;

          this.$nextTick(() => {
            this.resizeCanvas();
            this.drawFrame(0);
            this.$emit("ready");

            // Start remaining downloads only after frame 1 is visible.
            if ("requestIdleCallback" in window) {
              window.requestIdleCallback(
                () => this.loadRemainingFrames(1),
                { timeout: 250 }
              );
            } else {
              window.setTimeout(
                () => this.loadRemainingFrames(1),
                80
              );
            }
          });
        }

        const requestedIndex = Math.round(
          this.targetFrame
        );

        if (index === requestedIndex) {
          this.drawFrame(index);
        }
      };

      image.onerror = () => {
        /*
         * Do not block the entire animation if one
         * individual frame fails.
         */
        console.warn(
          `Scroll Hero frame failed: ${this.frames[index]}`
        );

        if (index === 0) {
          this.loading = false;
          this.$emit("failed");
        }
      };

      image.src = this.frames[index];
      this.images[index] = image;
    },

    loadRemainingFrames(startIndex) {
      let nextIndex = startIndex;

      const loadBatch = () => {
        if (
          this.destroyed ||
          nextIndex >= this.frames.length
        ) {
          return;
        }

        const batchEnd = Math.min(
          nextIndex + 5,
          this.frames.length
        );

        for (
          let index = nextIndex;
          index < batchEnd;
          index++
        ) {
          this.loadFrame(index);
        }

        nextIndex = batchEnd;

        if (nextIndex < this.frames.length) {
          if ("requestIdleCallback" in window) {
            window.requestIdleCallback(loadBatch, {
              timeout: 600,
            });
          } else {
            window.setTimeout(loadBatch, 80);
          }
        }
      };

      loadBatch();
    },

    handleScroll() {
      const hero = this.$refs.hero;

      if (!hero || !this.frames.length) {
        return;
      }

      const rect = hero.getBoundingClientRect();

      const scrollableDistance =
        hero.offsetHeight - window.innerHeight;

      if (scrollableDistance <= 0) {
        return;
      }

      const progress = Math.min(
        1,
        Math.max(
          0,
          -rect.top / scrollableDistance
        )
      );

      this.targetFrame =
        progress * (this.frames.length - 1);

      /*
       * Prioritize the frame currently requested by scroll.
       */
      const requestedIndex = Math.round(
        this.targetFrame
      );

      this.loadFrame(requestedIndex, true);

      if (requestedIndex > 0) {
        this.loadFrame(requestedIndex - 1, true);
      }

      if (requestedIndex < this.frames.length - 1) {
        this.loadFrame(requestedIndex + 1, true);
      }

      if (!this.animationRequest) {
        this.animationRequest = requestAnimationFrame(
          this.animateFrame
        );
      }
    },

    animateFrame() {
      /*
       * Smooth interpolation prevents sudden frame jumping.
       */
      this.currentFrame +=
        (this.targetFrame - this.currentFrame) * 0.22;

      const frameIndex = Math.max(
        0,
        Math.min(
          this.frames.length - 1,
          Math.round(this.currentFrame)
        )
      );

      this.drawNearestAvailableFrame(frameIndex);

      if (
        Math.abs(
          this.targetFrame - this.currentFrame
        ) > 0.01
      ) {
        this.animationRequest = requestAnimationFrame(
          this.animateFrame
        );
      } else {
        this.currentFrame = this.targetFrame;

        this.drawNearestAvailableFrame(
          Math.round(this.currentFrame)
        );

        this.animationRequest = null;
      }
    },

    drawNearestAvailableFrame(frameIndex) {
      if (this.isImageReady(this.images[frameIndex])) {
        this.drawFrame(frameIndex);
        return;
      }

      /*
       * Use the closest loaded frame while the exact frame
       * is still downloading.
       */
      for (
        let distance = 1;
        distance < this.frames.length;
        distance++
      ) {
        const previous = frameIndex - distance;
        const next = frameIndex + distance;

        if (
          previous >= 0 &&
          this.isImageReady(this.images[previous])
        ) {
          this.drawFrame(previous);
          return;
        }

        if (
          next < this.frames.length &&
          this.isImageReady(this.images[next])
        ) {
          this.drawFrame(next);
          return;
        }
      }
    },

    isImageReady(image) {
      return Boolean(
        image &&
        image.complete &&
        image.naturalWidth > 0
      );
    },

    drawFrame(frameIndex) {
      const canvas = this.$refs.canvas;
      const image = this.images[frameIndex];

      if (
        !canvas ||
        !this.isImageReady(image)
      ) {
        return;
      }

      const context = canvas.getContext("2d", {
        alpha: false,
        desynchronized: true,
      });

      if (!context) {
        return;
      }

      const imageRatio =
        image.naturalWidth / image.naturalHeight;

      const canvasRatio =
        this.canvasWidth / this.canvasHeight;

      let drawWidth;
      let drawHeight;
      let offsetX;
      let offsetY;

      /*
       * Same result as CSS object-fit: cover.
       */
      if (imageRatio > canvasRatio) {
        drawHeight = this.canvasHeight;
        drawWidth = drawHeight * imageRatio;
        offsetX =
          (this.canvasWidth - drawWidth) / 2;
        offsetY = 0;
      } else {
        drawWidth = this.canvasWidth;
        drawHeight = drawWidth / imageRatio;
        offsetX = 0;
        offsetY =
          (this.canvasHeight - drawHeight) / 2;
      }

      context.clearRect(
        0,
        0,
        this.canvasWidth,
        this.canvasHeight
      );

      context.drawImage(
        image,
        offsetX,
        offsetY,
        drawWidth,
        drawHeight
      );
    },

    resizeCanvas() {
      const canvas = this.$refs.canvas;

      if (!canvas) {
        return;
      }

      const rect = canvas.getBoundingClientRect();

      if (!rect.width || !rect.height) {
        return;
      }

      const pixelRatio = Math.min(
        window.devicePixelRatio || 1,
        2
      );

      this.canvasWidth = rect.width;
      this.canvasHeight = rect.height;

      canvas.width = Math.round(
        rect.width * pixelRatio
      );

      canvas.height = Math.round(
        rect.height * pixelRatio
      );

      const context = canvas.getContext("2d", {
        alpha: false,
        desynchronized: true,
      });

      context.setTransform(
        pixelRatio,
        0,
        0,
        pixelRatio,
        0,
        0
      );

      this.drawNearestAvailableFrame(
        Math.round(this.currentFrame)
      );
    },

    handleResize() {
      const wasMobile = this.isMobileScreen;
      this.updateScreenSize();

      if (wasMobile !== this.isMobileScreen) {
        this.resetAndLoadFrames();
        return;
      }

      this.resizeCanvas();
      this.handleScroll();
    },
  },
};
</script>

<style lang="scss" scoped>
.scroll-hero {
  position: relative;
  width: 100%;
  height: 200vh;
  background: #000;
}

.scroll-hero__sticky {
  position: sticky;
  top: 0;
  width: 100%;
  height: 100dvh;
  min-height: 100vh;
  overflow: hidden;
  background: #000;
}

.scroll-hero__canvas {
  display: block;
  width: 100%;
  height: 100%;
}

.scroll-hero__loader {
  position: absolute;
  inset: 0;
  z-index: 3;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #000;
}

.scroll-hero__loader span {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(255, 255, 255, 0.25);
  border-top-color: #fff;
  border-radius: 50%;
  animation: scrollHeroSpin 0.8s linear infinite;
}

@keyframes scrollHeroSpin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 767px) {
  .scroll-hero {
    height: 150vh;
  }

  .scroll-hero__sticky {
    height: 100dvh;
    min-height: 100vh;
  }
}

@media (prefers-reduced-motion: reduce) {
  .scroll-hero {
    height: 100vh;
  }
}
</style>
