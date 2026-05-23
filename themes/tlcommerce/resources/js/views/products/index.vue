<template>
  <div :class="mtClass">

    <div class="custom-container2">

      <div class="card mb-3">

        <div class="col-lg-12">
          <div class="section-header d-flex justify-content-between align-items-center">

            <div class="header-title">
              <h3 class="product_header mb-0" v-if="!categoryLoading">
                {{ $t("Products") }}
              </h3>
              <h3 class="product_header mb-0" v-if="categoryLoading">
                <skeleton :border-radius="5" :width="150" :height="30"></skeleton>
                <!-- <skeleton border-radius="5px" width="150px" height="30px"></skeleton> -->
              </h3>
            </div>

            <div class="header-count">
              <div v-if="categoryLoading">
                <p class="mb-0">
                  <skeleton :border-radius="5" :width="50" :height="10"></skeleton>
                  <!-- <skeleton border-radius="5px" width="50px" height="10px"></skeleton> -->
                </p>
              </div>
              <div v-if="!categoryLoading">
                <p class="mb-0" v-if="totalItems > 0">
                  {{ totalItems }} {{ $t("items found") }}
                </p>
                <p class="mb-0" v-else>{{ $t("No item found") }}</p>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
    <div class="row g-0 mobile-gap-10" v-if="productsLoading">

      <div class="col-6" v-for="(item, index) in productSkeletons" :key="index">
        <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
      </div>
    </div>
    <div class="row g-0 mobile-gap-10" v-else>

      <div v-for="product in paginatedItems" :key="product.id" class="col-6 compact-card p-2 pb-0">
        <single-product :item="product" styleEight />
      </div>
    </div>

  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import ShowingPerPage from "@/components/ui/ShowingPerPage.vue";
import WidgetTopCategory from "@/components/widget/WidgetTopCategory.vue";
import WidgetBrand from "@/components/widget/WidgetBrand.vue";
import WidgetRating from "@/components/widget/WidgetRating.vue";
import WidgetPrice from "@/components/widget/WidgetPrice.vue";
import BrandCollapseBox from "../../components/product/BrandCollapseBox.vue";
import SortingOption from "../../components/product/SortingOption.vue";
import Pagination from "v-pagination-3";
const axios = require("axios").default;
import { mapState, mapGetters } from "vuex";
export default {
  name: "Products",
  components: {
    PageHeader,
    SingleProduct,
    ShowingPerPage,
    WidgetTopCategory,
    WidgetBrand,
    WidgetRating,
    WidgetPrice,
    Pagination,
    BrandCollapseBox,
    SortingOption,
  },
  props: {
    disableMargin: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      productsLoading: true,
      brandLoading: true,
      categoryLoading: true,
      wToggle: false,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Products"),
          active: true,
        },
      ],
      currentPage: 1,
      perPage:
        this.$store.state.siteSettings != null
          ? parseInt(this.$store.state.siteSettings.product_per_page)
          : 18,
      totalItems: 0,
      sorting_by: "newest",
      paginatedItems: [],
      showFull: false,
      isFilterEmpty: false,
      categories: [],
      category_filter: {},
      brands: [],
      brand_filter: {},
      price_filter: {},
      rating_filter: null,
      paginationOptions: {
        chunk: 3,
        theme: "bootstrap4",
        hideCount: true,
      },
      productSkeletons: [
        {
          height: "370px",
        },
        {
          height: "370px",
        },
        {
          height: "370px",
        },
        {
          height: "370px",
        },
      ],
    };
  },
  computed: {

    ...mapGetters('layout', ['isSplitScreen', 'isMobile']),

    mtClass() {

      if (this.disableMargin) return '';

      if (this.isSplitScreen && !this.isMobile) {
        return 'mt-50';
      }
      return 'mt-1';
    }

  },
  mounted() {
    this.getCategories();
    this.getBrands();
    this.getProducts();
    document.title = this.$t("All Products");
  },

  methods: {
    /**
     * Get top categories
     *
     */
    getCategories() {
      axios
        .get("/api/v1/ecommerce-core/parent-categories")
        .then((response) => {
          if (response.status === 200) {
            this.categories = response.data.data;
            this.categoryLoading = false;
          }
        })
        .catch((error) => {
          this.categoryLoading = false;
        });
    },
    /**
     * Get Top brands
     */
    getBrands() {
      axios
        .get("/api/v1/ecommerce-core/brands")
        .then((response) => {
          if (response.status === 200) {
            this.brands = response.data.data;
            this.brandLoading = false;
          }
        })
        .catch((error) => {
          this.brandLoading = false;
        });
    },
    /**
     * Get products
     */
    getProducts() {
      window.scrollTo(0, 0);
      this.productsLoading = true;
      axios
        .post("/api/v1/ecommerce-core/products", {
          perPage: this.perPage,
          page: this.currentPage,
          brand_id: this.brand_filter.id,
          category_id: this.category_filter.id,
          min_price: this.price_filter.min,
          max_price: this.price_filter.max,
          rating: this.rating_filter,
          sorting: this.sorting_by,
        })
        .then((response) => {
          if (response.status === 200) {
            this.paginatedItems = response.data.data;
            this.totalItems = response.data.meta.total;

            // console.log("paginated_Items: ", this.paginatedItems);
            // console.log("total_Items: ", this.totalItems);

          }
          this.productsLoading = false;
        })
        .catch((error) => {
          this.productsLoading = false;
        });
    },

    sortingItems(item) {
      this.sorting_by = item;
      this.getProducts();
    },
    removeTag(item) {
      if (item === "brand") {
        this.brand_filter = {};
      } else if (item === "category") {
        this.category_filter = {};
      } else if (item === "price") {
        this.price_filter = {};
      } else if (item === "rating") {
        this.rating_filter = null;
      }
      this.currentPage = 1;
      this.getProducts();
    },
    removeAllTag() {
      this.category_filter = {};
      this.brand_filter = {};
      this.price_filter = {};
      this.rating_filter = null;
      this.currentPage = 1;
      this.getProducts();
    },
    addBrandFilter(el) {
      this.brand_filter = el;
      this.currentPage = 1;
      this.getProducts();
    },
    addCategoryFilter(el) {
      this.category_filter = el;
      this.currentPage = 1;
      this.getProducts();
    },
    addPriceFilter(el) {
      this.price_filter = el;
      this.currentPage = 1;
      this.getProducts();
    },
    addRatingFilter(el) {
      this.rating_filter = el;
      this.currentPage = 1;
      this.getProducts();
    },
  },
};
</script>

