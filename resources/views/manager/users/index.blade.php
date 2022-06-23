<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<p><a class="btn btn-primary" href="<?=url('manager/usuarios/editar')?>">Crear Usuario</a></p>

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Estado</th>
            <th>Correo Electrónico</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Cuenta</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php 
            $maxTimeOffLine = \Carbon\Carbon::now('America/Santiago')->subDays(env('OFFLINE_DAYS', 90));
        ?>
        <?php foreach($usuarios as $c):?>
        @if(is_null($c->Cuenta->deleted_at))
        <tr>
            <td>
                <?php
                    $lastLoginDate = new \Carbon\Carbon($c->last_login);
                ?>
                @if($c->is_disabled || $lastLoginDate->isBefore($maxTimeOffLine))
                    <span class="badge badge-danger">Deshabilitado</span>
                @else
                    <span class="badge badge-success">Habilitado</span>
                @endif
            </td>
            <td><?=$c->email?></td>
            <td><?=$c->nombre?></td>
            <td><?=$c->apellidos?></td>
            <td><?=$c->Cuenta->nombre?></td>
            <td><?= str_replace(",", ", ", $c->rol)?></td>
            <td style="width:250px;">
                <a class="btn btn-primary" href="<?=url('manager/usuarios/editar/' . $c->id)?>">
                    <i class="material-icons">edit</i> Editar
                </a>
                <a class="btn btn-danger" href="<?=url('manager/usuarios/eliminar/' . $c->id)?>"
                onclick="return confirm('¿Está seguro que desea desactivar a este usuario?')">
                    <i class="material-icons">delete</i> Desactivar
                </a>
            </td>
        </tr>
        @endif
        <?php endforeach ?>
        </tbody>
    </table>
</div>
