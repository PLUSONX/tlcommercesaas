<template>
  <div v-if="loading" id="preloader">
    <span class="circle-preloader"></span>
  </div>
</template>

<script>
export default {
  name: "Preloader",

  props: {
    loading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

#preloader {
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(240, 248, 255, 0.58);
  z-index: 99999;

  display: flex;
  align-items: center;
  justify-content: center;
}

.circle-preloader {
  width: 48px;
  height: 48px;
  display: inline-block;
  box-sizing: border-box;

  border: 5px solid #ffffff;
  border-bottom-color: $c1;
  border-radius: 50%;

  animation: circle-preloader-rotation 1s linear infinite;
}

@keyframes circle-preloader-rotation {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>