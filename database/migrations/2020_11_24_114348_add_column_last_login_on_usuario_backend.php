<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLastLoginOnUsuarioBackend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuario_backend', function (Blueprint $table) {
            $table->boolean('is_disabled')->nullable($value = true);
            $table->dateTime('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuario_backend', function (Blueprint $table) {
            $table->dropColumn('is_disabled');
            $table->dropColumn('last_login');
        });
    }
}
