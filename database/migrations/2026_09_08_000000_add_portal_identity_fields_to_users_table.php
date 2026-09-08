<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortalIdentityFieldsToUsersTable extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('users', 'usertype')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('usertype')->nullable()->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->nullable()->after('usertype');
            });
        }
    }

    public function down()
    {
        // Keep existing identity columns intact when rolling back portal tables.
    }
}
