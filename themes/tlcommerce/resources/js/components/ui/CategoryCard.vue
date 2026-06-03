<template>
  <router-link v-if="styleTwo" class="category-card--two bg-cover" :to="`/products/category/${cat.slug}`"
    :style="cleanImage ? { backgroundImage: `url(${cleanImage})` } : ''">
    <span class="category-content text-center d-block">
      <span class="category-name d-block">{{ cat.name }}</span>
    </span>
  </router-link>

  <router-link v-else :class="cardClass" :to="`/products/category/${cat.slug}`">
    <v-lazy-image :src="cleanImage" :alt="cat.name" />
    <span>{{ cat.name }}</span>
  </router-link>
</template>

<script>
import VLazyImage from "v-lazy-image";
import { mapGetters } from "vuex";

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
    ...mapGetters("layout", ["isSplitScreen"]),

    isSplitScreenLayout() {
      return this.isSplitScreen;
    },

    cardClass() {
      return this.isSplitScreenLayout
        ? "category-card category-card--split"
        : "category-card";
    },

    cleanImage() {
      const img = this.cat?.icon;
      if (!img) return "";

      return img.startsWith("/public") ? img.slice(7) : img;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

/* split-screen layout */
.category-card--split {
  padding: 0;
  margin: 0;
  background: transparent !important;
  background-color: transparent !important;
  box-shadow: none !important;
  border: none !important;
  border-radius: 0 !important;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: max-content !important;
  max-width: none !important;
  line-height: 1.4;
  text-align: center;
  text-decoration: none;

  img {
    border: 1px solid rgba(#707070, 0.3);
    padding: 5px;
    border-radius: 50%;
    margin: 0 0 4px 0;
    width: 40px;
    height: 40px;
    min-width: 40px;
    max-width: 40px;
    object-fit: cover;
    box-sizing: border-box;
  }

  span {
    font-size: 12px;
    font-weight: 500;
  }
}

/* default layout */
.layout__two .category-card {
  padding: 20px;
  background-color: #f7f8fa;
  display: flex;
  align-items: center;
  width: 100%;
  line-height: 1.4;

  img {
    border: 1px solid rgba(#707070, 0.3);
    padding: 5px;
    border-radius: 50%;
    margin-right: 15px;
    min-width: 60px;
    height: 60px;
    object-fit: cover;
  }

  span {
    font-size: 16px;
    font-weight: 500;
  }

  @media (max-width: 480px) {
    padding: 10px;

    img {
      margin-right: 10px;
      min-width: 40px;
      height: 40px;
    }

    span {
      font-size: 14px;
    }
  }
}

/* style-two card (both layouts) */
.category-card--two {
  padding: 80px 0;

  .category {
    &-content {
      display: block;
      padding: 15px 20px;
      position: relative;
      color: $title-color-four;
      z-index: 1;
      transition: 0.3s ease-in;
      opacity: 0;
      visibility: hidden;

      &:after {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        content: "";
        background: rgb(255, 255, 255);
        background: linear-gradient(90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 1) 50%,
            rgba(255, 255, 255, 0) 100%);
        z-index: -1;
        opacity: 0.4;
      }
    }

    &-name {
      font-size: 24px;
      font-weight: $bold;
      font-family: $title-font;
      $lh: 1.5;
      line-height: $lh;
    }
  }

  &:hover {
    .category {
      &-content {
        opacity: 1;
        visibility: visible;
      }
    }
  }
}
</style>
