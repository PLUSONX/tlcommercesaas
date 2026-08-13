<template>
  <!-- Modern layout: vertical image banners -->
  <div v-if="isModernLayout" class="modern-categories">
    <div class="modern-categories__list" v-if="!categoryLoading">
      <div
        v-for="(cat, index) in megaCategories"
        :key="cat.id || index"
        class="modern-categories__item"
      >
        <div
          class="modern-category-banner"
          :style="bannerStyle(cat)"
        >
          <router-link
            :to="`/products/category/${cat.slug}`"
            class="modern-category-banner__link"
          >
            <span class="modern-category-banner__overlay" aria-hidden="true"></span>
            <span class="modern-category-banner__title">{{ cat.name }}</span>
          </router-link>

          <button
            v-if="hasChildren(cat)"
            type="button"
            class="modern-category-banner__chevron"
            :class="{ 'is-expanded': isExpanded(cat) }"
            :aria-label="$t('Subcategories')"
            :aria-expanded="isExpanded(cat) ? 'true' : 'false'"
            @click.stop.prevent="toggleExpand(cat)"
          >
            <span class="material-icons">expand_more</span>
          </button>
        </div>

        <div v-if="hasChildren(cat) && isExpanded(cat)" class="modern-category-children">
          <router-link
            v-for="(subCat, i) in cat.childs.data"
            :key="subCat.id || `sub-${i}`"
            :to="`/products/category/${subCat.slug}`"
            class="modern-category-children__link"
          >
            {{ subCat.name }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="modern-categories__list" v-else>
      <skeleton
        v-for="n in 4"
        :key="`cat-skel-${n}`"
        class="modern-categories__skeleton"
        height="120px"
      ></skeleton>
    </div>
  </div>

  <!-- Default / split-screen: existing mega cards -->
  <div v-else class="">
    <page-header class="pt-3 pb-3" :items="bItems" />
    <div class="pt-60 pb-60 light-bg">
      <div class="custom-container2">
        <div class="row">
          <div class="col-12" v-if="!categoryLoading">
            <div class="card mb-30" v-for="(cat, index) in megaCategories" :key="index">
              <div class="card-header">
                <h5 class="mb-0 py-1">
                  <router-link :to="`/products/category/${cat.slug}`">
                    {{ cat.name }}
                  </router-link>
                </h5>
              </div>
              <div class="card-body">
                <div v-if="cat.childs.data" class="sub-categories">
                  <div class="row gx-0">
                    <div class="col-12">
                      <div class="row child-category-wrapper">
                        <div v-for="(subCatGroup, i) in cat.childs.data" :key="`subCatGroup-${i}`"
                          class="col-lg-2 child-category">
                          <!-- Sub Category Group -->
                          <div class="sub-category-group">
                            <h6 class="sub-category-title">
                              <router-link :to="`/products/category/${subCatGroup.slug}`">
                                {{ subCatGroup.name }}
                              </router-link>
                            </h6>

                            <ul class="sub-category list-unstyled mb-0">
                              <li v-for="(item, j) in subCatGroup.childs.data" :key="`item-${j}`"
                                class="sub-category-link">
                                <router-link :to="`/products/category/${item.slug}`">
                                  {{ item.name }}
                                </router-link>
                              </li>
                            </ul>
                          </div>
                          <!-- End Sub Category Group -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12" v-if="categoryLoading">
            <skeleton class="w-100 mb-20" height="300px"></skeleton>
            <skeleton class="w-100 mb-20" height="300px"></skeleton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  name: "Categories",
  components: {
    PageHeader,
  },
  data() {
    return {
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Categories"),
          active: true,
        },
      ],
      megaCategories: [],
      categoryLoading: true,
      expandedCategoryId: null,
    };
  },
  computed: {
    ...mapGetters("layout", ["layoutType"]),

    isModernLayout() {
      return this.layoutType === "modern";
    },
  },
  mounted() {
    document.title = this.$t("All Categories");
    this.getMegaCategories();
  },
  methods: {
    /**
     * Get Mega categories
     */
    getMegaCategories() {
      const headers = {
        "Content-Type": "application/json",
        "Accept-Language": localStorage.getItem("locale") || "en",
      };

      axios
        .post("/api/v1/ecommerce-core/mega-categories", {}, { headers })
        .then((response) => {
          if (response.status === 200) {
            console.log("response in category.vue: ", response);
            this.megaCategories = response.data.data;
          }
          this.categoryLoading = false;
        })
        .catch((error) => {
          console.error("getMegaCategories error:", error);
          this.categoryLoading = false;
        });
    },
    // getMegaCategories() {
    //   console.log("getMegaCategories method called!!!");
    //   const headers = {
    //     "Content-Type": "application/json",
    //     "Accept-Language": localStorage.getItem("locale") || "en",
    //   };

    //   axios
    //     .get("/api/v1/ecommerce-core/mega-categories", {
    //       headers: headers,
    //     })
    //     .then((response) => {
    //       console.log("response from categories 1: ", response);
    //       if (response.status === 200) {
    //         console.log("response from categories 2: ", response);
    //         this.megaCategories = response.data.data;
    //       }
    //       this.categoryLoading = false;
    //     })
    //     .catch((error) => {
    //       this.categoryLoading = false;
    //     });
    // },

    categoryImage(cat) {
      const img = cat?.icon;
      if (!img) return "";
      return img.startsWith("/public") ? img.slice(7) : img;
    },

    bannerStyle(cat) {
      const src = this.categoryImage(cat);
      if (!src) {
        return {
          backgroundImage: "linear-gradient(135deg, #5a4030 0%, #2c1c14 100%)",
        };
      }
      return {
        backgroundImage: `url('${src}')`,
      };
    },

    hasChildren(cat) {
      return Array.isArray(cat?.childs?.data) && cat.childs.data.length > 0;
    },

    categoryKey(cat) {
      return cat?.id ?? cat?.slug ?? cat?.name;
    },

    isExpanded(cat) {
      return this.expandedCategoryId === this.categoryKey(cat);
    },

    toggleExpand(cat) {
      const key = this.categoryKey(cat);
      this.expandedCategoryId = this.expandedCategoryId === key ? null : key;
    },
  },
};
</script>

