<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAdmitCardsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_admit_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('exam_type_id');
            $table->string('roll_no');
            $table->string('registration_no')->nullable();
            $table->string('admit_card_no')->unique();
            $table->date('issue_date');
            $table->string('exam_center')->nullable();
            $table->string('room_no')->nullable();
            $table->string('seat_no')->nullable();
            $table->text('instructions')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade');
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_admit_cards');
    }
}
