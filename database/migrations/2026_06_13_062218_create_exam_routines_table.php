<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRoutinesTable extends Migration
{
    public function up()
    {
        Schema::create('exam_routines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_type_id')->comment('Exam Type ID');
            $table->unsignedBigInteger('class_id')->comment('Student Class ID');
            $table->unsignedBigInteger('subject_id')->comment('Subject ID');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_no', 50)->nullable();
            $table->integer('full_mark')->default(100);
            $table->integer('pass_mark')->default(33);
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('school_subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_routines');
    }
}
