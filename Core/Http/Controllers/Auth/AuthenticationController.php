<?php

namespace Core\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use Core\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Core\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Core\Mail\EmailPasswordResetLink;
use Core\Models\AdminLoginActivityLog;
use Illuminate\Support\Facades\Session;
use Core\Http\Controllers\Api\DeviceTokenController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class AuthenticationController extends Controller
{
    private const SAVED_LOGIN_COOKIE = 'tl_saved_login';
    private const SAVED_LOGIN_MASK = '••••••••••••';

    public function setGeneralSettings()
    {
        getGeneralSettingNameAsArray('general_settings_name');
        getGeneralSettingNameAsArray('social_media_settings_name');
        getGeneralSettingNameAsArray('media_settings_name');
        getGeneralSettingNameAsArray('blog_comment_general_settings_name');
        getGeneralSettingNameAsArray('blog_comment_other_settings_name');
        saveMenuPositionName('menu_position');
    }

    /**
     * redirect to login page
     *
     * @return mixed
     */
    public function login()
    {
        $rawCookie = Cookie::get('remembered_tenant_url');
        $decoded = $rawCookie ? base64_decode($rawCookie) : null;
        $loggedOut = request()->boolean('logged_out');

        $rememberedTenantUrl = null;
        if ($decoded && str_contains($decoded, 'http')) {
            $rememberedTenantUrl = substr($decoded, strpos($decoded, 'http'));
        }

        Log::info('Home route hit', ['remembered_tenant_url' => $rememberedTenantUrl]);

        if ($rememberedTenantUrl && !$loggedOut && !session()->has('errors')) {
            return redirect()->away($rememberedTenantUrl);
        }

        $savedLogin = $this->getValidSavedLogin();

        Log::info('SAVED LOGIN PAGE CHECK', [
            'host' => request()->getHost(),
            'cookie_present' => request()->hasCookie(self::SAVED_LOGIN_COOKIE),
            'saved_login_valid' => $savedLogin !== null,
            'saved_email' => $savedLogin['email'] ?? null,
        ]);

        $viewData = [
            'loggedOut' => $loggedOut,
            'hasSavedLogin' => $savedLogin !== null,
            'rememberedLoginEmail' => $savedLogin['email'] ?? '',
            'savedPasswordMask' => self::SAVED_LOGIN_MASK,
        ];

        $this->setGeneralSettings();
        if (Auth::user()) {
            return redirect()->route('admin.dashboard');
        }

        if ($loggedOut) {
            return response()->view('core::base.auth.login', $viewData)
                ->withoutCookie('remembered_tenant_url');
        }

        return view('core::base.auth.login', $viewData);
    }


    /**
     * attempt to Login
     *
     * @param  mixed $request
     * @return mixed
     */
    public function attemptLogin(LoginRequest $request)
    {
        if (
            $request->boolean('saved_login') &&
            (string) $request->input('password') === self::SAVED_LOGIN_MASK
        ) {
            $savedLoginResponse = $this->attemptSavedLogin($request);

            if ($savedLoginResponse !== null) {
                return $savedLoginResponse;
            }

            return redirect()->route('core.login')
                ->withInput($request->only('email'))
                ->withErrors(['password' => translate('Saved login expired. Please enter your password.')])
                ->withoutCookie(self::SAVED_LOGIN_COOKIE);
        }

        $credentials = $request->only(
            'email',
            'password',
            'token',
            'remember_me'
        );

        Log::info('Login request received', [
            'email' => $credentials['email'] ?? null,
            'remember_me' => $request->boolean('remember_me'),
            'has_device_token' => !empty($credentials['token']),
        ]);

        $rememberMe = $request->boolean('remember_me');

        // dd($credentials);

        $path = $request->path();

        $preferTenantId = null;
        if (tenancy()->initialized) {
            $preferTenantId = tenant('id');
            tenancy()->end();

            Log::info('Tenant-domain login: resolving store via tl_store_users', [
                'prefer_tenant_id' => $preferTenantId,
                'email' => $credentials['email'],
            ]);

            $storeLoginResponse = $this->attemptStoreUserLogin($credentials, $rememberMe, $preferTenantId);
            if ($storeLoginResponse !== null) {
                return $storeLoginResponse;
            }
        }

        // Central domain - check BOTH central DB and tenant DBs

        // First, try to find user in central database
        $centralUser = User::where('email', $credentials['email'])->first();

        if ($centralUser && \Hash::check($credentials['password'], $centralUser->password)) {
            // User exists in central DB and password is correct

            // dd($centralUser);

            if ($centralUser->user_type == 1) {
                // Superadmin - login normally in central context
                Auth::login($centralUser, $rememberMe);
            } else {
                \Log::info('centralUser: ' . json_encode($centralUser));
                // Not superadmin - check if they own a tenant
                $saasAccount = DB::connection('mysql')->table('tl_saas_accounts')
                    ->where('user_id', $centralUser->id)
                    ->first();

                if ($saasAccount) {
                    $tenant = \App\Models\Tenant::where('id', $saasAccount->tenant_id)
                        ->first();

                    if ($tenant) {
                        // User owns a tenant - initialize and redirect
                        tenancy()->initialize($tenant);

                        // Authenticate in tenant context
                        $tenantUser = User::on('tenant')
                            ->where('email', $centralUser->email)
                            ->first();

                        if ($tenantUser && \Hash::check($credentials['password'], $tenantUser->password)) {
                            // Found the correct tenant!
                            // Create a one-time login token for secure redirect
                            $loginToken = \Str::random(64);

                            // Store token in central database with tenant and user info
                            \DB::connection('mysql')->table('tenant_login_tokens')->insert([
                                'token' => hash('sha256', $loginToken),
                                'tenant_id' => $tenant->id,
                                'email' => $credentials['email'],
                                'remember_me' => $rememberMe ? 1 : 0,
                                'created_at' => now(),
                                'expires_at' => now()->addMinutes(5), // 5 minute expiry
                            ]);

                            tenancy()->end();

                            \Log::info('Before attempting DeviceTokenController!!!');

                            Log::info('Tenant login token created', [
                                'tenant_id' => $tenant->id,
                                'email' => $credentials['email'] ?? null,
                                'remember_me' => $rememberMe,
                            ]);

                            if (!empty($credentials['token'])) {

                                Log::info('Inside of the If condition');

                                $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $tenantUser->id, $tenant->id);
                            }

                            \Log::info('After attempting DeviceTokenController!!!');


                            // Build URL with token
                            // $tenantUrl = $this->buildTenantDashboardUrl($tenant) . '?login_token=' . $loginToken;
                            $tenantUrl = $this->buildTenantLoginUrl($tenant) . '?login_token=' . $loginToken;

                            $response = redirect()->away($tenantUrl);

                            if ($rememberMe) {
                                $tenantDashboardUrl = $this->buildTenantDashboardUrl($tenant);

                                \Log::info('Setting remembered_tenant_url cookie', ['url' => $tenantDashboardUrl]);

                                $response = $response
                                    ->withCookie(\Cookie::make('remembered_tenant_url', base64_encode($tenantDashboardUrl), 60 * 24 * 365))
                                    ->withCookie($this->makeSavedLoginCookie($tenant, $tenantUser));
                            } else {
                                $response = $response->withoutCookie(self::SAVED_LOGIN_COOKIE);
                            }

                            toastNotification('success', translate("Welcome back!"));
                            return $response;
                            //  return redirect()->away($tenantUrl);
                        }

                        tenancy()->end();
                    }
                }

                // A non-superadmin central account without a valid tenant
                // must not be authenticated into the central dashboard.
                // This also handles deprecated accounts whose tenant was
                // removed or deactivated.
                return $this->invalidLoginResponse($request);
            }
        } else {

            Log::info('user != centralUser');

            // User not in central DB — might be a tenant-only user (tl_store_users)
            $storeLoginResponse = $this->attemptStoreUserLogin($credentials, $rememberMe, $preferTenantId);
            if ($storeLoginResponse !== null) {
                return $storeLoginResponse;
            }

            // Not in tl_store_users either — try saas accounts (central DB users)
            $saasAccounts = \DB::connection('mysql')->table('tl_saas_accounts')
                ->join('tl_users', 'tl_saas_accounts.user_id', '=', 'tl_users.id')
                ->where('tl_users.email', $credentials['email'])
                ->select('tl_saas_accounts.*')
                ->get();

            foreach ($saasAccounts as $saasAccount) {
                $tenant = \App\Models\Tenant::where('id', $saasAccount->tenant_id)
                    ->first();

                if ($tenant) {
                    tenancy()->initialize($tenant);

                    $tenantUser = User::on('tenant')
                        ->where('email', $credentials['email'])
                        ->first();

                    if ($tenantUser && \Hash::check($credentials['password'], $tenantUser->password)) {
                        $tenantUser->setConnection('tenant');
                        Auth::login($tenantUser);

                        if (!empty($credentials['token'])) {
                            Log::info('token != null');
                            $deviceTokenResponse = app(DeviceTokenController::class)
                                ->associateWithUser($credentials['token'], $tenantUser->id, $saasAccount->tenant_id);
                        }

                        $tenantUrl = $this->buildTenantDashboardUrl($tenant);
                        toastNotification('success', translate("Welcome back!"));
                        return redirect()->away($tenantUrl);
                    }

                    tenancy()->end();
                }
            }

            // Not found anywhere
            return $this->invalidLoginResponse($request);
        }

        // else {
        //     // User not in central DB or wrong password - might be a tenant-only user
        //     // Check if this email exists in any tenant's database through tl_saas_accounts
        //     $saasAccounts = \DB::connection('mysql')->table('tl_saas_accounts')
        //         ->join('tl_users', 'tl_saas_accounts.user_id', '=', 'tl_users.id')
        //         ->where('tl_users.email', $credentials['email'])
        //         ->select('tl_saas_accounts.*')
        //         ->get();

        //     if(!empty($saasAccounts)) {

        //         $tl_store_user = \DB::connection('mysql')->table('tl_store_users')
        //         ->where('tl_store_users.email', $credentials['email'])
        //         ->select('tl_store_users.*')
        //         ->first();

        //         if(!empty($tl_store_user)) {

        //             $tenant = \App\Models\Tenant::where('id', $tl_store_user->tenant_id)
        //                 ->where('status', 'active')
        //                 ->first();

        //             if ($tenant) {
        //                 tenancy()->initialize($tenant);

        //                 $tenantUser = User::on('tenant')
        //                     ->where('email', $credentials['email'])
        //                     ->first();

        //                 if ($tenantUser && \Hash::check($credentials['password'], $tenantUser->password)) {
        //                     // Found the correct tenant!
        //                     $tenantUser->setConnection('tenant');
        //                     Auth::login($tenantUser);

        //                     if (!empty($credentials['token'])) {

        //                         Log::info('token != null');

        //                         $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $user->id, tenant('id'));

        //                     }

        //                     $tenantUrl = $this->buildTenantDashboardUrl($tenant);
        //                     toastNotification('success', translate("Welcome back!"));
        //                     // return redirect()->away($tenantUrl);
        //                 }

        //                 tenancy()->end();
        //             }
        //         }
        //         else {

        //         }



        //     }
        //     else {

        //         $foundTenant = null;
        //         foreach ($saasAccounts as $saasAccount) {
        //             $tenant = \App\Models\Tenant::where('id', $saasAccount->tenant_id)
        //                 ->where('status', 'active')
        //                 ->first();

        //             if ($tenant) {
        //                 tenancy()->initialize($tenant);

        //                 $tenantUser = User::on('tenant')
        //                     ->where('email', $credentials['email'])
        //                     ->first();

        //                 if ($tenantUser && \Hash::check($credentials['password'], $tenantUser->password)) {
        //                     // Found the correct tenant!
        //                     $tenantUser->setConnection('tenant');
        //                     Auth::login($tenantUser);

        //                     if (!empty($credentials['token'])) {

        //                         Log::info('token != null');

        //                         $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $user->id, tenant('id'));

        //                     }

        //                     $tenantUrl = $this->buildTenantDashboardUrl($tenant);
        //                     toastNotification('success', translate("Welcome back!"));
        //                     return redirect()->away($tenantUrl);
        //                 }

        //                 tenancy()->end();
        //             }
        //         }
        //     }



        //     // Not found anywhere
        //     toastNotification('error', translate("Login Credentials Does not Match"));
        //     return redirect()->back()->withInput($request->only('email'));
        // }

        $user = Auth::user();

        Log::info('User Authentication Check', [
            'user' => $user,
        ]);

        if (!empty($credentials['token']) && $user->user_type != 1) {

            Log::info('Inside of the If condition');

            $tenantId = tenant('id');

            if (empty($tenantId)) {
                Log::warning('associateWithUser fallthrough: tenant_id is missing', [
                    'user_id' => $user->id,
                    'user_type' => $user->user_type,
                ]);
            }

            $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $user->id, $tenantId);
        }

        // Check user permissions based on path
        if (str_starts_with($path, getAdminPrefix())) {
            // Admin area access
            return redirect()->intended('/admin/dashboard');
        }

        if (str_starts_with($path, getSaasPrefix()) && $user->user_type == 4) {
            // SaaS user area access
            return redirect()->intended('/user/dashboard');
        }

        // No valid context matched - logout and deny access
        Auth::logout();
        toastNotification('error', translate("You don't have permission to access this area"));
        return redirect()->back();
    }

    private function invalidLoginResponse(LoginRequest $request)
    {
        toastNotification('error', translate("Login Credentials Does not Match"));

        return redirect()->route('core.login')
            ->withInput($request->only('email'))
            ->withErrors(['password' => translate("Login Credentials Does not Match")])
            ->withoutCookie('remembered_tenant_url');
    }

    /**
     * Resolve store staff login via central tl_store_users and redirect with token-login.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    private function attemptStoreUserLogin(array $credentials, bool $rememberMe, ?string $preferTenantId = null)
    {
        $storeUsers = DB::connection('mysql')->table('tl_store_users')
            ->where('email', $credentials['email'])
            ->get();

        if ($storeUsers->isEmpty()) {
            return null;
        }

        $matched = $storeUsers->filter(function ($row) use ($credentials) {
            return Hash::check($credentials['password'], $row->password);
        });

        if ($matched->isEmpty()) {
            return null;
        }

        $storeUser = $preferTenantId
            ? $matched->firstWhere('tenant_id', $preferTenantId)
            : null;

        if (!$storeUser) {
            $storeUser = $matched->first();
        }

        $tenant = \App\Models\Tenant::where('id', $storeUser->tenant_id)->first();

        if (!$tenant) {
            return null;
        }

        tenancy()->initialize($tenant);

        $tenantUser = User::on('tenant')
            ->where('email', $credentials['email'])
            ->first();

        if (!$tenantUser || !Hash::check($credentials['password'], $tenantUser->password)) {
            tenancy()->end();
            return null;
        }

        $loginToken = Str::random(64);

        tenancy()->end();

        DB::connection('mysql')->table('tenant_login_tokens')->insert([
            'token' => hash('sha256', $loginToken),
            'tenant_id' => $tenant->id,
            'email' => $credentials['email'],
            'remember_me' => $rememberMe ? 1 : 0,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ]);

        if (!empty($credentials['token'])) {
            Log::info('token != null');
            app(DeviceTokenController::class)->associateWithUser(
                $credentials['token'],
                $tenantUser->id,
                $storeUser->tenant_id
            );
        }

        $tenantUrl = $this->buildTenantLoginUrl($tenant) . '?login_token=' . $loginToken;

        Log::info('Store user login redirect', [
            'tenant_id' => $tenant->id,
            'tenant_url' => $tenantUrl,
        ]);

        $response = redirect()->away($tenantUrl);

        if ($rememberMe) {
            $tenantDashboardUrl = $this->buildTenantDashboardUrl($tenant);

            Log::info('Setting remembered_tenant_url cookie', ['url' => $tenantDashboardUrl]);

            $response = $response
                ->withCookie(Cookie::make('remembered_tenant_url', base64_encode($tenantDashboardUrl), 60 * 24 * 365))
                ->withCookie($this->makeSavedLoginCookie($tenant, $tenantUser));
        } else {
            $response = $response->withoutCookie(self::SAVED_LOGIN_COOKIE);
        }

        toastNotification('success', translate("Welcome back!"));

        return $response;
    }

    /**
     * Build the tenant dashboard URL for redirect
     * 
     * @param \App\Models\Tenant $tenant
     * @return string
     */
    protected function buildTenantDashboardUrl($tenant)
    {
        Log::info('buildTenantDashboardUrl method called!!!');

        // Get tenant domain/subdomain
        $domain = $tenant->domains()->first();

        // \Log::info('centralUser: ' . json_encode($centralUser));


        if (!$domain) {
            // Fallback if no domain configured
            throw new \Exception('No domain configured for tenant');
        }

        // Build full URL to dashboard
        $protocol = request()->secure() ? 'https://' : 'http://';
        $tenantUrl = $protocol . $domain->domain . '/admin/dashboard';

        return $tenantUrl;
    }


    protected function buildTenantLoginUrl($tenant)
    {
        // Get tenant domain/subdomain
        $domain = $tenant->domains()->first();

        if (!$domain) {
            throw new \Exception('No domain configured for tenant');
        }

        // Build full URL to token-login endpoint (NOT dashboard)
        $protocol = request()->secure() ? 'https://' : 'http://';

        $port = '';
        if (!app()->environment('production') && request()->getPort() != 80 && request()->getPort() != 443) {
            $port = ':' . request()->getPort();
        }

        $tenantUrl = $protocol . $domain->domain . $port . '/auth/token-login';

        return $tenantUrl;
    }



    /**
     * Attempt logout
     *
     * @return mixed
     */
    public function logout()
    {
        Log::info('logout method called!!!');

        $user = Auth::user();

        // Handle case where user is already logged out
        if (!$user) {
            // return redirect()->route('subscriber.login');
            // return redirect()->route('core.login');
            // return redirect()->away('https://platepilots.com/admin/login');  --Hassaan
            return redirect()->away($this->buildCentralLoginUrl() . '?logged_out=1')
                ->withoutCookie('remembered_tenant_url');
        }

        $user_type = $user->user_type;
        $currentPath = request()->path();

        // Log activity before logout
        $this->setupLoginLogoutActivity(false);

        // Better logging
        \Log::info('User logout', [
            'user_id' => $user->id,
            'user_type' => $user_type,
            'path' => $currentPath
        ]);

        Auth::logout();

        // Invalidate session and regenerate token for security
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        tenancy()->end();

        // redirect()->away(config('app.central_domain') . '/admin/login');
        // return redirect()->away('https://platepilots.com/admin/login');  --Hassaan
        return redirect()->away($this->buildCentralLoginUrl() . '?logged_out=1')
            ->withoutCookie('remembered_tenant_url');

        // $centralDomain = config('tenancy.central_domains')[0];
        // $scheme = request()->isSecure() ? 'https' : 'http';
        // $port = request()->getPort();
        // $adminPrefix = trim(getAdminPrefix(), '/');

        // $portSuffix = (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443))
        //     ? ':' . $port
        //     : '';

        // return redirect()->away($scheme . '://' . $centralDomain . $portSuffix . '/' . $adminPrefix . '/login');

        // return redirect()->away('http://127.0.0.1:8000/admin/login');

        // // Bypass Laravel's response pipeline entirely
        // header('Location: http://127.0.0.1:8000/admin/login', true, 302);
        // exit();

        //  \Log::info('LOGOUT STEP 2 - After auth logout');

        // $response = redirect()->away('http://127.0.0.1:8000/admin/login');

        // \Log::info('LOGOUT STEP 3 - Redirect created', [
        //     'location' => $response->getTargetUrl()
        // ]);

        // return $response;



        // \Log::info('Redirect target', ['url' => 'http://127.0.0.1:8000/admin/login']);
        // return response()->make('', 302, ['Location' => 'http://127.0.0.1:8000/admin/login']);

        // tenancy()->end();

        // return response('<script>window.location.href="http://127.0.0.1:8000/admin/login";</script>');



        // Determine redirect based on user type and context
        // if ($user_type == 4) { // Subscriber/Tenant user
        //     if (str_starts_with($currentPath, getSaasPrefix())) {
        //         // Central subscriber dashboard logout
        //         return redirect()->route('subscriber.login');
        //     }

        //     if (str_starts_with($currentPath, getAdminPrefix())) {
        //         // Store-specific dashboard logout (tenant context)
        //         return redirect()->route('core.login');
        //     }

        //     // Default fallback for subscribers
        //     // return redirect()->route('subscriber.login');

        // } else if ($user_type == 1) { // Super admin
        //     // if (str_starts_with($currentPath, getAdminPrefix())) {
        //     //     return redirect()->route('core.login'); // or 'superadmin.login' if different
        //     // }

        //     if (str_starts_with($currentPath, getAdminPrefix())) {
        //         return redirect()->away('http://127.0.0.1:8000' . getAdminPrefix() . '/login');
        //     }

        //     // return redirect()->route('tenant.login');

        // } else {
        //     // Any other user type
        //     // return redirect()->route('subscriber.login');
        //     // return redirect()->route('core.login');
        // }
    }
