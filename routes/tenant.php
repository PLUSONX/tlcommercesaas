<?php

// declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
// use App\Http\Controllers\Admin\DashboardController;
// use Core\Http\Controllers\DashboardController;
// use Plugin\Saas\Http\Controllers\Admin\DashboardController;
// use Plugin\Saas\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Core\Http\Controllers\Auth\AuthenticationController;
use Core\Http\Controllers\ThemesController;
// use Plugin\Saas\Http\Controllers\Admin\DashboardController;
use Core\Http\Controllers\DashboardController;
use App\Models\User;
use Plugin\Multivendor\Http\Controllers\Seller\OrderController;
use Plugin\Saas\Http\Controllers\User\UserController;
use Theme\Default\Http\Controllers\Frontend\FrontendController;
use Theme\TLCommerce\Http\Controllers\Api\SliderController;
use Theme\TLCommerce\Http\Controllers\Api\HomePageController;
use Theme\TLCommerce\Http\Controllers\Frontend\BlogController;
use Theme\TLCommerce\Http\Controllers\Frontend\PagesController;
use Plugin\TlcommerceCore\Http\Controllers\LayoutSettingsController;
use Theme\TLCommerce\Http\Controllers\Frontend\NewsletterController;
use Theme\TLCommerce\Http\Controllers\Frontend\ThemeOptionController;
use Plugin\TlcommerceCore\Http\Controllers\Api\CartController;
use Plugin\TlcommerceCore\Http\Controllers\Api\ProductController;
use Plugin\TlcommerceCore\Http\Controllers\Api\CustomerController;
use Plugin\TlcommerceCore\Http\Controllers\Api\SettingsController;
use Plugin\TlcommerceCore\Http\Controllers\Api\NotificationController;
use Plugin\TlcommerceCore\Http\Controllers\Api\CustomerAddressController;
use Plugin\TlcommerceCore\Http\Controllers\Api\CustomerWishlistController;
use Core\Http\Controllers\SystemController;
use Core\Http\Controllers\Api\TranslationController;
use Core\Http\Controllers\SeoController;
use Core\Http\Controllers\MenuController;
use App\Http\Controllers\UpdateController;
use Core\Http\Controllers\EmailController;
use Core\Http\Controllers\MediaController;
use Core\Http\Controllers\StyleController;
use Core\Http\Controllers\BackupController;
use Core\Http\Controllers\SiteMapController;
use Core\Http\Controllers\PluginsController;
use Core\Http\Controllers\LanguageController;
use Core\Http\Controllers\ActivityLogController;
use Core\Http\Controllers\AIAssistantController;
use Core\Http\Controllers\RolePermissionController;
use Core\Http\Controllers\GeneralSettingsController;
use Core\Http\Controllers\TagController as CoreTagController;
use Core\Http\Controllers\BlogController as CoreBlogController;
use Core\Http\Controllers\PageController as CorePageController;
use Core\Http\Controllers\CommentController as CoreCommentController;
use Core\Http\Controllers\BlogCategoryController as CoreBlogCategoryController;
use Core\Http\Controllers\Tenant\TagController as TenantTagController;
use Core\Http\Controllers\Tenant\PageController as TenantPageController;
use Core\Http\Controllers\Tenant\BlogController as TenantBlogController;
use Core\Http\Controllers\Tenant\CommentController as TenantCommentController;
use Core\Http\Controllers\Tenant\BlogCategoryController as TenantBlogCategoryController;
// use Theme\Default\Http\Controllers\Frontend\FrontendController;
// use plugins\saas\src\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// Route::middleware(['tenant'])->group(function () {

//     Route::get('/', [FrontendController::class, 'EcommerceHome'])->name('theme.ecommerce.home');

//     Route::get('/test-tenant', function() {
    
//         $tenant = tenant();
//         $user = User::find($tenant->user_id);
//         return [
//             'tenant_initialized' => tenancy()->initialized,
//             'tenant_id' => tenant('id'),
//             'tenant_data' => tenant(),
//             'user' => $user,
//             // 'domain' => request()->getHost(),
//             // 'full_url' => request()->url(),
//         ];
//     });

//     Route::group(['prefix' => 'api/v1'], function () {
//         Route::get('/locale/{lang}', [TranslationController::class, 'themeTranslations']);
//         Route::get('/cache-reset', [SystemController::class, 'clearSystemCacheFromApi']);
//     });


