<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221022
*/

namespace Modules\User\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('user::roles.index_title'));
        return view('user::role.index');
    }

    public function ajax()
    {
        $data = Role::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return ucfirst($d->name);
            })
            ->addColumn('action', function($d) {
                return '<a class="btn btn-secondary c-btn-sm" href="'. route('user.role.edit', $d->id) .'"><i class="bi bi-pen-fill p-0"></i></a>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__('user::roles.create_role'));

        $permissions = PermissionGroup::with('permissions')->get();
        $is_edit = false;

        return view('user::role.create', compact('permissions', 'is_edit'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'permissions.*' => 'required'
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors()->all();
                return response()->json($errors, 500);
            }

            $name = $request->name;
            $permissions = $request->permissions;
            $role = Role::create(['name' => $name]);

            // assign permission to role
            for ($a = 0; $a < count($permissions); $a++) {
                $d_permissions = Permission::findById($permissions[$a]);
                $role->givePermissionTo($d_permissions);
            }

            DB::commit();
            return response()->json('success');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        setTitle(__('user::roles.edit_role'));

        $permissions = PermissionGroup::with('permissions')->get();
        $data = Role::findById($id);
        $role_permission = $data->getAllPermissions();
        $permissions = collect($permissions)->map(function($item) use($role_permission) {
            foreach($item->permissions as $p) {
                $p->checked = false;
                foreach($role_permission as $rp) {
                    if ($p->id == $rp->id) {
                        $p->checked = true;
                    }
                }
            }
            return $item;
        })->all();
        $is_edit = true;
        return view('user::role.create', compact('permissions', 'data', 'role_permission', 'is_edit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'permissions.*' => 'required'
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors()->all();
                return response()->json($errors, 500);
            }

            $name = $request->name;
            $permissions = $request->permissions;

            $role = Role::findById($id);
            $currentPermission = $role->getAllPermissions();

            // save
            $role->name = $name;
            $role->save();

            // detach permission
            foreach($currentPermission as $cp) {
                $role->revokePermissionTo($cp);
            }

            // attach new permission
            for ($a = 0; $a < count($permissions); $a++) {
                $get_permissions = Permission::findById($permissions[$a]);
                $role->givePermissionTo($get_permissions);
            }
            
            DB::commit();
            return response()->json('Success Update Role');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findById($id);
            $role_permission = $role->getAllPermissions();

            // detach permissions
            foreach ($role_permission as $rp) {
                $role->revokePermissionTo($rp);
            }

            $role->delete();
            DB::commit();
            return response()->json(['message' => 'Success delete role']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage(),500);
        }
    }
}
