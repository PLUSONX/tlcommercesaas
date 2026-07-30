<div class="modal fade" id="product-layout-modal" tabindex="-1" role="dialog" aria-labelledby="productLayoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header">
                <h4 class="modal-title" id="productLayoutModalLabel">{{ translate('Product Layout') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('theme.tlcommerce.updateProductLayoutSettings') }}" method="POST"
                id="product-layout-form">
                @csrf

                <div class="modal-body">
                    <div class="form-row mb-20 align-items-center">
                        <div class="col-md-8">
                            <label class="font-16 bold black mb-1 d-block">{{ translate('List View') }}</label>
                            <p class="text-muted mb-0">
                                {{ translate('Display products in a vertical list instead of a grid on the shop page') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <label class="switch glow primary medium mb-0">
                                <input type="checkbox" name="is_list_view_enabled" value="1" id="is-list-view-enabled"
                                    {{ !empty($productListViewSettings->is_list_view_enabled) ? 'checked' : '' }}>
                                <span class="control"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row mb-20 align-items-center" id="organise-by-category-row">
                        <div class="col-md-8">
                            <label class="font-16 bold black mb-1 d-block">{{ translate('Organise by Category') }}</label>
                            <p class="text-muted mb-0">
                                {{ translate('Group products under top-level categories in a custom order') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <label class="switch glow primary medium mb-0">
                                <input type="checkbox" name="organise_by_category" value="1" id="organise-by-category"
                                    {{ !empty($productListViewSettings->organise_by_category) ? 'checked' : '' }}>
                                <span class="control"></span>
                            </label>
                        </div>
                    </div>

                    <div id="category-order-section">
                        <label class="font-16 bold black mb-2 d-block">{{ translate('Category Order') }}</label>
                        <p class="text-muted mb-3">
                            {{ translate('Drag categories to set the order shown on the shop page') }}
                        </p>

                        @if ($productListViewCategories->isEmpty())
                            <p class="alert alert-warning mb-0">{{ translate('No active top-level categories found') }}</p>
                        @else
                            <div id="product-layout-category-sortable">
                                @foreach ($productListViewCategories as $category)
                                    <div class="card mb-2 ui-state-default product-layout-category-item"
                                        data-category-id="{{ $category->id }}">
                                        <div class="card-body py-2 px-3 d-flex align-items-center">
                                            <i class="icofont-drag mr-2"></i>
                                            <span>{{ $category->name }}</span>
                                            <input type="hidden" name="category_order[]"
                                                value="{{ $category->id }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn long btn-danger" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn long btn-orange">
                        {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
