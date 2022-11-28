<?php

namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Entities\RecruitmentSetting;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('recruitment::view.recruitment_setting_title'));
        $class = dt_table_class();
        return view('recruitment::setting.index', compact('class'));
    }

    /**
     * Function to get recruitment step from ajax request
     * @return Response
     */
    public function getRecruitmentStep()
    {
        $data = RecruitmentSetting::all();

        return response()->json(['data' => $data]);
    }

    /**
     * Get detail notification setting based on given notification type id
     * @param int id
     * @return Response
     */
    public function getNotificationSetup($id)
    {
        try {
            // $data = RecruitmentSetting::select('id', 'step', 'name', '')
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Functin to get all data for datatables
     * @return DataTables
     */
    public function ajax()
    {
        $data = RecruitmentSetting::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return ucfirst($d->name);
            })
            ->addColumn('action', function($d) {
                return '
                    <button class="btn btn-sm btn-secondary" type="button"><i class="bi bi-pen-fill p-0"></i></button>
                ';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('recruitment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('recruitment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('recruitment::edit');
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
