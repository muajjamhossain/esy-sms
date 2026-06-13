<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSeatPlansTable extends Migration
{
    public function up()
    {
        Schema::create('exam_seat_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_type_id');
            $table->unsignedBigInteger('class_id');
            $table->string('room_no');
            $table->string('row_no')->nullable();
            $table->string('column_no')->nullable();
            $table->string('seat_no');
            $table->unsignedBigInteger('student_id');
            $table->string('roll_no');
            $table->string('bench_no')->nullable();
            $table->timestamps();

            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_seat_plans');
    }
}
