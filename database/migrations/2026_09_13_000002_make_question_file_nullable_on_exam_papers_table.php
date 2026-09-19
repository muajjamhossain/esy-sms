<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeQuestionFileNullableOnExamPapersTable extends Migration
{
    public function up()
    {
        try {
            DB::statement('ALTER TABLE exam_papers MODIFY question_file VARCHAR(255) NULL');
        } catch (\Throwable $e) {
            // fallback
        }
    }

    public function down()
    {
        try {
            DB::statement('ALTER TABLE exam_papers MODIFY question_file VARCHAR(255) NOT NULL');
        } catch (\Throwable $e) {
            // fallback
        }
    }
}
