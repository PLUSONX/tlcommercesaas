<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Plugin\TlcommerceCore\Models\Cities;
use Plugin\TlcommerceCore\Models\States;
use Plugin\TlcommerceCore\Models\Country;
use Plugin\TlcommerceCore\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Plugin\TlcommerceCore\Models\ProductTags;
use Plugin\TlcommerceCore\Models\ProductBrand;
use Plugin\TlcommerceCore\Models\ProductCategory;
use Plugin\TlcommerceCore\Models\ShippingProfile;
use Plugin\TlcommerceCore\Models\ProductAttribute;
use Plugin\TlcommerceCore\Models\ProductShareOption;
use Plugin\TlcommerceCore\Repositories\UnitRepository;
use Plugin\TlcommerceCore\Http\Requests\ProductRequest;
use Plugin\TlcommerceCore\Repositories\BrandRepository;
use Plugin\TlcommerceCore\Repositories\ColorRepository;
use Plugin\TlcommerceCore\Repositories\VatTaxRepository;
use Plugin\TlcommerceCore\Repositories\ProductRepository;
use Plugin\TlcommerceCore\Repositories\CategoryRepository;
use Plugin\TlcommerceCore\Repositories\LocationRepository;
use Plugin\TlcommerceCore\Repositories\ProductTagsRepository;
use Plugin\TlcommerceCore\Repositories\ProductAttributeRepository;
use Plugin\TlcommerceCore\Repositories\ProductConditionRepository;
use Plugin\TlcommerceCore\Repositories\ProductCollectionRepository;

class ProductController extends Controller
{

    /**
     * Product image standards.
     *
     * We validate media-manager image IDs before ProductRepository persists them.
     * Existing unchanged legacy images are not revalidated on edit.
     */
    private const THUMBNAIL_MIN_WIDTH = 800;
    private const THUMBNAIL_MIN_HEIGHT = 800;
    private const THUMBNAIL_MAX_WIDTH = 1600;
    private const THUMBNAIL_MAX_HEIGHT = 1600;
    private const THUMBNAIL_MAX_BYTES = 2097152; // 2 MB

    private const GALLERY_MIN_WIDTH = 1000;
    private const GALLERY_MIN_HEIGHT = 1000;
    private const GALLERY_MAX_WIDTH = 2400;
    private const GALLERY_MAX_HEIGHT = 2400;
    private const GALLERY_MAX_BYTES = 3145728; // 3 MB
    private const GALLERY_MAX_COUNT = 8;

    private const VARIANT_MIN_WIDTH = 1000;
    private const VARIANT_MIN_HEIGHT = 1000;
    private const VARIANT_MAX_WIDTH = 2400;
    private const VARIANT_MAX_HEIGHT = 2400;
    private const VARIANT_MAX_BYTES = 3145728; // 3 MB
    private const VARIANT_MAX_COUNT_PER_COLOR = 4;

    private const ALLOWED_IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    protected $product_repository;
    protected $category_repository;
    protected $brand_repository;
    protected $unit_repository;
    protected $product_condition_repository;
    protected $product_tag_repository;
    protected $vat_tax_repository;
    protected $color_repository;
    protected $product_attribute_repository;
    protected $location_repository;
    protected $collection_repository;

