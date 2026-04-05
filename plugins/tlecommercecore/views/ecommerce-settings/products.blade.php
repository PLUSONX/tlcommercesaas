<div class="card">
    <div class="card-body">
        <!-- <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold black ">{{ translate('Enable product reviews') }}
                </label>
            </div>
            <div class="col-sm-6">
                <label class="switch glow primary medium">
                    <input type="checkbox" name="enable_product_reviews" class="enable-product-review"
                        @if (getEcommerceSetting('enable_product_reviews') == config('settings.general_status.active')) checked @endif>
                    <span class="control"></span>
                </label>
            </div>
        </div> -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <!-- <div class="col-sm-3"> -->
                    <label class="switch glow primary medium">
                        <input type="checkbox" name="enable_product_reviews" class="enable-product-review"
                            @if (getEcommerceSetting('enable_product_reviews') == config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                <!-- </div> -->

                <label class="font-14 bold black ">{{ translate('Product Reviews') }}
                </label>
            </div>
            
        </div>
        <!-- <div
            class="product-review-setting-group {{ getEcommerceSetting('enable_product_reviews') == config('settings.general_status.active') ? '' : 'd-none' }}">
            <div class="form-row mb-20">
                <div class="col-sm-6">
                    <label class="font-14 bold black ">{{ translate('Enable star rating on product reviews') }}
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="switch glow primary medium">
                        <input type="checkbox" name="enable_product_star_rating"
                            @if (getEcommerceSetting('enable_product_star_rating') == config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                </div>
            </div>
            <div class="form-row mb-20">
                <div class="col-sm-6">
                    <label class="font-14 bold black ">{{ translate('Star rating should be required not optional') }}
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="switch glow primary medium">
                        <input type="checkbox" name="required_product_star_rating"
                            @if (getEcommerceSetting('required_product_star_rating') == config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                </div>
            </div>
            <div class="form-row mb-20">
                <div class="col-sm-6">
                    <label
                        class="font-14 bold black ">{{ translate('Show Verified customer label on product reviews') }}
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="switch glow primary medium">
                        <input type="checkbox" name="verified_customer_on_product_review"
                            @if (getEcommerceSetting('verified_customer_on_product_review') == config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                </div>
            </div>
            <div class="form-row mb-20">
                <div class="col-sm-6">
                    <label class="font-14 bold black ">{{ translate('Reviews can only be left by verified customer') }}
                    </label>
                </div>
                <div class="col-sm-6">
                    <label class="switch glow primary medium">
                        <input type="checkbox" name="only_varified_customer_left_review"
                            @if (getEcommerceSetting('only_varified_customer_left_review') == config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                </div>
            </div>
        </div> -->
        <!-- <hr> -->
        <div class="form-row mb-20">
            <div class="col-sm-6">

                <label class="switch glow primary medium">
                    <input type="checkbox" name="enable_product_compare"
                        @if (getEcommerceSetting('enable_product_compare') == config('settings.general_status.active')) checked @endif>
                    <span class="control"></span>
                </label>

                <label class="font-14 bold black ">{{ translate('Product Compare') }}
                </label>
            </div>
                
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-6">

                <label class="switch glow primary medium">
                    <input type="checkbox" name="enable_product_discount"
                        @if (getEcommerceSetting('enable_product_discount') == config('settings.general_status.active')) checked @endif>
                    <span class="control"></span>
                </label>

                <label class="font-14 bold black ">{{ translate('Product Discount') }}
                </label>
            </div>
            
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 black ">{{ translate('Display product perpage') }}
                </label>
            </div>
            <div class="col-md-12 mt-2">
                <input type="text" name="product_per_page" class="theme-input-style" 
                    value="{{ getEcommerceSetting('product_per_page') }}">
            </div>
        </div>
    </div>
</div>


<style>

    .col-sm-6 {
        display: flex;
        align-items: center; /* Vertically centers the toggle with the text */
        gap: 10px;           /* Adds space between the toggle and the label */
    }

    /* Ensure the label doesn't have a default bottom margin pushing it up */
    .col-sm-6 label {
        margin-bottom: 0 !important;
    }

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

      .switch.medium input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.medium .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: none !important;

    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.medium input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
        box-shadow: none !important;

    }


</style>