//     Route::group(['prefix' => 'api/v1/ecommerce-core'], function () {
//         /**
//          * Site properties
//          * 
//          * /api/v1/ecommerce-core
//          */
//         Route::get('site-properties', [SettingsController::class, 'siteProperties']);
//         Route::get('phone-codes', [SettingsController::class, 'phoneCodes']);
//         /**
//          * Product routes
//          * 
//          * api/v1/ecommerce-core
//          */
//         Route::get('product-configuration', [ProductController::class, 'productConfiguration']);
//         Route::post('products', [ProductController::class, 'products']);
//         Route::post('product-details', [ProductController::class, 'productDetails']);
//         Route::post('single-variant-info', [ProductController::class, 'singleVariantInfo']);
//         Route::post('color-variant-images', [ProductController::class, 'colorVariantImages']);
//         Route::post('related-products', [ProductController::class, 'relatedProducts']);
//         Route::post('top-selling-products', [ProductController::class, 'topSellingProducts']);
//         Route::post('get-product-reviews', [ProductController::class, 'productReviews']);
//         Route::get('brands', [ProductController::class, 'brands']);
//         Route::get('categories', [ProductController::class, 'categories']);
//         Route::get('parent-categories', [ProductController::class, 'parentCategories']);
//         Route::get('mega-categories', [ProductController::class, 'megaCategories']);



//         Route::post('category-details', [ProductController::class, 'categoryDetails']);
//         Route::post('deals-details', [ProductController::class, 'dealsDetails']);
//         Route::post('deals-products', [ProductController::class, 'dealsProducts']);
//         Route::post('search-suggestions', [ProductController::class, 'searchSuggestions']);
//         Route::post('search-products', [ProductController::class, 'searchProducts']);
//         Route::post('compare-items-details', [ProductController::class, 'compareItems']);
//         /**
//          * Shipping Locations
//          * 
//          */
//         Route::get("get-countries", [OrderController::class, 'countryList']);
//         Route::post("get-states-of-countries", [OrderController::class, 'countryStates']);
//         Route::post("get-cities-of-state", [OrderController::class, 'stateCities']);
//         /**
//          * Customer auth routes 
//          * 
//          * /api/v1/ecommerce-core/auth
//          */
//         Route::group(['prefix' => 'auth'], function () {
//             Route::post('customer-registration', [CustomerController::class, 'customerRegistration']);
//             Route::post('verify-customer-email', [CustomerController::class, 'verifyCustomerEmail']);
//             Route::post('customer-forgot-password', [CustomerController::class, 'customerForgotPassword']);
//             Route::post('verify-customer-reset-password-token', [CustomerController::class, 'VerifyCustomerResetPasswordToken']);
//             Route::post('customer-reset-password', [CustomerController::class, 'customerResetPassword']);
//             Route::post('customer-reset-email', [CustomerController::class, 'customerResetEmail']);
//             Route::post('customer-login', [CustomerController::class, 'customerLogin']);
//             Route::get('customer-refresh-auth', [CustomerController::class, 'refresh']);
//             Route::get('customer-logout', [CustomerController::class, 'customerLogout']);
//         });

//         /**
//          * Customer authenticated routes
//          * 
//          * /api/v1/ecommerce-core/customer
//          */
//         Route::group(['prefix' => 'customer', 'middleware' => 'auth:jwt-customer'], function () {
//             /**
//              * Customer information
//              * 
//              * /api/v1/ecommerce-core/customer
//              * 
//              */
//             Route::get('customer-basic-info', [CustomerController::class, 'customerBasicInfo']);
//             Route::post('update-customer-basic-info', [CustomerController::class, 'updateCustomerBasicInfo']);
//             Route::get('customer-email-reset-link', [CustomerController::class, 'customerEmailResetLink']);
//             Route::get('customer-dashboard', [CustomerController::class, 'customerDashboardDetails']);
//             Route::get('customer-summary', [CustomerController::class, 'customerSummary']);
//             /**
//              * Customer address
//              * 
//              * /api/v1/ecommerce-core/customer
//              */
//             Route::get('get-customer-all-address', [CustomerAddressController::class, 'customerAllAddress']);
//             Route::post('get-customer-address-details', [CustomerAddressController::class, 'customerAddressDetails']);
//             Route::post('store-customer-address', [CustomerAddressController::class, 'storeCustomerAddress']);
//             Route::post('update-customer-address', [CustomerAddressController::class, 'updateCustomerAddress']);

