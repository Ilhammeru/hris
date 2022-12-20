<?php

namespace Modules\Employee\Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Company\Entities\Division;
use Modules\Employee\Entities\Employee;
use Spatie\Permission\Models\Role;

class EmployeeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $ehs = Division::select('id', 'department_id')
                ->where('name', 'ehs')
                ->first();
            $recruitment = Division::select('id', 'department_id')
                ->where('name', 'Recruitment and Development')
                ->first();
            $city_id = 156; // jakarta city
            $city = \Indonesia::findCity($city_id, ['province', 'districts.villages']);
            $province_id = $city->province->id;
            $district_id = $city->districts[0]->id;
            $village_id = $city->districts[0]->villages[0]->id;
            $hrd_role = Role::findByName('hrd');

            // EHS PERMANENT TYPE
            $employee_1 = Employee::insertGetId([
                'employee_code' => 'EMP-00003',
                'name' => 'Ricky Harun',
                'email' => 'ricky@gmail.com',
                'phone' => '085795795795',
                'nik' => '3573042405960004',
                'division_id' => $ehs->id, // ehs division
                'department_id' => $ehs->department_id,
                'address' => 'Jl. bagong No. 5',
                'village_id' => $village_id,
                'district_id' => $district_id,
                'city_id' => $city_id,
                'province_id' => $province_id,
                'account_number' => '02214458555',
                'bank_name' => 'BNI',
                'social_media' => json_encode([
                    'instagram' => 'ihmrgm',
                    'twitter' => 'ilhammrgmlg'
                ]),
                'bpjs_ketenagakerjaan' => null,
                'bpjs_kesehatan' => null,
                'npwp' => null,
                'is_active' => true,
                'meta_experience' => json_encode([
                    [
                        'position' => 'EHS',
                        'company' => 'PT. Abal-abal',
                        'time_start' => '2021-01-01',
                        'time_end' => '2022-01-05'
                    ],
                    [
                        'position' => 'EHS',
                        'company' => 'PT. Jayakarta',
                        'time_start' => '2020-01-01',
                        'time_end' => '2020-12-05'
                    ],
                ]),
                'meta_education' => json_encode([
                    [
                        'name' => 'SDN Maju 4',
                        'type' => 'primary-school',
                        'graduate_year' => '2008'
                    ],
                    [
                        'type' => 'middle-school',
                        'name' => 'SMPN 100 Kurang',
                        'graduate_year' => '2011'
                    ],
                    [
                        'type' => 'high-school',
                        'name' => 'SMAN 100 Lagi',
                        'graduate_year' => '2014'
                    ],
                    [
                        'type' => 'university-1',
                        'name' => 'Universal Studio',
                        'graduate_year' => '2030',
                    ],
                    [
                        'type' => 'university-2',
                        'name' => null,
                        'graduate_year' => null,
                    ],
                ]),
                'mother_name' => 'Sutikem',
                'status' => 1, // permanent
                'internship_date' => date('Y-m-d', strtotime('2021-10-05')),
                'apply_vaccant_date' => date('Y-m-d', strtotime('2021-09-01')),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // RECRUITMENT INTERNSHIP TYPE AND FRESHGRAD
            $employee_2 = Employee::insertGetId([
                'employee_code' => 'EMP-00004',
                'name' => 'Joko wi',
                'email' => 'joko@gmail.com',
                'phone' => '085795795795',
                'nik' => '3573042405960004',
                'division_id' => $recruitment->id, // recruitment division
                'department_id' => $recruitment->department_id,
                'address' => 'Jl. bongsor No. 5',
                'village_id' => $village_id,
                'district_id' => $district_id,
                'city_id' => $city_id,
                'province_id' => $province_id,
                'account_number' => '01145874548',
                'bank_name' => 'BNI',
                'social_media' => json_encode([
                    'instagram' => 'ihmrgm',
                    'twitter' => 'ilhammrgmlg'
                ]),
                'bpjs_ketenagakerjaan' => '0029111222',
                'bpjs_kesehatan' => null,
                'npwp' => null,
                'is_active' => true,
                'meta_experience' => NULL,
                'meta_education' => json_encode([
                    [
                        'name' => 'SDN Maju 4',
                        'type' => 'primary-school',
                        'graduate_year' => '2008'
                    ],
                    [
                        'type' => 'middle-school',
                        'name' => 'SMPN 100 Kurang',
                        'graduate_year' => '2011'
                    ],
                    [
                        'type' => 'high-school',
                        'name' => 'SMAN 100 Lagi',
                        'graduate_year' => '2014'
                    ],
                    [
                        'type' => 'university-1',
                        'name' => 'Universal Studio',
                        'graduate_year' => '2030',
                    ],
                    [
                        'type' => 'university-2',
                        'name' => null,
                        'graduate_year' => null,
                    ],
                ]),
                'mother_name' => 'Suryani',
                'status' => 2, // intern
                'internship_date' => date('Y-m-d', strtotime('2022-10-05')),
                'apply_vaccant_date' => date('Y-m-d', strtotime('2022-09-20')),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // create user
            $user_1 = User::insertGetId([
                'email' => 'ricky@gmail.com',
                'password' => Hash::make('ricky123'),
                'role' => $hrd_role->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $user_2 = User::insertGetId([
                'email' => 'joko@gmail.com',
                'password' => Hash::make('joko123'),
                'role' => $hrd_role->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // update user_id in employee table
            Employee::where('id', $employee_1)
                ->update(['user_id' => $user_1]);
            Employee::where('id', $employee_2)
                ->update(['user_id' => $user_2]);

            // assign role
            $data_user_1 = User::find($user_1);
            $data_user_2 = User::find($user_2);
            $data_user_1->assignRole($hrd_role);
            $data_user_2->assignRole($hrd_role);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug($th->getMessage());
        }
    }
}
