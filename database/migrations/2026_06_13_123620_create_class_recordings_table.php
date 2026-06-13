<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRecordingsTable extends Migration
{
    public function up()
    {
        Schema::create('class_recordings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('online_class_id');
            $table->string('recording_url');
            $table->string('recording_type')->nullable();
            $table->string('file_size')->nullable();
            $table->dateTime('recording_date');
            $table->timestamps();

            $table->foreign('online_class_id')->references('id')->on('online_classes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_recordings');
    }
}
