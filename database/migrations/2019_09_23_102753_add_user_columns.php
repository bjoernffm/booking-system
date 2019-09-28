<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->after('name');
            $table->string('mobile')->nullable()->unique()->after('email_verified_at');
            $table->timestamp('mobile_verified_at')->nullable()->after('mobile');
            $table->renameColumn('name', 'firstname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lastname', 'mobile', 'mobile_verified_at']);
            $table->renameColumn('firstname', 'name');
        });
    }
}
