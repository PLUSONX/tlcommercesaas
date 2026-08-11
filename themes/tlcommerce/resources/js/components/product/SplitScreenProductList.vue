<template>
  <div :class="[mtClass, { 'products-page--rtl': isRtl }]">
    <div class="custom-container2 split-screen-product-list">
      <div v-if="loading" class="split-screen-product-list__loading">
        <skeleton
          v-for="index in 4"
          :key="index"
          height="110px"
          class="w-100 mb-3"
        ></skeleton>
      </div>

      <template v-else-if="organiseByCategory">
        <section
          v-for="section in sections"
          :key="section.category.id"
          class="split-screen-product-list__section"
        >
          <div class="split-screen-product-list__category-bar">
            {{ section.category.name }}
          </div>

          <product-list-row
            v-for="product in section.products"
            :key="product.id"
            :item="product"
          />
        </section>
      </template>

      <template v-else>
        <product-list-row
          v-for="product in flatProducts"
          :key="product.id"
          :item="product"
        />
      </template>

      <p
        v-if="!loading && !hasProducts"
        class="split-screen-product-list__empty text-muted mb-0"
      >
        {{ $t("No item found") }}
      </p>
    </div>
  </div>
</template>

<script>
import ProductListRow from "@/components/product/ProductListRow.vue";
import { mapGetters } from "vuex";

const axios = require("axios").default;

export default {
  name: "SplitScreenProductList",
  components: {
    ProductListRow,
  },
  props: {
    disableMargin: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loading: true,
      organiseByCategory: false,
      sections: [],
      flatProducts: [],
    };
  },
  computed: {
    ...mapGetters("layout", ["isSplitScreen", "isMobile", "isRtl"]),

    mtClass() {
      if (this.disableMargin) {
        return "";
      }

      if (this.isSplitScreen && !this.isMobile) {
        return "mt-50";
      }

      return "mt-1";
    },

    hasProducts() {
      if (this.organiseByCategory) {
        return this.sections.some((section) => section.products?.length > 0);
      }

      return this.flatProducts.length > 0;
    },
  },
  mounted() {
    this.fetchProductList();
  },
  methods: {
    fetchProductList() {
      this.loading = true;

      axios
        .get("/api/theme/tlcommerce/v1/split-screen-product-list")
        .then((response) => {
          if (response.status === 200 && response.data?.success) {
            const data = response.data.data ?? {};
            this.organiseByCategory = !!data.organise_by_category;
            this.sections = data.sections ?? [];
            this.flatProducts = data.products ?? [];
          }
        })
        .catch(() => {
          this.sections = [];
          this.flatProducts = [];
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
.split-screen-product-list {
  padding-top: 8px;
  padding-bottom: 24px;
}

.split-screen-product-list__section + .split-screen-product-list__section {
  margin-top: 8px;
}

.split-screen-product-list__category-bar {
  background: #f3f4f6;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 4px;
}

.split-screen-product-list__empty {
  padding: 16px 0;
}

.products-page--rtl .split-screen-product-list__category-bar {
  direction: rtl;
  text-align: right;
}

.products-page--rtl .split-screen-product-list__empty {
  direction: rtl;
  text-align: right;
}
</style>
