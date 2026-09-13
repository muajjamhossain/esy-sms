<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMcqFieldsToExamPapersTable extends Migration
{
    public function up()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->json('questions')->nullable()->after('answer_key_file');
            $table->boolean('is_published')->default(false)->after('questions');
            $table->timestamp('published_at')->nullable()->after('is_published');
        });
    }

    public function down()
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->dropColumn(['questions', 'is_published', 'published_at']);
        });
    }
}
