<template>
  <section
    v-if="hasFrames"
    ref="hero"
    class="scroll-hero"
    :style="{
  '--scroll-hero-height': scrollHeight + 'vh',
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
      scrollScheduled: false,
      activeLoads: 0,
      loadQueueIndex: 0,
    };
  },

  computed: {
  desktopFrames() {
    const frames = Array.isArray(this.config?.desktop)
      ? this.config.desktop
      : [];

    return this.sortAndDedupeFrames(
      frames.map((frame) => this.cleanFrameUrl(frame)).filter(Boolean),
      "desktop"
    );
  },

  mobileFrames() {
    const frames = Array.isArray(this.config?.mobile)
      ? this.config.mobile
      : [];

    return this.sortAndDedupeFrames(
      frames.map((frame) => this.cleanFrameUrl(frame)).filter(Boolean),
      "mobile"
    );
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

  // FIX: frames must play in numeric order (1, 2, 3 ... 124), but a zip
  // upload / directory listing on the backend often returns filenames in
  // lexicographic order instead: 1, 10, 100, 101, 11, 110 ... 2, 20 ...
  // That out-of-order array is what made the animation look like it was
  // randomly flipping between stills instead of playing smoothly — no
  // amount of loading/animation tuning on the frontend can fix frames
  // that are simply in the wrong sequence. This extracts the number from
  // each filename (e.g. ".../42.png" -> 42) and sorts on that, so the
  // component always plays frames in the correct order regardless of
  // what order the server/API returned them in.
  extractFrameNumber(url) {
    const matches = String(url).match(/(\d+)(?!.*\d)/);
    return matches ? parseInt(matches[1], 10) : NaN;
  },

  compareFrameUrls(a, b) {
    const numA = this.extractFrameNumber(a);
    const numB = this.extractFrameNumber(b);

    if (Number.isNaN(numA) || Number.isNaN(numB)) {
      // No usable number in one of the filenames — fall back to a plain
      // string compare rather than crashing the sort.
      return String(a).localeCompare(String(b));
    }

    return numA - numB;
  },

  // FIX: on top of the ordering fix, the source list itself can contain
  // the same frame number twice (e.g. frame 45 listed twice) while
  // another number is missing entirely (e.g. no 46 at all) — usually
  // from however the zip was unpacked/listed on the backend. Visually
  // this is exactly "frames repeating" (you sit on the same image for
  // two scroll steps) immediately followed by "cutting" (the next number
  // is simply absent, so the sequence jumps). Deduping by frame number
  // here (keeping the first occurrence, after sorting) guarantees each
  // position in the played sequence is a distinct, correctly-ordered
  // frame — it can't fix a genuinely missing frame file, but it removes
  // the duplicate-driven stutter and makes any real gaps visible in the
  // console instead of silently doubling up.
  sortAndDedupeFrames(frames, label) {
    const sorted = [...frames].sort(this.compareFrameUrls);

    const seen = new Set();
    const deduped = [];
    const duplicates = [];

    sorted.forEach((url) => {
      const num = this.extractFrameNumber(url);
      const key = Number.isNaN(num) ? url : num;

      if (seen.has(key)) {
        duplicates.push(url);
        return;
      }

      seen.add(key);
      deduped.push(url);
    });

    if (duplicates.length && typeof console !== "undefined") {
      console.warn(
    `[ScrollHero] Dropped ${duplicates.length} duplicate frame(s) in "${label}" — check how frames are extracted/listed on the backend:`,
    duplicates
);
    }

    // Also flag gaps in the numbering (e.g. ...44, 46... with 45 missing)
    // so a genuinely missing frame is easy to spot instead of just
    // showing up as an unexplained skip.
    const numbers = deduped
      .map((url) => this.extractFrameNumber(url))
      .filter((n) => !Number.isNaN(n));

    const gaps = [];
    for (let i = 1; i < numbers.length; i++) {
      if (numbers[i] - numbers[i - 1] > 1) {
        gaps.push(`${numbers[i - 1]} -> ${numbers[i]}`);
      }
    }

    if (gaps.length && typeof console !== "undefined") {
     console.warn(
    `[ScrollHero] Missing frame number(s) in "${label}" sequence:`,
    gaps
);
    }

    return deduped;
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
      this.activeLoads = 0;
      this.loadQueueIndex = 1;

      // Load only frame 1 first. Other frames must not compete with it.
      this.loadFrame(0, true);
    },

    loadFrame(index, priority = false, onSettled = null) {
      if (
        this.destroyed ||
        !this.frames[index]
      ) {
        if (onSettled) onSettled();
        return;
      }

      if (this.images[index]) {
        // Already requested/loaded — still let the caller move on.
        if (onSettled) onSettled();
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

            // FIX: keep only a handful of frames downloading/decoding at
            // once (a small worker pool) instead of either (a) trickling
            // 5 in every idle-callback cycle — far too slow for long
            // sequences, frames were still missing while scrolling — or
            // (b) firing all frames at once, which saturates the main
            // thread with simultaneous image decodes and starves the
            // scroll/draw loop, making playback look like it's skipping
            // between stills instead of running smoothly.
            this.loadRemainingFrames();
          });
        }

        const requestedIndex = Math.round(this.targetFrame);

        if (index === requestedIndex) {
          this.drawFrame(index);
        }

        if (onSettled) onSettled();
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

        if (onSettled) onSettled();
      };

      image.src = this.frames[index];
      this.images[index] = image;
    },

    loadRemainingFrames() {
      // Small worker pool: a bounded number of frames load/decode at the
      // same time, refilling as each one finishes. Fast (several frames
      // in flight at once, no artificial delay) without overwhelming the
      // main thread the way loading everything at once did.
      const concurrency = 4;

      const next = () => {
        if (this.destroyed) return;

        while (
          this.activeLoads < concurrency &&
          this.loadQueueIndex < this.frames.length
        ) {
          const index = this.loadQueueIndex++;

          if (this.images[index]) {
            continue;
          }

          this.activeLoads++;

          this.loadFrame(index, false, () => {
            this.activeLoads--;
            next();
          });
        }
      };

      next();
    },

    handleScroll() {
      // FIX: coalesce scroll/wheel/touchmove bursts into at most one
      // update per animation frame instead of running the full
      // getBoundingClientRect + state update on every single event.
      if (this.scrollScheduled) {
        return;
      }

      this.scrollScheduled = true;

      requestAnimationFrame(() => {
        this.scrollScheduled = false;
        this.updateTargetFrame();
      });
    },

    updateTargetFrame() {
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

      // FIX: draw the frame that matches the scroll position directly,
      // instead of chasing it through a separate lerp/rAF loop. That
      // catch-up animation is what made playback look choppy — under any
      // main-thread load (frames decoding, scroll events firing) the
      // "current" value lagged behind the real scroll position, and it
      // would visibly jump to catch up. Binding 1:1 to scroll (already
      // throttled to one update per animation frame by handleScroll) is
      // what makes a frame sequence read as smooth, continuous video.
      this.currentFrame = this.targetFrame;
      this.drawNearestAvailableFrame(requestedIndex);
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