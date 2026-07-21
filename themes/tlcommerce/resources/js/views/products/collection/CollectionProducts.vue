<template>
  <template v-if="isSplitScreen">
    <div :class="mtClass">

      <div class="custom-container2">

        <div class="card mb-3">

          <div class="col-lg-12">
            <div class="section-header d-flex justify-content-between align-items-center">

              <div class="header-title">
                <h3 class="product_header mb-0" v-if="!loadingDetails">
                  {{ collectionDetails?.name }}
                </h3>
                <h3 class="product_header mb-0" v-if="loadingDetails">
                  <skeleton :border-radius="5" :width="150" :height="30"></skeleton>
                </h3>
              </div>

              <div class="header-count">
                <div v-if="loadingDetails">
                  <p class="mb-0">
                    <skeleton :border-radius="5" :width="50" :height="10"></skeleton>
                  </p>
                </div>
                <div v-if="!loadingDetails">
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
      <div class="row g-0 mobile-gap-10" v-if="loadingProducts">

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
  </template>

  <template v-else>
    <div class="">
      <!-- <page-header :items="bItems" /> -->
      <div class="pb-60 light-bg">
        <template v-if="!loadingDetails">
          <template v-if="collectionDetails != null">
            <div class="flash-deals-banner p-0" v-if="collectionDetails.image != null">
              <div class="text-center">
                <img :src="cleanImage(collectionDetails.image)" :alt="collectionDetails.name" />
              </div>
            </div>
            <div class="flash-deals-countdown mb-60">
              <div class="custom-container2 py-2">
                <div class="row align-items-center">
                  <div class="col-md-5">
                    <h3 class="mb-md-0">
                      {{ collectionDetails.name }}
                    </h3>
                  </div>
                  <div class="col-md-7"></div>
                </div>
              </div>
            </div>
          </template>
        </template>
        <template v-if="loadingDetails">
          <skeleton class="w-100 mb-20" height="250px"></skeleton>
        </template>

        <div class="custom-container2" v-if="!loadingProducts">
          <div class="row mobile-gap-10">
            <div v-for="product in paginatedItems" :key="product.id" class="col-lg-3 col-md-4 col-6">
              <single-product :item="product" styleEight />
            </div>
          </div>
          <div class="row align-items-center mt-10" v-if="!loadingProducts">
            <div class="col-md-6">
              <ShowingPerPage class="text-center text-md-start" :items-per-page="perPage" :total-items="totalItems"
                :current-page="currentPage" />
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
              <pagination :options="paginationOptions" v-model="currentPage" :records="totalItems" :per-page="perPage"
                @paginate="getProducts" />
            </div>
          </div>
        </div>
        <div class="custom-container2" v-if="loadingProducts">
          <div class="row mobile-gap-10">
            <div class="col-lg-3 col-md-4 col-6" v-for="(item, index) in productSkeletons" :key="index">
              <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
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
import Pagination from "v-pagination-3";
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  name: "CollectionProducts",
  components: {
    PageHeader,
    SingleProduct,
    ShowingPerPage,
    Pagination,
  },
  data() {
    return {
      wToggle: false,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Collection"),
          active: true,
        },
      ],
      currentPage: 1,
      perPage:
        this.$store.state.siteSettings != null
          ? this.$store.state.siteSettings.product_per_page
          : 18,
      loadingDetails: true,
      loadingProducts: true,
      collectionDetails: null,
      paginatedItems: [],
      totalItems: 0,
      showFull: false,
      success: false,
      loading: false,
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
      if (this.isSplitScreen && !this.isMobile) {
        return 'mt-50';
      }
      return 'mt-1';
    },
  },
  mounted() {
    this.getCollectionDetails();
    this.getProducts();
  },
  methods: {
    cleanImage(img) {
      if (!img) return '';
      return img.replace(/^\/public/, '');
    },
    getCollectionDetails() {
      window.scrollTo(0, 0);
      axios
        .post("/api/theme/tlcommerce/v1/collection-details", {
          id: this.$route.params.id,
        })
        .then((response) => {
          if (response.data.success) {
            this.collectionDetails = response.data.details;
          }
          this.loadingDetails = false;
        })
        .catch((error) => {
          this.loadingDetails = false;
        });
    },
    getProducts() {
      window.scrollTo(0, 0);
      axios
        .post("/api/theme/tlcommerce/v1/collection-all-products", {
          perPage: this.perPage,
          page: this.currentPage,
          id: this.$route.params.id,
        })
        .then((response) => {
          if (response.data.success) {
            this.paginatedItems = response.data.data;
            this.totalItems = response.data.meta.total;
          }
          this.loadingProducts = false;
        })
        .catch((error) => {
          this.loadingProducts = false;
        });
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
  display: flex;
  flex-direction: column;
}

.light-bg .row.mobile-gap-10 > [class*="col-"] {
  display: flex;
  flex-direction: column;
}

.light-bg .row.mobile-gap-10 :deep(.single-product-item.style--eight) {
  width: 100% !important;
  margin-bottom: 0 !important;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.light-bg .row.mobile-gap-10 :deep(.product-summary) {
  flex: 1;
  display: flex;
  flex-direction: column;
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
  .mobile-gap-10 {
    --bs-gutter-x: 0rem !important;
    --bs-gutter-y: 0rem !important;
  }

  .mobile-gap-10 .compact-card {
    padding: 3px 2px 0 !important;
  }

  .mobile-gap-10 .compact-card :deep(.single-product-item) {
    box-shadow: none !important;
    border-radius: 6px !important;
    margin-bottom: 0 !important;
  }
}
</style>
