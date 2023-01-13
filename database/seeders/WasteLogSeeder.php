<?php

namespace Database\Seeders;

use App\Models\WasteCode;
use App\Models\WasteLog;
use App\Models\WasteLogIn;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class WasteLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        WasteLog::truncate();
        WasteLogIn::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
