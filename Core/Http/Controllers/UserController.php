<?php

namespace Core\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use Core\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Core\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Core\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * get all users
     *
     * @return mixed
     */
    public function users()
    {
        try {
            $users = User::whereNull('user_type')
                ->orWhere('user_type', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return view('core::base.users.users', compact('users'));
        } catch (Exception $e) {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Will redirect to user adding form
     *
     * @return mixed
     */
    public function addUser()
    {
        return view('core::base.users.add_user');
    }

    /**
     * store new user
     *
     * @param  UserRequest $request
     * @return mixed
     */
    public function storeUser(UserRequest $request)
    {
        try {

            // \Log::info('storeUser method called !!!!');

            $date = Carbon::now();
            $user_id = $date->format('y') . $date->format('m') . $date->format('d');
            DB::beginTransaction();

            // --- Store user in tenant DB (existing logic) ---
            $user = new User();
            $user->name = xss_clean($request['name']);
            $user->email = xss_clean($request['email']);
            $user->password = Hash::make($request['password']);
            $user->user_type = 1;
            $user->image = $request['pro_pic'];
            $user->status = isset($request['status']) ? config('settings.user_status.active') : config('settings.user_status.in_active');
            $user->saveOrFail();
            $user->uid = "STUFF" . $user->id . $user_id;
            $user->update();
            $user->assignRole($request['role']);

            // \Log::info('User data', [
            //     'User' => json_encode($user),
            //     'tenant_id' => tenant('id')
            // ]);

            // --- Mirror user to main DB tl_store_users ---
            DB::connection('mysql')->table('tl_store_users')->insert([
                'uid'        => $user->uid,
                'tenant_id'  => tenant('id'),
                'name'       => $user->name,
                'email'      => $user->email,
                'password'   => $user->password,
                'status'     => $user->status,
                'created_at' => $date,
            ]);

            DB::commit();
            toastNotification('success', 'User created successfully');
            return redirect()->route('core.users');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Adding user failure: {$e->getMessage()}");
            toastNotification('error', 'User create failed');
            return redirect()->back();
        }
    }
    // public function storeUser(UserRequest $request)
    // {
    //     try {
    //         $date = Carbon::now();
    //         $user_id = $date->format('y') . $date->format('m') . $date->format('d');

    //         DB::beginTransaction();
    //         $user = new User();
    //         $user->name = xss_clean($request['name']);
    //         $user->email = xss_clean($request['email']);
    //         $user->password = Hash::make($request['password']);
    //         $user->user_type = 1;
    //         $user->image = $request['pro_pic'];
    //         $user->status = isset($request['status']) ? config('settings.user_status.active') : config('settings.user_status.in_active');
    //         $user->saveOrFail();

    //         $user->uid = "STUFF" . $user->id . $user_id;
    //         $user->update();
    //         $user->assignRole($request['role']);

    //         DB::commit();

    //         toastNotification('success', 'User created successfully');
    //         return redirect()->route('core.users');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         toastNotification('error', 'User create failed');
    //         return redirect()->back();
    //     }
    // }

    /**
     * Will update user status
     *
     * @param  mixed $request
     * @return mixed
     */
    public function updateUserStatus(Request $request)
    {
        try {
            $user = User::findOrFail($request['id']);
            $user->status = $request['status'];
            $user->update();
            return response()->json([
                'success' => true,
                'message' => translate('User status updated successfully')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => translate("Unable to update user status")
            ], 500);
        }
    }

    /**
     * will redirect to user editing form
     *
     * @param  mixed $id
     * @return mixed
     */
    public function editUser($id)
    {
        try {
            $match_case = [
                ['tl_users.id', '=', $id]
            ];
            $data = [
                'tl_users.*',
                'tl_uploaded_files.path as pro_pic',
                'tl_uploaded_files.alt as pro_pic_alt',
                'tl_uploaded_files.id as pro_pic_id'
            ];
            $user = $this->user_repository->getUserProfileInfo($data, $match_case)->first();
            return view('core::base.users.edit_user', compact('user'));
        } catch (\Exception $th) {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }


    /**
     * update user details
     *
     * @param  mixed $request
     * @return mixed
     */
    public function updateUser(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find($request['id']);
            $user->name  = xss_clean($request['name']);
            $user->email = xss_clean($request['email']);
            $user->image = $request['pro_pic'];
            $user->status = isset($request['status']) ? config('settings.user_status.active') : config('settings.user_status.in_active');
            $user->update();

            $user->update();
            if (!$user->hasRole('Super Admin')) {
                if (isset($request['role']) && $request['role'] != null) {
                    $user->syncRoles($request['role']);
                }
            }

            $this->syncStoreUser($user);

            DB::commit();

            toastNotification('success', translate('User updated successfully'));
            return redirect()->route('core.edit.user', ['id' => $request['id']]);
        } catch (Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Unable to update user'));
            return redirect()->back();
        }
    }

    /**
     * delete user
     *
     * @param  mixed $request
     * @return mixed
     */
    public function deleteUser(Request $request)
    {
        try {
            if ($request['id'] == getSupperAdminId()) {
                toastNotification('Error', 'You can not delete supper admin');
                return redirect()->route('core.users');
            }
            $user = User::findOrFail($request['id']);
            $this->deleteStoreUser($user);
            $user->delete();
            toastNotification('Success', 'User deleted successfully');
            return redirect()->route('core.users');
        } catch (Exception $e) {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Will redirect to profile page
     */
    public function profile()
    {
        try {
            $id = Auth::user()->id;
            $match_case = [
                ['tl_users.id', '=', $id]
            ];
            $data = [
                'tl_users.*',
                'tl_uploaded_files.path as pro_pic',
                'tl_uploaded_files.alt as pro_pic_alt',
                'tl_uploaded_files.id as pro_pic_id'
            ];
            $user = $this->user_repository->getUserProfileInfo($data, $match_case)->first();
            return view('core::base.users.user_profile', compact('user'));
        } catch (Exception $ex) {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * update user profile
     *
     * @param  UserRequest $request
     * @return mixed
     */
    public function updateProfile(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find($request['id']);
            if (request('old_password') != null) {
                if (!Hash::check($request['old_password'], $user->password)) {
                    return back()->withErrors([
                        'old_password' => 'Incorrect password'
                    ]);
                }
            }
            $user->name  = xss_clean($request['name']);
            $user->email = xss_clean($request['email']);
            $passwordChanged = false;
            if (request('password') != null && request('password_confirmation') != null && request('old_password') != null) {
                $user->password = Hash::make($request['password']);
                $passwordChanged = true;
            }

            $user->update();
            $user->image = $request['pro_pic'];

            $user->update();

            $extra = [];
            if ($passwordChanged) {
                $extra['password'] = $user->password;
            }
            $this->syncStoreUser($user, $extra);

            DB::commit();
            toastNotification('success', translate('Profile updated successfully'));
            return redirect()->route('core.profile');
        } catch (Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Unable to update user profile'));
            return redirect()->route('core.profile');
        }
    }

    /**
     * Mirror tenant staff user changes to central tl_store_users.
     *
     * @param  User  $user
     * @param  array  $extra
     * @return void
     */
    protected function syncStoreUser(User $user, array $extra = [])
    {
        if (!tenancy()->initialized || empty($user->uid)) {
            return;
        }

        $data = array_merge([
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'updated_at' => Carbon::now(),
        ], $extra);

        DB::connection('mysql')->table('tl_store_users')
            ->where('uid', $user->uid)
            ->where('tenant_id', tenant('id'))
            ->update($data);
    }

    /**
     * Remove mirrored tenant staff user from central tl_store_users.
     *
     * @param  User  $user
     * @return void
     */
    protected function deleteStoreUser(User $user)
    {
        if (!tenancy()->initialized || empty($user->uid)) {
            return;
        }

        DB::connection('mysql')->table('tl_store_users')
            ->where('uid', $user->uid)
            ->where('tenant_id', tenant('id'))
            ->delete();
    }
}
