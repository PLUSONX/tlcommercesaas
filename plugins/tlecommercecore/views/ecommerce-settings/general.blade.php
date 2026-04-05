<div class="card">
    <div class="card-body">
        @if (count($currencies) > 0)
            <!-- <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 bold black">{{ translate('Default currency') }}
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="default_currency">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" @if (getEcommerceSetting('default_currency') == $currency->id) selected @endif>
                                {{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <p class="mt-0 font-13"> {{ translate('You can manage your currencies from') }}
                        <a href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}"
                            class="btn-link">{{ translate('Currencies Module') }}
                        </a>
                    </p>
                </div>
            </div> -->

            <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 black">{{ translate('Default currency') }}
                    </label>
                </div>
                <div class="col-md-12">
                    <select class="form-control theme-input-style mb-1" name="default_currency" style="border: 1px solid black !important;">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" @if (getEcommerceSetting('default_currency') == $currency->id) selected @endif>
                                {{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <p class="mt-0 font-13"> {{ translate('You can manage your currencies from') }}
                        <a href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}"
                            class="btn-link">{{ translate('Currencies Module') }}
                        </a>
                    </p>
                </div>
            </div>
        @else
            <p class="mt-0 font-13">
                {{ translate('To set default currency, plaese create a currency') }} <a
                    href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}"
                    class="btn-link">{{ translate('click here') }}</a></p>
        @endif

        @php
            $all_pages = \Core\Models\TlPage::where('publish_status', config('settings.general_status.active'))
                ->select('id', 'title')
                ->get();
        @endphp
        <div class="form-row mb-20">
            <div class="col-sm-4">
                <label class="font-14 black">{{ translate('Customer Term & Condition Page') }}
                </label>
            </div>
            <div class="col-md-12">
                <select class="form-control theme-input-style mb-1" name="customer_term_condition_page" style="border: 1px solid black !important;">
                    <option value="">{{ translate('Select a page') }}</option>
                    @foreach ($all_pages as $page)
                        <option value="{{ $page->id }}" @selected(getEcommerceSetting('customer_term_condition_page') == $page->id)>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-9">
                <p class="mt-0 font-13">
                    {{ translate('To create new page or manage existing pages from') }}
                    <a href="{{ route('core.page') }}" class="btn-link">{{ translate('Pages Module') }}
                    </a>
                </p>
            </div>
        </div>
        <!-- <div class="form-row mb-20">
            <div class="col-sm-4">
                <label class="font-14 bold black">{{ translate('Customer Term & Condition Page') }}
                </label>
            </div>
            <div class="col-sm-4">
                <select class="form-control" name="customer_term_condition_page">
                    <option value="">{{ translate('Select a page') }}</option>
                    @foreach ($all_pages as $page)
                        <option value="{{ $page->id }}" @selected(getEcommerceSetting('customer_term_condition_page') == $page->id)>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <p class="mt-0 font-13">
                    {{ translate('To create new page or manage existing pages from') }}
                    <a href="{{ route('core.page') }}" class="btn-link">{{ translate('Pages Module') }}
                    </a>
                </p>
            </div>
        </div> -->
        <!-- @if (isActivePluging('multivendor'))
            <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 bold black">{{ translate('Seller Term & Condition Page') }}
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="seller_term_condition_page">
                        <option value="">{{ translate('Select a page') }}</option>
                        @foreach ($all_pages as $page)
                            <option value="{{ $page->id }}" @selected(getEcommerceSetting('seller_term_condition_page') == $page->id)>
                                {{ $page->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <p class="mt-0 font-13">
                        {{ translate('To create new page or manage existing pages from') }}
                        <a href="{{ route('core.page') }}" class="btn-link">{{ translate('Pages Module') }}
                        </a>
                    </p>
                </div>
            </div>
        @endif -->
    </div>
</div>


<style>

    .theme-input-style {
        padding-left: 10px !important; 
        padding-right: 10px !important; 
        width: 100%;
        background: white;
        border: 1px solid black;
    }

    .theme-input-style:focus, 
    .theme-input-style:active,
    .theme-input-style:hover {
        background-color: white !important;
        /* background-: white !important; Extra insurance */
        outline: none;                /* Optional: removes default browser glow */
        border: 1px solid black !important; /* Keeps your border consistent */
    }

    .btn-link {
        color: #ff5A1f !important;
    }

    select.theme-input-style {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        /* adjust 12px to move arrow left/right */
         background-position: right 12px bottom 10px;
         /* background-position: calc(100% - 12px) center !important; */
        padding-right: 2rem;
    }



</style>