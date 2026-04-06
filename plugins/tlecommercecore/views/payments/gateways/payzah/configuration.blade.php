@php
    $currencies = getAllCurrencies();
    $selecected_currency = \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'payzah_currency',
    );
    $default_currency = $selecected_currency == null ? getDefaultCurrency() : $selecected_currency;
@endphp
<div class="p-3 payment-method-item-body">
    <div class="configuration">
        <form id="credential-form">
            <input type="hidden" name="payment_id" value="{{ $method->id }}">
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Logo') }}</label>
                <div class="input-option">
                    @include('core::base.includes.media.media_input', [
                        'input' => 'paystack_logo',
                        'data' => \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
                            $method->id,
                            'payzah_logo'),
                    ])
                </div>
            </div>
            <div class="form-group mb-20">
                <label class="black bold">{{ translate('Currency') }}</label>
                <div class="mb-2">
                    <a href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}"
                        class="mt-2 btn-link">({{ translate('Please setup exchange rate for the choosed currency') }})</a>
                </div>
                <div class="input-option">
                    <select name="paystack_currency" class="theme-input-style selectCurrency">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->code }}" class="text-uppercase"
                                {{ $currency->code == $default_currency ? 'selected' : '' }}>
                                {{ $currency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Payzah Secret Key') }}</label>
                <div class="input-option">
                    <input type="text" class="theme-input-style" name="payzah_secret_key"
                        placeholder="Enter Payzah Secret Key"
                        value="{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'payzah_secret_key') }}"
                        required />
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Instruction') }}</label>
                <div class="input-option">
                    <textarea name="payzah_instruction" id="instruction" class="theme-input-style">{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'payzah_instruction') }}</textarea>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn long payment-credental-update-btn btn-orange"
                    data-payment-btn="{{ $method->id }}">{{ translate('Save Changes') }}</button>
            </div>
        </form>
    </div>
    <div class="instruction">
        <a href="https://payzah.com/" target="_blank" class="btn-link">Payzah</a>
        <p>
            Customer can buy product and pay directly using Payzah
        </p>
        <p class="semi-bold">
            Configuration instruction for Payzah
        </p>
        <p>To use Payzah, you need to:</p>
        <ol>
            <li style="list-style-type: decimal">
                Register with Payzah
            </li>
            <li style="list-style-type: decimal">
                <p>
                    After registration at Payzah, you will have the Secret Key
                </p>
            </li>
            <li style="list-style-type: decimal">
                <p>
                    Enter the Secret Key into the box in left hand
                </p>
            </li>
        </ol>
    </div>
</div>



<style>

    button.btn-orange,
    a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        border-radius: 8px !important;
        box-shadow: none !important;

    }

    button.btn-orange:hover,
    a.btn-orange:hover {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        color: #fff !important;
        box-shadow: none !important;

    }

    button.btn-orange:focus,
    button.btn-orange:active,
    button.btn-orange:active:focus {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* 1. The background of the switch track when ON */
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

        /* 1. Change the Active Page background and border */
     .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
   .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

    select.theme-input-style {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px bottom 10px; /* adjust 12px to move arrow left/right */
        padding-right: 2rem;
    }

    .theme-input-style {
        width: 100%;
        background-color: white !important;
        border: 1px solid black !important;
    }

    .theme-input-style:focus, 
    .theme-input-style:active,
    .theme-input-style:hover {
        background-color: white !important;
        outline: none;                /* Optional: removes default browser glow */
        border: 1px solid black !important; /* Keeps your border consistent */
    }

      .btn-link {
        color: #ff5A1f !important;
    }


</style>
