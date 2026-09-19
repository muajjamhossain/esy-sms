<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationMinutesToExamPapersTable extends Migration
{
    public function up()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_papers', 'duration_minutes')) {
                $table->unsignedSmallInteger('duration_minutes')->nullable()->after('max_marks');
            }
        });
    }

    public function down()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            if (Schema::hasColumn('exam_papers', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }
        });
    }
}
