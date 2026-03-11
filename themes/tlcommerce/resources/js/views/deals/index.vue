<template>
  <!-- <div class=""> -->
  <div :class="mtClass">
    <!-- <page-header :items="bItems" /> -->
    <div class="pb-60 light-bg" :class="{
      'force-mobile-layout': forcedMobile,
      'mobile-content-wrapper': forcedMobile
    }">
      <template v-if="!loadingDetails">
        <template v-if="dealsInfo != null">
          <div class="flash-deals-banner p-0" :style="{ background: dealsInfo.background_color }"
            v-if="dealsInfo.banner">
            <div class="text-center">
              <img :src="cleanImage(dealsInfo.banner)" :alt="dealsInfo.title" />
              <!-- <img :src="dealsInfo.banner" :alt="dealsInfo.title" /> -->
            </div>
          </div>
          <div class="flash-deals-countdown mb-60">
            <div class="custom-container2 py-2">
              <div class="row align-items-center">
                <div class="col-md-5">
                  <h3 class="mb-md-0" :style="{ color: dealsInfo.text_color }">
                    {{ dealsInfo.title }}
                  </h3>
                </div>
                <div class="col-md-7">
                  <countdown class="justify-content-md-end" :deadline="dealsInfo.deadline" />
                </div>
              </div>
            </div>
          </div>
        </template>
      </template>
      <template v-if="loadingDetails">
        <skeleton class="w-100 mb-20" height="200px"></skeleton>
      </template>
      <div class="custom-container2" v-if="!loadingProducts">
        <div class="row mobile-gap-10">
          <!-- <div
            v-for="product in paginatedItems"
            :key="product.id"
            class="col-lg-2 col-md-3 col-6"
          > -->
          <div v-for="product in paginatedItems" :key="product.id" class="col-6">
            <single-product :item="product" styleEight />
          </div>
        </div>

        <div class="row align-items-center mt-10">
          <div class="col-md-6">
            <!-- Showing Per Page -->
            <ShowingPerPage class="text-center text-md-start" :items-per-page="perPage" :total-items="totalItems"
              :current-page="currentPage" />
            <!-- Showing Per Page -->
          </div>
          <div class="col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
            <!-- Pagination -->
            <pagination v-if="totalItems > 0" :options="paginationOptions" v-model="currentPage" :records="totalItems"
              :per-page="perPage" @paginate="getProducts" />
            <!-- End Pagination -->
          </div>
        </div>
      </div>
      <div class="custom-container2" v-if="loadingProducts">
        <div class="row mobile-gap-10">
          <div class="col-lg-2 col-md-3 col-6" v-for="(item, index) in productSkeletons" :key="index">
            <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import ShowingPerPage from "@/components/ui/ShowingPerPage.vue";
import Countdown from "@/components/ui/Countdown.vue";
import Pagination from "v-pagination-3";
const axios = require("axios").default;
import { mapState, mapGetters } from "vuex";
export default {
  name: "DealProducts",
  components: {
    PageHeader,
    SingleProduct,
    ShowingPerPage,
    Countdown,
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
          text: this.$t("Deals"),
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
      paginatedItems: [],
      totalItems: 0,
      dealsInfo: {},
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

    forcedMobile() {
      // identical intent to MainLayout.vue
      if (this.isSplitScreen) {
        return true;
      }
      return this.isMobile;
    },

    mtClass() {

      if (this.isSplitScreen) {
        return 'mt-50';
      }
      else {
        return 'mt-1';
      }
    }

  },
  mounted() {
    this.getDealsInfo();
  },

  methods: {

    cleanImage(img) {
      if (!img) return '';

      // Only remove '/public' prefix, keep the leading '/'
      return img.replace(/^\/public/, '');
    },

    /**
     * Get deals Info
     */
    getDealsInfo() {
      window.scrollTo(0, 0);
      axios
        .post("/api/v1/ecommerce-core/deals-details", {
          permalink: this.$route.params.id,
        })
        .then((response) => {
          if (response.data.success) {
            this.dealsInfo = response.data.data;
            document.title = response.data.data.title;
            this.getProducts();
          }
          this.loadingDetails = false;
        })
        .catch((error) => {
          this.loadingDetails = false;
        });
    },
    /**
     * Get products
     */
    getProducts() {
      window.scrollTo(0, 0);
      this.loadingProducts = true;
      axios
        .post("/api/v1/ecommerce-core/deals-products", {
          perPage: this.perPage,
          page: this.currentPage,
          deal_id: this.dealsInfo.id,
        })
        .then((response) => {
          if (response.status == 200) {
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
.force-mobile-layout .row>[class*="col-"] {
  flex: 0 0 100% !important;
  max-width: 100% !important;
}

.force-mobile-layout .product-content .image,
.force-mobile-layout .product-content .product-img {
  min-width: 0 !important;
  max-width: 100px;
}



.force-mobile-layout .cart-image-review {
  max-width: 100% !important;
  height: auto !important;
  flex-shrink: 1 !important;
}

.force-mobile-layout .d-none.d-lg-block,
.force-mobile-layout .d-lg-block {
  display: none !important;
}

.force-mobile-layout .d-block.d-lg-none,
.force-mobile-layout .d-lg-none {
  display: block !important;
}


.force-mobile-layout .d-block.d-lg-none.col-12 {
  width: 100% !important;
  display: flex !important;
  flex-wrap: wrap !important;
  justify-content: space-between !important;
}
</style>
