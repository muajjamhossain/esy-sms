<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRoutinesTable extends Migration
{
    public function up()
    {
        Schema::create('class_routines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('routine_day_id');
            $table->unsignedBigInteger('start_time_id');
            $table->unsignedBigInteger('end_time_id');
            $table->string('room_no')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Foreign keys
            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('school_subjects')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('routine_day_id')->references('id')->on('routine_days')->onDelete('cascade');
            $table->foreign('start_time_id')->references('id')->on('class_times')->onDelete('cascade');
            $table->foreign('end_time_id')->references('id')->on('class_times')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_routines');
    }
}
