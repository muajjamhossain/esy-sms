<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryLoansTable extends Migration
{
    public function up()
    {
        Schema::create('library_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('library_books')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->date('issued_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();
            $table->timestamps();

            $table->index(['borrower_id', 'returned_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('library_loans');
    }
}
