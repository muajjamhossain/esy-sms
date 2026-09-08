<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionEventsTable extends Migration
{
    public function up()
    {
        Schema::create('institution_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('student_classes')->nullOnDelete();
            $table->string('title');
            $table->string('type')->default('event');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('audience')->default('everyone');
            $table->timestamps();

            $table->index(['starts_at', 'ends_at', 'audience']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('institution_events');
    }
}
