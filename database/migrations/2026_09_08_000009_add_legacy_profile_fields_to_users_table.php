<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLegacyProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'mobile')) $table->string('mobile')->nullable();
            if (! Schema::hasColumn('users', 'address')) $table->string('address')->nullable();
            if (! Schema::hasColumn('users', 'gender')) $table->string('gender')->nullable();
            if (! Schema::hasColumn('users', 'religion')) $table->string('religion')->nullable();
            if (! Schema::hasColumn('users', 'fname')) $table->string('fname')->nullable();
            if (! Schema::hasColumn('users', 'mname')) $table->string('mname')->nullable();
            if (! Schema::hasColumn('users', 'dob')) $table->date('dob')->nullable();
            if (! Schema::hasColumn('users', 'id_no')) $table->string('id_no')->nullable();
            if (! Schema::hasColumn('users', 'code')) $table->string('code')->nullable();
            if (! Schema::hasColumn('users', 'image')) $table->string('image')->nullable();
            if (! Schema::hasColumn('users', 'designation_id')) $table->unsignedBigInteger('designation_id')->nullable();
            if (! Schema::hasColumn('users', 'salary')) $table->decimal('salary', 12, 2)->nullable();
            if (! Schema::hasColumn('users', 'join_date')) $table->date('join_date')->nullable();
            if (! Schema::hasColumn('users', 'status')) $table->tinyInteger('status')->default(1);
        });
    }

    public function down()
    {
        // Preserve legacy profile data when rolling back newer feature migrations.
    }
}
