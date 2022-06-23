<?php

namespace App\Listeners;

use App\Events\UsuarioInactivoEvent;
use App\Models\HistorialModificacion;
use App\Models\Proceso;
use App\Models\UsuarioBackend;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserBlockedException;


class LogSuccessfulLogin
{
    public $offlineDays;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // esto podría ser una variable de entorno
        $this->offlineDays = env('OFFLINE_DAYS', 90);
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        // soy front no CU ni anonimo
        if ($user->user_type == 'frontend') {
            if ($user->registrado && !$user->open_id) {
                $this->verificarUsuarioDeshabilitado($user);
                $this->verificaUltimoLogin($user);
            }
        } else {
            // soy backend o manager
            $this->verificarUsuarioDeshabilitado($user);
            $this->verificaUltimoLogin($user);
        }
    }

    public function verificaUltimoLogin($user) {

        $currentDate = Carbon::now('America/Santiago');
        $maxTimeOffLine = Carbon::now('America/Santiago')->subDays($this->offlineDays);
        
        if (null != $user->last_login) {
            
            $lastLoginDate = new Carbon($user->last_login);    

            if ($lastLoginDate->isBefore($maxTimeOffLine)) {

                throw new UserBlockedException(
                    'El usuario no se ha conectado hace mas de 90 dias',
                    $user
                );

            } else {
                // El usuario se ha conectado en menos de 90 dias
                $user->last_login = $currentDate->format('y-m-d H:i:s');
                $user->save();
            }
        } else {
            // si pase los filtros de restriccion, actualizo mi fecha de last_login y continuo...
            $user->last_login = $currentDate->format('y-m-d H:i:s');
            $user->save();
        }
    }

    public function verificarUsuarioDeshabilitado($user) {
        if ($user->is_disabled) {
            throw new UserBlockedException(
                'El usuario se encuentra deshabilitado',
                $user
            );
        }
    }
}
