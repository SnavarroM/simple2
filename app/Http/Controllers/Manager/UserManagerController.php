<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\UsuarioManager;
use App\Rules\SafetyPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserManagerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users_manager= DB::table('usuario_manager')->get();

        $data['users_manager'] = $users_manager;        
        $data['title'] = 'Mantenedor Usuarios Manager';
        $data['content'] = view('manager.users_manager.index', $data);

        return view('layouts.manager.app', $data);
    }


    /**
     * @param null $id_user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id_user = null)
    {
        if ($id_user)
            $usuario = UsuarioManager::find($id_user);
        else
            $usuario = new UsuarioManager();

        $data['usuario'] = $usuario;
        $data['title'] = property_exists($usuario, 'id') ? 'Editar' : 'Crear Usuario Manager';
        $data['content'] = view('manager.users_manager.edit', $data);

        return view('layouts.manager.app', $data);
    }


    /**
     * @param Request $request
     * @param null $id_user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function edit_form(Request $request, $id_user = null)
    {
        if ($id_user)
            $usuario = UsuarioManager::find($id_user);
         else
            $usuario = new UsuarioManager();

        $validations = [
            'nombre' => 'required',
            'email' => 'required',
        ];

        if (null != $id_user) {
            if (null != $request->input('password')) {
                $validations['password'] = [
                    'required',
                    'min:8',
                    'confirmed',
                    new SafetyPassword
                ];
            }
        } else {
            $validations['password'] = [
                'required',
                'min:8',
                'confirmed',
                new SafetyPassword
            ];
        }

        $request->validate($validations);

        $respuesta = new \stdClass();
        $usuario->is_disabled = $request->has('is_disabled') ? true : false;
        $usuario->nombre = $request->input('nombre');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->usuario = $request->input('usuario');
        $usuario->email = $request->input('email');
        if (null != $request->input('password')) {
            $usuario->password = Hash::make($request->input('password'));
            $usuario->last_login = \Carbon\Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        }

        $usuario->save();
        $request->session()->flash('success', 'Usuario Manager guardado con éxito.');
        $respuesta->validacion = true;
        $respuesta->redirect = url('manager/usermanager');

        return response()->json($respuesta);
    }

     /**
     * @param Request $request
     * @param $id_user
     */
    public function delete(Request $request, $id_user){
        $usuario = UsuarioManager::find($id_user);
        $usuario->delete();

        $request->session()->flash('success', 'Usuario Manager eliminado con éxito.');
        return redirect('manager/usermanager');
    }


}
