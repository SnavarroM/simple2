<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsuarioBackendModificarRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,'operacion,reportes','seguimiento,gestion') WHERE rol = 'operacion,reportes'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,',reportes','') WHERE rol LIKE '%,reportes%'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,',reportes,','') WHERE rol LIKE '%reportes,%'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,'reportes','gestion') WHERE rol LIKE '%reportes%'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,',operacion','') WHERE rol LIKE '%,operacion%'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,'operacion,','') WHERE rol LIKE '%operacion,%'");
        DB::statement("UPDATE usuario_backend SET rol= replace(rol,'operacion','seguimiento') WHERE rol LIKE '%operacion%'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
