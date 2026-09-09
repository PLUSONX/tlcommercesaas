<form id="courier-properties-form">

    @php
        $provider = $courier_properties['provider'] ?? 'armada';
        $isKarrix = $provider === 'karrix';
        $isSmooth = $provider === 'smooth';
    @endphp

    <input type="hidden" name="shipping_courier_id" id="shipping_courier_id"  value="{{ $courier_properties['shipping_courier_id'] ?? 0 }}">

    @if ($isSmooth)
        <div class="form-row mb-20">
            <label class="font-14 bold black">{{ translate('Smooth API Key') }} </label>
            <input type="password" name="api_key" autocomplete="new-password"
                placeholder="{{ translate('Leave blank to keep the saved Smooth API key') }}"
                value="" class="theme-input-style">
        </div>

        <div class="form-row mb-20">
            <label class="font-14 bold black">{{ translate('Smooth Store Slug') }} </label>
            <input type="text" name="branch_id"
                placeholder="{{ translate('Paste the pre-shared Smooth Store Slug') }}"
                value="{{ $courier_properties['branch_id'] ?? '' }}" class="theme-input-style">
        </div>

        <div class="alert alert-info mb-20">
            <div><strong>{{ translate('Authentication:') }}</strong> {{ translate('api-key header') }}</div>
            <div><strong>{{ translate('Status Callback:') }}</strong>
                {{ url('/api/smooth/orders/status-update/{owner_slug}/{order_id}') }}
            </div>
            <div class="mt-1">
                {{ translate('Smooth becomes usable automatically after both API Key and Store Slug are saved. No API Secret is required by the supplied Smooth API schema.') }}
            </div>
        </div>
    @else
        <div class="form-row mb-20">
            <label class="font-14 bold black">{{ translate($isKarrix ? 'Karrix API Token' : 'Api Key') }} </label>
            <input type="{{ $isKarrix ? 'password' : 'text' }}" name="api_key" autocomplete="off"
                placeholder="{{ translate($isKarrix ? 'Leave blank to keep the saved Karrix token' : 'Paste Api Key here') }}"
                value="{{ $courier_properties['api_key'] ?? '' }}" class="theme-input-style">

        </div>

        <div class="form-row mb-20">
            <label class="font-14 bold black">{{ translate($isKarrix ? 'Webhook Signing Secret' : 'Api Secret') }} </label>
            <input type="password" name="api_secret" autocomplete="new-password"
                placeholder="{{ translate($isKarrix ? 'Leave blank to keep the saved webhook secret' : 'Leave blank to keep the saved API secret') }}"
                value="" class="theme-input-style">

        </div>

        <div class="form-row mb-20">
            <label class="font-14 bold black">{{ translate($isKarrix ? 'Pickup Location ID' : 'Branch Id') }} </label>
            <input type="text" name="branch_id"
                placeholder="{{ translate($isKarrix ? 'Paste the saved Karrix pickup location ID' : 'Paste Branch Id here') }}"
                value="{{ $courier_properties['branch_id'] ?? '' }}" class="theme-input-style">

        </div>

        @if ($isKarrix)
            <div class="alert alert-info mb-20">
                {{ translate('Webhook URL:') }}
                <strong>{{ url('/api/carrier/karrix/webhook') }}</strong>
            </div>
        @endif
    @endif
    
    <div class="form-row">
        <div class="col-12 text-right">
            <button type="submit" class="btn long btn-orange properties-courier-btn">{{ translate('Save Changes') }}</button>
        </div>
    </div>
</form>
<script>
    /**
     * Update courier information
     * 
     **/
    $('.properties-courier-btn').on('click', function(e) {
        e.preventDefault();
        $(document).find(".invalid-input").remove();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            data: $('#courier-properties-form').serialize(),
            url: '{{ route('plugin.carrier.shipping.courier.submit.properties') }}',
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                $.each(response.responseJSON.errors, function(field_name, error) {
                    $(document).find('[name=' + field_name + ']').after(
                        '<div class="invalid-input">' + error + '</div>')
                })
            }
        });
    });
</script>


<style>

    button.btn-orange,
    a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        border-radius: 6px !important;
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


</style>