//             /**
//              * Customer wishlist
//              * 
//              * /api/v1/ecommerce-core/customer
//              */
//             Route::post('store-product-to-wishlist', [CustomerWishlistController::class, 'storeProductToWishlist']);
//             Route::post('get-customer-wishlist-product', [CustomerWishlistController::class, 'getCustomerWishlistProducts']);
//             Route::post('product-remove-from-wishlist', [CustomerWishlistController::class, 'removeProductFromWishlist']);
//             /**
//              * Customer cart
//              * 
//              * /api/v1/ecommerce-core/customer/cart
//              */
//             Route::post('cart/store-cart-item', [CartController::class, 'storeCartProduct']);
//             Route::get('cart/cart-items-list', [CartController::class, 'getCartItems']);
//             Route::post('cart/remove-item', [CartController::class, 'removeCartItem']);
//             Route::post('cart/update-cart-item', [CartController::class, 'updateCartItem']);
//             /**
//              * Customer order
//              * 
//              * /api/v1/ecommerce-core/customer
//              */
//             Route::post('order/create', [OrderController::class, 'createCustomerOrder']);
//             Route::post('cancel-order', [OrderController::class, 'cancelOrder']);
//             Route::post('order/details', [OrderController::class, 'customerOrderDetails']);
//             Route::post('order/return', [OrderController::class, 'customerOrderReturn']);
//             Route::post('return-requests', [OrderController::class, 'customerReturnRequests']);
//             Route::post('make-order-payment', [OrderController::class, 'makeOrderPayment']);
//             Route::post('orders', [OrderController::class, 'customerOrders']);
//             Route::post('review-product', [OrderController::class, 'reviewProduct']);

//             /**
//              * Customer notification
//              * 
//              * /api/v1/ecommerce-core/customer
//              */
//             Route::post('notification/list', [NotificationController::class, 'customerNotifications']);
//             Route::post('mark-as-read-single-notification', [NotificationController::class, 'markAsRead']);
//             Route::get('mark-as-read-all-notification', [NotificationController::class, 'markAsReadAllNotification']);
//         });
//         /**
//          * Customer checkout
//          * 
//          * /api/v1/ecommerce-core
//          */
//         //Attachment
//         Route::post('upload-attachment-in-order', [OrderController::class, 'uploadOrderAttachment']);
//         Route::post('remove-attachment-in-order', [OrderController::class, 'removeOrderAttachment']);

//         Route::post('cart/validate-cart-items', [OrderController::class, 'validateCartItems']);
//         Route::post('apply-coupon', [OrderController::class, 'applyCoupon']);
//         Route::post('get-shipping-options', [OrderController::class, 'shippingOptions']);
//         Route::post('active-payment-methods', [OrderController::class, 'activePaymentMethods']);
//         Route::post('guest/checkout', [OrderController::class, 'guestCheckout']);
//         Route::post('guest/order/details', [OrderController::class, 'guestCustomerOrderDetails']);
//     });

//     Route::group(['prefix' => 'api/theme/tlcommerce/v1'], function () {
//     Route::get('/active-sliders', [SliderController::class, 'sliders']);
//     Route::get('/active-home-page-sections', [HomePageController::class, 'homePageSections']);

//     //Deals Sections
//     Route::post('/deal-details', [HomePageController::class, 'dealsDetails']);
//     Route::post('/deal-products', [HomePageController::class, 'dealProducts']);

//     //Collections sections
//     Route::post('/collection-details', [HomePageController::class, 'collectionDetails']);
//     Route::post('/collection-all-products', [HomePageController::class, 'collectionAllProducts']);

//     //Blogs lists
//     Route::post('/home-page-blogs-list', [HomePageController::class, 'homePageBlogs']);


//     //Menus & widgets
//     Route::get('get-all-menus-for-ecommerce-home', [LayoutSettingsController::class, 'getAllMenusForEcommerceHome']);
//     Route::get('get-footer-widgets', [LayoutSettingsController::class, 'getFooterWidgets']);


//     //Theme Options
//     Route::get('/get-back-to-top-style', [ThemeOptionController::class, 'getBackToTopStyle']);
//     Route::get('/get-404-page-style', [ThemeOptionController::class, 'get404PageStyle']);
//     Route::get('/get-preloader-style', [ThemeOptionController::class, 'getPreloaderStyle']);
//     Route::get('/get-theme-color', [ThemeOptionController::class, 'getThemeColor']);
//     Route::get('/get-theme-style', [ThemeOptionController::class, 'getThemeStyle']);
//     Route::get('/get-theme-color', [ThemeOptionController::class, 'getPresentColor']);
//     Route::get('/get-blog-theme-style', [ThemeOptionController::class, 'getBlogThemeStyle']);

//     //Blogs
//     Route::get('/blogs', [BlogController::class, 'blogs']);
//     Route::get('/get-related-blogs', [BlogController::class, 'getRelatedBlogs']);
    
