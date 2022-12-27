<?php

namespace Database\Seeders;

use App\Models\WasteCode;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class WasteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        WasteCode::truncate();
        Schema::enableForeignKeyConstraints();

        WasteCode::insert([
            [
                'code' => 'A337-1',
                'description' => 'Limbah medis yang memiliki karakteristik infeksius',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'A102-d',
                'description' => 'Accu atau batu baterai bekas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'A108-d',
                'description' => 'Limbah atau sampah yang terkontaminasi B3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'B110-d',
                'description' => 'Kain Majun',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'B104-d',
                'description' => 'Kemasan bekas limbah B3 (RT)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'B105-d',
                'description' => 'Minyak pelumas bekas (Oli)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'B107-d',
                'description' => 'Limbah Elektronik (Lampu, cathode ray tube, PCB, Karet Kawat)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
