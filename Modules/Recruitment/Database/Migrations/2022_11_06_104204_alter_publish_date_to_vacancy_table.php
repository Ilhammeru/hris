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
        Schema::table('vacancy', function(Blueprint $table) {
            $table->timestamp('publish_date')->nullable();
            $table->integer('publish_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy', function(Blueprint $table) {
            $table->dropColumn('publish_date');
            $table->dropColumn('publish_by');
        });
    }
};
