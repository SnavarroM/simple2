<?php

namespace App\Listeners;

use App\Models\HistorialModificacion;
use App\Models\Proceso;
use App\Models\UsuarioBackend;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserBlockedException;

class LogFailedAuthenticationAttempt
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
        if(request()->is('backend/*'))
        {
            $user = \App\Models\UsuarioBackend::where('email',request()->input('email'))->first();
        }elseif(request()->is('manager/*'))
        {
            $user = \App\Models\UsuarioManager::where('usuario',request()->input('usuario'))->first();
        }
        else
        {
            $user = \App\User::where('email',request()->input('email'))->first();
        }
        
        // Verifico que el usuario exista
        // soy front no CU ni anonimo
        if($user !== null){
            if ($user->user_type == 'frontend') {
                if ($user->registrado && !$user->open_id) {
                    $this->verificarUsuarioDeshabilitado($user);
                    $this->verificaUltimoLogin($user);
                }
            } else {
                $this->verificarUsuarioDeshabilitado($user);
                $this->verificaUltimoLogin($user);
            }
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
