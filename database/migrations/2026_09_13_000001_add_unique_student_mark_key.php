<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueStudentMarkKey extends Migration
{
    public function up()
    {
        $duplicates = DB::table('student_marks')
            ->select('student_id', 'year_id', 'class_id', 'assign_subject_id', 'exam_type_id', DB::raw('MAX(id) as keep_id'), DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'year_id', 'class_id', 'assign_subject_id', 'exam_type_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('student_marks')
                ->where('student_id', $duplicate->student_id)
                ->where('year_id', $duplicate->year_id)
                ->where('class_id', $duplicate->class_id)
                ->where('assign_subject_id', $duplicate->assign_subject_id)
                ->where('exam_type_id', $duplicate->exam_type_id)
                ->where('id', '<>', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('student_marks', function (Blueprint $table) {
            $table->unique(
                ['student_id', 'year_id', 'class_id', 'assign_subject_id', 'exam_type_id'],
                'student_marks_unique_result'
            );
        });
    }

    public function down()
    {
        Schema::table('student_marks', function (Blueprint $table) {
            $table->dropUnique('student_marks_unique_result');
        });
    }
}
