<template>
  <!-- Style Two (Background Image View) -->
  <router-link v-if="styleTwo" class="category-card-two bg-cover" :to="`/products/category/${cat.slug}`"
    :style="cat.icon ? { backgroundImage: `url(${cat.icon})` } : ''">
    <span class="category-content text-center d-block">
      <span class="category-name d-block">{{ cat.name }}</span>
    </span>
  </router-link>

  <!-- Split Screen View -->
  <router-link v-else-if="isSplitScreen" class="category-card-split" :to="`/products/category/${cat.slug}`">
    <v-lazy-image :src="cleanImage" :alt="cat.name" />
    <span>{{ cat.name }}</span>
  </router-link>

  <!-- Default View -->
  <router-link v-else class="category-card-default" :to="`/products/category/${cat.slug}`">
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
    ...mapGetters('layout', ['isSplitScreen', 'isMobile']),

    cleanImage() {
      const img = this.cat?.icon;
      if (!img) return '';
      return img.startsWith('/public') ? img.slice(7) : img;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

// 1. Default View (Horizontal Layout)
.category-card-default {
  display: flex;
  flex-direction: row;
  align-items: center;
  width: 100%;
  padding: 20px;
  background-color: #f7f8fa;
  text-decoration: none;
  line-height: 1.4;

  img {
    width: 60px;
    height: 60px;
    margin-right: 15px;
    border: 1px solid rgba(#707070, 0.3);
    border-radius: 50%;
    padding: 5px;
    object-fit: cover;
    flex-shrink: 0;
  }

  span {
    font-size: 16px;
    font-weight: 500;
  }

  @media (max-width: 480px) {
    padding: 10px;

    img {
      width: 40px;
      height: 40px;
      margin-right: 10px;
    }

    span {
      font-size: 14px;
    }
  }
}

// 2. Split Screen View (Vertical/Column Layout)
// .category-card-split {
//   display: flex;
//   flex-direction: column;
//   align-items: center;
//   padding: 0;
//   margin: 0;
//   background: transparent !important;
//   background-color: transparent !important;
//   border: none !important;
//   box-shadow: none !important;
//   text-decoration: none;
//   text-align: center;
//   line-height: 1.4;

//   img {
//     width: 85px;
//     height: 85px;
//     margin: 0 0 6px 0;
//     border: 1px solid rgba(#707070, 0.3);
//     border-radius: 50%;
//     padding: 5px;
//     object-fit: cover;
//   }

//   span {
//     font-size: 12px;
//     font-weight: 500;
//   }

//   @media (max-width: 480px) {
//     img {
//       width: 40px;
//       height: 40px;
//       margin-bottom: 4px;
//     }

//     span {
//       font-size: 11px;
//     }
//   }
// }

.category-card-split {
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

// 3. Style Two: Full Background Image Card
.category-card-two {
  display: block;
  padding: 80px 0;
  text-decoration: none;

  .category-content {
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

  .category-name {
    font-size: 24px;
    font-weight: $bold;
    font-family: $title-font;
    line-height: 1.5;
  }

  &:hover {
    .category-content {
      opacity: 1;
      visibility: visible;
    }
  }
}
</style>