//     Route::get('/get-blog-sidebar-widgets', [LayoutSettingsController::class, 'getBlogSidebarWidgets']);

//     Route::post('/blog/comment/create', [BlogController::class, 'createBlogComment']);
//     Route::post('/blog/comment', [BlogController::class, 'loadBlogComment']);
//     Route::get('/blog/search', [BlogController::class, 'blogBySearch']);
//     Route::get('/blog/{slug}', [BlogController::class, 'blog_details']);
//     Route::get('/preview-blog/{slug}', [BlogController::class, 'previewBlog']);

//     //Pages
//     Route::get('/page/{slug}', [PagesController::class, 'pageDetails']);
//     Route::get('/preview-page/{slug}', [PagesController::class, 'previewPage']);
//     Route::post('/newsletter-store', [NewsletterController::class, 'store']);
// });
// });



// Route::group(['prefix' => getAdminPrefix(), 'middleware' => ['tenant']], function () {

    

//     Route::get('/test-tenant', function() {
    
//         $tenant = tenant();
//         $user = User::find($tenant->user_id);
//     return [
//         'tenant_initialized' => tenancy()->initialized,
//         'tenant_id' => tenant('id'),
//         'tenant_data' => tenant(),
//         'user' => $user,
//         // 'domain' => request()->getHost(),
//         // 'full_url' => request()->url(),
//     ];
// });

// // In routes/tenant.php - add this at the very top after the use statements
// Route::get('/debug-controller', function() {
//     return [
//         'tenant_initialized' => tenancy()->initialized,
//         'IS_USER_REGISTERED' => env('IS_USER_REGISTERED'),
//         'view_exists' => view()->exists('plugin/saas::includes.dashboard'),
//         'registered_namespaces' => app('view')->getFinder()->getHints(),
//         'saas_view_file_exists' => file_exists(base_path('plugins/saas/views/includes/dashboard.blade.php')),
//         'active_plugins' => function_exists('getActivePlugins') ? getActivePlugins(true) : 'function not found',
//     ];
// });
//     // Test route
//     Route::get('/test', function() {
//         return 'Tenant routes are working! Tenant: ' . tenant('id');
//     });
    

//     Route::get('/logout', [AuthenticationController::class, 'logout'])->name('tenant.logout');


//     // Guest routes (login)
//         Route::middleware(['guest'])->group(function () {
//         Route::get('/', [AuthenticationController::class, 'login'])->name('tenant.login');
//         Route::get('/login', [AuthenticationController::class, 'login'])->name('tenant.login');
//         Route::post('/login', [AuthenticationController::class, 'attemptLogin'])->name('tenant.login.post');
//     });
    
    
//     // Authenticated routes
//     Route::group(['middleware' => 'auth'], function () {

//         Route::get('/dashboard', [DashboardController::class, 'dashboard'])
//         ->name('tenant.dashboard'); 

//         // Users
//         Route::get('/profile', [UserController::class, 'profile'])->name('tenant.profile');
//         // Route::get('profile', [UserController::class, 'profile'])->name('subscriber.profile');
//         Route::post('update-profile', [UserController::class, 'updateProfile'])->name('subscriber.update.profile')->middleware('demo');
//          /**
//          * Orders
//          */
//         Route::get('orders', [OrderController::class, 'ordersList'])->name('plugin.multivendor.seller.dashboard.order.list');
//         Route::post('/order-status-details', [OrderController::class, 'orderStatusDetails'])->name('plugin.multivendor.seller.dashboard.order.status.details');
//         Route::get('order-details/{id}', [OrderController::class, 'orderDetails'])->name('plugin.multivendor.seller.dashboard.order.details');
//         Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('plugin.multivendor.seller.dashboard.order.status.update');
//         Route::post('/accept-order', [OrderController::class, 'acceptOrder'])->name('plugin.multivendor.seller.dashboard.order.accept');
//         Route::post('/cancel-order', [OrderController::class, 'cancelOrder'])->name('plugin.multivendor.seller.dashboard.order.cancel');
//         Route::post('/cancel-order-item', [OrderController::class, 'cancelOrderItem'])->name('plugin.multivendor.seller.dashboard.order.item.cancel');
//         Route::post('/sales-chart-report', [OrderController::class, 'salesChartReport'])->name('tenant.reports.sales.chart');

//          //Media Settings
//         Route::middleware(['can:Manage Media Settings'])->group(function () {
//             Route::get('/image-settings', [MediaController::class, 'imageSettings'])->name('core.image.settings');
//             Route::post('/store-media-settings', [MediaController::class, 'storeMediaSettings'])->name('core.store.media.settings')->middleware('demo');
//         });