    public function __construct(ProductCollectionRepository $collection_repository, ProductRepository $product_repository, CategoryRepository $category_repository, BrandRepository $brand_repository, UnitRepository $unit_repository, ProductConditionRepository $product_condition_repository, ProductTagsRepository $product_tag_repository, VatTaxRepository $vat_tax_repository, ColorRepository $color_repository, ProductAttributeRepository $product_attribute_repository, LocationRepository $location_repository)
    {
        $this->product_repository = $product_repository;
        $this->category_repository = $category_repository;
        $this->brand_repository = $brand_repository;
        $this->unit_repository = $unit_repository;
        $this->product_condition_repository = $product_condition_repository;
        $this->product_tag_repository = $product_tag_repository;
        $this->vat_tax_repository = $vat_tax_repository;
        $this->color_repository = $color_repository;
        $this->product_attribute_repository = $product_attribute_repository;
        $this->location_repository = $location_repository;
        $this->collection_repository = $collection_repository;
    }
    /**
     * Will return product list
     *
     * @return mixed
     */
    public function productList(Request $request)
    {
        return view('plugin/tlecommercecore::products.product.product_list')->with([
            'products' => $this->product_repository->productManagement($request, null, 'inhouse')
        ]);
    }
    /**
     * Will return product dropdown options
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function productDropdownOptions(Request $request)
    {
        // dd($request);
        // $query = Product::where('tenant_id', tenant('id')) 
        //             ->select('id', 'name as text');
        $query = Product::query()->select('id', 'name as text');
        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10);
        $morePages = true;

        if (empty($categories->nextPageUrl())) {
            $morePages = false;
        }
        $results = array(
            "results" => $categories->items(),
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }

    /**
     * Will load product quick action modal form
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function viewProductQuickActionForm(Request $request)
    {
        return view('plugin/tlecommercecore::products.product.product_quick_action_modal')->with([
            'product_details' => $this->product_repository->productDetails($request['id']),
            'action' => $request['action'],
        ]);
    }
    /**
     * Will update product discount
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProductDiscount(Request $request)
    {
        $res = $this->product_repository->updateProductDiscount($request);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
    /**
     * Will update product price
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProductPrice(Request $request)
    {
        $res = $this->product_repository->updateProductPrice($request);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }

    /**
     * Will update product stock
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProductStock(Request $request)
    {
        $res = $this->product_repository->updateProductStock($request);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }

    /**
     * Will redirect new  product page
     *
     * @return mixed
     */
    public function addNewProduct()
    {
        $isModernLayoutActive = $this->isModernLayoutActive();

        return view('plugin/tlecommercecore::products.product.add_new_product')->with([
            'units' => $this->unit_repository->unitList(),
            'conditions' => $this->product_condition_repository->conditionList(),
            'shipping_profiles' => ShippingProfile::all(),
            'colors' => $this->color_repository->colorList([
                config('settings.general_status.active')
            ]),
            'attributes' => $this->product_attribute_repository->attributeList(
                config('settings.general_status.active')
            ),
            'product_collections' => $this->collection_repository->collections([
                config('settings.general_status.active')
            ]),

            // Layout check
            'isModernLayoutActive' => $isModernLayoutActive,
        ]);
    }
    /**
     * Will store new product
     *
     * @param ProductRequest $request
     * @return mixed
     */
    public function storeNewProduct(ProductRequest $request)
    {
        // Product images are optional on create.
        // No product-level image validation is applied here.

        if ($request['product_type'] == config('tlecommercecore.product_variant.variable') && !$request->has('variations')) {
            toastNotification('error', 'Invalid Product Variations');
            return redirect()->back();
        }
        $res = $this->product_repository->storeNewProduct($request);
        if ($res == true) {
            toastNotification('success', translate('New product created successfully'), 'Success');
            return redirect()->route('plugin.tlcommercecore.product.list');
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Will redirect product edit page
     *
     * @param Int $id
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function editProduct($id, Request $request)
    {
        $isModernLayoutActive = $this->isModernLayoutActive();

        return view('plugin/tlecommercecore::products.product.edit_product')->with([
            'product_details' => $this->product_repository->editProduct($id),
            'lang' => $request->lang ?? getDefaultLang(),
            'shipping_profiles' => ShippingProfile::all(),
            'product_collections' => $this->collection_repository->collections([
                config('settings.general_status.active')
            ]),
            'isModernLayoutActive' => $isModernLayoutActive,
        ]);
    }

    public function updateProduct(ProductRequest $request)
    {
        // Non-default language edits only change translations in ProductRepository,
        // so do not block them because of legacy media dimensions.
        if ($request->input('lang') == null || $request->input('lang') == getDefaultLang()) {
            $existingProduct = Product::with(['gallery_images', 'color_images'])->find($request->id);
            $this->validateProductMedia($request, $existingProduct);
        }

        if ($request['product_type'] == config('tlecommercecore.product_variant.variable') && !$request->has('variations')) {
            toastNotification('error', 'Invalid Product Variations');
            return redirect()->back();
        }
        $res = $this->product_repository->updateProduct($request);
        if ($res == true) {
            toastNotification('success', translate('Product update successfully'), 'Success');
            return redirect()->route('plugin.tlcommercecore.product.edit', ['id' => $request->id, 'lang' => $request->lang]);
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Will update product status
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateProductStatus(Request $request)
    {
        $res = $this->product_repository->changeStatus($request->id);
        if ($res == true) {
            toastNotification('success', translate('Product status updated successfully'));
        } else {
            toastNotification('error', translate('Unable to change status'));
        }
    }
    /**
     * Will update product status
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateProductApprovalStatus(Request $request)
    {
        $res = $this->product_repository->changeApprovalStatus($request->id);
        if ($res == config('settings.general_status.active')) {
            toastNotification('success', 'Product approved  successfully');
        }
        if ($res == config('settings.general_status.in_active')) {
            toastNotification('warning', 'Removed approval status successfully');
        }
        if (!$res) {
            toastNotification('error', 'Unable to change status');
        }
    }
    /**
     * Will update product featured status
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateProductFeaturedStatus(Request $request)
    {
        $res = $this->product_repository->updateFeaturedStatus($request->id);
        if ($res == true) {
            toastNotification('success', 'Product featured status updated successfully', 'Success');
        } else {
            toastNotification('error', 'Unable to change status', 'Failed');
        }
    }
    /**
     * Will delete product
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function deleteProduct(Request $request)
    {
        $res = $this->product_repository->deleteProduct($request->id);
        if ($res === true) {
            toastNotification('success', 'Product deleted successfully', 'Success');
            return redirect()->back();
            // return redirect()->route('plugin.tlcommercecore.product.list');
        }
        if ($res === 'has_orders') {
            toastNotification('error', translate('This product cannot be deleted because it has been ordered. Unpublish it instead.'), 'Warning');
            return redirect()->back();
        }
        toastNotification('error', 'This product can not be deleted', 'Warning');
        return redirect()->back();
    }
    /**
     * Will applied bulk products
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function productBulkAction(Request $request)
    {
        try {
            if ($request->has('items') && $request->has('action')) {
                //Bulk delete
                if ($request['action'] == 'delete_all') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->deleteProduct($product_id);
                    }
                    toastNotification('success', translate('Items Deleted Successfully'));
                }
                //Bulk active
                if ($request['action'] == 'active') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->changeStatus($product_id, config('settings.general_status.active'));
                    }
                    toastNotification('success', translate('Items make active successfully'));
                }
                //Bulk inactive
                if ($request['action'] == 'in_active') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->changeStatus($product_id, config('settings.general_status.in_active'));
                    }
                    toastNotification('success', translate('Items make inactive successfully'));
                }
                //Bulk remove discount
                if ($request['action'] == 'remove_discount') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->updateProductDiscount($request, $product_id, 0);
                    }
                    toastNotification('success', translate('Remove discount from items successfully'));
                }
                //Bulk make feature
                if ($request['action'] == 'feature_active') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->updateFeaturedStatus($product_id, config('settings.general_status.active'));
                    }
                    toastNotification('success', translate('Selected items featured successfully'));
                }
                //Bulk remove from featured list
                if ($request['action'] == 'feature_in_active') {
                    foreach ($request['items'] as $product_id) {
                        $this->product_repository->updateFeaturedStatus($product_id, config('settings.general_status.in_active'));
                    }
                    toastNotification('success', translate('Selected items remove from featured list'));
                }
            }
        } catch (\Exception $e) {
            toastNotification('error', translate('Action Failed'));
        } catch (\Error $e) {
            toastNotification('error', translate('Action Failed'));
        }
    }
    /**
     * Add product choice  option
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function addProductChoiceOption(Request $request)
    {
        $attributes = ProductAttribute::with('attribute_values')->where('id', $request->attribute_id)->first();
        return view('plugin/tlecommercecore::products.product.choice_option')->with([
            'attribute' => $attributes
        ]);
    }
    /**
     * Generate product variant combination
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function variantCombination(Request $request)
    {

        $option_choices = array();

        if ($request->has('product_attributes')) {
            $product_options = $request->product_attributes;
            sort($product_options, SORT_NUMERIC);

            foreach ($product_options as $key => $option) {

                $option_name = 'attribute_' . $option . '_selected';
                $choices = array();

                if ($request->has($option_name)) {

                    $product_option_values = $request[$option_name];
                    sort($product_option_values, SORT_NUMERIC);

                    foreach ($product_option_values as $key => $item) {
                        array_push($choices, $item);
                    }
                    $option_choices[$option] =  $choices;
                }
            }
        }
        if ($request->has('selected_colors')) {
            $option_choices['color'] = $request->selected_colors;
        }

        $combinations = array(array());
        foreach ($option_choices as $property => $property_values) {
            $tmp = array();
            foreach ($combinations as $combination_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = $combination_item + array($property => $property_value);
                }
            }
            $combinations = $tmp;
        }
        return view('plugin/tlecommercecore::products.product.variant_combination')->with(
            [
                'combinations' => $combinations
            ]
        );
    }
    /**
     * Get color variant image upload options
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function colorVariantImageInput(Request $request)
    {
        $colors = $request->selected_colors;
        $file_user_filter = false;
        if ($request->has('seller_id') && $request['seller_id'] != null) {
            $file_user_filter = true;
        }
        return view('plugin/tlecommercecore::products.product.color_variant_images')->with(
            [
                'colors' => $colors,
                'file_user_filter' => $file_user_filter
            ]
        );
    }

    /**
     * Will return product category options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function productCategoryOption(Request $request)
    {
        $query = ProductCategory::with(['childs' => function ($q) {
            $q->where('status', config('settings.general_status.active'))
                ->select('id', 'name', 'parent');
        }, 'category_translations'])
            ->select('id', 'name', 'parent')
            ->where('status', config('settings.general_status.active'));

        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $categories = $query->orderBy('id', 'asc')->paginate(2);

        $output = [];

        foreach ($categories->items() as $category) {
            $item['id'] = $category->id;
            $item['text'] = $category->translation('name', getLocale());
            array_push($output, $item);

            if ($category->childs != null) {
                foreach ($category->childs as $child) {
                    $sub_item['id'] = $child->id;
                    $sub_item['text'] = '-- ' . $child->translation('name', getLocale());
                    array_push($output, $sub_item);

                    if ($child->childs != null) {
                        foreach ($child->childs as $pro_child) {
                            $sub_sub_item['id'] = $pro_child->id;
                            $sub_sub_item['text'] = '--- ' . $pro_child->translation('name', getLocale());
                            array_push($output, $sub_sub_item);
                        }
                    }
                }
            }
        }

        $morePages = true;

        if (empty($categories->nextPageUrl())) {
            $morePages = false;
        }
        $results = array(
            "results" => $output,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
    /**
     * Will return product brand options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function productBrandsOption(Request $request)
    {
        $query = ProductBrand::with(['brand_translations' => function ($q) {
            $q->select('name', 'brand_id', 'lang');
        }])
            ->select('id', 'name')
            ->where('status', config('settings.general_status.active'));


        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $brands = $query->orderBy('id', 'asc')->paginate(10);

        $morePages = true;

        if (empty($brands->nextPageUrl())) {
            $morePages = false;
        }
        $output = collect($brands->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->translation('name', getLocale())
            ];
        });
        $results = array(
            "results" => $output,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
    /**
     * Will Return product tags options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function productTagsOption(Request $request)
    {

        $query = ProductTags::select('id', 'name as text')
            ->where('status', config('settings.general_status.active'));


        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $tags = $query->orderBy('id', 'DESC')->paginate(10);

        $morePages = true;

        if (empty($tags->nextPageUrl())) {
            $morePages = false;
        }

        $results = array(
            "results" => $tags->items(),
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
    /**
     * Will return product cod countries dropdown options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function codCountriesDropdownOptions(Request $request)
    {
        $query = Country::with(['country_translations'])->select('id', 'name')
            ->where('status', config('settings.general_status.active'))
            ->orderBy('name', 'ASC');


        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $countries = $query->orderBy('name', 'ASC')->paginate(10);

        $collection = new Collection($countries->items());

        $modifiedCollection = $collection->map(function ($item) {
            $item->id = $item->id;
            $item->text = $item->translation('name');
            return $item;
        });

        $morePages = true;

        if (empty($countries->nextPageUrl())) {
            $morePages = false;
        }

        $results = array(
            "results" => $modifiedCollection,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }

    /**
     * Will return cod state dropdown options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function codStateDropdownOptions(Request $request)
    {
        $query = States::with(['state_translations'])->select('id', 'name')
            ->where('status', config('settings.general_status.active'))
            ->orderBy('name', 'ASC');


        if ($request->has('countries')) {
            $term = trim($request->term);
            $query = $query->whereIn('country_id', $request->countries);
        }

        if ($request->has('country')) {
            $term = trim($request->term);
            $query = $query->where('country_id', $request['country']);
        }

        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $states = $query->orderBy('name', 'ASC')->paginate(10);

        $collection = new Collection($states->items());

        $modifiedCollection = $collection->map(function ($item) {
            $item->id = $item->id;
            $item->text = $item->translation('name');
            return $item;
        });

        $morePages = true;

        if (empty($states->nextPageUrl())) {
            $morePages = false;
        }

        $results = array(
            "results" => $modifiedCollection,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
    /**
     * Will return cod cities dropdown options
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function codCityDropdownOptions(Request $request)
    {
        $query = Cities::with(['city_translations'])->select('id', 'name')
            ->where('status', config('settings.general_status.active'))
            ->orderBy('name', 'ASC');

        if ($request->has('states')) {
            $term = trim($request->term);
            $query = $query->whereIn('state_id', $request->states);
        }

        if ($request->has('state')) {
            $term = trim($request->term);
            $query = $query->where('state_id', $request['state']);
        }

        if ($request->has('term')) {
            $term = trim($request->term);
            $query = $query->where('name', 'LIKE',  '%' . $term . '%');
        }

        $cities = $query->orderBy('name', 'ASC')->paginate(10);

        $collection = new Collection($cities->items());

        $modifiedCollection = $collection->map(function ($item) {
            $item->id = $item->id;
            $item->text = $item->translation('name');
            return $item;
        });


        $morePages = true;

        if (empty($cities->nextPageUrl())) {
            $morePages = false;
        }

        $results = array(
            "results" => $modifiedCollection,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }

    /**
     * Will return product share options
     *
     * @return mixed
     */
    public function shareOptions()
    {
        $share_options = ProductShareOption::all();
        return view('plugin/tlecommercecore::products.product.share_options')->with(
            [
                'share_options' => $share_options,
            ]
        );
    }
    /**
     * Update status of product share option
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function shareOptionUpdateStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $option = ProductShareOption::find($request['id']);
            $option->status = $option->status == config('settings.general_status.active') ? config('settings.general_status.in_active') : config('settings.general_status.active');
            $option->save();
            DB::commit();
            toastNotification('success', translate('Status updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Status update failed'));
        }
    }

    /**
     * Will return product reviews list
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function productReviewsList(Request $request)
    {
        $reviews = $this->product_repository->reviewList($request);

        return view('plugin/tlecommercecore::products.product.reviews')->with(
            [
                'reviews' => $reviews,
            ]
        );
    }
    /**
     * will update  product review status
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateProductReviewStatus(Request $request)
    {
        $res = $this->product_repository->updateReviewStatus($request['id']);

        if ($res) {
            toastNotification('success', translate('Review status updated successfully'));
        } else {
            toastNotification('error', translate('Status update failed'));
        }
    }
    /**
     * will return product review details
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     *
     */
    public function productReviewDetails(Request $request)
    {
        $details = $this->product_repository->productReviewDetails($request['id']);

        return view('plugin/tlecommercecore::products.product.review_details')->with(
            [
                'details' => $details,
            ]
        );
    }
    /**
     * Will delete  product review review
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function productReviewdelete(Request $request)
    {
        $res = $this->product_repository->productReviewDelete($request['id']);

        if ($res) {
            toastNotification('success', translate('Review deleted successfully'));
            return to_route('plugin.tlcommercecore.product.reviews.list');
        } else {
            toastNotification('error', translate('Review delete failed'));
            return redirect()->back();
        }
    }

    /**
     * Validate a media-manager selection immediately from the product form.
     *
     * Read-only endpoint: it does not save/update/delete anything.
     * It reuses the exact same image standards as validateProductMedia().
     */
    public function validateProductMediaSelection(Request $request)
    {
        $type = trim((string) $request->input('type', ''));
        $field = trim((string) $request->input('field', ''));
        $value = $request->input('value');
        $errors = [];

        if ($type === 'thumbnail') {
            $thumbnailId = $this->normalizeMediaId($value);

            if ($thumbnailId === null) {
                $errors[] = 'Thumbnail image is required.';
            } else {
                $error = $this->validateMediaImage(
                    $thumbnailId,
                    'Thumbnail image',
                    self::THUMBNAIL_MIN_WIDTH,
                    self::THUMBNAIL_MIN_HEIGHT,
                    self::THUMBNAIL_MAX_WIDTH,
                    self::THUMBNAIL_MAX_HEIGHT,
                    self::THUMBNAIL_MAX_BYTES,
                    true
                );

                if ($error !== null) {
                    $errors[] = $error;
                }
            }
        } elseif ($type === 'gallery') {
            $galleryIds = $this->parseMediaIds($value);

            if (count($galleryIds) > self::GALLERY_MAX_COUNT) {
                $errors[] = 'Gallery can contain a maximum of ' . self::GALLERY_MAX_COUNT . ' images.';
            } else {
                foreach ($galleryIds as $index => $imageId) {
                    $error = $this->validateMediaImage(
                        $imageId,
                        'Gallery image #' . ($index + 1),
                        self::GALLERY_MIN_WIDTH,
                        self::GALLERY_MIN_HEIGHT,
                        self::GALLERY_MAX_WIDTH,
                        self::GALLERY_MAX_HEIGHT,
                        self::GALLERY_MAX_BYTES,
                        true
                    );

                    if ($error !== null) {
                        $errors[] = $error;
                    }
                }
            }
        } elseif ($type === 'variant') {
            if (!preg_match('/^color_.+_image$/', $field)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Invalid color variant image field.'],
                ], 422);
            }

            $variantIds = $this->parseMediaIds($value);

            if (count($variantIds) > self::VARIANT_MAX_COUNT_PER_COLOR) {
                $errors[] = 'A color can contain a maximum of '
                    . self::VARIANT_MAX_COUNT_PER_COLOR
                    . ' variant images.';
            } else {
                foreach ($variantIds as $index => $imageId) {
                    $error = $this->validateMediaImage(
                        $imageId,
                        'Color variant image #' . ($index + 1),
                        self::VARIANT_MIN_WIDTH,
                        self::VARIANT_MIN_HEIGHT,
                        self::VARIANT_MAX_WIDTH,
                        self::VARIANT_MAX_HEIGHT,
                        self::VARIANT_MAX_BYTES,
                        true
                    );

                    if ($error !== null) {
                        $errors[] = $error;
                    }
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['Invalid product media validation type.'],
            ], 422);
        }

        return response()->json([
            'success' => empty($errors),
            'errors' => $errors,
        ]);
    }

    /**
     * Validate media-manager images selected for a product.
     *
     * IMPORTANT:
     * - thumbnail_image and gallery_images in this project are media IDs, not UploadedFile objects.
     * - Therefore normal Laravel rules such as `image|dimensions` cannot validate these values directly.
     * - This method resolves the selected media ID with getFilePath(), finds the local file,
     *   then validates MIME, file size, dimensions and aspect ratio before ProductRepository stores IDs.
     * - On update, unchanged existing images are intentionally skipped so old products are not broken.
     */
    private function validateProductMedia(ProductRequest $request, ?Product $existingProduct = null): void
    {
        $errors = [];

        /*
        |--------------------------------------------------------------------------
        | Thumbnail
        |--------------------------------------------------------------------------
        | Standard:
        | - JPEG / PNG / WEBP
        | - square 1:1
        | - 800x800 minimum
        | - 1600x1600 maximum
        | - 2 MB maximum
        */
        $thumbnailId = $this->normalizeMediaId($request->input('thumbnail_image'));
        $existingThumbnailId = $existingProduct
            ? $this->normalizeMediaId($existingProduct->thumbnail_image)
            : null;

        if ($existingProduct === null && $thumbnailId === null) {
            $errors['thumbnail_image'][] = 'Thumbnail image is required.';
        }

        $thumbnailChanged = $existingProduct === null
            ? $thumbnailId !== null
            : $thumbnailId !== null && $thumbnailId !== $existingThumbnailId;

        if ($thumbnailChanged) {
            $error = $this->validateMediaImage(
                $thumbnailId,
                'Thumbnail image',
                self::THUMBNAIL_MIN_WIDTH,
                self::THUMBNAIL_MIN_HEIGHT,
                self::THUMBNAIL_MAX_WIDTH,
                self::THUMBNAIL_MAX_HEIGHT,
                self::THUMBNAIL_MAX_BYTES,
                true
            );

            if ($error !== null) {
                $errors['thumbnail_image'][] = $error;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Gallery images
        |--------------------------------------------------------------------------
        | Standard:
        | - up to 8 images
        | - JPEG / PNG / WEBP
        | - square 1:1
        | - 1000x1000 minimum
        | - 2400x2400 maximum
        | - 3 MB maximum per image
        |
        | Gallery remains optional to preserve the existing product behavior.
        */
        $galleryIds = $this->parseMediaIds($request->input('gallery_images'));

        if (count($galleryIds) > self::GALLERY_MAX_COUNT) {
            $errors['gallery_images'][] = 'Gallery can contain a maximum of ' . self::GALLERY_MAX_COUNT . ' images.';
        }

        $existingGalleryIds = [];
        if ($existingProduct !== null) {
            $existingProduct->loadMissing('gallery_images');
            $existingGalleryIds = $existingProduct->gallery_images
                ->pluck('image_id')
                ->map(fn($id) => $this->normalizeMediaId($id))
                ->filter()
                ->values()
                ->all();
        }

        $galleryChanged = $existingProduct === null
            ? !empty($galleryIds)
            : $this->mediaIdSetsDiffer($galleryIds, $existingGalleryIds);

        if ($galleryChanged && count($galleryIds) <= self::GALLERY_MAX_COUNT) {
            foreach ($galleryIds as $index => $imageId) {
                $error = $this->validateMediaImage(
                    $imageId,
                    'Gallery image #' . ($index + 1),
                    self::GALLERY_MIN_WIDTH,
                    self::GALLERY_MIN_HEIGHT,
                    self::GALLERY_MAX_WIDTH,
                    self::GALLERY_MAX_HEIGHT,
                    self::GALLERY_MAX_BYTES,
                    true
                );

                if ($error !== null) {
                    $errors['gallery_images'][] = $error;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Color variant images
        |--------------------------------------------------------------------------
        | Validate only a color whose submitted image list is new/changed.
        | This protects existing products with older image dimensions.
        */
        if ($request->has('selected_colors') && is_array($request->input('selected_colors'))) {
            if ($existingProduct !== null) {
                $existingProduct->loadMissing('color_images');
            }

            foreach ($request->input('selected_colors') as $colorId) {
                $inputName = 'color_' . $colorId . '_image';

                if (!$request->has($inputName)) {
                    continue;
                }

                $variantIds = $this->parseMediaIds($request->input($inputName));

                if (count($variantIds) > self::VARIANT_MAX_COUNT_PER_COLOR) {
                    $errors[$inputName][] = 'A color can contain a maximum of '
                        . self::VARIANT_MAX_COUNT_PER_COLOR
                        . ' variant images.';
                    continue;
                }

                $existingVariantIds = [];
                if ($existingProduct !== null) {
                    $existingVariantIds = $existingProduct->color_images
                        ->where('color_id', $colorId)
                        ->pluck('image')
                        ->map(fn($id) => $this->normalizeMediaId($id))
                        ->filter()
                        ->values()
                        ->all();
                }

                $variantChanged = $existingProduct === null
                    ? !empty($variantIds)
                    : $this->mediaIdSetsDiffer($variantIds, $existingVariantIds);

                if (!$variantChanged) {
                    continue;
                }

                foreach ($variantIds as $index => $imageId) {
                    $error = $this->validateMediaImage(
                        $imageId,
                        'Color variant image #' . ($index + 1),
                        self::VARIANT_MIN_WIDTH,
                        self::VARIANT_MIN_HEIGHT,
                        self::VARIANT_MAX_WIDTH,
                        self::VARIANT_MAX_HEIGHT,
                        self::VARIANT_MAX_BYTES,
                        true
                    );

                    if ($error !== null) {
                        $errors[$inputName][] = $error;
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Validate one media-manager image ID.
     */
    private function validateMediaImage(
        $mediaId,
        string $label,
        int $minWidth,
        int $minHeight,
        int $maxWidth,
        int $maxHeight,
        int $maxBytes,
        bool $requireSquare = true
    ): ?string {
        if ($mediaId === null) {
            return $label . ' is required.';
        }

        $displayPath = getFilePath($mediaId);
        if (!$displayPath || !is_string($displayPath)) {
            return $label . ' is invalid or the selected media file no longer exists.';
        }

        $absolutePath = $this->resolveMediaAbsolutePath($displayPath);
        if ($absolutePath === null || !is_file($absolutePath)) {
            return $label . ' file could not be found on the server.';
        }

        $imageInfo = @getimagesize($absolutePath);
        if ($imageInfo === false) {
            return $label . ' must be a valid image file.';
        }

        $mime = strtolower((string) ($imageInfo['mime'] ?? ''));
        if (!in_array($mime, self::ALLOWED_IMAGE_MIMES, true)) {
            return $label . ' must be JPG, JPEG, PNG, or WEBP.';
        }

        $fileSize = @filesize($absolutePath);
        if ($fileSize === false) {
            return $label . ' file size could not be checked.';
        }

        if ($fileSize > $maxBytes) {
            return $label . ' must not exceed ' . $this->formatBytesForMessage($maxBytes) . '.';
        }

        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);

        if ($width < $minWidth || $height < $minHeight) {
            return $label . ' is too small. Minimum size is '
                . $minWidth . 'x' . $minHeight . ' px; uploaded image is '
                . $width . 'x' . $height . ' px.';
        }

        if ($width > $maxWidth || $height > $maxHeight) {
            return $label . ' is too large. Maximum size is '
                . $maxWidth . 'x' . $maxHeight . ' px; uploaded image is '
                . $width . 'x' . $height . ' px.';
        }

        if ($requireSquare && $width !== $height) {
            return $label . ' must use a 1:1 square aspect ratio. Uploaded image is '
                . $width . 'x' . $height . ' px.';
        }

        return null;
    }

    /**
     * Convert the public URL/path returned by getFilePath() to a real local file.
     * Supports the common /public/..., /storage/... and direct public paths.
     */
    private function resolveMediaAbsolutePath(string $displayPath): ?string
    {
        $path = parse_url($displayPath, PHP_URL_PATH);
        $path = $path !== null && $path !== false ? $path : $displayPath;
        $path = rawurldecode($path);
        $relative = ltrim($path, '/');

        if ($relative === '') {
            return null;
        }

        $candidates = [];

        if (strpos($relative, 'public/') === 0) {
            $withoutPrefix = substr($relative, strlen('public/'));
            $candidates[] = public_path($withoutPrefix);
            $candidates[] = storage_path('app/public/' . $withoutPrefix);
        } elseif (strpos($relative, 'storage/') === 0) {
            $withoutPrefix = substr($relative, strlen('storage/'));
            $candidates[] = public_path($relative);
            $candidates[] = storage_path('app/public/' . $withoutPrefix);
        } else {
            $candidates[] = public_path($relative);
            $candidates[] = storage_path('app/public/' . $relative);
        }

        foreach (array_unique($candidates) as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Accept the project's comma-separated media IDs and normalize them.
     */
    private function parseMediaIds($value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            $values = $value;
        } else {
            $values = explode(',', (string) $value);
        }

        $ids = [];
        foreach ($values as $valueItem) {
            $id = $this->normalizeMediaId($valueItem);
            if ($id !== null) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    private function normalizeMediaId($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function mediaIdSetsDiffer(array $first, array $second): bool
    {
        $first = array_values(array_unique(array_map('strval', $first)));
        $second = array_values(array_unique(array_map('strval', $second)));
        sort($first, SORT_STRING);
        sort($second, SORT_STRING);

        return $first !== $second;
    }

    private function formatBytesForMessage(int $bytes): string
    {
        if ($bytes >= 1048576) {
            $mb = $bytes / 1048576;
            return rtrim(rtrim(number_format($mb, 1, '.', ''), '0'), '.') . ' MB';
        }

        return (string) $bytes . ' bytes';
    }

    private function isModernLayoutActive(): bool
    {
        return DB::table('tl_store_layouts')
            ->where('name', 'modern')
            ->where('is_active', 1)
            ->exists();
    }
}
