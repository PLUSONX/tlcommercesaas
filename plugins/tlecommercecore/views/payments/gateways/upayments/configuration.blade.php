@php
    $currencies = getAllCurrencies();
    $selecected_currency = \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'upayments_currency',
    );
    $default_currency = $selecected_currency == null ? getDefaultCurrency() : $selecected_currency;
    $gateway_src = \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'upayments_gateway_src',
    );
    $gateway_src = $gateway_src ?: 'knet';
    $integration_mode = \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'upayments_integration_mode',
    );
    $integration_mode = $integration_mode ?: 'non_whitelabel';
@endphp
<div class="p-3 payment-method-item-body">
    <div class="configuration">
        <form id="credential-form">
            <input type="hidden" name="payment_id" value="{{ $method->id }}">
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
                        placeholder="Enter Upayments Bearer API Token (sandbox non-WL: jtest123)"
                        value="{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'upayments_api_token') }}"
                        required />
                </div>
                <p class="mt-1 mb-0" style="font-size: 13px;">
                    Sandbox non-whitelabel test token: <code>jtest123</code> — use with Integration Mode
                    <strong>Non-Whitelabel</strong> only.
                </p>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Callback Base URL') }} ({{ translate('optional') }})</label>
                <div class="input-option">
                    <input type="url" class="theme-input-style" name="upayments_callback_base_url"
                        placeholder="https://your-tenant-domain.com or ngrok HTTPS URL"
                        value="{{ \Plugin\TlcommerceCore\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'upayments_callback_base_url') }}" />
                </div>
                <p class="mt-1 mb-0" style="font-size: 13px;">
                    Leave empty to use the current tenant domain. uPayments requires public <strong>HTTPS</strong>
                    return/cancel/webhook URLs. For local Laragon testing, use an ngrok URL here.
                </p>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Integration Mode') }}</label>
                <div class="input-option">
                    <select name="upayments_integration_mode" class="theme-input-style">
                        <option value="non_whitelabel" {{ $integration_mode === 'non_whitelabel' ? 'selected' : '' }}>
                            Non-Whitelabel (hosted checkout — recommended)
                        </option>
                        <option value="whitelabel" {{ $integration_mode === 'whitelabel' ? 'selected' : '' }}>
                            Whitelabel (requires paymentGateway.src on account)
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Payment Gateway Source') }}</label>
                <div class="input-option">
                    <select name="upayments_gateway_src" class="theme-input-style">
                        <option value="knet" {{ $gateway_src === 'knet' ? 'selected' : '' }}>KNET (whitelabel only)</option>
                        <option value="cc" {{ $gateway_src === 'cc' ? 'selected' : '' }}>Credit Card (whitelabel only)</option>
                    </select>
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
            Customer is redirected to the Upayments hosted payment page for KNET.
        </p>
        <p class="semi-bold">
            Configuration instruction for Upayments
        </p>
        <p>To use Upayments, you need to:</p>
        <ol>
            <li style="list-style-type: decimal">
                Register with Upayments and obtain your API Bearer token
            </li>
            <li style="list-style-type: decimal">
                Enter the token, currency (KWD), and enable sandbox for testing
            </li>
            <li style="list-style-type: decimal">
                Webhook URL (must be public HTTPS):
                <code>{{ str_replace('http://', 'https://', url('/payment/upayments/webhook')) }}</code>
            </li>
            <li style="list-style-type: decimal">
                For sandbox: token <code>jtest123</code> + Integration Mode <strong>Non-Whitelabel</strong>
            </li>
        </ol>
    </div>
</div>

@include('plugin/tlecommercecore::payments.gateways.upayments.partials.configuration_styles')