//         Route::get('/media-page', [MediaController::class, 'mediaPage'])->name('tenant.media.page')->middleware(['can:Manage Media']);

//         Route::post('/upload-media-file', [MediaController::class, 'uploadMediaFile'])->name('core.upload.media.file')->middleware(['check.subscription', 'demo']);
//         Route::post('/update-media-file-info', [MediaController::class, 'updateMediaFileInfo'])->name('core.update.media.file.info');
//         Route::post('/filter-media-list', [MediaController::class, 'filterMediaList'])->name('core.filter.media.list');
//         Route::post('/delete-media-file', [MediaController::class, 'deleteMediaFile'])->name('core.delete.media.file')->middleware('demo');
//         Route::post('/get-media-details-by-id', [MediaController::class, 'getMediaDetailsById'])->name('core.get.media.details.by.id');

//         Route::post('/apply-watermark-image', [MediaController::class, 'applyWatermarkImage'])->name('core.apply.watermark.image');


//          //Packages
//         Route::get('/create-package', [PackageController::class, 'createPackage'])->name('plugin.saas.create.package')->middleware('can:Manage Packages');
//         Route::post('/store-package', [PackageController::class, 'storePackage'])->name('plugin.saas.store.package');
//         Route::get('/packages', [PackageController::class, 'packages'])->name('plugin.saas.packages')->middleware('can:Manage Packages');
//         Route::post('/get-packages-according-to-plan', [PackageController::class, 'getPackageAccordingToPlan'])
//             ->name('plugin.saas.get.packages.according.to.plan')
//             ->withoutMiddleware('admin.subscriber.separation:1');

//         Route::get('/edit-package/{id}', [PackageController::class, 'editPackage'])->name('plugin.saas.edit.package')->middleware('can:Manage Packages');
//         Route::post('/update-package', [PackageController::class, 'updatePackage'])->name('plugin.saas.update.package');
//         Route::post('/delete-package', [PackageController::class, 'deletePackage'])->name('plugin.saas.delete.package');

            
//         //Package plannings
//         Route::get('/package-plans', [PackageController::class, 'packagePlans'])->name('plugin.saas.package.plans')->middleware('can:Manage Packages');
//         Route::post('/store-package-plan', [PackageController::class, 'storePackagePlans'])->name('plugin.saas.store.package.plan');
//         Route::post('/update-package-plan', [PackageController::class, 'updatePackagePlan'])->name('plugin.saas.update.package.plan');
//         Route::post('/delete-package-plan', [PackageController::class, 'deletePackagePlan'])->name('plugin.saas.delete.package.plan');

//          //Coupons Controlling
//         Route::get('/create-coupon', [CouponController::class, 'createCoupons'])->name('plugin.saas.create.coupons')->middleware('can:Manage Coupons');
//         Route::post('/store-coupons', [CouponController::class, 'storeCoupons'])->name('plugin.saas.store.coupons');
//         Route::get('/coupons', [CouponController::class, 'coupons'])->name('plugin.saas.coupons')->middleware('can:Manage Coupons');
//         Route::get('/edit-coupon/{id}', [CouponController::class, 'editCoupon'])->name('plugin.saas.edit.coupon')->middleware('can:Manage Coupons');
//         Route::post('/update-coupon', [CouponController::class, 'updateCoupon'])->name('plugin.saas.update.coupon');
//         Route::post('/delete-coupon', [CouponController::class, 'deleteCoupon'])->name('plugin.saas.delete.coupon');

//         //Payment methods configurations
//         Route::get('/payment-methods', [PaymentController::class, 'paymentMethods'])->name('plugin.saas.payments.methods')->middleware('can:Manage Payments');
//         Route::post('/change-payment-method-status', [PaymentController::class, 'changePaymentMethodStatus'])->name('plugin.saas.payments.methods.status.update');
//         Route::post('/change-tenant-payment-method-status', [PaymentController::class, 'changeTenantPaymentMethodStatus'])->name('plugin.saas..tenant.payments.methods.status.update');
//         Route::post('/get-payment-method-credential', [PaymentController::class, 'getPaymentMethodCredentials'])->name('plugin.saas.payments.methods.credential.edit');
//         Route::post('/update-payment-method-credential', [PaymentController::class, 'updatePaymentMethodCredential'])->name('plugin.saas.payments.methods.credential.update');

