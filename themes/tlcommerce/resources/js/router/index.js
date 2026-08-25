import { createRouter, createWebHistory } from "vue-router";
import { loadLanguageAsync } from "../i18n_setup";
import store from "../store";
import { isChunkLoadError, reloadOnceForChunkError } from "../utils/chunkLoadRecovery";
import { safeGetItem } from "../utils/safeStorage";

/**
 * Check customer is logged in or not
 * 
 * @param {*} to 
 * @param {*} from 
 * @param {*} next 
 */
function customerguard(to, from, next) {
  // console.log("store.state.isCustomerLogin: ", store.state.isCustomerLogin);
  // console.log("store.state.customerToken: ", store.state.customerToken);
  if (store.state.isCustomerLogin) {
    next();
  } else {
    next('/login');
  }
}
function isCustomerLogin(to, from, next) {
  // console.log("isCustomerLogin method called!!!");
  // console.log("isCustomerLogin: ", store.state.isCustomerLogin);

  if (!store.state.isCustomerLogin) {
    next();
  } else {
    next('/dashboard');
  }
}
const router = createRouter({
  routes: [
    {
      path: "/",
      name: "home",
      component: () => import(/* webpackChunkName: "Home" */ '../views/index.vue'),
    },
    // {
    //   path: "/",
    //   redirect: { name: 'products' }
    // },
    {
      path: "/store-info",
      name: "storeInfo",
      component: () => import(/* webpackChunkName: "StoreInfo" */ '../views/store-info/index.vue'),
    },
    {
      path: "/feedback",
      name: "customerFeedback",
      component: () => import(/* webpackChunkName: "CustomerFeedback" */ '../views/feedback/index.vue'),
    },
    {
      path: "/quiz/:slug",
      name: "Quiz",
      component: () => import(/* webpackChunkName: "Quiz" */ '../views/quiz/_slug/index.vue'),
    },
    {
      path: "/products",
      name: "products",
      component: () => import(/* webpackChunkName: "ProductPage" */ '../views/products/index.vue'),
    },
    {
      path: "/categories",
      name: "categories",
      component: () => import(/* webpackChunkName: "ProductCategories" */ '../views/category/index.vue'),
    },
    {
      path: "/products/:id",
      name: "product",
      component: () => import(/* webpackChunkName: "ProductDetails" */ '../views/products/_id/index.vue'),
    },
    {
      path: "/product/search",
      name: "searchProduct",
      component: () => import(/* webpackChunkName: "SearchResult" */ './../views/products/search/index.vue'),
    },
    {
      path: "/products/category/:id",
      name: "categoryProducts",
      component: () => import(/* webpackChunkName: "CategoryProducts" */ '../views/products/category/_id/index.vue'),
    },
    {
      path: "/deals/:id",
      name: "Deals",
      component: () => import(/* webpackChunkName: "DealPage" */ '../views/deals/index.vue'),
    },
    {
      path: "/collection/:id",
      name: "Collection",
      component: () => import(/* webpackChunkName: "CollectionPage" */ '../views/products/collection/CollectionProducts.vue'),
    },
    {
      path: "/blog/category/:id",
      name: "blog-category",
      component: () => import(/* webpackChunkName: "CategoryBlogPage" */ '../views/blog/category/_id/index.vue'),
    },
    {
      path: "/blog/tag/:id",
      name: "blog-tag",
      component: () => import(/* webpackChunkName: "TagBlogs" */ '../views/blog/tag/_id/index.vue'),
    },
    {
      path: "/blogs/search-result",
      name: "blog-search-result",
      component: () => import(/* webpackChunkName: "SearchBlogs" */ '../views/blog/search/index.vue'),
    },

    {
      path: "/blog",
      name: "blogs",
      component: () => import(/* webpackChunkName: "ListBlogs" */ '../views/blog/index.vue'),
    },
    {
      path: "/blog/:id",
      name: "blog",
      component: () => import(/* webpackChunkName: "SingleBlog" */ '../views/blog/_id/index.vue'),
    },
    {
      path: "/blog-preview",
      name: "blog-preview",
      component: () => import(/* webpackChunkName: "PreviewBlog" */ '../views/blog-preview/index.vue'),
    },
    {
      path: "/page/:id(.*)",
      name: "page",
      component: () => import(/* webpackChunkName: "SinglePage" */ '../views/page/_id/index.vue'),
    },
    {
      path: "/page-preview/:id",
      name: "page-preview",
      component: () => import(/* webpackChunkName: "PagePreview" */ '../views/page-preview/_id/index.vue'),
    },
    {
      path: "/cart",
      name: "Cart",
      redirect: { name: "Checkout", query: { step: "cart" } },
    },
    {
      path: "/select/location",
      name: "Location",
      component: () => import(/* webpackChunkName: "ShippingCart" */ '../views/location/index.vue'),
    },
    {
      path: "/checkout",
      name: "Checkout",
      component: () => import(/* webpackChunkName: "CustomerCheckout" */ '../views/checkout/index.vue'),
    },
    {
      path: "/order-success/:id",
      name: "Confirm Order",
      component: () => import(/* webpackChunkName: "OrderSuccess" */ '../views/checkout/confirm-order/index.vue'),
    },
    {
      path: "/compare",
      name: "Compare",
      component: () => import(/* webpackChunkName: "ProductCompare" */ '../views/compare/index.vue'),
    },
    //customer dashboard
    {
      path: "/dashboard",
      name: "Dashboard",
      component: () => import(/* webpackChunkName: "CustomerDashboard" */ '../views/dashboard/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/purchase-history",
      name: "Purchase History",
      component: () => import(/* webpackChunkName: "CustomerPurchaseHistory" */ '../views/dashboard/purchase-history/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/order-details/:id",
      name: "Order Details",
      component: () => import(/* webpackChunkName: "CustomerOrderDetails" */ '../views/dashboard/order-details/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/order-review/:id",
      name: "OrderReview",
      component: () => import(/* webpackChunkName: "CustomerOrderReview" */ '../views/dashboard/order-review/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/guest/order-details/:id",
      name: "Guest Order Details",
      component: () => import(/* webpackChunkName: "GuestOrderDetails" */ '../views/guest/order-details/index.vue'),
    },
    {
      path: "/dashboard/address",
      name: "CustomerAddress",
      component: () => import(/* webpackChunkName: "CustomerAddress" */ '../views/dashboard/address/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/wishlist",
      name: "Wishlist",
      component: () => import(/* webpackChunkName: "CustomerWishlist" */ '../views/dashboard/wishlist/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/wallet",
      name: "wallet",
      component: () => import(/* webpackChunkName: "CustomerWallet" */ '../views/dashboard/my-wallet/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/refund/requests",
      name: "CustomerRefundRequest",
      component: () => import(/* webpackChunkName: "CustomerOrderRefundList" */ '../views/dashboard/refunds/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/refund/details/:id",
      name: "CustomerRefundRequestDetails",
      component: () => import(/* webpackChunkName: "CustomerRefundDetails" */ '../views/dashboard/refund-details/index.vue'),
      beforeEnter: customerguard
    },
    {
      path: "/dashboard/manage-account",
      name: "ManageAccount",
      component: () => import(/* webpackChunkName: "CustomerManageAccount" */ '../views/dashboard/manage-account/index.vue'),
      beforeEnter: customerguard
    },
    //End Customer dashboard
    {
      path: "/register",
      name: "CustomerRegister",
      component: () => import(/* webpackChunkName: "CustomerRegistration" */ '../views/auth/register.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/login",
      name: "CustomerLogin",
      component: () => import(/* webpackChunkName: "CustomerLogin" */ '../views/auth/login.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/password/forgot",
      name: "CustomerPasswordForgot",
      component: () => import(/* webpackChunkName: "CustomerForgotPassword" */ '../views/auth/forgotPassword.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/password/reset",
      name: "ResetPassword",
      component: () => import(/* webpackChunkName: "CustomerPasswordReset" */ '../views/auth/resetPassword.vue'),
    },
    {
      path: "/email/reset",
      name: "ResetEmail",
      component: () => import(/* webpackChunkName: "CustomerEmailReset" */ '../views/auth/resetEmail.vue'),
    },
    {
      path: "/customer/email-verification",
      name: "CustomerEmailVerification",
      component: () => import(/* webpackChunkName: "CustomerEmailVerification" */ '../views/auth/verification.vue'),
    },
    {
      path: "/seller-register",
      name: "SellerRegister",
      component: () => import(/* webpackChunkName: "SellerRegistration" */ '../views/multivendor/auth/register.vue')
    },
    /**
     * Shop Routes
     */
    {
      path: "/all-shops",
      name: "AllShops",
      component: () => import(/* webpackChunkName: "AllShops" */ '../shop/views/all_shops.vue'),
    },
    {
      path: "/shop/:slug",
      name: "ShopPage",
      component: () => import(/* webpackChunkName: "ShopPage" */ '../shop/views/shop_page.vue'),
    },

    {
      path: "/404",
      name: "PageNotExist",
      component: () => import(/* webpackChunkName: "ErrorPage" */ '../views/Error.vue'),
    },
    {
      path: "/:catchAll(.*)", // Unrecognized path automatically matches 404
      redirect: "/404",
    },
  ],
  scrollBehavior(_to, _from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    return { top: 0, left: 0 };
  },
  history: createWebHistory('/'),
});

router.beforeEach((to, from, next) => {
  const lang = safeGetItem("locale") || "en";
  loadLanguageAsync(lang)
    .then(() => next())
    .catch(() => next());
});

router.afterEach(() => {
  requestAnimationFrame(() => {
    const scrollEl = document.querySelector(".content-split-nudge");
    if (scrollEl) {
      scrollEl.scrollTop = 0;
    }
    window.scrollTo(0, 0);
  });
});

router.onError((err) => {
  if (isChunkLoadError(err)) {
    reloadOnceForChunkError();
  }
});

export default router;
