<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_code_id')
                ->references('id')
                ->on('waste_code')
                ->onDelete('CASCADE');
            $table->string('waste_type');
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
        Schema::table('waste_logs', function(Blueprint $table) {
            $table->dropForeign(['waste_code_id']);
        });
        Schema::dropIfExists('waste_logs');
    }
};
