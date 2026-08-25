@php
    $idPrefix = $idPrefix ?? '';
    $emailId = $idPrefix . 'email';
    $passwordId = $idPrefix . 'password';
    $togglePasswordId = $idPrefix . 'togglePassword';
    $rememberMeId = $idPrefix . 'remember_me';
    $tokenId = $idPrefix . 'token';
    $demoLoginBtnId = $idPrefix . 'demoLoginBtn';
    $submitBtnId = $idPrefix . 'submitBtn';
    $host = request()->getHost();
    $centralDomains = config('tenancy.central_domains', ['localhost']);
    $isCentralDomain = in_array($host, $centralDomains);
@endphp
<div class="card bg-white p-3 py-4 login-form-card" style="border-radius: 12px !important; overflow: hidden !important;">
    <div class="text-center pt-3">
        <div class="mt-3">
            <h3>
                Welcome to <span style="color: #FF5A1F;">Platepilot</span>
            </h3>
        </div>
        <h5 class="mt-3">{{ translate('Just drop in your login info to kick off your adventure!') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('core.attemptLogin') }}" method="post" class="login-form">
            @csrf
            <div class="form-group mb-20">
                <label for="{{ $emailId }}" class="mb-2 font-14 black">{{ translate('Email') }}</label>

                <div class="input-with-icon">
                    <x-lucide-mail class="input-icon" />
                    <input type="email" id="{{ $emailId }}" name="email" class="theme-input-style"
                        placeholder="{{ translate('Email Address') }}" value="{{ old('email') }}">
                </div>

                @if ($errors->has('email'))
                    <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group mb-10">
                <label for="{{ $passwordId }}" class="mb-2 font-14 black">{{ translate('Password') }}</label>

                <div class="input-with-icon">
                    <x-lucide-lock class="input-icon" />

                    <input type="password" id="{{ $passwordId }}" name="password" class="theme-input-style"
                        placeholder="{{ translate('Password') }}">

                    <button type="button" id="{{ $togglePasswordId }}" class="password-toggle-btn" tabindex="-1"
                        aria-label="Toggle password visibility">
                        <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg class="eye-off-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                            <line x1="2" y1="2" x2="22" y2="22" />
                        </svg>
                    </button>
                </div>

                @if ($errors->has('password'))
                    <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center mb-20">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="{{ $rememberMeId }}"
                        value="1">
                    <label class="form-check-label" for="{{ $rememberMeId }}">
                        {{ translate('Remember Me') }}
                    </label>
                </div>

                <a href="{{ route('core.password.reset.link') }}"
                    style="color: #FF5A1F; font-size: 0.9rem;">{{ translate('Forgot Password?') }}</a>
            </div>

            <input type="hidden" name="token" id="{{ $tokenId }}">

            <div class="d-flex align-items-center" style="margin-bottom: 0px !important;">
                <button type="submit" id="{{ $submitBtnId }}" class="btn btn-block btn-orange login-submit-btn">
                    <span class="login-btn-label">{{ translate('Log In') }}</span>
                    <span class="login-btn-spinner" aria-hidden="true"></span>
                </button>
            </div>

            <!-- @if ($isCentralDomain)
            <div class="d-flex align-items-center mt-3">
                <button type="button" id="{{ $demoLoginBtnId }}" class="btn btn-block btn-demo-outline"
                    data-email="support.platepilot@gmail.com" data-password="demo123">
                    {{ translate('Try demo dashboard') }}
                </button>
            </div>
            @endif -->

            <div class="text-center" style="margin-top: 20px;">
                <h5 class="mt-3">{{ translate('If you are having trouble, please contact') }}</h5>
                <h5 class="mt-3" style="color: #FF5A1F !important; margin-top: 10px;">
                    {{ translate('support@platepilots.com') }}</h5>
            </div>
        </form>
    </div>
</div>
