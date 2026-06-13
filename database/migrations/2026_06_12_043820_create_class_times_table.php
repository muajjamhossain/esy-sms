<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClassTimesTable extends Migration
{
    public function up()
    {
        Schema::create('class_times', function (Blueprint $table) {
            $table->id();
            $table->string('time_slot');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('time_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // Insert default time slots
        DB::table('class_times')->insert([
            ['time_slot' => '1st Period', 'start_time' => '08:00', 'end_time' => '09:00', 'time_order' => 1, 'created_at' => now()],
            ['time_slot' => '2nd Period', 'start_time' => '09:00', 'end_time' => '10:00', 'time_order' => 2, 'created_at' => now()],
            ['time_slot' => '3rd Period', 'start_time' => '10:00', 'end_time' => '11:00', 'time_order' => 3, 'created_at' => now()],
            ['time_slot' => 'Break', 'start_time' => '11:00', 'end_time' => '11:30', 'time_order' => 4, 'created_at' => now()],
            ['time_slot' => '4th Period', 'start_time' => '11:30', 'end_time' => '12:30', 'time_order' => 5, 'created_at' => now()],
            ['time_slot' => '5th Period', 'start_time' => '12:30', 'end_time' => '13:30', 'time_order' => 6, 'created_at' => now()],
            ['time_slot' => '6th Period', 'start_time' => '14:00', 'end_time' => '15:00', 'time_order' => 7, 'created_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('class_times');
    }
}
