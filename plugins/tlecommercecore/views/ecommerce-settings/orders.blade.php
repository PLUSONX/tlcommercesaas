<div class="card">
    <div class="card-body">
        <div class="form-row mb-20">
            <label class="font-14 black col-sm-4">{{ translate('Order code prefix') }}
            </label>
            <div class="col-md-12">
                <input type="text" name="order_code_prefix" value="{{ getEcommerceSetting('order_code_prefix') }}"
                    class="theme-input-style" placeholder="{{ translate('Enter prefix') }}">
            </div>
        </div>
        <div class="form-row mb-20">
            <label class="font-14 black col-sm-4">{{ translate('Order code prefix seperator') }}
            </label>
            <div class="col-md-12">
                <input type="text" name="order_code_prefix_seperator"
                    value="{{ getEcommerceSetting('order_code_prefix_seperator') }}" class="theme-input-style"
                    placeholder="{{ translate('Enter prefix seperator') }}">
            </div>
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-4">
                <label class="font-14 black">{{ translate('Can cancel order within') }}
                </label>
            </div>
            <div class="col-md-12">
                <div class="input-group addon" style="gap: 5px !important;">
                    <input type="text" name="cancel_order_time_limit"
                        value="{{ getEcommerceSetting('cancel_order_time_limit') }}" placeholder="0"
                        class="theme-input-style" style="width: 75% !important;">
                    <div class="input-group-append" style="width: 24% !important;">
                        <select class="theme-input-style" name="cancel_order_time_limit_unit" >
                            <option value="{{ config('tlecommercecore.time_unit.Days') }}"
                                @if (getEcommerceSetting('cancel_order_time_limit_unit') == config('tlecommercecore.time_unit.Days')) selected @endif>
                                {{ translate('Days') }}</option>
                            <option value="{{ config('tlecommercecore.time_unit.Hours') }}"
                                @if (getEcommerceSetting('cancel_order_time_limit_unit') == config('tlecommercecore.time_unit.Hours')) selected @endif>
                                {{ translate('Hours') }}</option>
                            <option value="{{ config('tlecommercecore.time_unit.Minutes') }}"
                                @if (getEcommerceSetting('cancel_order_time_limit_unit') == config('tlecommercecore.time_unit.Minutes')) selected @endif>
                                {{ translate('Minutes') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-4">
                <label class="font-14 black">{{ translate('Can return order within') }}
                </label>
            </div>
            <div class="col-md-12">
                <div class="input-group addon" style="gap: 5px !important;">
                    <input type="text" name="return_order_time_limit"
                        value="{{ getEcommerceSetting('return_order_time_limit') }}" placeholder="0"
                        class="theme-input-style" style="width: 75% !important;">
                    <div class="input-group-append" style="width: 24% !important;">
                        <select class="theme-input-style" name="return_order_time_limit_unit">
                            <option value="{{ config('tlecommercecore.time_unit.Days') }}"
                                @if (getEcommerceSetting('return_order_time_limit_unit') == config('tlecommercecore.time_unit.Days')) selected @endif>
                                {{ translate('Days') }}</option>
                            <option value="{{ config('tlecommercecore.time_unit.Hours') }}"
                                @if (getEcommerceSetting('return_order_time_limit_unit') == config('tlecommercecore.time_unit.Hours')) selected @endif>
                                {{ translate('Hours') }}</option>
                            <option value="{{ config('tlecommercecore.time_unit.Minutes') }}"
                                @if (getEcommerceSetting('return_order_time_limit_unit') == config('tlecommercecore.time_unit.Minutes')) selected @endif>
                                {{ translate('Minutes') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>

    .theme-input-style {
        padding-left: 10px !important; 
        padding-right: 10px !important; 
        width: 100%;
        background: white;
        border: 1px solid black !important;
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
