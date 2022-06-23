<?php

namespace App\Listeners;

use App\Models\HistorialModificacion;
use App\Models\Proceso;
use App\Models\UsuarioBackend;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegistraModificacionListener
{
    private $user;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (isset($this->user->user_type) && $this->user->user_type == 'backend') {

            try {
                $proceso = Proceso::find($event->proceso_id);
            } catch(Exception $e) {
                Log::info('Error al registrar una modificacion de Flujo', [
                    'error' => $e
                ]);
            }

            if ($proceso) {
                try {
                    $record = new HistorialModificacion();
                    $record->description = $event->description;
                    $record->created_at = Carbon::now();
                    $record->usuario_id = $this->user->id;
                    $record->proceso_id = $event->proceso_id;
                    $record->save();
                    
                    /**
                     * Inicio actualización de etapas marcadas con error cuando están configuradas con vencimiento
                     */
                    $tareas_con_vencimiento = Tarea::where('proceso_id',$event->proceso_id)->where('vencimiento',1)->count();
                    if($tareas_con_vencimiento > 0)
                    {
                        Log::info('Inicio actualización de etapas vencidas con error proceso '.$event->proceso_id);
                        DB::statement('UPDATE etapa SET vencimiento_avance = ? WHERE tramite_id IN (SELECT id FROM tramite WHERE proceso_id = ?) AND vencimiento_avance = ?',['corregido',$event->proceso_id,'error']);
                        DB::commit();
                        Log::info('Fin actualización de etapas vencidas con error'.$event->proceso_id);
                    }
                    /**
                     * Fin actualización de etapas marcadas con error cuando están configuradas con vencimiento
                     */

                    $proceso->updated_at = Carbon::now();
                    $proceso->save();
                    Log::info('=== Se ha registrado una modificacion de Flujo  ========>', [
                        'usuario' => $this->user->email,
                        'entidad' => $event->description
                    ]);

                } catch(Exception $e) {
                    Log::info('Error al registrar una modificacion de Flujo', [
                        'error' => $e
                    ]);
                }
            }
        }
    }
}
