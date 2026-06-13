<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRoutineDaysTable extends Migration
{
    public function up()
    {
        Schema::create('routine_days', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('bn_name')->nullable();
            $table->integer('day_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // Insert default days
        DB::table('routine_days')->insert([
            ['name' => 'Saturday', 'bn_name' => 'শনিবার', 'day_order' => 1, 'created_at' => now()],
            ['name' => 'Sunday', 'bn_name' => 'রবিবার', 'day_order' => 2, 'created_at' => now()],
            ['name' => 'Monday', 'bn_name' => 'সোমবার', 'day_order' => 3, 'created_at' => now()],
            ['name' => 'Tuesday', 'bn_name' => 'মঙ্গলবার', 'day_order' => 4, 'created_at' => now()],
            ['name' => 'Wednesday', 'bn_name' => 'বুধবার', 'day_order' => 5, 'created_at' => now()],
            ['name' => 'Thursday', 'bn_name' => 'বৃহস্পতিবার', 'day_order' => 6, 'created_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('routine_days');
    }
}