///  Hassaan created

    /**
     * Create the central-domain saved-login cookie.
     * The real password is never stored.
     */
private function makeSavedLoginCookie($tenant, User $tenantUser)
{
    /*
    |--------------------------------------------------------------------------
    | Cookie-only saved login
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Laravel's EncryptCookies middleware encrypts response cookies for us and
    | decrypts them before request()->cookie() returns them.
    |
    | Therefore DO NOT call Crypt::encryptString() here for the new format.
    | The browser still receives an encrypted/tamper-protected cookie.
    |
    | The real password is NEVER stored.
    |
    */

    $passwordSignature = hash_hmac(
        'sha256',
        (string) $tenantUser->getAuthPassword(),
        (string) config('app.key')
    );

    $payload = [
        'v' => 2,
        'user_id' => (int) $tenantUser->id,
        'email' => (string) $tenantUser->email,
        'tenant_id' => (string) $tenant->id,
        'password_signature' => $passwordSignature,
        'expires_at' => now()->addDays(30)->timestamp,
    ];

    // Plain JSON at application level. EncryptCookies encrypts it on response.
    $cookieValue = json_encode($payload, JSON_UNESCAPED_SLASHES);

    $host = request()->getHost();

    $isLocal =
        $host === 'localhost'
        || $host === '127.0.0.1'
        || str_ends_with($host, '.localhost');

    $secure = $isLocal
        ? false
        : request()->isSecure();

    Log::info('[saved-login] cookie created', [
        'host' => $host,
        'tenant_id' => (string) $tenant->id,
        'user_id' => (int) $tenantUser->id,
        'email' => (string) $tenantUser->email,
        'secure' => $secure,
        'format' => 'framework-encrypted-v2',
    ]);

    return Cookie::make(
        self::SAVED_LOGIN_COOKIE,
        $cookieValue,
        60 * 24 * 30,
        '/',
        null,
        $secure,
        true,
        false,
        'lax'
    );
}

    /**
     * Decode a saved-login cookie safely.
     *
     * New format:
     *   request()->cookie() already contains decrypted JSON because Laravel's
     *   EncryptCookies middleware decrypted the transport cookie.
     *
     * Transitional legacy format:
     *   The previous patch manually encrypted the JSON before EncryptCookies
     *   encrypted it again. After middleware removes the outer encryption,
     *   request()->cookie() still contains one encrypted payload. We support
     *   that format temporarily so currently logged-in users are not impacted.
     */
