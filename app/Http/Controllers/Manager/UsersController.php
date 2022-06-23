<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Doctrine;
use Illuminate\Support\Facades\Hash;
use App\Models\UsuarioBackend;
use App\Rules\SafetyPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Proceso;

class UsersController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {   

        $data['usuarios'] = UsuarioBackend::get();

        $data['title'] = 'Usuarios Backend';
        $data['content'] = view('manager.users.index', $data);

        return view('layouts.manager.app', $data);
    }

    /**
     * @param null $usuario_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($usuario_id = null)
    {
        if ($usuario_id){
            $usuario = UsuarioBackend::find($usuario_id);
            $data['procesos_selected'] = $usuario->procesos_permiso()->get()->groupBy('id')->keys()->toArray();
            $data['procesos'] = Proceso::where('cuenta_id',$usuario->cuenta_id)->where('activo',1)->get();
            $data['edit'] = true;
        }else{
            $usuario = new UsuarioBackend();
        }
        
        $data['usuario'] = $usuario;
        $data['cuentas'] = Doctrine::getTable('Cuenta')->findAll();

        $data['title'] = property_exists($usuario, 'id') ? 'Editar' : 'Crear';
        $data['content'] = view('manager.users.edit', $data);

        return view('layouts.manager.app', $data);
    }

    /**
     * @param Request $request
     * @param null $usuario_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function edit_form(Request $request, $usuario_id = null)
    {
        if ($usuario_id)
            $usuario = UsuarioBackend::find($usuario_id);
        else
            $usuario = new UsuarioBackend();

        $validations = [
            'email' => 'required|email',
            'nombre' => 'required',
            'apellidos' => 'required',
            'cuenta_id' => 'required',
            'rol' => 'required',
        ];

        $messages = [
            'email.required' => 'El campo Correo Electrónico es obligatorio',
            'nombre.required' => 'El campo Nombre es obligatorio',
            'apellidos.required' => 'El campo Apellidos es obligatorio',
            'cuenta_id.required' => 'El campo Cuenta es obligatorio',
            'rol.required' => 'El campo Rol es obligatorio',
        ];

        $password = $request->input('password', null);
        if (!$usuario->id) {
            $validations['password'] = [
                'required',
                'min:8',
                'confirmed',
                new SafetyPassword
            ];
        }else{
            if (null != $password) {
                $validations['password'] = [
                    'required',
                    'min:8',
                    'confirmed',
                    new SafetyPassword
                ];
            }
            $usuario->last_login = \Carbon\Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        }

        $request->validate($validations);

        $respuesta = new \stdClass();
        $usuario->email = $request->input('email');
        $usuario->nombre = $request->input('nombre');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->cuenta_id = $request->input('cuenta_id');
        $usuario->is_disabled = $request->has('is_disabled') ? true : false;
        $usuario->rol = implode(",", $request->input('rol'));

        if (!is_null($password)) {
            $usuario->password = Hash::make($request->input('password'));
        }

        $usuario->save();

        //Eliminamos todas las relaciones que tenga este usuario con sus procesos
        $usuario->procesos_permiso()->detach();

        //Insertamos las nuevas relaciones
        if ($request->has('procesos')) {
            foreach ($request->input('procesos') as $id) {
                $usuario->procesos_permiso()->attach($id);
            }
        }

        $usuario->save();

        $request->session()->flash('success', 'Usuario guardado con éxito.');
        $respuesta->validacion = true;
        $respuesta->redirect = url('manager/usuarios');

        return response()->json($respuesta);
    }

    /**
     * @param Request $request
     * @param $usuario_id
     */
    public function delete(Request $request, $usuario_id)
    {
        $usuario = UsuarioBackend::find($usuario_id);
       if (!is_null($usuario)) {
        $usuario = UsuarioBackend::where('id','=', $usuario->id)->update(['is_disabled' => 1]);
       }
        $request->session()->flash('success', 'Usuario desactivado con éxito.');
        return redirect('manager/usuarios');
    }

    public function getProcesos($cuenta_id)
    {
        $procesos = Proceso::select('id','nombre')->where('cuenta_id',$cuenta_id)->where('activo',1)->get();
        return response()->json($procesos);
    }

}
