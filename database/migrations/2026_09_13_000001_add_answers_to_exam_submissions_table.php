<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnswersToExamSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->json('answers')->nullable()->after('answer_file');
        });
    }

    public function down()
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->dropColumn('answers');
        });
    }
}
