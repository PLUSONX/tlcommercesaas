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
    $credentials = $request->only('email', 'password');
    $path = $request->path();

    // Log the attempt
    \Log::info('Login attempt', ['email' => $credentials['email'], 'path' => $path]);

    // Check if we're in tenant context and authenticate accordingly
    if (tenancy()->initialized) {
        // Tenant domain - authenticate against tenant database
        $user = \Core\Models\User::on('tenant')
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
        // Central domain - use default authentication
        if (!Auth::attempt($credentials)) {
            toastNotification('error', translate("Login Credentials Does not Match"));
            return redirect()->back()->withInput($request->only('email'));
        }
    }

    $user = Auth::user();

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
//     public function attemptLogin(LoginRequest $request)
// {
//     $credentials = $request->only('email', 'password');
//     $path = request()->path();

//     // Better debugging
//     \Log::info('Login attempt', ['email' => $credentials['email'], 'path' => $path]);

//     if (!Auth::attempt($credentials)) {
//         toastNotification('error', translate("Login Credentials Does not Match"));
//         return redirect()->back()->withInput($request->only('email'));
//     }

    

//     $user = Auth::user();

//     if (tenancy()->initialized) {
//         $user->setConnection('tenant');
//      }
     
//       if (str_starts_with($path, getAdminPrefix())) {
//         return redirect()->intended('/admin/dashboard');
//     }
    
//     if (str_starts_with($path, getSaasPrefix()) && $user->user_type == 4) {
//         return redirect()->intended('/user/dashboard');
//     }

//     // No valid context matched
//     Auth::logout();
//     toastNotification('error', translate("You don't have permission to access this area"));
//     return redirect()->back();

//     // Tenant context
//     // if (tenant()) {
//     //     if (str_starts_with($path, getAdminPrefix()) && in_array($user->user_type, [1, 4])) {
//     //         return redirect()->route('tenant.dashboard');
//     //     }
        
//     //     Auth::logout();
//     //     toastNotification('error', translate("You don't have permission to access this area"));
//     //     return redirect()->back();
//     // }

//     // Super admin context
  
// }
//     public function attemptLogin(LoginRequest $request)
// {
//     $credentials = $request->only('email', 'password');
//     $path = request()->path();

//     // Better debugging
//     \Log::info('Login attempt', ['email' => $credentials['email'], 'path' => $path]);

//     if (!Auth::attempt($credentials)) {
//         toastNotification('error', translate("Login Credentials Does not Match"));
//         return redirect()->back()->withInput($request->only('email'));
//     }

//     $user = Auth::user();

//     // Tenant context
//     if (tenant()) {
//         if (str_starts_with($path, getAdminPrefix()) && in_array($user->user_type, [1, 4])) {
//             return redirect()->route('tenant.dashboard');
//         }
        
//         Auth::logout();
//         toastNotification('error', translate("You don't have permission to access this area"));
//         return redirect()->back();
//     }

//     // Super admin context
//     if (str_starts_with($path, getSuperAdminPrefix()) && $user->user_type == 1) {
//         return redirect()->intended('/superadmin/dashboard');
//     }
    
//     if (str_starts_with($path, getSaasPrefix()) && $user->user_type == 4) {
//         return redirect()->intended('/user/dashboard');
//     }

//     // No valid context matched
//     Auth::logout();
//     toastNotification('error', translate("You don't have permission to access this area"));
//     return redirect()->back();
// }
//     public function attemptLogin(LoginRequest $request)
// {
//     $credentials = $request->only('email', 'password');
//     // $host = request()->getHost();
//     $path = request()->path();

//     \Log::info('Login attempt', ['email' => $credentials['email'], 'path' => $path]);

//     if(Auth::attempt($credentials)) {
//         $user = Auth::user();

//         if(tenant()) {
//             if((str_starts_with($path, getAdminPrefix()) && ($user->user_type == 1 || $user->user_type == 4 ))) {
//                 return redirect()->intended('/admin/dashboard');
//             } 
//             else {
//                 $this->logout();
//             }
//         }
//         else {
//             if (str_starts_with($path, getSuperAdminPrefix()) && $user->user_type == 1) {
//                 return redirect()->intended('/superadmin/dashboard');
//             }
//             else if(str_starts_with($path, getSaasPrefix()) && $user->user_type == 4) {
//                 return redirect()->intended('/user/dashboard');
//             }
//             else {
//                 $this->logout();
//             }
//         }
        
//     }  
//     else {
//         toastNotification('error', translate("Login Credentials Does not Match"));
//         return redirect()->back();
//     } 

//     // if (Auth::attempt($credentials)) {
//     //     $this->setupLoginLogoutActivity(true);
//     //     toastNotification('success', translate('Login successful'));

//     //     $user = Auth::user();
//     //     $path = $request->path(); // get the URL path, e.g., "superadmin/login" or "admin/login"

