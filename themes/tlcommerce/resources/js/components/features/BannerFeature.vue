<template>
  <div class="banner-feature">

    <img v-if="imagePath" :src="`/${imagePath}`" :alt="settings?.feature_image_name || 'Feature Banner'"
      class="feature-image" />
    <div v-else class="placeholder">
      No image available
    </div>
  </div>
</template>

<script>
export default {
  name: 'BannerFeature',
  props: {
    settings: {
      type: Object,
      required: true
    },
    imagePath: {
      type: String,
      default: null
    }
  },
}
</script>

<style scoped>
.banner-feature {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  /*
  border-radius: 12px;
  overflow: hidden;
  */
}

.feature-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.placeholder {
  color: #999;
  font-size: 18px;
}
</style>