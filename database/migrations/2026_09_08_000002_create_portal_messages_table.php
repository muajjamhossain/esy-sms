<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortalMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('portal_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('portal_conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'read_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_messages');
    }
}
