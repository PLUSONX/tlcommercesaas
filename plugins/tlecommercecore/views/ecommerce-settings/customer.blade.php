<div class="card">
    <div class="card-body">
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="switch glow primary medium">
                    <input type="checkbox" name="customer_auto_approved" @checked(getEcommerceSetting('customer_auto_approved') == config('settings.general_status.active'))>
                    <span class="control"></span>
                </label>
                <label class="font-14 black">{{ translate('Customer auto approval') }}
                </label>
            </div>
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="switch glow primary medium">
                    <input type="checkbox" name="customer_email_varification" @checked(getEcommerceSetting('customer_email_varification') == config('settings.general_status.active'))>
                    <span class="control"></span>
                </label>
                <label class="font-14 black">{{ translate('Customer email verification') }}
                </label>
            </div>
                
            <div class="col-md-12">
                <p class="mt-2 font-13">
                    {{ translate('You need to complete email configuration and Cron Job setup') }}
                    <!-- <br> -->
                    <a href="{{ route('core.email.smtp.configuration') }}"
                        class="btn-link">{{ translate('Configure Email') }}
                    </a>
                </p>
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