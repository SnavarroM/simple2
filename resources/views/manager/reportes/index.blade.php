<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<table class="table">
    <thead>
    <tr>
        <form method="GET" action="{{ route('manager.reportes.index') }}">
            <th></th>
            <th>
                <div class="form-group">
                    <label for="nombre-cuenta">Cuenta</label>
                    <input type="text" class="form-control" id="nombre-cuenta" name="nombre_cuenta" value="{{ $nombre_cuenta  }}" placeholder="Nombre cuenta...">
                </div>
            </th>
            <th>
                <div class="form-group">
                    <label for="nombre-reporte">Nombre Reporte</label>
                    <input type="text" class="form-control" id="nombre-reporte" name="nombre_reporte" value="{{ $nombre_reporte  }}" placeholder="Nombre reporte...">
                </div>
            </th>
            <th>
                <div class="form-group">
                    <label>Fecha desde</label>
                    <input type="date" name="fecha_desde"  value="{{ $fecha_desde  }}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Fecha hasta</label>
                    <input type="date" name="fecha_hasta"  value="{{ $fecha_hasta  }}" class="form-control">
                </div>
            </th>
            <th>
                <div class="form-group">
                    <label for="usuario-email">Email Solicitante</label>
                    <input type="text" class="form-control" id="usuario-email" name="email"  value="{{ $solicitante  }}"placeholder="Email usuario...">
                </div>
            </th>
            <th>
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select name="rol" class="form-control">
                        <option value="">-- Seleccione --</option>
                        <option value="super" @if($rol=="super") selected  @endif> super </option>
                        <option value="modelamiento" @if($rol=="modelamiento") selected  @endif> modelamiento </option>
                        <option value="seguimiento" @if($rol=="seguimiento") selected  @endif> seguimiento </option>
                        <option value="gestion" @if($rol=="gestion") selected  @endif> gestion </option>
                        <option value="desarrollo" @if($rol=="desarrollo") selected  @endif> desarrollo </option>
                        <option value="configuracion" @if($rol=="configuracion") selected  @endif> configuracion </option>
                    </select>
                </div>
            </th>
            <th>
                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" class="form-control">
                        <option value="">-- Seleccione --</option>
                        <option value="created" @if($status=="created") selected  @endif>Created</option>
                        <option value="running" @if($status=="running") selected  @endif>Running</option>
                        <option value="error" @if($status=="error") selected  @endif>Error</option>
                        <option value="finished" @if($status=="finished") selected  @endif>Finished</option>
                    </select>
                </div>
            </th>
            <th>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                </div>
            </th>
        </form>
    </tr>
    <tr>
        <th>N°</th>
        <th>Cuenta</th>
        <th>Nombre Reporte</th>
        <th>Fecha Solicitud</th>
        <th>Email Solicitante</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes as $reporte)
        <tr>
            <td>{{ $reporte->id }}</td>
            <td>{{ $reporte->nombre_cuenta }}</td>
            <td>{{ $reporte->nombre_reporte }}</td>
            <td>{{ $reporte->created_at }}</td>
            <td>{{ $reporte->solicitante }}</td>
            <td>{{ $reporte->usuario_rol }}</td>
            <td>{{ $reporte->status }}</td>
            <td>
                <a class="btn btn-danger" href="{{ route('manager.reportes.delete', $reporte->id )}}"
                onclick="return confirm('¿Está seguro que eliminar este registro?')">
                    <i class="material-icons">delete</i> Eliminar
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if(count($reportes) < 1)
    <div class="alert alert-primary" role="alert">
        No se encontraron resultados
    </div>
@endif

{{ $reportes->appends([
    'nombre_cuenta' => $nombre_cuenta,
    'nombre_reporte' => $nombre_reporte,
    'fecha_desde' => $fecha_desde,
    'fecha_hasta' => $fecha_hasta,
    'email' => $solicitante,
    'rol' => $rol,
    'status' => $status
])->links('vendor.pagination.bootstrap-4') }}
