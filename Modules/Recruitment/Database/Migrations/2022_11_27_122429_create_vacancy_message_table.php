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
        Schema::create('vacancy_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sender_email', 150)->comment('Sender email');
            $table->string('sender_phone', 10)->nullable();
            $table->string('receiver_email', 150);
            $table->string('receiver_phone', 10)->nullable();
            $table->text('message');
            $table->integer('vacancy_id');
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('vacancy_message');
    }
};
