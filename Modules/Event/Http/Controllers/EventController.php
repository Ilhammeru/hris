<?php

namespace Modules\Event\Http\Controllers;

use App\Exports\ExportAttendees;
use App\Models\AttendantList;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventAttendees;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__("event::view.event"));
        return view('event::index');
    }

    public function ajax()
    {
        $data = Event::all();
        return DataTables::of($data)
            ->editColumn('option_finisher', function ($d) {
                if ($d->option_finisher == 1) {
                    $text = 'Signature';
                } else {
                    $text = 'Confirmation box';
                }

                return $text;
            })
            ->editColumn('name', function ($d) {
                return '<a href="'. route('event.show', $d->id) .'">'. $d->name .'</a>';
            })
            ->addColumn('guestbook', function ($d) {
                return '<a href="'. route('event.guestbook', $d->slug) .'" target="_blank">'. route('event.guestbook', $d->slug) .'</a>';
            })
            ->addColumn('action', function($d) {
                return set_action_table([
                    'edit' => [
                        'paramOnClick' => $d->id,
                    ]
                ]);
            })
            ->editColumn('start_date', function ($d) {
                return date('Y-m-d H:i', strtotime($d->start_date));
            })
            ->editColumn('end_date', function ($d) {
                return date('Y-m-d H:i', strtotime($d->end_date));
            })
            ->rawColumns(['action', 'option_finisher', 'start_date', 'end_date', 'guestbook', 'name'])
            ->make(true);
    }

    public function ajaxAttendees($id)
    {
        $data = EventAttendees::with('attendant.position')->where('event_id', $id)->get();

        return DataTables::of($data)
            ->addColumn('name', function ($d) {
                return ucfirst($d->attendant->name);
            })
            ->addColumn('employee_id', function ($d) {
                return $d->attendant->employee_id;
            })
            ->addColumn('position', function ($d) {
                return $d->attendant->position->name;
            })
            ->editColumn('signature', function ($d) {
                return '<img src="'. $d->signature .'" style="width: 90px; height: auto;" />';
            })
            ->editColumn('check_in_at', function ($d) {
                return date('d F Y H:i', strtotime($d->check_in_at));
            })
            ->addColumn('vaccine', function ($d) {
                return strtoupper($d->attendant->vaccine_booster) ?? '-';
            })
            ->rawColumns([
                'name', 'employee_id', 'position',
                'signature', 'check_in_at',
            ])
            ->make(true);
    }

    public function exportAttendees($id)
    {
        $data = EventAttendees::with('attendant.position')->where('event_id', $id)->get();
        $data = collect($data)->map(function ($item) {
            $img = $item->signature;
            $item['signature_path'] = null;
            if ($img) {
                $folderPath = public_path() . '/export/attendees/image/'; //path location
                
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $uniqid = uniqid();
                $file = $folderPath . $uniqid . '.'.$image_type;
                file_put_contents($file, $image_base64);
                Image::make($file)->resize(50,50)
                    ->save('export/attendees/image/' . $uniqid . '.' . $image_type);
                $item['signature_path'] = $file;
            }

            return $item;
        })->all();
        return Excel::download(new ExportAttendees($data), 'attendees.xlsx');
    }

    public function guestbook($slug)
    {
        $data = Event::where('slug', $slug)
            ->first();
        $current = Redis::get('attendant_list');
        if (!$current) {
            $all = AttendantList::all();
            Redis::set('attendant_list', json_encode($all));
        }
        $current = json_decode($current, true);
        $employees = $current;
        return view('event::guestbook', compact('data', 'employees'));
    }

    public function check_in(Request $request)
    {
        DB::beginTransaction();
        try {
            /**
             * Validation
             */
            $check = EventAttendees::where('attendant_id', $request->attendant_id)
                ->where('event_id', $request->event_id)
                ->first();
            if ($check) {
                DB::rollBack();
                return response()->json(['message' => __('view.already_check_in'), 'status' => 1]);
            }
            $attendee = AttendantList::find($request->attendant_id);
            if (!$attendee) {
                DB::rollBack();
                return response()->json(['message' => __('event::view.attendee_not_found')], 500);
            }

            EventAttendees::insert([
                'attendant_id' => $request->attendant_id,
                'event_id' => $request->event_id,
                'check_in_at' => Carbon::now(),
                'signature' => $request->signature,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            $vaccine = $request->vaccine;
            $attend = AttendantList::find($request->attendant_id);
            $attend->vaccine_booster = $vaccine;
            $attend->save();
            DB::commit();
            
            return response()->json(['message' => __('view.success_check_in')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function guestbook_list(Request $request)
    {
        $key = $request->term;
        $current = Redis::get('attendant_list');
        if (!$current) {
            $data = AttendantList::where('employee_id', 'like', '%' . $key . '%')
                ->orWhere('name', 'LIKE', '%' . $key . '%')
                ->get();
    
            $data = collect($data)->map(function ($item) {
                $item['value'] = $item->name . ' (' . ucfirst(strtolower($item->position->name)) . ')';
    
                return $item;
            })->all();
            Redis::set('attendant_list', json_encode($data));
        }
        $current = json_decode($current, true);
        $data = collect($current)->where('employee_id', $key)
            ->values();
        
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('event::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $event_date = $request->event_date;
        $exp = explode(' - ', $event_date);
        $start_date = date('Y-m-d H:i', strtotime($exp[0]));
        $end_date = date('Y-m-d H:i', strtotime($exp[1]));
        $slug = implode('-', explode(' ', strtolower($request->name)));
        
        $payload = [
            'name' => $request->name,
            'slug' => $slug,
            'option_finisher' => $request->option_finisher,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        Event::insert($payload);

        return response()->json(['message' => __('event::view.success_create_event')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        setTitle(__("event::view.detail_event"));
        return view('event::show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Event::find($id);
        $data->event_date = date('Y-m-d H:i', strtotime($data->start_date)) . ' - ' . date('Y-m-d H:i', strtotime($data->end_date));
        return response()->json(['message' => 'success', 'data' => $data]);
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
            $event_date = $request->event_date;
            $exp = explode(' - ', $event_date);
            $start_date = date('Y-m-d H:i', strtotime($exp[0]));
            $end_date = date('Y-m-d H:i', strtotime($exp[1]));
            $slug = implode('-', explode(' ', strtolower($request->name)));

            $data = Event::find($id);
            $data->name = $request->name;
            $data->slug = $slug;
            $data->option_finisher = $request->option_finisher;
            $data->start_date = $start_date;
            $data->end_date = $end_date;
            $data->save();

            return response()->json(['message' => __('view.success_update_event')]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update event'], 500);
        }
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
