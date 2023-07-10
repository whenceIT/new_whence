<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\Permission;
use App\Models\Repair;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class UserController extends Controller
{
    public function __construct()
    {

        $this->middleware('sentinel');
    }

    public function dashboard()
    {

        return view('dashboard');
    }

    public function index()
    {
        if (!Sentinel::hasAccess('users.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
     

   $data = DB::table('users')->select('users.*')

->join('role_users', 'role_users.user_id', '=', 'users.id')
->join('roles', 'roles.id', '=', 'role_users.role_id')
->where('roles.name','!=', 'Client')->get();

        // $data = User::where('roles')->get();
        return view('user.data', compact('data'));
    }

    //client users only
    public function client_users_index()
    {
        if (!Sentinel::hasAccess('users.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }


   $data = DB::table('users')->select('users.*')

->join('role_users', 'role_users.user_id', '=', 'users.id')
->join('roles', 'roles.id', '=', 'role_users.role_id')
->where('roles.name', 'Client')->get();
        return view('user.client_users_data', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('users.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('user.create');
    }

    /**credentials
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('users.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $rules = array(
            'email' => 'required|unique:users',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
            'first_name' => 'required',
            'last_name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Flash::warning(trans('general.validation_error'));
            return redirect()->back()->withInput()->withErrors($validator);

        } else {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'notes' => $request->notes,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'office_id'=> $request->office_id,
            ];
            $user = Sentinel::registerAndActivate($credentials);
            $role = Sentinel::findRoleById($request->role);
            $role->users()->attach($user->id);
            //check custom fields
            if (Setting::where('setting_key', 'enable_custom_fields')->first()->setting_value == 1) {
                $custom_fields = CustomField::where('category', 'users')->get();
                foreach ($custom_fields as $key) {
                    $custom_field = new CustomFieldMeta();
                    $id = "custom_field_" . $key->id;
                    if ($key->field_type == "checkbox") {
                        if (!empty($request->$id)) {
                            $custom_field->name = serialize($request->$id);
                        } else {
                            $custom_field->name = serialize([]);
                        }
                    } else {
                        $custom_field->name = $request->$id;
                    }
                    $custom_field->parent_id = $user->id;
                    $custom_field->custom_field_id = $key->id;
                    $custom_field->category = "users";
                    $custom_field->save();
                }
            }
            GeneralHelper::audit_trail("Create", "Users", $user->id);
            Flash::success("Successfully Saved");
            return redirect('user/data');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        if (!Sentinel::hasAccess('users.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        return view('user.show', compact('user'));
    }

    public function edit($user)
    {
        if (!Sentinel::hasAccess('users.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }


        foreach ($user->roles as $sel) {
            $selected = $sel->id;
        }
        return view('user.edit', compact('user', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Sentinel::hasAccess('users.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $user = Sentinel::findById($id);
        $credentials = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'notes' => $request->notes,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'office_id'=> $request->office_id,
            'external_id'=> $request->external_id,
        ];

        if (!empty($request->password)) {
            $credentials['password'] = $request->password;
        }
        if ($request->role != $request->previous_role) {

            $role = Sentinel::findRoleById($request->previous_role);
            $role->users()->detach($user->id);
            $role = Sentinel::findRoleById($request->role);
            $role->users()->attach($user->id);
        }
        $user = Sentinel::update($user, $credentials);
        if (Setting::where('setting_key', 'enable_custom_fields')->first()->setting_value == 1) {
            $custom_fields = CustomField::where('category', 'users')->get();
            foreach ($custom_fields as $key) {
                if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                    'users')->first())
                ) {
                    $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                        $id)->where('category', 'users')->first();
                } else {
                    $custom_field = new CustomFieldMeta();
                }
                $kid = "custom_field_" . $key->id;
                if ($key->field_type == "checkbox") {
                    if (!empty($request->$kid)) {
                        $custom_field->name = serialize($request->$kid);
                    } else {
                        $custom_field->name = serialize([]);
                    }
                } else {
                    $custom_field->name = $request->$kid;
                }
                $custom_field->parent_id = $id;
                $custom_field->custom_field_id = $key->id;
                $custom_field->category = "users";
                $custom_field->save();
            }
        }
        GeneralHelper::audit_trail("Update", "Users", $user->id);
   

        Flash::success("Successfully Saved");
        return redirect('user/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('users.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        if (Sentinel::getUser()->id == $id) {
            Flash::warning("You cannot delete your account");
            return redirect()->back();
        }
        $user = Sentinel::findById($id);
        $user->delete();
        GeneralHelper::audit_trail("Delete", "Users", $user->id);
        Flash::success("Successfully Deleted");
        return redirect('user/data');
    }

    public function profile()
    {

        $user = Sentinel::findById(Sentinel::getUser()->id);
        return view('user.profile', compact('user'));
    }

    public function edit_profile()
    {

        $user = Sentinel::getUser();
        return view('user.edit_profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $user = Sentinel::findById(Sentinel::getUser()->id);
        $credentials = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'notes' => $request->notes,
            'gender' => $request->gender,
            'phone' => $request->phone
        ];
        if (!empty($request->password)) {
            $credentials['password'] = $request->password;
        }
        $user = Sentinel::update($user, $credentials);

        if(Sentinel::inRole('client')){
         return   app('App\Http\Controllers\ClientController')->clientSelfUpdate($request);
        }


        Flash::success("Successfully Saved");
        return redirect('dashboard');
    }

//manage permissions
    public function indexPermission()
    {
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        return view('user.permission.data', compact('data'));
    }

    public function createPermission()
    {
        $parents = Permission::where('parent_id', 0)->get();
        $parent = array();
        $parent['0'] = "None";
        foreach ($parents as $key) {
            $parent[$key->id] = $key->name;
        }

        return view('user.permission.create', compact('parent'));
    }

    public function storePermission(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->parent_id = $request->parent_id;
        $permission->description = $request->description;
        if (!empty($request->slug)) {
            $permission->slug = $request->slug;
        } else {
            $permission->slug = str_slug($request->name, '_');
        }

        $permission->save();
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }

    public function editPermission($permission)
    {
        $parents = Permission::where('parent_id', 0)->get();
        $parent = array();
        $parent['0'] = "None";
        foreach ($parents as $key) {
            $parent[$key->id] = $key->name;
        }
        if ($permission->parent_id == 0) {
            $selected = 0;
        } else {
            $selected = 1;
        }

        return view('user.permission.edit', compact('parent', 'permission', 'selected'));
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->parent_id = $request->parent_id;
        $permission->description = $request->description;
        if (!empty($request->slug)) {
            $permission->slug = $request->slug;
        } else {
            $permission->slug = str_slug($request->name, '_');
        }
        $permission->save();
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }

//manage roles
    public function indexRole()
    {
        if (!Sentinel::hasAccess('users.roles.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $data = EloquentRole::all();
        return view('user.role.data', compact('data'));
    }

    public function createRole()
    {
        if (!Sentinel::hasAccess('users.roles.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        return view('user.role.create', compact('data'));
    }

    public function storeRole(Request $request)
    {
        if (!Sentinel::hasAccess('users.roles.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $role = new EloquentRole();
            $role->name = $request->name;
            $role->slug = GeneralHelper::getUniqueSlug($role, $request->name);
            $role->time_limit = $request->time_limit;
            if ($request->time_limit == 1) {
                if (strtotime($request->from_time) >= strtotime($request->to_time)) {
                    Flash::success("To time must be greater than from time");
                    return redirect()->back()->withInput();
                }
                $role->from_time = $request->from_time;
                $role->to_time = $request->to_time;
                $role->access_days = json_encode($request->access_days);
            } else {
                $role->access_days = json_encode([]);
            }
            $role->save();
            if (!empty($request->permission)) {
                foreach ($request->permission as $key) {
                    $role->updatePermission($key, true, true)->save();
                }
            }
            GeneralHelper::audit_trail("Create Role", "Users", $role->id);
            Flash::success("Successfully Saved");
            return redirect('user/role/data');
        }
    }

    public function editRole($id)
    {
        if (!Sentinel::hasAccess('users.roles.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        $role = EloquentRole::find($id);
        return view('user.role.edit', compact('data', 'role'));
    }

    public function updateRole(Request $request, $id)
    {
        if (!Sentinel::hasAccess('users.roles.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $role = EloquentRole::find($id);
        $role->name = $request->name;
        $role->slug = GeneralHelper::getUniqueSlug($role, $request->name);
        $role->time_limit = $request->time_limit;
        if ($request->time_limit == 1) {
            if (strtotime($request->from_time) >= strtotime($request->to_time)) {
                Flash::warning("To time must be greater than from time");
                return redirect()->back()->withInput();
            }
            $role->from_time = $request->from_time;
            $role->to_time = $request->to_time;
            $role->access_days = json_encode($request->access_days);
        } else {
            $role->access_days = json_encode([]);
        }
        $role->permissions = array();
        $role->save();
        //remove permissions which have not been ticked
        //create and/or update permissions
        if (!empty($request->permission)) {
            foreach ($request->permission as $key) {
                $role->updatePermission($key, true, true)->save();
            }
        }

        GeneralHelper::audit_trail("Update Role", "Users", $role->id);
        Flash::success("Successfully Saved");
        return redirect('user/role/data');
    }

    public function deletePermission($id)
    {
        Permission::destroy($id);
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }

    public function deleteRole($id)
    {
        if (!Sentinel::hasAccess('users.roles.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        EloquentRole::destroy($id);
        GeneralHelper::audit_trail("Delete Role", "Users", $id);
        Flash::success("Successfully Saved");
        return redirect('user/role/data');
    }
}