//         //Currency Settings
//         Route::get('/add-currency', [CurrencyController::class, 'addCurrency'])->name('plugin.saas.add.currency')->middleware('can:Manage SAAS Settings');
//         Route::post('/add-currency', [CurrencyController::class, 'storeCurrency'])->name('plugin.saas.store.currency');
//         Route::get('/all-currencies', [CurrencyController::class, 'allCurrencies'])->name('plugin.saas.all.currencies')->middleware('can:Manage SAAS Settings');
//         Route::post('/update-currency-status', [CurrencyController::class, 'updateCurrencyStatus'])->name('plugin.saas.update.currency.status');
//         Route::get('/edit-currency/{id}', [CurrencyController::class, 'editCurrency'])->name('plugin.saas.edit.currency')->middleware('can:Manage SAAS Settings');
//         Route::post('/update-currency', [CurrencyController::class, 'updateCurrency'])->name('plugin.saas.update.currency');
//         Route::post('/delete-currency', [CurrencyController::class, 'deleteCurrency'])->name('plugin.saas.currency.delete');

//         //Saas General Settings
//         Route::get('/saas-general-settings', [SystemController::class, 'generalSettings'])->name('plugin.saas.general.settings')->middleware('can:Manage SAAS Settings');
//         Route::post('/saas-store-general-settings', [SystemController::class, 'storeGeneralSettings'])->name('plugin.saas.admin.store.general.settings');
//         Route::get('/notification-settings', [SystemController::class, 'saasNotificationSettings'])->name('plugin.saas.notification.settings')->middleware('can:Manage SAAS Settings');
//         Route::post('/notification-settings', [SystemController::class, 'storeSaasNotificationSettings'])->name('plugin.saas.admin.notification.settings');

//         /**
//          * Manage Subscriber
//          */
//         Route::get('subscribers', [SubscriberController::class, 'index'])->name('plugin.saas.customers.list')->middleware('can:Manage Subscriptions');
//         Route::post('subscriber-store', [SubscriberController::class, 'storeSubscriber'])->name('plugin.saas.subscriber.store');
//         Route::post('subscriber-edit', [SubscriberController::class, 'editSubscriber'])->name('plugin.saas.subscriber.edit')->middleware('can:Manage Subscriptions');
//         Route::post('subscriber-update', [SubscriberController::class, 'updateSubscriber'])->name('plugin.saas.subscriber.update');
//         Route::get('subscriber-details/{id}', [SubscriberController::class, 'subscriberDetails'])->name('plugin.saas.subscriber.details')->middleware('can:Manage Subscriptions');
//         Route::post('subscriber-delete', [SubscriberController::class, 'subscriberDelete'])->name('plugin.saas.subscriber.delete');

//         //Payment History
//         Route::get('payment-history', [AdminPaymentController::class, 'paymentHistory'])->name('plugin.saas.admin.payment.history')->middleware('can:Manage Subscriptions');
//         Route::get('print-subscription-payment-invoice/{store_id}', [AdminPaymentController::class, 'printInvoice'])->name('plugin.saas.admin.print.subscription.payment.invoice')->middleware('can:Manage Subscriptions');

//         //Custom domains
//         Route::get('custom-domains', [DomainController::class, 'customDomainRequest'])->name('plugin.saas.admin.custom.domain.request')->middleware('can:Manage Subscriptions');
//         Route::post('delete-custom-domain', [DomainController::class, 'deleteCustomDomain'])->name('plugin.saas.admin.delete.custom.domain');
//         Route::post('update-custom-domain', [DomainController::class, 'updateCustomDomain'])->name('plugin.saas.admin.update.custom.domain');

//         //Dashboard
//         Route::post('sales-chart-report', [DashboardController::class, 'salesChartReport'])->name('plugin.saas.dash.sales.chart');


//          /**
//          * Reports Routes
//          */
//         Route::middleware(['can:Manage Product Reports'])->group(function () {
//             Route::get('/products-report', [ReportController::class, 'productReport'])->name('plugin.tlcommercecore.reports.products');
//         });

//         Route::middleware(['can:Manage Wishlist Reports'])->group(function () {
//             Route::get('/products-wishlist-report', [ReportController::class, 'productWishlistReport'])->name('plugin.tlcommercecore.reports.products.wishlist');
//         });

//         Route::middleware(['can:Manage Keyword Search Reports'])->group(function () {
//             Route::get('/user-keyword-search', [ReportController::class, 'userKeywordSearch'])->name('plugin.tlcommercecore.reports.search.keyword');
//         });

//         Route::post('/sales-chart-report', [ReportController::class, 'salesChartReport'])->name('plugin.tlcommercecore.reports.sales.chart');

//          //----Blog & page----//

