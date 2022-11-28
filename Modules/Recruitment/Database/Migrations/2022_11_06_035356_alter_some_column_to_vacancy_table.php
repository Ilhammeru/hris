<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vacancy', function(Blueprint $table){
            $table->tinyInteger('job_type_id')->unsigned();
            $table->tinyInteger('working_type')->comment('1 for WFO, 2 for WFA, 3 for Hybrid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy', function(Blueprint $table){
            $table->dropColumn('job_type_id');
            $table->dropColumn('working_type');
        });
    }
};
