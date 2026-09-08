<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('student_classes')->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('audience')->default('everyone');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['audience', 'published_at', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
