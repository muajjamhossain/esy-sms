<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortalConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('portal_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index(['participant_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_conversations');
    }
}
