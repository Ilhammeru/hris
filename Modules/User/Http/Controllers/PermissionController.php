<?php

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

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('user::permissions.index_title'));
        return view('user::permission.index');
    }

    /**
     * Function to show data for datatable
     */
    public function ajax()
    {
        $permissions = Permission::with('group:id,name')->get();
        return DataTables::of($permissions)
            ->addColumn('action', function($d) {
                return '<a class="btn btn-secondary c-btn-sm" href="'. route('user.permission.edit', $d->id) .'"><i class="bi bi-pen-fill p-0"></i></a>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
            })
            ->addColumn('group', function($d) {
                return ucfirst($d->group->name);
            })
            ->rawColumns(['action', 'group'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__('user::permissions.index_title'));

        return view('user::permission.create');
    }

    /**
     * Function to get list of permission group
     * @return DataTables
     */
    public function indexGroup()
    {
        $data = PermissionGroup::all();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucfirst($data->name);
            })
            ->addColumn('action', function($d) {
                return '<button class="btn btn-secondary c-btn-sm" type="button" onclick="editGroup('. $d->id .')"><i class="bi bi-pen-fill p-0"></i></button>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Function to get list of permission group to append in form
     * @return Response
     */
    public function listGroup()
    {
        $data = PermissionGroup::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function storeGroup(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $name = $request->name;
            $group = new PermissionGroup();
            $group->name = $name;
            $group->save();

            return response()->json('Permission Group Stored');
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'permission_group' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }
            
            $name = $request->name;
            $group = $request->permission_group;
            $exp = implode('-', explode(' ', $name));
            Permission::create(['name' => $exp, 'permission_group_id' => $group]);

            return response()->json('Success stored permission');
        } catch (\Throwable $th) {
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
        setTitle(__('user::permissions.edit_permission'));

        $data = Permission::findById($id);
        
        return view('user::permission.create', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function editGroup($id)
    {
        $data = PermissionGroup::find($id);
        
        return response()->json($data);
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
                'permission_group' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }
            
            $permission = Permission::findById($id);
            
            $name = $request->name;
            $group = $request->permission_group;
            $exp = implode('-', explode(' ', $name));
            $permission->name = $exp;
            $permission->permission_group_id = $group;
            $permission->save();

            DB::commit();
            return response()->json('Success stored permission');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateGroup(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }
            
            $group = PermissionGroup::find($id);
            $name = $request->name;
            $group->name = $name;
            $group->save();

            DB::commit();
            return response()->json('Success update permission group');
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
            $permission = Permission::findById($id);
            $roles = $permission->roles;

            // detach permission
            if (count($roles) > 0) {
                foreach ($roles as $r) {
                    $role = Role::findById($r->id);
                    $role->revokePermissionTo($permission);
                }
            }

            //delete permission
            $permission->delete();
            
            DB::commit();
            return response(['message' => 'Success delete permission']);
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
    public function destroyGroup($id)
    {
        DB::beginTransaction();
        try {
            $permission_group = PermissionGroup::find($id);
            $roles = $permission_group->roles;

            // delete all permission that related to this group id
            $permissions = Permission::where('permission_group_id', $id)
                ->get();
            foreach ($permissions as $permission) {
                // detach role has permission
                $roles = $permission->roles;
                foreach ($roles as $r) {
                    $role = Role::findById($r->id);
                    $role->revokePermissionTo($permission);
                }

                $permission->delete();
            }

            //delete all permission that related to this group id
            $permission_group->delete();
            
            DB::commit();
            return response(['message' => 'Success delete permission']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }
}
