<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<p><a class="btn btn-primary" href="<?=url('manager/frontend/cuentas/')?>">Volver</a></p>

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Usuario</th>
            <th>Correo Electrónico</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Pertenece a</th>
            <th>Cuenta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($usuarios as $user)
            <tr>
                <td>{{$user->usuario}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->nombres}}</td>
                <td>{{$user->apellido_paterno}} {{$user->apellido_materno}}</td>
                <td>{{$user->grupo_usuarios->implode('nombre', ', ')}}</td>
                <td>{{$user->cuenta->nombre_largo}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{$usuarios->links('vendor.pagination.bootstrap-4')}}
</div>
