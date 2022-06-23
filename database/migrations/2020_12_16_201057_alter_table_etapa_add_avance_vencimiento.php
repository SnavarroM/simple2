<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEtapaAddAvanceVencimiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Dejamos los siguientes estados y su descripción:
         * 
         * pendiente: cada vez que una etapa se encuentre configurada con vencimiento quedará en estado pendiente para que la lambda la considere al momento de avanzar.
         * error: si al momento de avanzar la etapa se produjo una excepción, esta quedará con error hasta que la institución revise si hay problemas en el proceso y sea solucionado.
         * corregido: una vez que la institución dueña del proceso corrija este último, será considerada por la lambda para intentar avanzar una etapa vencida.
         * avanzado: la etapa vencida pudo avanzar exitosamente.
         */
        Schema::table('etapa', function (Blueprint $table) {
            $table->enum('vencimiento_avance', ['pendiente','error','corregido', 'avanzado'])->after('vencimiento_at')->nullable();
        });
        /**
         * Al momento de ejecutar la migración todas las etapas que involucren vencimiento quedarán pendientes para que la lambda pueda ejecutar las etapas vencidas
         */
        DB::statement("UPDATE etapa SET vencimiento_avance='pendiente' WHERE vencimiento_at is not null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etapa', function (Blueprint $table) {
            $table->dropColumn('vencimiento_avance');
        });
    }
}