<style scoped>
.card {
  width: 100% !important;
  margin: 0 !important;
  padding: 0 !important;
  border: none !important;
  border-radius: 0;
  box-shadow: none;
}

.col-6 :deep(> div) {
  border-radius: 12px;
  overflow: hidden;
}

@media (max-width: 479px) {
  .compact-card :deep(> .single-product-item) {
    overflow: visible;
  }

  .compact-card :deep(.single-product-item > .position-relative) {
    overflow: hidden;
    border-radius: 12px 12px 0 0;
  }
}

.compact-card :deep(.single-product-item) {
  width: 100% !important;
  box-sizing: border-box !important;
}

.compact-card :deep(img) {
  width: 60% !important;
  height: auto !important;
  display: block;
  margin: 0 auto;
}

@media (max-width: 500px) {
  .mobile-gap-10 {
    --bs-gutter-x: 0rem !important;
    --bs-gutter-y: 0rem !important;
  }

  .mobile-gap-10 .compact-card {
    padding: 1px !important;
    /* padding-bottom: 2px !important; */
  }

  .mobile-gap-10 .compact-card :deep(.single-product-item) {
    box-shadow: none !important;
    border-radius: 6px !important;
    margin-bottom: 0 !important;
  }
}
</style>

<!-- <style scoped>

.col-6 :deep(.product-card),
.col-6 :deep(.style-eight-wrapper) {
  border-radius: 12px !important;
  overflow: hidden;
  background: #fff;
  border: 1px solid #eee;
}

.col-6 :deep(> div) {
  border-radius: 12px;
  overflow: hidden;
}



.card {
  width: 100% !important;
  margin: 0 !important;
  padding: 0 !important;
  border: none !important;
  border-radius: 0;
  box-shadow: none;


}


@media (max-width: 995px) and (min-width: 768px) {
  .compact-card :deep(.single-product-item) {
    width: 100% !important;
    box-sizing: border-box !important;
  }
}

.compact-card :deep(.single-product-item) {
  width: 100% !important;
  box-sizing: border-box !important;
}


.compact-card :deep(img) {
  height: auto !important;
  width: 60% !important;
  display: block;
  margin: 0 auto;


  @media (max-width: 768px) {
    width: 60% !important;
  }

}
</style> -->