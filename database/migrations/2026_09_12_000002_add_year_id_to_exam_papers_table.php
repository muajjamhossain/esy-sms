<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearIdToExamPapersTable extends Migration
{
    public function up()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->foreignId('year_id')->after('created_by')->constrained('student_years')->restrictOnDelete();
        });
    }

    public function down()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->dropForeign(['year_id']);
            $table->dropColumn('year_id');
        });
    }
}