//         // Blog Category Routes
//         Route::controller(TenantBlogCategoryController::class)->group(function () {
//             Route::middleware(['can:Manage Category'])->group(function () {
//                 Route::get('/blog-category', 'blogCategory')->name('core.blog.category');
//                 Route::get('/add-blog-category', 'addBlogCategory')->name('core.add.blog.category')->middleware('check.subscription');
//                 Route::post('/store-blog-category', 'storeBlogCategory')->name('core.store.blog.category');
//                 Route::get('/edit-blog-category/{id}', 'editBlogCategory')->name('core.edit.blog.category');
//                 Route::post('/update-blog-category', 'updateBlogCategory')->name('core.update.blog.category');
//                 Route::post('/blog-category/delete', 'deleteBlogCategory')->name('core.delete.blog.category')->middleware('demo');
//                 Route::post('/blog-category/bulk-delete', 'bulkDeleteBlogCategory')->name('core.bulk.delete.blog.category')->middleware('demo');
//                 Route::post('/blog-category/featured-status-update', 'updateBlogCategoryFeaturedStatus')->name('core.update.blog.category.featured.status');
//                 Route::post('/blog-category/publish-status-update', 'updateBlogCategoryPublicStatus')->name('core.update.blog.category.publish.status');
//             });
//         });

//         //Tag Routes
//         Route::controller(TenantTagController::class)->group(function () {
//             Route::middleware(['can:Manage Tag'])->group(function () {
//                 Route::get('/tag', 'tag')->name('core.tag');
//                 Route::get('/add-tag', 'addTag')->name('core.add.tag');
//                 Route::post('/store-tag', 'storeTag')->name('core.store.tag');
//                 Route::get('/edit-tag/{id}', 'editTag')->name('core.edit.tag');
//                 Route::post('/update-tag', 'updateTag')->name('core.update.tag');
//                 Route::post('/tag/delete', 'deleteTag')->name('core.delete.tag')->middleware('demo');
//                 Route::post('/tag/bulk-delete', 'bulkDeleteTag')->name('core.bulk.delete.tag')->middleware('demo');
//                 Route::post('/tag/publish-status-update', 'updateTagPublicStatus')->name('core.update.tag.publish.status');
//             });
//         });

//         // Blog  Route
//         Route::controller(TenantBlogController::class)->group(function () {
//             Route::get('/blog-list', 'blog')->name('tenant.core.blog')->middleware(['can:Show Blog']);
//             Route::get('/add-blog', 'addBlog')->name('tenant.add.blog')->middleware(['can:Create Blog', 'check.subscription']);
//             Route::post('/store-blog', 'storeBlog')->name('core.store.blog')->middleware(['can:Create Blog']);
//             Route::get('/edit-blog/{id}', 'editBlog')->name('core.edit.blog')->middleware(['can:Edit Blog']);
//             Route::post('/update-blog', 'updateBlog')->name('core.update.blog')->middleware(['can:Edit Blog']);
//             Route::post('/blog/delete', 'deleteBlog')->name('core.delete.blog')->middleware(['can:Delete Blog', 'demo']);
//             Route::post('/blog/bulk-delete', 'bulkDeleteBlog')->name('core.bulk.delete.blog')->middleware(['can:Delete Blog', 'demo']);
//             Route::post('/blog/featured-status-update', 'updateBlogFeaturedStatus')->name('core.update.blog.featured.status')->middleware(['can:Edit Blog']);
//             Route::post('/blog-category-load', 'categoryLoad')->name('core.blog.category.load');
//             Route::post('/blog-tag-load', 'TagLoad')->name('core.blog.tag.load');
//             Route::post('/blog-content-image', 'blogContentImage')->name('core.blog.content.image');
//             Route::post('/blog-draft-preview',  'blogDraftPreview')->name('core.blog.draft.preview');
//         });

//         //Blog Comment Routes
//         Route::controller(TenantCommentController::class)->group(function () {
//             Route::middleware(['can:Manage Comment'])->group(function () {
//                 Route::get('/comments', 'comment')->name('core.blog.comment');
//                 Route::post('/comment/bulk/action',  'bulkAction')->name('core.blog.comment.bulk.action')->middleware('demo');
//                 Route::get('/comments/edit/{id}', 'editComment')->name('core.blog.comment.edit')->middleware('demo');
//                 Route::post('/comments/update', 'updateComment')->name('core.blog.comment.update');
//                 Route::post('/comment/status', 'changeStatus')->name('core.blog.comment.status')->middleware('demo');
//                 Route::post('/comment/delete', 'commentDelete')->name('core.blog.comment.delete')->middleware('demo');
//                 Route::get('/comment-setting', 'commentSetting')->name('core.blog.comment.setting');
//                 Route::post('/comment-setting/update', 'updateCommentSetting')->name('core.blog.comment.setting.update')->middleware('demo');
//                 // both in frontend and core
//                 Route::post('/blog/comment/reply', [TenantCommentController::class, 'replyBlogComment'])->name('core.blog.comment.reply')->middleware('demo');
//             });
//         });

