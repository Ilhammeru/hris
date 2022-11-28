<?php

namespace Modules\Recruitment\Http\Controllers;

use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Company\Entities\Department;
use Modules\Company\Entities\Division;
use Modules\Employee\Entities\Employee;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\Recruitment;
use Modules\Recruitment\Entities\VacancyMessage;
use Modules\Recruitment\Events\MessageFromVacancy;
use Modules\Recruitment\Http\Services\RecruitmentService;
use Pusher\Pusher;
use Spatie\Tags\Tag;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('recruitment::view.recruitment'));
        return view('recruitment::index');
    }

    /**
     * Function to get all data for datatable
     */
    public function ajax()
    {
        $data = Recruitment::with(['department:id,name', 'division:id,name'])
            ->get();
        return DataTables::of($data)
            ->editColumn('title', function($d) {
                return '<a href="'. route('employee.recruitment.show', $d->id) .'">'. ucfirst($d->title) .'</a>';
            })
            ->addColumn('start_date', function($d) {
                return date('d M Y', strtotime($d->start));
            })
            ->addColumn('end_date', function($d) {
                return date('d M Y', strtotime($d->end));
            })
            ->editColumn('department_id', function($d) {
                return ucfirst($d->department->name);
            })
            ->editColumn('division_id', function($d) {
                return ucfirst($d->division->name);
            })
            ->editColumn('created_by', function($d) {
                return $d->created_by();
            })
            ->addColumn('action', function($d) {
                return '<a class="btn btn-secondary c-btn-sm" href="'. route('employee.recruitment.edit', $d->id) .'"><i class="bi bi-pen-fill p-0"></i></a>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash-fill p-0"></i></button>';
            })
            ->rawColumns(['start_date', 'end_date', 'department_id', 'division_id','action', 'title'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        setTitle(__('recruitment::view.create_recruitment'));

        $model = new Recruitment();
        $departments = Department::all();
        $divisions = Division::all();
        $working_type = $model->getWorkingType();
        $job_type = $model->getJobType();
        $data = null;

        return view('recruitment::create', compact('departments', 'divisions', 'working_type', 'job_type', 'data'));
    }

    /**
     * Function to get all available tags
     * @return Response
     */
    public function getTag($id)
    {
        $tags = Tag::all();
        $selected_tags = [];

        if ($id != 0) {
            $current_data = Recruitment::find($id);
            $selected_tags = $current_data->tags;
            
            $tags = collect($tags)->map(function($item) use($selected_tags) {
                $item->active = false;
                collect($selected_tags)->map(function($i) use($item) {
                    if ($item->id == $i->id) {
                        $item->active = true;
                    }
                });
                return $item;
            })->values();
            $selected_tags = collect($selected_tags)->pluck('name')->all();
        }

        return response()->json(['data' => $tags, 'selected_tags' => $selected_tags]);
    }

    /**
     * Function to store tags
     * @param Request
     * @param string tag
     * @return Response
     */
    public function storeTag(Request $request)
    {
        try {
            $name = $request->tag;

            $save = Tag::findOrCreate($name, 'recruitment');
            return response()->json(['data' => $save, 'message' => 'Tag successfully create']);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
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
                'title' => 'required',
                'dates' => 'required',
                'department' => 'required',
                'division' => 'required',
                'needs' => 'required',
                'working_type' => 'required',
                'job_type' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors()->all());
            }

            $dates = $request->dates;
            $exp = explode(' - ', $dates);
            $start_date = date('Y-m-d', strtotime($exp[0]));
            $end_date = date('Y-m-d', strtotime($exp[1]));

            $tags = $request->tags;

            $vacancy = new Recruitment();
            $vacancy->title = $request->title;
            $vacancy->description = $request->description;
            $vacancy->needs = $request->needs;
            $vacancy->department_id = $request->department;
            $vacancy->division_id = $request->division;
            $vacancy->start = $start_date;
            $vacancy->end = $end_date;
            $vacancy->job_type_id = $request->job_type;
            $vacancy->working_type = $request->working_type;
            $vacancy->is_active = $request->publish;
            $vacancy->created_by = auth()->user()->id;
            $vacancy->save();

            $vacancy->syncTagsWithType($tags, 'recruitment');

            return response()->json(['message' => 'Success create a vacancy']);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        setTitle(__('recruitment::view.detail_vacancy'));

        $data = Recruitment::with('applicants')->find($id);
        $publish_by = $data->publish();
        $tags = $data->tags;
        $url_general = route('employee.recruitment.show.general', $id);
        $job_type = $data->getJobTypeById($data->job_type_id);
        $work_type = $data->getWorkingTypeById($data->working_type);
        $remaining_time = Carbon::createFromDate(date('Y', strtotime($data->end)), date('m', strtotime($data->end)), date('d', strtotime($data->end)))->diffForHumans();
        // event(new MessageFromVacancy('ilham'));
        return view('recruitment::show', compact('data', 'job_type', 'work_type', 'tags', 'remaining_time', 'publish_by', 'url_general'));
    }

    public function sendMessage(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = Recruitment::find($id);
            // TODO: get the pic detail

            // dummy pic detail
            $receiver_mail = 'gumilang.dev@gmail.com';
            $model = new VacancyMessage();
            $model->sender_email = $request->email;
            $model->sender_phone = $request->phone;
            $model->message = $request->message;
            $model->vacancy_id = $id;
            $model->receiver_email = $receiver_mail;
            $model->save();

            setMessageNotif();

            DB::commit();
            return response()->json(['message' => "Your message is delivered. We'll be in touch asap"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to show list applicant of given vacancy id
     * @param int id
     * @return Renderable
     */
    public function detailVacancyApplicant($id)
    {
        setTitle(__('recruitment::view.detail_vacancy_applicant'));
        $data = Applicant::where('vacancy_id', $id)->get();
        return view('recruitment::detail-vacancy-applicant', compact('data', 'id'));
    }

    /**
     * Function to get all data applicant for AJAX request
     * @return DataTables
     */
    public function ajaxApplicant($id)
    {
        $data = Applicant::with('detail:id,name,email,phone,address')
            ->where('vacancy_id', $id)
            ->get();
        return DataTables::of($data)
            ->addColumn('fullname', function($d) {
                return ucfirst($d->detail->name);
            })
            ->addColumn('email', function($d) {
                return $d->detail->email;
            })
            ->addColumn('phone', function($d) {
                return $d->detail->phone;
            })
            ->addColumn('address', function($d) {
                return $d->detail->address;
            })
            ->addColumn('cv', function($d) {
                return '<a href="'. route('employee.recruitment.cv.applicant', $d->id) .'" target="_blank">'. __('view.see_details') .'</a>';
            })
            ->addColumn('action', function($d) {
                return '
                    <button class="btn btn-sm btn-success" id="applicant-accept-btn" type="button" onclick="acceptApplicant('. $d->id .', '. $d->vacancy_id .')" data-tippy-content="Accept this Applicant"><i class="bi bi-check-lg p-0" style="line-height: 0;"></i></button>
                    <button class="btn btn-sm btn-danger" id="applicant-accept-btn" type="button" data-tippy-content="Reject this Applicant"><i class="bi bi-x p-0" style="line-height: 0;"></i></button>
                ';
            })
            ->rawColumns(['fullname', 'cv', 'email', 'phone', 'action'])
            ->make(true);
    }

    /**
     * Function to accept applicant to the next stage of recruitment
     * @param int id
     * @param int vacancy_id
     * @return Response
     */
    public function acceptApplicant(Request $request)
    {
        try {
            $id = $request->id;
            $vacancy_id = $request->id;
            $service = new RecruitmentService();

            $data = Applicant::select('progress_recruitment')
                ->find($id);
            $do = $service->acceptApplicant($data);
            
            
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Function to show the PDF's file of CV from given applicant ID
     * @return Renderable
     */
    public function viewApplicantCv($id)
    {
        $applicant = Applicant::select('cv')
            ->find($id);

        return response()->file(public_path() . '/uploads/applicant/' . $applicant->cv);
    }

    /**
     * Function to show detail vacancy for general user
     * @param int id
     * @return Renderable
     */
    public function showForGeneral($id)
    {
        $data = Recruitment::find($id);

        $pageTitle = $data->title;

        $publish_by = $data->publish();
        $tags = $data->tags;
        $job_type = $data->getJobTypeById($data->job_type_id);
        $work_type = $data->getWorkingTypeById($data->working_type);
        $remaining_time = Carbon::createFromDate(date('Y', strtotime($data->end)), date('m', strtotime($data->end)), date('d', strtotime($data->end)))->diffForHumans();
        return view('recruitment::show-general', compact('data', 'job_type', 'work_type', 'tags', 'remaining_time', 'publish_by', 'pageTitle'));
    }

    /**
     * Function to show apply form
     * @param int id
     * @return Renderable
     */
    public function applyForm($id)
    {
        $data = Recruitment::find($id);
        $pageTitle = $data->title;
        $provinces = \Indonesia::allProvinces();
        
        return view('recruitment::apply-form', compact('provinces', 'pageTitle', 'id'));
    }

    /**
     * Function to save all applicant data
     * @return Response
     */
    public function applyJob(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'fullname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
                'village' => 'required',
                'expectation_salary' => 'required',
                'curriculum_vitae' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json($validation->errors()->all(), 500);
            }

            $vacancy = Recruitment::find($id);

            $employee = new Employee();
            $employee->employee_code = create_employee_code();
            $employee->name = $request->fullname;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->nik = '0';
            $employee->division_id = $vacancy->division_id;
            $employee->department_id = $vacancy->department_id;
            $employee->address = $request->address;
            $employee->village_id = $request->village;
            $employee->district_id = $request->district;
            $employee->city_id = $request->city;
            $employee->province_id = $request->province;
            $employee->status = Employee::VACCANT;
            $employee->save();

            $file_name = implode('_', explode(' ', strtolower($request->fullname))) . '_' . $id . '_' . '.pdf';
            // $file = $request->file('curriculum_vitae')->storeAs('applicant', $file_name);
            $file = Storage::disk('public')->putFileAs('applicant', $request->curriculum_vitae, $file_name);
            $applicant = new Applicant();
            $applicant->employee_id = $employee->id;
            $applicant->vacancy_id = $id;
            $applicant->expectation_salary = $request->expectation_salary;
            $applicant->cv = $file_name;
            $applicant->progress_recruitment = 1;
            $applicant->save();

            DB::commit();
            return response()->json(['data' => 'Success save applicant']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Function to publish vacancy
     * @param int id
     * @return Response
     */
    public function publish($id)
    {
        DB::beginTransaction();
        try {
            $data = Recruitment::select('publish_date', 'publish_by', 'id', 'is_active')
                ->find($id);
            $data->publish_date = Carbon::now();
            $data->publish_by = auth()->user()->id;
            $data->is_active = true;
            $data->save();

            $res = [
                'publish_date' => date('d M Y', strtotime($data->publish_date)),
                'publish_by' => $data->publish(),
                'id' => $data->id,
                'is_active' => $data->is_active
            ];
            DB::commit();
            return response()->json(['message' => 'Vacancy now publish to public', 'data' => $res]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        setTitle(__('recruitment::view.create_recruitment'));
        $model = Recruitment::find($id);
        $tags = $model->tags;
        $departments = Department::all();
        $divisions = Division::all();
        $working_type = $model->getWorkingType();
        $job_type = $model->getJobType();
        $data = $model;

        return view('recruitment::edit', compact('departments', 'divisions', 'working_type', 'job_type', 'data', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $vacancy = Recruitment::find($id);
        $current_tags = $vacancy->tags;

        DB::beginTransaction();
        try {
            $validate = Validator::make($request->all(), [
                'title' => 'required',
                'dates' => 'required',
                'department' => 'required',
                'division' => 'required',
                'needs' => 'required',
                'working_type' => 'required',
                'job_type' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors()->all());
            }

            $dates = $request->dates;
            $exp = explode(' - ', $dates);
            $start_date = date('Y-m-d', strtotime($exp[0]));
            $end_date = date('Y-m-d', strtotime($exp[1]));

            $tags = $request->tags;

            // detach tag
            foreach ($current_tags as $ct) {
                if (count($current_tags) > 0) {
                    $vacancy->detachTag(Tag::find($ct->id));
                }
            }

            /**
             * TODO: create validation when this user unpublished this vacancy and already have a applicants, return error
             */

            $vacancy->title = $request->title;
            $vacancy->description = $request->description;
            $vacancy->needs = $request->needs;
            $vacancy->department_id = $request->department;
            $vacancy->division_id = $request->division;
            $vacancy->start = $start_date;
            $vacancy->end = $end_date;
            $vacancy->job_type_id = $request->job_type;
            $vacancy->working_type = $request->working_type;
            $vacancy->is_active = $request->publish;
            $vacancy->created_by = auth()->user()->id;
            $vacancy->save();

            if ($tags) {
                $vacancy->syncTagsWithType($tags, 'recruitment');
            }

            DB::commit();
            return response()->json(['message' => 'Success create a vacancy', 'data' => route('employee.recruitment.show', $vacancy->id)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
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
            $data = Recruitment::find($id);
            $data->delete();

            return response()->json(['message' => 'Success delete vacancy']);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
