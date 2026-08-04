@php
    $currencies = getAllCurrencies();
    $selecected_currency = \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'upayments_currency',
    );
    $default_currency = $selecected_currency == null ? getDefaultCurrency() : $selecected_currency;
@endphp
<div class="p-3 payment-method-item-body">
    <div class="configuration">
        <form id="credential-form">
            <input type="hidden" name="payment_id" value="{{ $method->id }}">
            <input type="hidden" name="upayments_gateway_src" value="apple-pay">
            <input type="hidden" name="upayments_integration_mode" value="whitelabel">
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Logo') }}</label>
                <div class="input-option">
                    @include('core::base.includes.media.media_input', [
                        'input' => 'upayments_logo',
                        'data' => \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
                            $method->id,
                            'upayments_logo'),
                    ])
                </div>
            </div>
            <div class="form-group mb-20">
                <label class="black bold">{{ translate('Currency') }}</label>
                <div class="mb-2">
                    <a href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}"
                        class="mt-2 btn-link">({{ translate('Please setup exchange rate for the selected currency') }})</a>
                </div>
                <div class="input-option">
                    <select name="upayments_currency" class="theme-input-style selectCurrency">
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
                <label class="black bold mb-2">{{ translate('Upayments API Token') }}</label>
                <div class="input-option">
                    <input type="text" class="theme-input-style" name="upayments_api_token"
                        placeholder="Enter Upayments Bearer API Token"
                        value="{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'upayments_api_token') }}"
                        required />
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Sandbox Mode') }}</label>
                <div class="input-option">
                    <label class="switch medium">
                        <input type="checkbox" name="sandbox" @if (
                            \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'sandbox') ==
                                config('settings.general_status.active')) checked @endif>
                        <span class="control"></span>
                    </label>
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Instruction') }}</label>
                <div class="input-option">
                    <textarea name="upayments_instruction" id="instruction" class="theme-input-style">{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'upayments_instruction') }}</textarea>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn long payment-credental-update-btn btn-orange"
                    data-payment-btn="{{ $method->id }}">{{ translate('Save Changes') }}</button>
            </div>
        </form>
    </div>
    <div class="instruction">
        <a href="https://upayments.com/" target="_blank" class="btn-link">Upayments</a>
        <p>
            Customer is redirected to the Upayments hosted Apple Pay payment page.
        </p>
        <p class="semi-bold">
            Configuration instruction for Upayments Apple Pay
        </p>
        <p>Use the same Upayments API token as KNET if both are on one merchant account.</p>
        <p>Webhook URL: <code>{{ url('/payment/upayments/webhook') }}</code></p>
    </div>
</div>

@include('plugin/tlecommercecore::payments.gateways.upayments.partials.configuration_styles')
