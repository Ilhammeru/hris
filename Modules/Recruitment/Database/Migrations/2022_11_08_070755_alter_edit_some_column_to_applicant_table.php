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
        Schema::table('applicant', function(Blueprint $table) {
            $table->text('application_letter')->nullable()->change();
            $table->timestamp('reject_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant', function(Blueprint $table) {
            $table->text('application_letter')->change();
            $table->timestamp('reject_at')->change();
        });
    }
};
