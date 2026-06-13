<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoutine;

class ClassRoutineSeeder extends Seeder
{
    public function run()
    {
        $routines = [
            // Class 1 (Play) routines
            ['class_id' => 1, 'subject_id' => 1, 'employee_id' => 2, 'routine_day_id' => 1, 'start_time_id' => 1, 'end_time_id' => 2, 'room_no' => '101'],
            ['class_id' => 1, 'subject_id' => 2, 'employee_id' => 3, 'routine_day_id' => 1, 'start_time_id' => 2, 'end_time_id' => 3, 'room_no' => '101'],
            ['class_id' => 1, 'subject_id' => 3, 'employee_id' => 2, 'routine_day_id' => 2, 'start_time_id' => 1, 'end_time_id' => 2, 'room_no' => '102'],

            // Class 2 (One) routines
            ['class_id' => 2, 'subject_id' => 1, 'employee_id' => 2, 'routine_day_id' => 1, 'start_time_id' => 1, 'end_time_id' => 2, 'room_no' => '201'],
            ['class_id' => 2, 'subject_id' => 2, 'employee_id' => 3, 'routine_day_id' => 1, 'start_time_id' => 2, 'end_time_id' => 3, 'room_no' => '201'],
            ['class_id' => 2, 'subject_id' => 3, 'employee_id' => 2, 'routine_day_id' => 2, 'start_time_id' => 1, 'end_time_id' => 2, 'room_no' => '202'],
        ];

        foreach ($routines as $routine) {
            ClassRoutine::create($routine);
        }
    }
}
