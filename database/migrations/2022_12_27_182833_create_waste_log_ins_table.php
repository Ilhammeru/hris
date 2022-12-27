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
        Schema::create('waste_log_ins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_log_id')
                ->references('id')
                ->on('waste_logs')
                ->onDelete('CASCADE');
            $table->float('qyt')->default(0);
            $table->timestamp('date')->nullable();
            $table->string('waste_source')->nullable();
            $table->timestamp('exp')->nullable();
            $table->string('code_number');
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
        Schema::table('waste_log_ins', function(Blueprint $table) {
            $table->dropColumn(['waste_log_id']);
        });
        Schema::dropIfExists('waste_log_ins');
    }
};
