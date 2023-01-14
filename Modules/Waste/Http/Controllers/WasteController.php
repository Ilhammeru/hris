<?php

namespace Modules\Waste\Http\Controllers;

use App\Models\WasteCode;
use App\Models\WasteLogIn;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $codes = WasteCode::all();
        return view('waste::index', compact('codes'));
    }

    public function ajaxIn()
    {
        $q = WasteLogIn::query();
        $q->with('log.code');
        // apply filter codes
        if (request()->has('codes')) {
            $codes = array_unique(request()->codes);
            $q->whereHas('log', function (Builder $query) use ($codes) {
                $query->whereIn('waste_code_id', $codes);
            });
        }

        // apply filter date start
        if (request()->has('date_start') && request()->has('date_end')) {
            $start = date('Y-m-d', strtotime(request()->date_start)) . ' 00:00:00';
            $end = date('Y-m-d', strtotime(request()->date_end)) . ' 00:00:00';
            $dates = [$start, $end];
            $q->whereBetween('date', $dates);
        } else {
            $start = date('Y-m-d') . ' 00:00:00';
            $end = date('Y-m-d') . ' 00:00:00';
            $dates = [$start, $end];
            $q->whereBetween('date', $dates);
        }

        // apply filter exp
        if (request()->exp_start != '' && request()->exp_end != '') {
            $start = date('Y-m-d', strtotime(request()->exp_start)) . ' 00:00:00';
            $end = date('Y-m-d', strtotime(request()->exp_end)) . ' 00:00:00';
            $dates = [$start, $end];
            $q->whereBetween('exp', $dates);
        }
        $data = $q->get();
        return DataTables::of($data)
            ->addColumn('type', function($d) {
                $code = $d->log->code->code;
                $detail = $d->log->waste_detail;
                return ucfirst($code . ' (' . $detail . ')');
            })
            ->editColumn('date', function($d) {
                return date('d F Y', strtotime($d->date));
            })
            ->editColumn('waste_source', function($d) {
                return ucfirst($d->waste_source);
            })
            ->editColumn('qty', function($d) {
                return '<b>' . number_format($d->qty, 2, '.', '') . ' Kg </b>';
            })
            ->editColumn('exp', function($d) {
                return date('d F Y', strtotime($d->exp));
            })
            ->rawColumns(['type', 'data', 'qty', 'exp'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('waste::create');
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
        return view('waste::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('waste::edit');
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
