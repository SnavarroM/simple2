<?php

namespace App\Http\Controllers\Manager;


use App\Helpers\Doctrine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Doctrine_Manager;
use App\Models\UsuarioBackend;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use Illuminate\Database\Eloquent\SoftDeletes;


class StatusReportController extends Controller
{
    public function index(Request $request) {
        $nombre_cuenta = $request->input('nombre_cuenta', null);
        $nombre_reporte = $request->input('nombre_reporte', null);
        $fecha_desde = $request->input('fecha_desde', null);
        $fecha_hasta = $request->input('fecha_hasta', null);
        $solicitante = $request->input('email', null);
        $rol = $request->input('rol', null);
        $status = $request->input('status', null);

        $reportes = Job::join('usuario_backend', 'jobs.user_id', '=', 'usuario_backend.id')
                    ->join('cuenta', 'usuario_backend.cuenta_id', '=', 'cuenta.id')
                    ->select(
                        'jobs.id as id',
                        'jobs.created_at as created_at',
                        'jobs.extra as nombre_reporte',
                        'jobs.status as status',
                        'usuario_backend.email as solicitante',
                        'usuario_backend.rol as usuario_rol',
                        'cuenta.nombre as nombre_cuenta'
                    );

        if ($nombre_cuenta) {
            $reportes->where('cuenta.nombre', 'like', "%$nombre_cuenta%");
        }

        if ($nombre_reporte) {
            $reportes->where('jobs.extra', 'like', "%$nombre_reporte%");
        }

        if ($solicitante) {
            $reportes->where('usuario_backend.email', 'like', "%$solicitante%");
        }

        if ($fecha_desde && $fecha_hasta) {
            $reportes->where('jobs.created_at', '>=', $fecha_desde);
            $reportes->where('jobs.created_at', '<=', $fecha_hasta);
        } else if ($fecha_desde) {
            $reportes->where('jobs.created_at', '>', $fecha_desde);
        } else if ($fecha_hasta) {
            $reportes->where('jobs.created_at', '<', $fecha_hasta);
        }

        if ($rol) {
            $reportes->where('usuario_backend.rol', '=', $rol);
        }

        if ($status) {
            $reportes->where('jobs.status', '=', $status);
        }

        $reportes = $reportes->orderBy('id', 'DESC')->paginate(20);

        $data['title'] = 'Estado Reportes';
        $data['reportes'] = $reportes;
        $data['nombre_cuenta'] = $nombre_cuenta;
        $data['nombre_reporte'] = $nombre_reporte;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['solicitante'] = $solicitante;
        $data['rol'] = $rol;
        $data['status'] = $status;
        $data['content'] = view('manager.reportes.index', $data);

        return view('layouts.manager.app', $data);
    }


    /**
     * @param Request $request
     * @param $report_id
     */
    public function delete(Request $request, $reporte_id){
        $anuncio = Job::find($reporte_id);
        $anuncio->delete();

        $request->session()->flash('success', 'Registro de estado de reporte eliminado con éxito.');
        return redirect('manager/reportes');
    }
}
