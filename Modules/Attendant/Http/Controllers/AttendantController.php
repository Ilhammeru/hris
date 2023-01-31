<?php

namespace Modules\Attendant\Http\Controllers;

use App\Imports\AttendantListImport;
use App\Models\AttendantList;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Company\Entities\Position;
use Modules\Event\Entities\EventAttendees;
use Yajra\DataTables\Facades\DataTables;

class AttendantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__("attendant::view.attendant"));
        return view('attendant::index');
    }

    public function ajax()
    {
        $data = AttendantList::all();
        return DataTables::of($data)
            ->editColumn('position_id', function ($d) {
                return ucfirst($d->position->name);
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
            ->rawColumns(['position_id', 'action'])
            ->make(true);
    }

    public function import(Request $request)
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file');
    
            $import = Excel::toCollection(new AttendantListImport, $file);
            $data = [];
            foreach ($import[0] as $key => $row) {
                $position = $row[3];
                $model = Position::firstOrNew(
                    ['name' => $position],
                    ['division_id' => 6],
                );
                $model->save();

                $employee_id = implode('', explode('-', $row[2]));

                $attendant = AttendantList::firstOrNew(
                    ['name' => $row[1], 'employee_id' => $employee_id, 'position_id' => $model->id]
                );
                $attendant->save();
            }
            /**
             * Add to redis
             */
            $all = AttendantList::all();
            $data = collect($all)->map(function ($item) {
                $item['value'] = $item->name . ' (' . ucfirst(strtolower($item->position->name)) . ')';

                return $item;
            })->all();
            Redis::set('attendant_list', json_encode($data));
            DB::commit();
            
            return response()->json(['message' => $data]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__("attendant::view.employee"));
        return view('attendant::index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        AttendantList::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
            ],
            [
                'name' => $request->name,
                'position_id' => $request->position_id,
            ]
        );

        /**
         * Add to redis
         */
        $all = AttendantList::all();
        $data = collect($all)->map(function ($item) {
            $item['value'] = $item->name . ' (' . ucfirst(strtolower($item->position->name)) . ')';

            return $item;
        })->all();
        Redis::set('attendant_list', json_encode($data));

        return response()->json(['message' => __('view.success_update_data')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('attendant::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('attendant::edit');
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
        /**
         * Check attendant event relation
         */

        $data = AttendantList::find($id);
        $data->delete();

        return response()->json(['message' => __('view.delete_success')]);
    }
}
