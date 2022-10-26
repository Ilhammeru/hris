<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221023
*/

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Company\Entities\Department;
use Modules\Company\Entities\Division;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('company::company.index_title'));
        return view('company::division.index');
    }

    /**
     * Function to get data for datatable
     * @return DataTables
     */
    public function ajax()
    {
        $data = Division::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return ucfirst($d->name);
            })
            ->addColumn('action', function($d) {
                return '<button type="button" class="btn btn-secondary c-btn-sm" onclick="editDiv('. $d->id .')"><i class="bi bi-pen-fill p-0"></i></button>
                <button class="btn btn-secondary c-btn-sm" type="button" id="btn-delete-division" onclick="deleteDiv('. $d->id .')"><i class="bi bi-trash p-0"></i></button>';
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
        $departments = Department::all();
        $view = view('company::division.form', ['departments' => $departments])->render();
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
                'name' => 'required|unique:division,name',
                'department_id' => 'required'
            ]);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $name = $request->name;
            $department_id = $request->department_id;

            $division = new Division();
            $division->name = $name;
            $division->department_id = $department_id;

            $division->save();

            return response()->json(['message' => __('company::company.department_stored'), 'type' => 'division']);
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
            $data = Division::findOrFail($id);
            $departments = Department::all();
            $view = view('company::division.form', compact('data', 'departments'))->render();
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
            $rules = [
                'name' => 'required',
                'department_id' => 'required'
            ];
            $division = Division::find($id);
            if (strtolower($division->name) != $request->name) {
                $rules['name'] = 'required|unique:division,name';
            }
            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                $error = $validate->errors()->all();
                return response()->json($error, 500);
            }

            $name = $request->name;
            $department_id = $request->department_id;

            $division->name = $name;
            $division->department_id = $department_id;

            $division->save();

            return response()->json(['message' => __('company::company.department_stored'), 'type' => 'division']);
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
        try {
            $division = Division::find($id);
            $division->delete();

            return response()->json(['message' => 'Success delete division']);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }
}
