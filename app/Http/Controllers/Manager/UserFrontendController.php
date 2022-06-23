<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Doctrine;
use Illuminate\Support\Facades\Hash;
use App\Models\Cuenta;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserFrontendController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['cuentas'] = Cuenta::all();

        $data['title'] = 'Cuenta';
        $data['content'] = view('manager.user_frontend.index_cuentas', $data);

        return view('layouts.manager.app', $data);
    }

    /**
     * @param null $cuenta_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get_usuarios(Request $request, $cuenta_id)
    {
        $cuenta = Cuenta::find($cuenta_id);
        if (!is_null($cuenta))
            $usuarios = User::where('cuenta_id',$cuenta_id)->funcionarios();
        else
            abort(404);

        $per_page = 10;
        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $per_page) - $per_page;
        $usuariosTotal = $usuarios->get()->count();
        $usuarios->limit($per_page)->offset($offset);
        $usuariosResult = $usuarios->get();

        $usuarios = new LengthAwarePaginator(
            $usuariosResult, // Only grab the items we need
            $usuariosTotal, // Total items
            $per_page, // Items per page
            $page, // Current page
            // We need this so we can keep all old query parameters from the url
            ['path' => $request->url()]
        );

        $data['usuarios'] = $usuarios;
        $data['title'] = 'Usuarios Frontend';
        $data['content'] = view('manager.user_frontend.index', $data);

        return view('layouts.manager.app', $data);
    }

}
