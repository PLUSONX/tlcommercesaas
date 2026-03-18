<template>
  <router-link v-if="styleTwo" class="category-card--two bg-cover" :to="`/products/category/${cat.slug}`"
    :style="cat.icon ? { backgroundImage: `url(${cat.icon})` } : ''">
    <span class="category-content text-center d-block">
      <span class="category-name d-block">{{ cat.name }}</span>
    </span>
  </router-link>

  <router-link v-else class="category-card" :to="`/products/category/${cat.slug}`">
    <v-lazy-image :src="cleanImage" :alt="cat.name" />
    <!-- <v-lazy-image :src="`${cat.icon}`" :alt="cat.name" /> -->
    <span>{{ cat.name }}</span>
  </router-link>
</template>

<script>
import VLazyImage from "v-lazy-image";
export default {
  name: "CategoryCard",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    cat: {
      type: Object,
      required: true,
    },
    styleTwo: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    cleanImage() {
      // return this.cat.icon.replace('/public', '');
      const img = this.cat?.icon;
      if (!img) return '';

      return img.startsWith('/public')
        ? img.slice(7)
        : img;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.category-card {
  padding: 0; // remove card padding
  background-color: transparent; // remove card background
  display: flex;
  flex-direction: column; // stack vertically
  align-items: center;
  width: 100%;
  line-height: 1.4;
  text-align: center;
  text-decoration: none; // useful for router-link

  img {
    border: 1px solid rgba(#707070, 0.3);
    padding: 5px;
    border-radius: 50%;
    margin: 0 0 10px 0; // space below image
    width: 85px;
    height: 85px;
    object-fit: cover;
  }

  span {
    font-size: 12px;
    font-weight: 500;
  }

  @media (max-width: 480px) {
    img {
      width: 40px;
      height: 40px;
      margin-bottom: 8px;
    }

    span {
      font-size: 12px;
    }
  }
}
</style>
