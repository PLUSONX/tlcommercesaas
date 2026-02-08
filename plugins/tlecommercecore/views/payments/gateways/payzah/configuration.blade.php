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
                        class="mt-2">({{ translate('Please setup exchange rate for the choosed currency') }})</a>
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
            <div>
                <button class="btn long payment-credental-update-btn"
                    data-payment-btn="{{ $method->id }}">{{ translate('Save Changes') }}</button>
            </div>
        </form>
    </div>
    <div class="instruction">
        <a href="https://payzah.com/" target="_blank">Payzah</a>
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
