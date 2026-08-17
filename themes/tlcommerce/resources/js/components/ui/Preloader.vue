<template>
  <div id="preloader" v-if="loading">
    <div class="loader"></div>
  </div>
</template>
<script>
export default {
  name: "Preloader",
  props: {
    loading: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";
#preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #f0f8ff94;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}
.loader {
  --color-1: #fff;
  --color-2: #{$c1};
  --size: 1px;

  width: calc(54 * var(--size));
  height: calc(54 * var(--size));
  position: relative;
  border-radius: calc(4 * var(--size));
  background-color: var(--color-1);
  background-image:
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
    radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0);
  background-repeat: no-repeat;
  animation:
    move 4s linear infinite,
    rotate 2s linear infinite;
}

@keyframes rotate {
  0%,
  20% {
    transform: rotate(0deg);
  }
  30%,
  40% {
    transform: rotate(90deg);
  }
  50%,
  60% {
    transform: rotate(180deg);
  }
  70%,
  80% {
    transform: rotate(270deg);
  }
  90%,
  100% {
    transform: rotate(360deg);
  }
}
@keyframes move {
  0%,
  9% {
    background-position:
      calc(-12 * var(--size)) calc(-15 * var(--size)),
      calc(-12 * var(--size)) 0,
      calc(-12 * var(--size)) calc(15 * var(--size)),
      calc(12 * var(--size)) calc(-15 * var(--size)),
      calc(12 * var(--size)) 0,
      calc(12 * var(--size)) calc(15 * var(--size));
  }
  10%,
  25% {
    background-position:
      0 calc(-15 * var(--size)),
      calc(-12 * var(--size)) 0,
      calc(-12 * var(--size)) calc(15 * var(--size)),
      calc(34 * var(--size)) calc(-15 * var(--size)),
      calc(12 * var(--size)) 0,
      calc(12 * var(--size)) calc(15 * var(--size));
  }
  30%,
  45% {
    background-position:
      0 calc(-34 * var(--size)),
      calc(-12 * var(--size)) calc(-10 * var(--size)),
      calc(-12 * var(--size)) calc(12 * var(--size)),
      calc(34 * var(--size)) calc(-15 * var(--size)),
      calc(12 * var(--size)) calc(-10 * var(--size)),
      calc(12 * var(--size)) calc(12 * var(--size));
  }
  50%,
  65% {
    background-position:
      0 calc(-34 * var(--size)),
      calc(-12 * var(--size)) calc(-34 * var(--size)),
      calc(-12 * var(--size)) calc(12 * var(--size)),
      calc(34 * var(--size)) calc(-12 * var(--size)),
      0 calc(-10 * var(--size)),
      calc(12 * var(--size)) calc(12 * var(--size));
  }
  70%,
  85% {
    background-position:
      0 calc(-34 * var(--size)),
      calc(-12 * var(--size)) calc(-34 * var(--size)),
      0 calc(12 * var(--size)),
      calc(34 * var(--size)) calc(-12 * var(--size)),
      0 calc(-10 * var(--size)),
      calc(34 * var(--size)) calc(12 * var(--size));
  }
  90%,
  100% {
    background-position:
      0 calc(-34 * var(--size)),
      calc(-12 * var(--size)) calc(-34 * var(--size)),
      0 0,
      calc(34 * var(--size)) calc(-12 * var(--size)),
      0 0,
      calc(34 * var(--size)) calc(12 * var(--size));
  }
}
</style>
