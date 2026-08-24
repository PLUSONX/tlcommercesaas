<template>
  <template v-if="isFeaturePaneLayout">
    <div :class="[mtClass, { 'products-page--rtl': isRtl }]">

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

        <div class="row g-0 mobile-gap-10" v-if="productsLoading">

          <div class="col-6" v-for="(item, index) in productSkeletons" :key="index">
            <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
          </div>
        </div>
        <div class="row g-0 mobile-gap-10" v-else>

          <div v-for="product in paginatedItems" :key="product.id" class="col-6 compact-card">
            <single-product :item="product" styleEight />
          </div>
        </div>

      </div>

    </div>
  </template>

  <template v-else>
    <div class="">
      <page-header :items="bItems" />
      <div class="pt-30 pt-lg-60 pb-60 light-bg">
        <div class="custom-container2">
          <div class="row">
            <div class="col-lg-3">
              <div class="widget_wrap" :class="{ active: wToggle }">
                <button class="close-btn btn-circle d-lg-none" @click.prevent="wToggle = !wToggle">
                  <span class="material-icons"> close </span>
                </button>
                <div class="widget_wrap-inner">
                  <!-- <WidgetTopCategory v-if="!categoryLoading" :categories="categories" :selected-cat="category_filter"
                    @filter="addCategoryFilter" />
                  <div v-if="categoryLoading" class="widget widget-style-1 widget_top_category mb-4">
                    <skeleton height="300px"></skeleton>
                  </div> -->
                  <!-- <WidgetBrand
                    v-if="!brandLoading"
                    :brands="brands"
                    :selected-brand="brand_filter"
                    @filter="addBrandFilter"
                  /> -->
                  <div v-if="brandLoading" class="widget widget-style-1 widget_top_category mb-4">
                    <skeleton height="300px"></skeleton>
                  </div>
                  <!-- <WidgetRating :selected-item="rating_filter" @filter="addRatingFilter" /> -->
                  <WidgetPrice :selected-option="price_filter" @filter="addPriceFilter" />
                </div>
              </div>
            </div>
            <div class="col-lg-9">
              <div class="row">
                <div class="col-12">
                  <div class="mb-40 shadow-card">
                    <div class="row">
                      <div class="col-lg-6 order-1 order-lg-0 col-xl-6">
                        <div class="section-header">
                          <h3 class="product_header" v-if="!categoryLoading">
                            {{ $t("Products") }}
                          </h3>
                          <h3 class="product_header" v-if="categoryLoading">
                            <skeleton border-radius="5px" width="150px" height="30px"></skeleton>
                          </h3>
                          <div v-if="categoryLoading">
                            <p class="mt-1">
                              <skeleton border-radius="5px" width="50px" height="10px"></skeleton>
                            </p>
                          </div>
                          <div v-if="!categoryLoading">
                            <p v-if="totalItems > 0">
                              {{ totalItems }} {{ $t("items found") }}
                            </p>
                            <p v-else>{{ $t("No item found") }}</p>
                          </div>
                        </div>
                      </div>
                      <sorting-option class="col-lg-6 order-0 order-lg-1 text-lg-right" :data-loading="categoryLoading"
                        :selected-item="sorting_by" @sorting-items="sortingItems"
                        @filter-toggle="wToggle = !wToggle"></sorting-option>
                    </div>
                    <!-- Brand Collapse Box -->
                    <brand-collapse-box :brands="brands" :brand-loading="brandLoading" @select-brand="addBrandFilter"
                      v-if="!brandLoading && brands.length > 0"></brand-collapse-box>
                    <!-- End Brand Collapse Box -->
                    <!--Filter items-->
                    <div class="filter-tag-wrap" v-if="
                      brand_filter.id ||
                      category_filter.id ||
                      price_filter.max ||
                      rating_filter != null
                    ">
                      <div class="filter-tags">
                        <h6 class="filtered-by mb-0">{{ $t("Filtered By") }}:</h6>

                        <div class="ant-tag" v-if="brand_filter.id">
                          <span class="ant-tag-text">{{
                            brand_filter.name
                          }}</span>
                          <span class="material-icons" @click.prevent="removeTag('brand')">close</span>
                        </div>
                        <div class="ant-tag" v-if="category_filter.id">
                          <span class="ant-tag-text">{{
                            category_filter.name
                          }}</span>
                          <span class="material-icons" @click.prevent="removeTag('category')">close</span>
                        </div>
                        <div class="ant-tag" v-if="rating_filter != null">
                          <span class="ant-tag-text">{{ rating_filter }} Star</span>
                          <span class="material-icons" @click.prevent="removeTag('rating')">close</span>
                        </div>
                        <div class="ant-tag" v-if="price_filter.max">
                          <span class="ant-tag-text">
                            <the-currency :amount="price_filter.min"></the-currency>-
                            <the-currency :amount="price_filter.max"></the-currency>
                          </span>
                          <span class="material-icons" @click.prevent="removeTag('price')">close</span>
                        </div>

                        <span class="clear-all" @click.prevent="removeAllTag">{{
                          $t("CLEAR ALL")
                        }}</span>
                      </div>
                    </div>
                    <!--End filter items-->
                  </div>
                </div>
              </div>
              <div class="row mobile-gap-10 products-listing-grid" v-if="productsLoading">
                <div class="col-lg-3 col-6" v-for="(item, index) in productSkeletons" :key="index">
                  <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
                </div>
              </div>
              <div class="row mobile-gap-10 products-listing-grid" v-else>
                <div v-for="product in paginatedItems" :key="product.id" class="col-lg-3 col-6">
                  <single-product :item="product" styleEight listing-grid />
                </div>
              </div>
              <div class="row align-items-center mt-10" v-if="!productsLoading">
                <div class="col-md-6">
                  <!-- Showing Per Page -->
                  <ShowingPerPage class="text-center text-md-start" :items-per-page="perPage" :total-items="totalItems"
                    :current-page="currentPage" />
                  <!-- Showing Per Page -->
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                  <!-- Pagination -->
                  <pagination :options="paginationOptions" v-model="currentPage" :records="totalItems"
                    :per-page="perPage" @paginate="getProducts" />
                  <!-- End Pagination -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
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
import { mapGetters } from "vuex";
export default {
  name: "Products",
  emits: ["ready"],
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
          height: "400px",
        },
        {
          height: "400px",
        },
        {
          height: "400px",
        },
        {
          height: "400px",
        },
      ],
    };
  },
  computed: {

    ...mapGetters('layout', ['isFeaturePaneLayout', 'isMobile', 'isRtl']),

    mtClass() {

      if (this.disableMargin) return '';

      if (this.isFeaturePaneLayout && !this.isMobile) {
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
  watch: {
    productsLoading(loading) {
      if (!loading) {
        this.$emit("ready");
      }
    },
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
            this.categories = response.data?.data ?? [];
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
            this.brands = response.data?.data ?? [];
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
            this.paginatedItems = response.data?.data ?? [];
            this.totalItems = response.data?.meta?.total ?? 0;

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
/* Default layout — sidebar filter cards */
.widget_wrap :deep(.widget-style-1) {
  border-radius: 6px;
  overflow: hidden;
}

/* Default layout — products header / filter bar */
.shadow-card {
  border-radius: 6px;
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

.col-6 :deep(> div) {
  border-radius: 12px;
}

@media (max-width: 768px) {
  .compact-card :deep(> .single-product-item) {
    overflow: visible;
  }

  .compact-card :deep(.single-product-item > .position-relative) {
    overflow: hidden;
    border-radius: 12px 12px 0 0;
  }
}

.compact-card {
  padding: 6px 6px 0;
}

.compact-card :deep(.single-product-item) {
  width: 100% !important;
  box-sizing: border-box !important;
}

.compact-card :deep(img) {
  width: 100% !important;
  height: auto !important;
  display: block;
  margin: 0 auto;
}

@media (max-width: 500px) {
  .row.g-0.mobile-gap-10 {
    --bs-gutter-x: 0rem !important;
    --bs-gutter-y: 0rem !important;
  }

  .row.g-0.mobile-gap-10 .compact-card {
    padding: 3px 2px 0 !important;
  }

  .row.g-0.mobile-gap-10 .compact-card :deep(.single-product-item) {
    box-shadow: none !important;
    border-radius: 6px !important;
    margin-bottom: 0 !important;
  }
}

/* Default layout product grid (non-split-screen) */
.products-listing-grid>[class*="col-"] {
  display: flex;
  flex-direction: column;
  padding-bottom: 10px;
}

.products-listing-grid :deep(.single-product-item.style--eight) {
  width: 100% !important;
  margin-bottom: 0 !important;
  flex: 1;
  display: flex;
  flex-direction: column;
  border-radius: 6px;
  overflow: hidden;
}

.products-listing-grid :deep(.single-product-item.style--eight > .position-relative) {
  border-radius: 6px 6px 0 0;
  overflow: hidden;
}

.products-listing-grid :deep(.product-summary) {
  flex: 1;
  display: flex;
  flex-direction: column;
}

@media (max-width: 575px) {
  .products-listing-grid {
    margin-right: -5px;
    margin-left: -5px;
  }

  .products-listing-grid>[class*="col-"] {
    padding-right: 5px;
    padding-left: 5px;
  }

  .products-listing-grid :deep(.single-product-item.style--eight) {
    margin-bottom: 10px !important;
  }
}

/* RTL — explicit swap (split-screen content column is direction: ltr) */
.products-page--rtl .section-header {
  flex-direction: row-reverse;
  direction: ltr;
}

.products-page--rtl .header-title,
.products-page--rtl .header-count {
  direction: rtl;
  text-align: right;
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