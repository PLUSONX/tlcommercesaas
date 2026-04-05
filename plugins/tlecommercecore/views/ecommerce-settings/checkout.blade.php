 <div class="card">
     <div class="card-body">
         <div class="form-row mb-30">
             <div class="col-sm-6">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_billing_address" @checked(getEcommerceSetting('enable_billing_address') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black">{{ translate('Billing Address') }}
                 </label>
             </div>
                 
         </div>
         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="use_shipping_address_as_billing_address" @checked(getEcommerceSetting('use_shipping_address_as_billing_address') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>

                 <label
                     class="font-14 black ">{{ translate('Use shipping address by default') }}
                 </label>
             </div>
                 
         </div>
         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_guest_checkout" @checked(getEcommerceSetting('enable_guest_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black ">{{ translate('Guest Checkout') }}
                 </label>
             </div>
                 
         </div>


         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="send_invoice_to_customer_mail" @checked(getEcommerceSetting('send_invoice_to_customer_mail') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>

                 <label class="font-14 black">{{ translate('Send invoice to customer email') }}
                 </label>
             </div>
                 
             <div class="col-md-12">
                 <p class="mt-1 font-13">
                     {{ translate('You need to complete email configuration and Cron Job setup') }}
                     <!-- <br> -->
                     <a href="{{ route('core.email.smtp.configuration') }}"
                         class="btn-link">{{ translate('Configure Email') }}
                     </a>
                 </p>
             </div>
         </div>
         <!-- <div class="form-row mb-20 {{ isActivePluging('coupon') ? '' : 'area-disabled mb-0' }}"> -->
         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_coupon_in_checkout" class="enable-coupon-in-checkout"
                         @checked(getEcommerceSetting('enable_coupon_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black">{{ translate('Coupon in Checkout') }}
                 </label>
             </div>
                 
             <!-- @if (isActivePluging('coupon')) -->
                 <div class="col-md-12">
                     <p class="mt-0 font-13">{{ translate('You can manage your coupons from ') }}
                         <a href="{{ route('plugin.tlcommercecore.marketing.coupon.list') }}" class="btn-link">
                             Coupons Module
                         </a>
                     </p>
                 </div>
             <!-- @endif -->

         </div>
         <!-- @if (isActivePluging('coupon')) -->
             <div
                 class="form-row mb-30 multiple-coupon-checkout">
                 <div class="col-sm-6">
                    <label class="switch glow primary medium">
                         <!-- <input type="checkbox" name="enable_multiple_coupon_in_checkout" @checked(getEcommerceSetting('enable_multiple_coupon_in_checkout') == config('settings.general_status.active'))> -->
                         <input type="checkbox" name="enable_multiple_coupon_in_checkout">
                         <span class="control"></span>
                     </label>
                     <label class="font-14 black ">{{ translate('Multiple Coupon in single order') }}
                     </label>
                 </div>
             </div>
         <!-- @endif -->
         <!--Wallet-->
         <!-- @if (isActivePluging('wallet'))
             <div class="form-row mb-20">
                 <div class="col-sm-6">
                     <label class="font-14 bold black ">{{ translate('Enable wallet in checkout') }}
                     </label>
                 </div>
                 <div class="col-sm-6">
                     <label class="switch glow primary medium">
                         <input type="checkbox" name="enable_wallet_in_checkout" @checked(getEcommerceSetting('enable_wallet_in_checkout') == config('settings.general_status.active'))>
                         <span class="control"></span>
                     </label>
                 </div>
             </div>
         @endif -->
         <!--End wallet-->
         <!--Order note-->
         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_order_note_in_checkout" @checked(getEcommerceSetting('enable_order_note_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black ">{{ translate('Order Note') }}
                 </label>
             </div>
                 
         </div>
         <!--End order note-->
         <!--Documents-->
         <!-- <div class="form-row mb-20">
             <div class="col-sm-6">
                 <label class="font-14 bold black ">{{ translate('Enable document in checkout') }}
                 </label>
             </div>
             <div class="col-sm-6">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_document_in_checkout" @checked(getEcommerceSetting('enable_document_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
             </div>
         </div> -->
         <!--End Documents-->
         <!--Carriers-->
         <!-- <div class="form-row mb-20 {{ isActivePluging('carrier') ? '' : 'area-disabled mb-0' }}"> -->
         <div class="form-row mb-30 ">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_carrier_in_checkout" @checked(getEcommerceSetting('enable_carrier_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black ">{{ translate('Carrier in Checkout') }}
                 </label>
             </div>
                 
             <!-- @if (isActivePluging('carrier')) -->
                 <div class="col-md-12">
                     <p class="mt-0 font-13">{{ translate('Manage your') }}
                         <a href="{{ route('plugin.carrier.list') }}" class="btn-link">3rd Party
                             Carriers
                         </a>
                     </p>
                 </div>
             <!-- @endif -->
         </div>
         <!--End carriers-->
         <!--Pickup points-->
         <!-- <div class="form-row mb-20 {{ isActivePluging('pickuppoint') ? '' : 'area-disabled mb-0' }}">
             <div class="col-sm-6">
                 <label class="font-14 bold black ">{{ translate('Enable pickup point in checkout') }}
                 </label>
             </div>
             <div class="col-sm-1">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_pickuppoint_in_checkout" @checked(getEcommerceSetting('enable_pickuppoint_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
             </div>
             @if (isActivePluging('pickuppoint'))
                 <div class="col-sm-5">
                     <p class="mt-0 font-13">{{ translate('Manage your') }}
                         <a href="{{ route('plugin.pickuppoint.pickup.points') }}" class="btn-link">
                             Pickup Points
                         </a>
                     </p>
                 </div>
             @endif
         </div>
         @if (!isActivePluging('pickuppoint'))
             <div class="form-row mb-20">
                 <div class="col-12">
                     <p class="mt-0 font-13">
                         {{ translate('To enable pickup point you need to activate') }}
                         <a href="{{ route('core.plugins.index') }}" class="btn-link">
                             Pickup Point Plugin
                         </a>
                     </p>
                 </div>
             </div>
         @endif -->
         <!--End Pickup points-->
         <!--Min order amount-->
         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_minumun_order_amount" class="enable-minumun-order-amount"
                         @checked(getEcommerceSetting('enable_minumun_order_amount') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black">{{ translate('Minimum Order Amount') }}
                 </label>
             </div>
                 
         </div>
         <div
             class="form-row mb-30 minimum-order-amount {{ getEcommerceSetting('enable_minumun_order_amount') == config('settings.general_status.active') ? '' : 'd-none' }}">
             <label class="font-14 bold black col-sm-6">{{ translate('Minimum order amount') }}
             </label>
             <div class="col-md-12">

                 <input type="number" name="min_order_amount" value="{{ getEcommerceSetting('min_order_amount') }}"
                     class="theme-input-style col-sm-6" placeholder="0.00">
             </div>
         </div>
         <!--End mi order amount-->
         <!-- <h4 class="mb-3">Checkout Form</h4>
         <div class="form-row mb-20">
             <div class="col-sm-4">
                 <label class="font-14 bold black ">
                     {{ translate('Name') }}
                 </label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_name_in_checkout" @checked(getEcommerceSetting('enable_name_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Enable/ Disbale</label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="name_required_in_checkout" @checked(getEcommerceSetting('name_required_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Required/ Optional</label>
             </div>
         </div>

         <div class="form-row mb-20">
             <div class="col-sm-4">
                 <label class="font-14 bold black ">
                     {{ translate('Email') }}
                 </label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_email_in_checkout" @checked(getEcommerceSetting('enable_email_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Enable/ Disbale</label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="email_required_in_checkout" @checked(getEcommerceSetting('email_required_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Required/ Optional</label>
             </div>
         </div>

         <div class="form-row mb-20">
             <div class="col-sm-4">
                 <label class="font-14 bold black ">
                     {{ translate('Phone') }}
                 </label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_phone_in_checkout" @checked(getEcommerceSetting('enable_phone_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Enable/ Disbale</label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="phone_required_in_checkout" @checked(getEcommerceSetting('phone_required_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Required/ Optional</label>
             </div>
         </div>

         <div class="form-row mb-20">
             <div class="col-sm-4">
                 <label class="font-14 bold black ">
                     {{ translate('Address') }}
                 </label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_address_in_checkout" @checked(getEcommerceSetting('enable_address_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Enable/ Disbale</label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="address_required_in_checkout" @checked(getEcommerceSetting('address_required_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Required/ Optional</label>
             </div>
         </div>

         <div class="form-row mb-20">
             <div class="col-sm-4">
                 <label class="font-14 bold black ">
                     {{ translate('Post Code') }}
                 </label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_post_code_in_checkout" @checked(getEcommerceSetting('enable_post_code_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Enable/ Disbale</label>
             </div>
             <div class="col-sm-4 align-items-center d-flex gap-10">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="post_code_required_in_checkout" @checked(getEcommerceSetting('post_code_required_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 bold black">Required/ Optional</label>
             </div>
         </div> -->

         <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="enable_personal_info_guest_checkout" @checked(getEcommerceSetting('enable_personal_info_guest_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black ml-2">{{ translate('Personal Information In Guest Checkout') }}
                 </label>
             </div>
                 
         </div>
         <!-- <div class="form-row mb-30">
             <div class="col-sm-6">
                <label class="switch glow primary medium">
                     <input type="checkbox" name="create_account_in_guest_checkout" @checked(getEcommerceSetting('create_account_in_guest_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
                 <label class="font-14 black ">{{ translate('Create Account In Guest Checkout') }}
                 </label>
             </div>
                 
         </div> -->
         <!-- <div class="form-row mb-20">
             <div class="col-sm-6">
                 <label class="font-14 bold black ">
                     {{ translate('Hide Country, State and city dropdown in checkout') }}
                 </label>
             </div>
             <div class="col-sm-1">
                 <label class="switch glow primary medium">
                     <input type="checkbox" name="hide_country_state_city_in_checkout" @checked(getEcommerceSetting('hide_country_state_city_in_checkout') == config('settings.general_status.active'))>
                     <span class="control"></span>
                 </label>
             </div>
             <div class="col-sm-5">
                 <p class="mt-0 font-13">
                     {{ translate('If you enable hide Country, State and city dropdown in checkout then you must select Flat rate shipping cost or Product wise shiping cost. Based on shipping profile not working') }}
                     <a href="{{ route('plugin.tlcommercecore.shipping.configuration') }}" class="btn-link">
                         {{ translate('Configure Shipping Options') }}
                     </a>
                 </p>
             </div>
         </div> -->
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