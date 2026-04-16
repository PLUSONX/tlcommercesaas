<form id="courier-properties-form">

    <input type="hidden" name="shipping_courier_id" id="shipping_courier_id"  value="{{ $courier_properties['shipping_courier_id'] ?? 0 }}">

    <div class="form-row mb-20">
        <label class="font-14 bold black">{{ translate('Api Key') }} </label>
        <input type="text" name="api_key" placeholder="{{ translate('Paste Api Key here') }}"
            value="{{ $courier_properties['api_key'] ?? '' }}" class="theme-input-style">

    </div>

    <div class="form-row mb-20">
        <label class="font-14 bold black">{{ translate('Api Secret') }} </label>
        <input type="text" name="api_secret" placeholder="{{ translate('Paste Api Secret here') }}"
            value="{{ $courier_properties['api_secret'] ?? '' }}" class="theme-input-style">

    </div>

    <div class="form-row mb-20">
        <label class="font-14 bold black">{{ translate('Branch Id') }} </label>
        <input type="text" name="branch_id" placeholder="{{ translate('Paste Branch Id here') }}"
            value="{{ $courier_properties['branch_id'] ?? '' }}" class="theme-input-style">

    </div>
    
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
