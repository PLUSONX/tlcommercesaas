<template>
  <template v-if="isSplitScreen">
    <div class="single-product-item single-product--split d-inline-block style--eight">
      <div class="position-relative overflow-hidden">
        <router-link :to="categoryLink" class="d-block">
          <v-lazy-image class="w-100" :src="cleanImage(cat.icon)" :alt="cat.name" />
        </router-link>
      </div>

      <div class="d-flex flex-column justify-content-between product-summary">
        <div class="row product-title-price-row justify-content-between align-items-baseline d-flex flex-column flex-sm-row">
          <div class="col pe-0 product-title-col">
            <h4 class="product-title">
              <router-link :to="categoryLink" :title="cat.name">
                {{ cat.name }}
              </router-link>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </template>

  <template v-else>
    <div class="single-product-item single-product--split d-flex flex-column">
      <div class="position-relative overflow-hidden">
        <router-link :to="categoryLink" class="d-block">
          <v-lazy-image class="w-100" :src="cleanImage(cat.icon)" :alt="cat.name" />
        </router-link>
      </div>

      <div class="product-summary text-center">
        <h4 class="product-title">
          <router-link :to="categoryLink" :title="cat.name">
            {{ cat.name }}
          </router-link>
        </h4>
      </div>
    </div>
  </template>
</template>

<script>
import VLazyImage from "v-lazy-image";
import { mapGetters } from "vuex";
import { cleanMediaPath } from "@/utils/sectionProps";

export default {
  name: "SingleCategory",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    cat: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters("layout", ["isSplitScreen"]),

    categoryLink() {
      return `/products/category/${this.cat.slug}`;
    },
  },
  methods: {
    cleanImage(icon) {
      return cleanMediaPath(icon);
    },
  },
};
</script>
