<?php

namespace App;

use App\Notifications\UserFrontResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'usuario';
    
    public $user_type = 'frontend';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 'salt', 'nombres', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function grupo_usuarios()
    {
        return $this->belongsToMany('App\Models\GrupoUsuarios', 'grupo_usuarios_has_usuario', 'usuario_id', 'grupo_usuarios_id');
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserFrontResetPasswordNotification($token));
        // $curl = curl_init();
        // $to = $this->email;
        // $subject = 'Reestablecer contraseña';
        // $varurl = route('password.reset', [$token,  $this->email]);
        // $rectangulo = asset('img/reportes/Rectangulo2.png');
        // $logo = asset('img/reportes/logo_reporte.png');
        // $message = 
        //         '<div>
        //         <div style="background-image: url('.$rectangulo.'); height:521px;width:512px;top: 40px;z-index: -1; margin:0 auto;">
        //         <div style="text-indent:40px;line-height:0px"><img src="'.$logo.'"/></div>
        //             <br style="line-height: 5.1;">
        //             <h1 style="width: 397px;color: #373737;font-family: Roboto, sans-serif;font-size: 25px;font-weight: bold;text-indent:40px; ">'.$subject.'</h1>
        //             <br>
        //             <div style="text-indent:40px;text-align:justify;font-family: Roboto, sans-serif;font-size: 16px;line-height: 24px;">Haga click en el siguiente link para reestablacer su contraseña: </div><p>
        //             <div style="text-indent:40px;text-align:justify;font-family: Roboto, sans-serif;font-size: 16px;line-height: 24px;"><a href="'.$varurl.'">'.$varurl.' </a></div><br> 
        //             </p>
        //             </div>
        //         </div>';

        // // Petición de envio
        // try{
        //     $data_body['from'] = env('SERVICIO_CORREO_FROM');
        //     $data_body['to'] = [$to];
        //     $data_body['subject'] = base64_encode($subject);
        //     $data_body['body'] = base64_encode($message);
        //     $data_body['category'] = env('APP_MAIN_DOMAIN', 'localhost');
        //     $data_body = json_encode($data_body);

        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => env('SERVICIO_CORREO_ENDPOINT'),
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => "",
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => "POST",
        //         CURLOPT_POSTFIELDS => $data_body,
        //         CURLOPT_HTTPHEADER => array(
        //             "Content-Type: application/json",
        //             "x-api-key: ".env('SERVICIO_CORREO_API_KEY'),
        //             "User-Agent: Mozilla/5.0"
        //         ),
        //     ));
        // }catch(Exception $e) {
        //     Log::info('Error en la petición de envío \n\n', [
        //         'error' => $e
        //     ]);
        //     return false;
        // }

        // // Ejecución de envio de correo
        // try{
        //     $response = curl_exec($curl);
        //     $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //     curl_close($curl);   
        //     if ((int)$http_status != 200) {
        //         Log::info('========> MENSAJE =======:', [
        //             'http_status' => $http_status,
        //             'message' => $response
        //         ]);
        //         throw new EnviarCorreoException();
        //     }
        // }catch(Exception $e) {
        //     Log::info('Error en la ejecución de servicio de correo \n\n', [
        //         'error' => $e
        //     ]);
        //     if ((int)$http_status != 200) {
        //         throw new EnviarCorreoException();
        //     }
        //     return false;
        // }
    }

    public function cuenta()
    {
        return $this->belongsTo('App\Models\Cuenta', 'cuenta_id');
    }

    /*
     * Scope de funcionarios
    */
    public function scopeFuncionarios($query)
    {
        return $query->where('registrado',1)->where('open_id',0);
    }
}
