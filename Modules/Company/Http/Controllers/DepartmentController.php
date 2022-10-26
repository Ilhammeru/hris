<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Company\Entities\Department;
use Modules\Company\Entities\Division;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('company::index');
    }

    /**
     * Function to get data for datatable
     * @return DataTables
     */
    public function ajax()
    {
        $data = Department::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return ucfirst($d->name);
            })
            ->addColumn('action', function($d) {
                return '<button class="btn btn-secondary c-btn-sm" type="button" onclick="editDept('. $d->id .')"><i class="bi bi-pen-fill p-0"></i></button>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteDept('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
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
        $view = view('company::department.form')->render();
        return response()->json(['body' => $view]);
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
                'name' => 'required|unique:department,name'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $name = $request->name;
            $dept = new Department();
            $dept->name = $name;
            $dept->save();

            return response()->json(['message' => 'Department saved successfully', 'type' => 'department']);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('company::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = Department::findOrFail($id);
            $view = view('company::department.form', compact('data'))->render();
            return response()->json(['body' => $view, 'is_edit' => true]);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $rules = ['name' => 'required'];
            $name = $request->name;
            $data = Department::find($id);
            if (strtolower($data->name) != strtolower($name)) {
                $rules['name'] = 'required|unique:department,name';
            }
            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $data->name = $name;
            $data->save();

            return response()->json(['message' => 'Department updated successfully', 'type' => 'department']);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
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
            $data = Department::with('divisions')->findOrFail($id);
            $current_divisions = $data->divisions;
            $data->delete();

            foreach ($current_divisions as $division) {
                Division::where('id', $division->id)
                    ->delete();
            }

            DB::commit();
            return response(['message' => 'Department deleted successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage(), 500);
        }
    }
}