<style scoped>
.child-category-wrapper .child-category:not(:last-child) {
  margin-bottom: 2rem !important;
}

/* Modern categories banner list */
.modern-categories {
  background: #f3f2f0;
  padding: 12px 12px 28px;
  min-height: 40vh;
}

.modern-categories__list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  max-width: 720px;
  margin: 0 auto;
}

.modern-categories__skeleton {
  width: 100%;
  border-radius: 20px;
  overflow: hidden;
}

.modern-category-banner {
  position: relative;
  min-height: 120px;
  border-radius: 20px;
  overflow: hidden;
  background-size: cover;
  background-position: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.modern-category-banner__link {
  display: block;
  position: relative;
  min-height: 120px;
  padding: 18px 48px 16px 18px;
  text-decoration: none;
  color: #fff;
}

.modern-category-banner__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg,
    rgba(20, 16, 12, 0.15) 0%,
    rgba(20, 16, 12, 0.55) 100%
  );
  pointer-events: none;
}

.modern-category-banner__title {
  position: absolute;
  left: 18px;
  bottom: 16px;
  z-index: 1;
  margin: 0;
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 1.35rem;
  font-weight: 500;
  line-height: 1.2;
  color: #fff;
  letter-spacing: 0.01em;
}

.modern-category-banner__chevron {
  position: absolute;
  right: 12px;
  bottom: 12px;
  z-index: 2;
  width: 36px;
  height: 36px;
  padding: 0;
  border: 0;
  border-radius: 50%;
  background: transparent;
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.modern-category-banner__chevron .material-icons {
  font-size: 26px;
  transition: transform 0.2s ease;
}

.modern-category-banner__chevron.is-expanded .material-icons {
  transform: rotate(180deg);
}

.modern-category-children {
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin-top: 6px;
  padding: 4px 4px 0;
}

.modern-category-children__link {
  display: block;
  padding: 12px 16px;
  border-radius: 12px;
  background: #fff;
  color: #2d2a26;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 500;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
}

.modern-category-children__link:hover {
  background: #fafaf9;
  color: var(--mainC, #2d2a26);
}
</style>
