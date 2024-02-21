<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('lecturing_schedules')) {
            Schema::rename('lecturing_schedules', 'lecturings');
        } else {
            Schema::create('lecturings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('audience_code', 15);
                $table->string('date')->nullable();
                $table->char('start_time', 5);
                $table->char('end_time', 5)->nullable();
                $table->string('time_text', 20)->nullable();
                $table->string('lecturer_name', 60);
                $table->string('imam_name', 60)->nullable();
                $table->string('muadzin_name', 60)->nullable();
                $table->string('title', 60)->nullable();
                $table->string('book_title', 60)->nullable();
                $table->string('book_writer', 60)->nullable();
                $table->string('book_link')->nullable();
                $table->string('video_link')->nullable();
                $table->string('audio_link')->nullable();
                $table->string('description')->nullable();
                $table->boolean('is_off')->default(0);
                $table->unsignedInteger('creator_id');
                $table->timestamps();

                $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('lecturings');
    }
};
