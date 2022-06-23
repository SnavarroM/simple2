<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsuarioAllAddLastLoginNow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE usuario_manager SET last_login=NOW() WHERE id > 0");
        DB::statement("UPDATE usuario_backend SET last_login=NOW() WHERE id > 0");
        DB::statement("UPDATE usuario SET last_login=NOW() WHERE open_id=0 AND registrado=1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE usuario_manager SET last_login=NULL WHERE id > 0");
        DB::statement("UPDATE usuario_backend SET last_login=NULL WHERE id > 0");
        DB::statement("UPDATE usuario SET last_login=NULL WHERE open_id=0 AND registrado=1");
    }
}
