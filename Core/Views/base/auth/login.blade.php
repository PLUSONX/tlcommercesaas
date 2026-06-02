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
        min-height: 500px !important;
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

    .input-with-icon {
        position: relative;
    }

    .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: inherit;
        display: flex;
        align-items: center;
    }

    .password-toggle-btn svg {
        width: 18px;
        height: 18px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .password-toggle-btn:hover svg {
        opacity: 1;
    }

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
        background: #ff5A1F !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    button.btn-demo-outline {
        background: #fff !important;
        border: 1px solid #ff5A1f !important;
        color: #ff5A1f !important;
        transition: background 0.2s ease, color 0.2s ease;
        box-shadow: none !important;
        border-radius: 6px !important;
    }

    button.btn-demo-outline:hover {
        background: #fff5f0 !important;
        color: #e64a10 !important;
        border-color: #e64a10 !important;
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
                        <!-- <div class="form-group mb-10">
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

                                <button type="button"
                                    id="togglePassword"
                                    class="password-toggle-btn"
                                    tabindex="-1"
                                    aria-label="Toggle password visibility">
                                    {{-- Eye icon (shown by default) --}}
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    {{-- Eye-off icon (hidden by default) --}}
                                    <svg class="eye-off-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                        <line x1="2" y1="2" x2="22" y2="22"/>
                                    </svg>
                                </button>
                            </div>

                            @if ($errors->has('password'))
                                <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <!-- End Form Group -->

                        <!-- <div class="d-flex justify-content-end mb-20"> -->

                        <div class="d-flex justify-content-between align-items-center mb-20">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" value="1">
                                <label class="form-check-label" for="remember_me" style="font-size: 0.9rem;">
                                    {{ translate('Remember Me') }}
                                </label>
                            </div>

                            <a href="{{ route('core.password.reset.link') }}" style="color: #FF5A1F; font-size: 0.9rem;" >{{ translate('Forgot Password?') }}</a>
                        </div>

                        <input type="hidden" name="token" id="token">


                        <div class="d-flex align-items-center" style="margin-bottom: 0px !important;">
                            <button type="submit" class="btn btn-block btn-orange">{{ translate('Log In') }}</button>
                        </div>

                        <!-- @if ($isCentralDomain)
                        <div class="d-flex align-items-center mt-3">
                            <button type="button"
                                id="demoLoginBtn"
                                class="btn btn-block btn-demo-outline"
                                data-email="support.platepilot@gmail.com"
                                data-password="demo123">
                                {{ translate('Try demo dashboard') }}
                            </button>
                        </div>
                        @endif -->

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

@section('custom_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const savedToken = localStorage.getItem('token');
        const tokenInput = document.getElementById('token');
        if (savedToken && tokenInput) {
            tokenInput.value = savedToken;
        }

        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const input = document.getElementById('password');
                const eyeIcon = this.querySelector('.eye-icon');
                const eyeOffIcon = this.querySelector('.eye-off-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'inline';
                } else {
                    input.type = 'password';
                    eyeIcon.style.display = 'inline';
                    eyeOffIcon.style.display = 'none';
                }
            });
        }

        const demoLoginBtn = document.getElementById('demoLoginBtn');
        if (demoLoginBtn) {
            demoLoginBtn.addEventListener('click', function () {
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('password').value = this.dataset.password;
            });
        }
    });
</script>
@endsection