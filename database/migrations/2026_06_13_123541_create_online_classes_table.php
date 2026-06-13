<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineClassesTable extends Migration
{
    public function up()
    {
        Schema::create('online_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->comment('Student Class ID');
            $table->unsignedBigInteger('subject_id')->comment('Subject ID');
            $table->unsignedBigInteger('teacher_id')->comment('Teacher (User) ID');
            $table->string('topic');
            $table->text('description')->nullable();
            $table->string('zoom_meeting_id');
            $table->text('join_url');
            $table->text('start_url')->nullable();
            $table->string('password')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration')->comment('Duration in minutes');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->tinyInteger('status')->default(1)->comment('0=Cancelled, 1=Scheduled, 2=Ongoing, 3=Completed');
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('school_subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('online_classes');
    }
}
