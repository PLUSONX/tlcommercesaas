@php
    use Theme\TLCommerce\Http\Resources\QuizResultsConfig;

    $profilesSource = isset($layoutConfigForForm)
        ? ($layoutConfigForForm['results']['product_profiles'] ?? null)
        : (isset($quiz) && is_array($quiz->layout_config['results'] ?? null)
            ? ($quiz->layout_config['results']['product_profiles'] ?? null)
            : null);
    $profilesRaw = old('results_product_profiles_json')
        ? json_decode(old('results_product_profiles_json'), true)
        : $profilesSource;
    $productProfiles = QuizResultsConfig::normalizeProductProfiles(
        is_array($profilesRaw) ? $profilesRaw : null
    );
    $isDefaultLang = $isDefaultLang ?? true;
    $layoutMode = old('results_layout_mode', isset($layoutConfigForForm)
        ? ($layoutConfigForForm['results']['layout_mode'] ?? 'featured_card')
        : (isset($quiz) ? ($quiz->layout_config['results']['layout_mode'] ?? 'featured_card') : 'featured_card'));
    $showProfilesPanel = $layoutMode === 'product_color_card';
@endphp

<input type="hidden" name="results_product_profiles_json" id="results-product-profiles-json" value='@json($productProfiles)'>

<div id="results-product-profiles-panel" class="results-theme-section mb-3 {{ $showProfilesPanel ? '' : 'd-none' }}">
    <div class="intro-panel-label">{{ translate('Product result profiles') }}</div>
    <p class="text-muted small mb-3">
        {{ translate('Map each quiz outcome product to a display color and tagline. The product image is taken from the catalog.') }}
    </p>

    <div class="form-row mb-20 align-items-end @if (!$isDefaultLang) area-disabled @endif">
        <div class="col-md-8">
            <label class="font-14 bold black">{{ translate('Add product') }}</label>
            <select id="results-product-search" class="theme-input-style w-100"></select>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn long btn-orange" id="results-add-product-profile">{{ translate('Add to list') }}</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="hoverable text-nowrap border-top2" id="results-product-profiles-table">
            <thead style="background: #F3F4F6;">
                <tr>
                    <th>{{ translate('Product') }}</th>
                    <th style="width: 200px;">{{ translate('Color') }}</th>
                    <th>{{ translate('Tagline') }}</th>
                    <th style="width: 100px;">{{ translate('Remove') }}</th>
                </tr>
            </thead>
            <tbody id="results-product-profiles-body">
                @foreach ($productProfiles as $index => $profile)
                    @php
                        $colorId = 'results-profile-color-' . $profile['product_id'];
                        $taglineId = 'results-profile-tagline-' . $profile['product_id'];
                        $productName = $profile['product_name'] ?? '';
                        if ($productName === '' && !empty($profile['product_id'])) {
                            $profileProduct = \Plugin\TlcommerceCore\Models\Product::find($profile['product_id']);
                            $productName = $profileProduct
                                ? $profileProduct->translation('name', $lang ?? getDefaultLang())
                                : '';
                        }
                    @endphp
                    <tr data-product-id="{{ $profile['product_id'] }}" data-product-name="{{ $productName }}">
                        <td class="results-profile-name">{{ $productName ?: ('#' . $profile['product_id']) }}</td>
                        <td>
                            <div class="@if (!$isDefaultLang) area-disabled @endif">
                                @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                                    'id' => $colorId,
                                    'value' => $profile['color'] ?? '#c9a84c',
                                    'class' => 'results-profile-color-input',
                                ])
                            </div>
                        </td>
                        <td>
                            <input type="text" id="{{ $taglineId }}" class="theme-input-style w-100 results-profile-tagline-input"
                                value="{{ $profile['tagline'] ?? '' }}" placeholder="{{ translate('THE GOLDEN OPTIMIST') }}">
                        </td>
                        <td>
                            <button type="button" class="btn long btn-sm btn-danger results-profile-remove @if (!$isDefaultLang) area-disabled @endif">{{ translate('Remove') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p id="results-product-profiles-empty" class="text-muted small mb-0 {{ count($productProfiles) ? 'd-none' : '' }}">
        {{ translate('No products added yet. Search and add products that can appear as quiz results.') }}
    </p>
</div>

<style>
    #results-product-profiles-table .input-group { margin-bottom: 0; }
    #results-product-profiles-panel .select2-container { width: 100% !important; }
</style>
