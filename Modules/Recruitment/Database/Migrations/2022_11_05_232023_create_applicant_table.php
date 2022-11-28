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
        Schema::create('applicant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('vacancy_id')->unsigned();
            $table->string('expectation_salary')->default(0);
            $table->text('cv');
            $table->text('application_letter');
            $table->integer('progress_recruitment');
            $table->timestamp('reject_at')->comment('Timestamp to define when user has been reject by HR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant');
    }
};
