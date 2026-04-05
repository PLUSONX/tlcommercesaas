@php
$system_name = getGeneralSetting('system_name');
$desktop_logo = !empty(getGeneralSetting('admin_logo')) ? getFilePath(getGeneralSetting('admin_logo')) : '';
$login_bg_image = !empty(getGeneralSetting('login_bg_image')) ? getFilePath(getGeneralSetting('login_bg_image')) : '';

$login_bg_image = str_replace('/public', '', $login_bg_image);

$host = request()->getHost();
$centralDomains = config('tenancy.central_domains', ['localhost']);
$isCentralDomain = in_array($host, $centralDomains);



@endphp
@extends('core::base.auth.auth_layout')
@section('title')
{{ translate('Login') }}
@endsection
@section('custom_css')
<style>
    body {
        background-image: url('/themes/default/1920.png');
        background-size: 100% 100%;
        background-attachment: fixed;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .login-page-layout {
        padding-right: 5% !important;
    }

    .card {
        width: 450px !important; /* Forces the width to exactly 400px */
        height: 500px !important;
        flex: none !important;    /* Prevents Flexbox from shrinking/growing it */
    }
   
    /* @media (min-width: 1025px) {
        .login-page-layout {
            padding-right: 80px !important;
        }
    } */

    @media (max-width: 1200px) {
        body {
            background-image: url('/themes/default/bg.png');
            background-size: cover;
            background-attachment: scroll;
        }

        .login-page-layout {
        padding-right: 0px !important;
    }

        .card {
            width: 400px !important;
            
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .row {
            justify-content: center !important;
        }

        .col-xl-3 {
            margin-right: 0 !important; /* neutralizes me-5 on mobile */
        }
    }

    @media (max-width: 900px) {

        .login-page-layout {
        padding-right: 10px !important;
    }

        .card {
            width: 350px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }

    h5.mt-3 {
        font-weight: 400 !important;      /* Removes the Bold (standard weight) */
        text-transform: none !important;   /* Stops the automatic Uppercasing */
        color: #6c757d !important;         /* Makes the text a subtle grey */
        font-size: 0.9rem;                 /* Optional: makes it slightly smaller */
    }

    h3 {
        text-transform: none !important;   /* Stops the automatic Uppercasing */
    }
    
    .input-with-icon {
    position: relative;
    width: 100%;
    background: white;
    
}

.input-icon {
    position: absolute;
    left: 12px;           /* Distance from left border */
    top: 50%;             /* Move to middle */
    transform: translateY(-50%); /* Perfectly center vertically */
    width: 18px;
    height: 18px;
    color: #6c757d;       /* Muted icon color */
    pointer-events: none; /* Allows clicking "through" the icon to the input */
    z-index: 2;
    
}

.theme-input-style {
    padding-left: 40px !important; /* Push text to the right so it doesn't hit the icon */
    width: 100%;
    background: white;
    border: 1px solid black;
}

.theme-input-style:focus, 
.theme-input-style:active,
.theme-input-style:hover {
    background-color: white !important;
    background: white !important; /* Extra insurance */
    outline: none;                /* Optional: removes default browser glow */
    border: 1px solid black !important; /* Keeps your border consistent */
}

</style>
@endsection
@section('main_content')
<!-- <div class="container-fluid login-page-layout position-relative">
    <div class="align-items-center h-100 justify-content-center row py-5">
        <div class="col-xl-3 col-lg-4  col-12 mx-auto">
            <div class="card bg-white p-3 py-4" style="border-radius: 12px !important; overflow: hidden !important;"> -->
<div class="container-fluid login-page-layout position-relative">
    <div class="align-items-center h-100 justify-content-end row py-5">
        <!-- <div class="col-xl-3 col-lg-5 col-12"> -->
        <div class="col-auto">
            <div class="card bg-white p-3 py-4" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="text-center pt-3">
                    <div class="mt-3">
                        
                             <h3>
                                Welcome to <span style="color: #FF5A1F;">Platepilot</span>
                            </h3>
                    </div>
                    <!-- <h4 class="mt-3">{{ translate('Welcome Back') }}</h4> -->
                    <h5 class="mt-3">{{ translate('Just drop in your login info to kick off your adventure!') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.attemptLogin') }}" method="post">
                        @csrf
                        <!-- Form Group -->
                        <!-- <div class="form-group mb-20">
                            <label for="email" class="mb-2 font-14 black">{{ translate('Email') }}</label>
                            <x-lucide-mail style="width: 20px; height: 20px; margin-left: 2px;" />
                            <input type="email" id="email" name="email" class="theme-input-style" placeholder="{{ translate('Email Address') }}" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                            @endif
                        </div> -->
                        <div class="form-group mb-20">
                            <label for="email" class="mb-2 font-14 black">{{ translate('Email') }}</label>
                            
                            <div class="input-with-icon">
                                <x-lucide-mail class="input-icon" />
                                <input type="email" id="email" name="email" class="theme-input-style" placeholder="{{ translate('Email Address') }}" value="{{ old('email') }}">
                            </div>

                            @if ($errors->has('email'))
                                <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <!-- <div class="form-group mb-20">
                            <label for="password" class="mb-2 font-14 black">{{ translate('Password') }}</label>
                            <input type="password" id="password" name="password" class="theme-input-style" placeholder="{{ translate('********') }}">
                            @if ($errors->has('password'))
                            <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                            @endif
                        </div> -->
                        <div class="form-group mb-10">
                            <label for="password" class="mb-2 font-14 black">{{ translate('Password') }}</label>
                            
                            <div class="input-with-icon">
                                <x-lucide-lock class="input-icon" />
                                
                                <input type="password" 
                                    id="password" 
                                    name="password" 
                                    class="theme-input-style" 
                                    placeholder="{{ translate('Password') }}">
                            </div>

                            @if ($errors->has('password'))
                                <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <!-- End Form Group -->

                        <div class="d-flex justify-content-end mb-20">
                            <a href="{{ route('core.password.reset.link') }}" style="color: #FF5A1F; font-size: 0.9rem;" >{{ translate('Forgot Password?') }}</a>
                        </div>

                        <input type="hidden" name="token" id="token">


                        <div class="d-flex align-items-center" style="margin-bottom: 0px !important;">
                            <button type="submit" class="btn btn-block btn-orange">{{ translate('Log In') }}</button>
                        </div>

                        <div class="text-center" style="margin-top: 20px;">
                        
                            <h5 class="mt-3" >{{ translate('If you are having trouble, please contact') }}</h5>
                            
                            <h5 class="mt-3" style="color: #FF5A1F !important; margin-top: 10px;">{{ translate('support@platepilots.com') }}</h5>
                        
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // const demoToken = localStorage.setItem('token': 'f-z18W1YQ7qAtj7LORijw5:APA91bG0FQQS3GhU3bWqvl6bGOe2OGIhlt7ea2f0owcYj2EndlM7fnjCTwUDzgQ_7ePd3CLqhzP3B3uAlSVSFwKBGnX7wQELAgsU8CesRWcFQCbn6Sg4OyM');

        const savedToken = localStorage.getItem('token');
        
        if (savedToken) {
            document.getElementById('token').value = savedToken;
        }
    });
</script>



<style>

button.btn-orange,
a.btn-orange {
    background: #ff5A1f !important;
    border-color: #e64a10 !important;
    color: #fff !important;
    transition: background 0.2s ease;
    box-shadow: none !important;
    border-radius: 6px !important;
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
    background: #ff5A1f !important;
    border-color: #e07b00 !important;
    box-shadow: none !important;
    outline: none !important;
}

.text_color:hover {
    color: #fff !important;
    text-decoration: underline; /* Optional: adds a line on hover for better UX */
}

</style>