//         // Page Routes
//         Route::controller(TenantPageController::class)->group(function () {
//             Route::get('/page-list', 'page')->name('core.page')->middleware(['can:Show Page']);
//             Route::get('/add-page', 'addPage')->name('core.page.add')->middleware(['can:Create Page', 'check.subscription']);
//             Route::post('/store-page', 'storePage')->name('core.page.store')->middleware(['can:Create Page']);
//             Route::get('/edit-page', 'editPage')->name('core.page.edit')->middleware(['can:Edit Page']);
//             Route::post('/update-page', 'updatePage')->name('core.page.update')->middleware(['can:Edit Page']);
//             Route::post('/page/delete', 'deletePage')->name('core.page.delete')->middleware(['can:Delete Page', 'demo']);
//             Route::post('/page/bulk-delete', 'bulkDeletePage')->name('core.bulk.delete.page')->middleware(['can:Delete Page', 'demo']);
//             Route::post('/page-change-status', 'pageStatusChange')->name('core.page.status.change')->middleware(['can:Edit Page']);
//             Route::post('/page-content-image', 'pageContentImage')->name('core.page.content.image');
//             Route::post('/page-draft-preview', 'pageDraftPreview')->name('core.page.draft.preview');
//             Route::get('/page-preview/{slug}', 'pagePreview')->name('core.page.preview');
//             Route::get('/page-make-homepage/{page}', 'makeHomepage')->name('core.page.make.homepage')->middleware(['can:Manage Tlcommerce Page Builder', 'demo']);
//         });
    

//         // Route::post('/logout', [AuthenticationController::class, 'logout'])->name('tenant.logout');


//         //     Route::post('/activate-license', [DashboardController::class, 'licenseActive'])
//         // ->name('tenant.license.active')->middleware(['can:Manage Dashboard']);

//     });

// }
//     Route::group(['middleware' => 'auth'])->group(function () {

//          Route::get('/dashboard', [DashboardController::class, 'dashboard'])
//         ->name('tenant.dashboard')
//         ->middleware(['can:Manage Dashboard']);  

// //         Route::get('/dashboard', function() {
// //     try {
// //         $controller = new \Plugin\Saas\Http\Controllers\Admin\DashboardController();
// //         return $controller->dashboard();
// //     } catch (\Exception $e) {
// //         return response()->json([
// //             'error' => $e->getMessage(),
// //             'file' => $e->getFile(),
// //             'line' => $e->getLine(),
// //             'trace' => $e->getTraceAsString()
// //         ], 500);
// //     }
// // })->name('tenant.dashboard');
//         // Route::get('/dashboard', '\Plugin\Saas\Http\Controllers\Admin\DashboardController@dashboard')->name('tenant.dashboard');
//         // Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('tenant.dashboard')->middleware(['can:Manage Dashboard']);

//         //   Route::post('/activate-license', [DashboardController::class, 'licenseActive'])
//         // ->name('admin.license.active')->middleware(['can:Manage Dashboard']);

        
//         Route::post('/logout', [AuthenticationController::class, 'logout'])->name('tenant.logout');
//     //     Route::prefix('api/theme/tlcommerce/v1')->group(function () {
//     //     Route::get('get-theme-color', [ThemesController::class, 'getThemeColor']);
//     // });
//         // Add other tenant admin routes here
//     });
    
// });
// Route::group(['prefix' => getAdminPrefix(), 'middleware' => ['handle.expired.account']], function () {

//        Route::get('/test', function() {
//         return 'Tenant routes are working! Tenant: ' . tenant('id');
//     });
    
//     // Login routes
//     Route::get('/login', [AuthenticationController::class, 'login'])->name('tenant.login');
//     Route::post('/login', [AuthenticationController::class, 'attemptLogin'])->name('tenant.attemptLogin');
//     // Route::post('/login', [AuthenticationController::class, 'attemptlogin']);
//     Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

//     // Tenant Admin Authentication Routes
//     Route::prefix('admin')->name('tenant.')->group(function () {
        
//         // Protected tenant admin routes
//         Route::group(['middleware' => 'auth'], function () {
//             Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
// //             // Add other tenant admin routes here
// //         });
//         });

//     });
    
// });