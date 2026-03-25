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

class AuthenticationController extends Controller
{
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
        // dd("LOGIN CONTROLLER WORKS");
        // return 'LOGIN CONTROLLER WORKS';
        $this->setGeneralSettings();
        if (Auth::user()) {
            return redirect()->route('admin.dashboard');
        } else {
            // return view('core.login');
            return view('core::base.auth.login');
        }
    }


    /**
     * attempt to Login
     *
     * @param  mixed $request
     * @return mixed
     */
     public function attemptLogin(LoginRequest $request)
    {
        $credentials = $request->only(
            'email', 
            'password',
            'token'
            );

        Log::info('Credentials Check 1', [
                'credentials 1' => json_encode($credentials),
        ]);

        // dd($credentials);

        $path = $request->path();

        // Check if we're in tenant context and authenticate accordingly
        if (tenancy()->initialized) {
            // Tenant domain - authenticate against tenant database
            $user = User::on('tenant')
                ->where('email', $credentials['email'])
                ->first();

            if ($user && \Hash::check($credentials['password'], $user->password)) {
                $user->setConnection('tenant');
                Auth::login($user);
            } else {
                toastNotification('error', translate("Login Credentials Does not Match"));
                return redirect()->back()->withInput($request->only('email'));
            }
        } else {
            // Central domain - check BOTH central DB and tenant DBs

            // First, try to find user in central database
            $centralUser = User::where('email', $credentials['email'])->first();

            if ($centralUser && \Hash::check($credentials['password'], $centralUser->password)) {
                // User exists in central DB and password is correct

                // dd($centralUser);

                if ($centralUser->user_type == 1) {
                    // Superadmin - login normally in central context
                    Auth::login($centralUser);
                } else {
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
                                    'created_at' => now(),
                                    'expires_at' => now()->addMinutes(5), // 5 minute expiry
                                ]);

                                tenancy()->end();

                                \Log::info('Before attempting DeviceTokenController!!!');

                                Log::info('Credentials Check 2', [
                                    'credentials 2' => json_encode($credentials),
                                ]);

                                if (!empty($credentials['token'])) {

                                    Log::info('Inside of the If condition');

                                    $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $tenantUser->id, tenant('id'));

                                }

                                \Log::info('After attempting DeviceTokenController!!!');


                                // Build URL with token
                                // $tenantUrl = $this->buildTenantDashboardUrl($tenant) . '?login_token=' . $loginToken;
                                $tenantUrl = $this->buildTenantLoginUrl($tenant) . '?login_token=' . $loginToken;

                                 // Build URL to the token-login endpoint (not dashboard)
                                 
                                 toastNotification('success', translate("Welcome back!"));
                                 return redirect()->away($tenantUrl);
                            }

                            tenancy()->end();
                        }
                    }

                    // // User exists in central but no tenant - login normally (might be SaaS user)
                    Auth::login($centralUser);
                }
            } else {
                // User not in central DB or wrong password - might be a tenant-only user
                // Check if this email exists in any tenant's database through tl_saas_accounts
                $saasAccounts = \DB::connection('mysql')->table('tl_saas_accounts')
                    ->join('tl_users', 'tl_saas_accounts.user_id', '=', 'tl_users.id')
                    ->where('tl_users.email', $credentials['email'])
                    ->select('tl_saas_accounts.*')
                    ->get();

                $foundTenant = null;
                foreach ($saasAccounts as $saasAccount) {
                    $tenant = \App\Models\Tenant::where('id', $saasAccount->tenant_id)
                        ->where('status', 'active')
                        ->first();

                    if ($tenant) {
                        tenancy()->initialize($tenant);

                        $tenantUser = User::on('tenant')
                            ->where('email', $credentials['email'])
                            ->first();

                        if ($tenantUser && \Hash::check($credentials['password'], $tenantUser->password)) {
                            // Found the correct tenant!
                            $tenantUser->setConnection('tenant');
                            Auth::login($tenantUser);

                            $tenantUrl = $this->buildTenantDashboardUrl($tenant);
                            toastNotification('success', translate("Welcome back!"));
                            // return redirect()->away($tenantUrl);
                        }

                        tenancy()->end();
                    }
                }

                // Not found anywhere
                toastNotification('error', translate("Login Credentials Does not Match"));
                return redirect()->back()->withInput($request->only('email'));
            }
        }

        $user = Auth::user();

        Log::info('User Authentication Check', [
            'user' => $user,
        ]);

        if (!empty($credentials['token']) && $user->user_type != 1) {

            Log::info('Inside of the If condition');

            $deviceTokenResponse = app(DeviceTokenController::class)->associateWithUser($credentials['token'], $user->id, tenant('id'));

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
   

    /**
     * Build the tenant dashboard URL for redirect
     * 
     * @param \App\Models\Tenant $tenant
     * @return string
     */
    protected function buildTenantDashboardUrl($tenant)
    {
        // Get tenant domain/subdomain
        $domain = $tenant->domains()->first();

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
        $user = Auth::user();

        // Handle case where user is already logged out
        if (!$user) {
            // return redirect()->route('subscriber.login');
            // return redirect()->route('core.login');
        return redirect()->away('https://platepilots.com/admin/login');

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
        return redirect()->away('https://platepilots.com/admin/login');

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
