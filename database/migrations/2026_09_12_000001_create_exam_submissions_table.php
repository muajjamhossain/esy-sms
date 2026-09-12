<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_paper_id')->constrained('exam_papers')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('answer_file');
            $table->decimal('ai_marks', 6, 2)->nullable();
            $table->decimal('final_marks', 6, 2)->nullable();
            $table->text('ai_feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['exam_paper_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_submissions');
    }
}