//     //     echo "<script>console.log('Full URL:', '" . request()->fullUrl() . "');</script>";
//     //     echo "<script>console.log('Path:', '" . $path . "');</script>";
//     //     echo "<script>console.log('Request path:', '" . request()->path() . "');</script>";
//     //     echo "<script>console.log('getAdminPrefix():', '" . getAdminPrefix() . "');</script>";
//     //     echo "<script>console.log('user_type:', " . $user->user_type . ");</script>";
//     //     echo "<script>console.log('tenant:', " . json_encode(tenant()) . ");</script>";

//     //     // Decide redirect based on URL prefix and user type
//     //     if (str_starts_with($path, getSuperAdminPrefix()) && $user->user_type == 1) {
//     //         return redirect()->intended('/superadmin/dashboard');
//     //     }

//     //     if (str_starts_with($path, getSaasPrefix()) && $user->user_type == 4) {
//     //         // Central subscriber dashboard
//     //         return redirect()->intended('/user/dashboard');
//     //     }

//     //     if (str_starts_with($path, getAdminPrefix()) && $user->user_type == 4) {
//     //         // Store-specific dashboard
//     //         echo "<script>console.log('condition worked!');</script>";
            
//     //          return redirect()->route('tenant.dashboard'); 
//     //         // return redirect()->intended('/admin/dashboard');


//     //     }

//     // //     // fallback (if URL or user type is inconsistent)
//     // //     // return redirect()->intended('/');
//     // }

//     // toastNotification('error', translate("Login Credentials Does not Match"));
//     // return redirect()->back();
// }

    // public function attemptLogin(LoginRequest $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $this->setupLoginLogoutActivity(true);
    //         toastNotification('success', translate('Login successful'));
    //         // if (Auth::user()->status == config('settings.user_status.in_active')) {
    //         //     $this->logout();
    //         // }
    //         return redirect()->route('admin.dashboard');
    //     }
    //     toastNotification('error', translate("Login Credentials Does not Match"));
    //     return redirect()->back();
    // }


//     public function attemptLogin(LoginRequest $request)
// {
//     $credentials = $request->only('email', 'password');
    
//     \Log::info('Login attempt', ['email' => $credentials['email']]);
    
//     if (Auth::attempt($credentials)) {
//         \Log::info('Auth successful', [
//             'user_id' => Auth::user()->id,
//             'user_type' => Auth::user()->user_type,
//             'status' => Auth::user()->status,
//             'is_authenticated' => Auth::check(),
//         ]);
        
//         $this->setupLoginLogoutActivity(true);
//         toastNotification('success', translate('Login successful'));
        
//         if (Auth::user()->status == config('settings.user_status.in_active')) {
//             \Log::info('User inactive, logging out');
//             $this->logout();
//         }
        
//         \Log::info('Redirecting to admin.dashboard');
//         return redirect()->route('admin.dashboard');
//     }
    
//     \Log::info('Auth failed');
//     toastNotification('error', translate("Login Credentials Does not Match"));
//     return redirect()->back();
// }

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
        return redirect()->route('subscriber.login');
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

    // Determine redirect based on user type and context
    if ($user_type == 4) { // Subscriber/Tenant user
        if (str_starts_with($currentPath, getSaasPrefix())) {
            // Central subscriber dashboard logout
            return redirect()->route('subscriber.login');
        }
        
        if (str_starts_with($currentPath, getAdminPrefix())) {
            // Store-specific dashboard logout (tenant context)
            return redirect()->route('core.login');
        }
        
        // Default fallback for subscribers
        // return redirect()->route('subscriber.login');
        
    } else if ($user_type == 1) { // Super admin
        if (str_starts_with($currentPath, getAdminPrefix())) {
            return redirect()->route('core.login'); // or 'superadmin.login' if different
        }
        
        // return redirect()->route('tenant.login');
        
    } else {
        // Any other user type
        return redirect()->route('subscriber.login');
    }
}
    // public function logout()
    // {
    //     $user_type = Auth::user()->user_type;
    //     $currentPath = request()->path();
    //     $this->setupLoginLogoutActivity(false);
    //     Auth::logout();

    //     echo "<script>console.log('Request path:', '" . request()->path() . "');</script>";


    //     if ($user_type == 4) {
    //         // return redirect()->route('subscriber.login');
    //         if (str_starts_with($currentPath, getSaasPrefix())) {
    //         // Central subscriber dashboard logout
    //         return redirect()->route('subscriber.login');
    //     }

    //     if (str_starts_with($currentPath, getAdminPrefix())) {
    //         // Store-specific dashboard logout
    //         return redirect()->route('core.login'); // whatever your route is
    //     }

    //     // default fallback
    //     return redirect()->route('subscriber.login');

    //     } else if ($user_type == 1)  {
    //         return redirect()->route('core.login');
    //     }else {
    //         return redirect()->route('subscriber.login');
    //     }
    // }

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