private function decodeSavedLoginCookie(string $cookieValue): ?array
{
    /*
    |--------------------------------------------------------------------------
    | IMPORTANT FOR THIS PROJECT
    |--------------------------------------------------------------------------
    |
    | In this Laravel installation request()->cookie('tl_saved_login') is
    | returning the already-decrypted cookie WITH Laravel's CookieValuePrefix:
    |
    |     40-character-HMAC|{JSON}
    |
    | For your current payload:
    |     40 prefix chars + 1 "|" + ~216 JSON chars = 257 chars
    |
    | That exactly matches the 257 length shown in your log.
    |
    | Therefore the prefix must be removed BEFORE json_decode().
    |
    */

    $candidates = array_values(array_unique([
        $cookieValue,
        rawurldecode($cookieValue),
    ]));

    /*
    |--------------------------------------------------------------------------
    | 1. Current project format
    |--------------------------------------------------------------------------
    |
    | Laravel has already decrypted the transport cookie, but the framework
    | prefix is still present.
    |
    */
    foreach ($candidates as $candidate) {
        $value = $candidate;
        $hadPrefix = false;

        if (preg_match('/^[a-f0-9]{40}\|/i', $value) === 1) {
            $value = substr($value, 41);
            $hadPrefix = true;
        }

        $data = json_decode($value, true);

        if (is_array($data)) {
            Log::info('[saved-login] cookie decoded', [
                'format' => $hadPrefix
                    ? 'laravel-prefix-json'
                    : 'json',
            ]);

            return $data;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Legacy compatibility
    |--------------------------------------------------------------------------
    |
    | Keep support for cookies created by the earlier manually-encrypted
    | implementation. This avoids disrupting users who may still have one.
    |
    */
    foreach ($candidates as $candidate) {
        try {
            $decrypted = Crypt::decryptString($candidate);
        } catch (\Throwable $e) {
            continue;
        }

        $decryptedCandidates = array_values(array_unique([
            $decrypted,
            rawurldecode($decrypted),
        ]));

        foreach ($decryptedCandidates as $decryptedCandidate) {
            $value = $decryptedCandidate;

            if (preg_match('/^[a-f0-9]{40}\|/i', $value) === 1) {
                $value = substr($value, 41);
            }

            $data = json_decode($value, true);

            if (is_array($data)) {
                Log::info('[saved-login] cookie decoded', [
                    'format' => 'legacy-encrypted',
                ]);

                return $data;
            }
        }
    }

    /*
     * Do not log the cookie itself because it is an authentication credential.
     */
    Log::warning('[saved-login] unable to decode cookie', [
        'length' => strlen($cookieValue),
        'has_laravel_prefix' => preg_match(
            '/^[a-f0-9]{40}\|/i',
            $cookieValue
        ) === 1,
    ]);

    return null;
}

    /**
     * Validate the saved-login cookie against the current tenant user/password hash.
     */
private function getValidSavedLogin(): ?array
{
    Log::info('[saved-login] checking', [
        'host' => request()->getHost(),
        'cookie_present' => request()->hasCookie(
            self::SAVED_LOGIN_COOKIE
        ),
        'tenancy_initialized' => tenancy()->initialized,
    ]);

    if (tenancy()->initialized) {
        Log::warning(
            '[saved-login] rejected: tenancy already initialized'
        );

        return null;
    }

    $cookieValue = request()->cookie(
        self::SAVED_LOGIN_COOKIE
    );

    if (empty($cookieValue)) {
        Log::info(
            '[saved-login] no remembered account cookie'
        );

        return null;
    }

    try {
        $data = $this->decodeSavedLoginCookie((string) $cookieValue);

        if (!is_array($data)) {
            Log::warning(
                '[saved-login] rejected: invalid cookie payload'
            );

            return null;
        }

        if (
            empty($data['user_id']) ||
            empty($data['email']) ||
            empty($data['tenant_id']) ||
            empty($data['password_signature']) ||
            empty($data['expires_at'])
        ) {
            Log::warning(
                '[saved-login] rejected: incomplete payload'
            );

            return null;
        }

        if ((int) $data['expires_at'] < time()) {
            Log::warning(
                '[saved-login] rejected: cookie expired'
            );

            return null;
        }

        $tenant = \App\Models\Tenant::where(
            'id',
            $data['tenant_id']
        )->first();

        if (!$tenant) {
            Log::warning(
                '[saved-login] rejected: tenant missing',
                [
                    'tenant_id' => $data['tenant_id'],
                ]
            );

            return null;
        }

        tenancy()->initialize($tenant);

        try {
            $tenantUser = User::on('tenant')
                ->where('id', (int) $data['user_id'])
                ->where('email', $data['email'])
                ->first();

            if (!$tenantUser) {
                Log::warning(
                    '[saved-login] rejected: tenant user missing',
                    [
                        'tenant_id' => $data['tenant_id'],
                        'user_id' => $data['user_id'],
                        'email' => $data['email'],
                    ]
                );

                return null;
            }

            $currentPasswordSignature = hash_hmac(
                'sha256',
                (string) $tenantUser->getAuthPassword(),
                (string) config('app.key')
            );

            if (
                !hash_equals(
                    $currentPasswordSignature,
                    (string) $data['password_signature']
                )
            ) {
                Log::warning(
                    '[saved-login] rejected: password changed'
                );

                return null;
            }

            Log::info('[saved-login] VALID', [
                'tenant_id' => (string) $tenant->id,
                'user_id' => (int) $tenantUser->id,
                'email' => (string) $tenantUser->email,
            ]);

            return [
                'tenant' => $tenant,
                'user_id' => (int) $tenantUser->id,
                'email' => (string) $tenantUser->email,
            ];

        } finally {
            tenancy()->end();
        }

    } catch (\Throwable $e) {
        if (tenancy()->initialized) {
            tenancy()->end();
        }

        Log::warning('[saved-login] validation exception', [
            'class' => get_class($e),
            'message' => $e->getMessage(),
        ]);

        return null;
    }
}

    /**
     * One-click login after explicit logout when Remember Me was previously selected.
     */
    private function attemptSavedLogin(LoginRequest $request)
    {
        $savedLogin = $this->getValidSavedLogin();

        if (!$savedLogin) {
            Log::warning(
                '[saved-login] automatic login rejected'
            );

            return null;
        }

        if (
            strcasecmp(
                trim((string) $request->input('email')),
                trim((string) $savedLogin['email'])
            ) !== 0
        ) {
            Log::warning(
                '[saved-login] rejected: email does not match'
            );

            return null;
        }

        $tenant = $savedLogin['tenant'];
        $rememberMe = $request->boolean('remember_me');
        $loginToken = Str::random(64);

        /*
         * Keep the project's EXISTING short-lived tenant handoff exactly as-is.
         * This is not the persistent Remember Me storage.
         */
        DB::connection('mysql')->table('tenant_login_tokens')->insert([
            'token' => hash('sha256', $loginToken),
            'tenant_id' => $tenant->id,
            'email' => $savedLogin['email'],
            'remember_me' => $rememberMe ? 1 : 0,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ]);

        $tenantUrl = $this->buildTenantLoginUrl($tenant) . '?login_token=' . $loginToken;
        $response = redirect()->away($tenantUrl);

        /*
         * If the user intentionally unchecks Remember Me on the saved-login form,
         * allow this login once and remove only the persistent browser cookie.
         */
        if (!$rememberMe) {
            Log::info(
                '[saved-login] remember me unchecked - removing cookie'
            );

            $response = $response->withoutCookie(
                self::SAVED_LOGIN_COOKIE
            );
        }

        toastNotification('success', translate('Welcome back!'));

        return $response;
    }

    /**
     * Build the central-domain login URL.
     *
     * Production uses the canonical application URL. Local development uses
     * the configured local central domain instead of a production APP_URL.
     */
    private function buildCentralLoginUrl(): string
    {
        $centralDomains = config('tenancy.central_domains', []);
        $scheme = request()->isSecure() ? 'https' : 'http';
        $port = request()->getPort();

        if (app()->environment('production')) {
            $centralUrl = (string) config('app.url', '');
            $parsedCentralUrl = parse_url($centralUrl);
            $parsedCentralUrl = is_array($parsedCentralUrl) ? $parsedCentralUrl : [];
            $centralDomain = $parsedCentralUrl['host'] ?? null;

            if ($centralDomain) {
                $scheme = $parsedCentralUrl['scheme'] ?? $scheme;
                $port = $parsedCentralUrl['port'] ?? null;
            } else {
                $centralDomain = $centralDomains[0] ?? 'localhost';
            }
        } else {
            $centralDomain = config('tenancy.local_central_domain', 'localhost');
        }

        $portSuffix = ($port !== null && (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)))
            ? ':' . $port
            : '';

        $adminPrefix = trim(getAdminPrefix(), '/');

        return $scheme . '://' . $centralDomain . $portSuffix . '/' . $adminPrefix . '/login';
    }

    /**
     * redirect to password reset page
     *
     * @return mixed
     */
    public function passwordResetLink()
    {
        if (Auth::user()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('core::base.auth.password_reset_link');
        }
    }

    /**
     * will send password reset link to user email address
     *
     * @param  mixed $request
     * @return mixed
     */
    public function emailResetPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tl_users',
        ]);
        try {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $template = DB::table('tl_email_template_properties')
                ->where('email_type', config('settings.email_template.reset_user_password'))
                ->select([
                    'subject'
                ])->first();

            $data = [
                'template_id' => config('settings.email_template.reset_user_password'),
                'keywords' => getEmailTemplateVariables(config('settings.email_template.reset_user_password'), true),
                'subject' => $template->subject,
                '_reset_password_link_' => route('core.reset.password', $token)
            ];

            Mail::to($request->email)->send(new EmailPasswordResetLink($data));
            toastNotification('success', translate('We have e-mailed your password reset link'));
            return back();
        } catch (Exception $ex) {
            Toastr::error(translate('Unable to send email !'));
            return back();
        }
    }

    /**
     * reset password
     *
     * @param  mixed $token
     * @return mixed
     */
    public function resetPassword($token)
    {
        return view('core::base.auth.reset_password', ['token' => $token]);
    }

    /**
     * reset password
     *
     * @param  mixed $request
     * @return mixed
     */
    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tl_users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token
                ])->first();

            if (!$updatePassword) {
                toastNotification('error', 'Invalid token');
                return back();
            }

            $user_details = DB::table('tl_users')->where('email', '=', $request['email'])->first();
            $user = User::find($user_details->id);
            $user->password = Hash::make($request['password']);
            $user->update();

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            toastNotification('success', translate('Your password has been reset'));
            if ($user->user_type == 4) {
                return redirect()->route('subscriber.login');
            } else {
                return redirect()->route('core.login');
            }
        } catch (\Exception $th) {
            toastNotification('error', translate('Unable to reset password'));
            return back();
        }
    }

    /**
     * setup login logout activity
     *
     * @param  mixed $is_for_login
     * @return mixed
     */
    public function setupLoginLogoutActivity($is_for_login)
    {
        if ($is_for_login) {
            $user_ip_address = getUserIpAddr();
            $os = get_operating_system();
            $browser = get_browser_name();
            $user_id = Auth::user()->id;
            $user_name = Auth::user()->name;

            $login_activity = new AdminLoginActivityLog();
            $login_activity->user_id = $user_id;
            $login_activity->login_at = Carbon::now()->toDateTimeString();
            $login_activity->os = $os;
            $login_activity->browser = $browser;
            $login_activity->ip = $user_ip_address;
            $login_activity->saveOrFail();

            Session::put($user_name, $login_activity->id);
        } else {
            $user_name = Auth::user()->name;
            $login_activity_id = Session::get($user_name);
            $login_activity = AdminLoginActivityLog::find($login_activity_id);
            if ($login_activity != null) {
                $login_activity->logout_at = Carbon::now()->toDateTimeString();
                $login_activity->update();
            }
        }
    }
}
