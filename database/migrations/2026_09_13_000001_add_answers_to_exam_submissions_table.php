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
            $table->unsignedInteger('correct_count')->nullable()->after('answers');
            $table->unsignedInteger('wrong_count')->nullable()->after('correct_count');
            $table->unsignedInteger('unanswered_count')->nullable()->after('wrong_count');
            $table->unsignedInteger('total_questions')->nullable()->after('unanswered_count');
        });

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE exam_submissions MODIFY answer_file VARCHAR(255) NULL');
        } catch (\Throwable $e) {
            // In case driver does not support alter modify
        }
    }

    public function down()
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->dropColumn(['answers', 'correct_count', 'wrong_count', 'unanswered_count', 'total_questions']);
        });
    }
}
