<?php

namespace Modules\Employee\Http\Controllers;

use App\Models\Province;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Department;
use Modules\Company\Entities\Division;
use Modules\Employee\Entities\Employee;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__("employee::view.employee"));
        return view('employee::index');
    }

    /**
     * Function to show data to Datatables
     *
     * @return DataTables
     */
    public function ajax()
    {
        $data = Employee::select('name', 'division_id', 'status', 'internship_date', 'id')
            ->active()
            ->get();

        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return set_link_text($d->name, route('employee.detail.profile', $d->id));
            })
            ->editColumn('division_id', function($d) {
                $division = $d->division;
                return $division->name;
            })
            ->editColumn('status', function($d) {
                return $d->employement_status;
            })
            ->addColumn('working_time', function($d) {
                return $d->working_time;
            })
            ->addColumn('action', function($d) {
                return set_action_table([
                    'edit' => [
                        'paramOnClick' => $d->id,
                    ],
                    'delete' => [
                        'paramOnClick' => $d->id,
                    ]
                ]);
            })
            ->rawColumns(['division_id', 'working_time', 'action', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__('employee::view.add_employee'));
        $provinces = \Laravolt\Indonesia\Models\Province::all();
        $departments = Department::all();
        return view('employee::create', compact('provinces', 'departments'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->id;
        $provinces = \Indonesia::findProvince($id, ['cities']);
        $data = $provinces->cities;
        return response()->json(['data' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrict(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->id;
        $cities = \Indonesia::findCity($id, ['districts']);
        $data = $cities->districts;
        return response()->json(['data' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVillage(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->id;
        $cities = \Indonesia::findDistrict($id, ['villages']);
        $data = $cities->villages;
        return response()->json(['data' => $data]);
    }

    public function getDivision(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->department_id;
        $data = Division::where('department_id', $id)->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        return response()->json($request->all());
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Employee::find($id);
        setTitle(__("employee::view.detail_employee"));
        return view('employee::show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('employee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
