<?php

/**
 * @author Ilham Gumilang <gumilang.dev@gmail.com>
 * Datetime 20221022
 */

namespace Modules\User\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Modules\User\Notifications\RegisterUser;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('user::users.index_title'));

        return view('user::user.index');
    }

    /**
     * Function to get data for datatables
     * @return DataTables
     */
    public function ajax()
    {
        $data = User::with('role')->get();
        return DataTables::of($data)
            ->editColumn('email', function($d) {
                return $d->email;
            })
            ->editColumn('role', function($d) {
                $role = $d->role;
                $data_role = Role::findById($role);
                return strtoupper($data_role->name);
            })
            ->addColumn('action', function($d) {
                return '<a class="btn btn-secondary c-btn-sm" href="'. route('user.edit', $d->id) .'"><i class="bi bi-pen-fill p-0"></i></a>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
            })
            ->rawColumns(['action', 'role'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__('user::users.create_user'));
        
        $roles = Role::all();
        return view('user::user.create', compact('roles'));
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
                'email' => 'required|unique:users,email',
                'password' => 'required|min:6',
                'retype_password' => 'required_with:password|same:password',
                'role' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }
    
            $email = $request->email;
            $password = Hash::make($request->password);
            $role = $request->role;
    
            $user = new User();
            $user->email = $email;
            $user->password = $password;
            $user->role = $role;
            $user->save();

            // assign to role
            $r = Role::findById($role);
            $user->assignRole($r);
    
            sendEmail([
                'name' => $user->email,
                'email' => $user->email,
                'password' => $request->password,
                'receiver' => $user->email,
                'receiver_name' => 'Name'
            ]);
    
            DB::commit();
            return response()->json(['message' => 'User stored Successfully']);
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
        setTitle(__('user::users.edit_user'));
        
        $roles = Role::all();
        $data = User::find($id);
        return view('user::user.create', compact('roles', 'data'));
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
                'email' => 'required',
                'retype_password' => 'required_with:password|same:password',
                'role' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $user = User::find($id);
            $current_role = $user->role;
            $current_email = $user->email;
    
            $email = $request->email;
            $role = $request->role;

            if ($request->password || $request->password != '') {
                $password = Hash::make($request->password);
                $user->password = $password;
            }
    
            $user->email = $email;
            $user->role = $role;
            $user->save();

            // detach current role
            $role_c = Role::findById($current_role);
            $user->removeRole($role_c);

            // assign to role
            $r = Role::findById($role);
            $user->assignRole($r);
    
            DB::commit();
            return response()->json(['message' => 'User updated Successfully']);
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
            $data = User::find($id);
            $current_role = $data->role;

            // detach role
            $data->removeRole($current_role);

            // delete
            $data->delete();
            DB::commit();
            return response()->json(['message' => 'User deleted']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage(),500);
        }
    }
}
