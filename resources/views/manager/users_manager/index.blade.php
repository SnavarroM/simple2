<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<p><a class="btn btn-primary" href="<?=url('manager/usermanager/editar')?>">Crear Usuario</a></p>

<table class="table">
    <thead>
    <tr>
        <th>Estado</th>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Correo</th>
        <th>Fecha Creación</th>
        <th>Fecha Actualización</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php 
        $maxTimeOffLine = \Carbon\Carbon::now('America/Santiago')->subDays(env('OFFLINE_DAYS', 90));
    ?>
    <?php foreach($users_manager as $um):?>
    <tr>
        <td>
            <?php
                $lastLoginDate = new \Carbon\Carbon($um->last_login);
            ?>
            @if($um->is_disabled || $lastLoginDate->isBefore($maxTimeOffLine))
                <span class="badge badge-danger">Deshabilitado</span>
            @else
                <span class="badge badge-success">Habilitado</span>
            @endif
        </td>
        <td><?=$um->nombre?> <?=$um->apellidos?></td>
        <td><?=$um->usuario?></td>
        <td><?=$um->email?></td>
        <td><?=$um->created_at?></td>
        <td><?=$um->updated_at?></td>
        <td>
            <a class="btn btn-primary" href="<?=url('manager/usermanager/editar/' . $um->id)?>">
                <i class="material-icons">edit</i> Editar
            </a>
            <a class="btn btn-danger" href="<?=url('manager/usermanager/eliminar/' . $um->id)?>"
               onclick="return confirm('¿Está seguro que desea eliminar este usuario?')">
                <i class="material-icons">delete</i> Eliminar
            </a>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
