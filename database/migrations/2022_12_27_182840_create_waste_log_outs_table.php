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
        Schema::create('waste_log_outs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_log_id')
                ->references('id')
                ->on('waste_logs')
                ->onDelete('CASCADE');
            $table->foreignId('waste_log_in_id')
                ->references('id')
                ->on('waste_log_ins')
                ->onDelete('CASCADE');
            $table->float('qty')->default(0);
            $table->timestamp('date')->nullable();
            $table->string('target_out')->nullable();
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
        Schema::table('waste_log_outs', function(Blueprint $table) {
            $table->dropColumn(['waste_log_id', 'waste_log_in_id']);
        });
        Schema::dropIfExists('waste_log_outs');
    }
};
