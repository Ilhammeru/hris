<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('nik');
            $table->integer('division_id');
            $table->integer('department_id');
            $table->string('address');
            $table->integer('village_id');
            $table->integer('district_id');
            $table->integer('city_id');
            $table->integer('province_id');
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->json('social_media')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('npwp')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('meta_experience')->nullable();
            $table->json('meta_education')->nullable();
            $table->string('mother_name')->nullable();
            $table->tinyInteger('status')->comment('1 for permanent, 2 for internship, 3 for vaccant, 4 for reject applicant');
            $table->timestamp('internship_date')->nullable()->comment('Date when user assign the internship letter');
            $table->timestamp('permanent_date')->nullable()->comment('Date when user assign the permanent letter');
            $table->timestamp('apply_vaccant_date')->nullable()->comment('Date when user apply for available vacancy');
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
        Schema::dropIfExists('employees');
    }
};
