<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<p><a class="btn btn-primary" href="<?=url('manager/cuentas/editar/')?>">Crear Cuenta</a></p>

<table class="table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Nombre largo</th>
        <th class="text-center">Ambiente</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cuentas as $c):?>
    <tr>
        <td><?=$c->nombre?></td>
        <td><?=$c->nombre_largo?></td>
        <td class="text-center"><span class="badge badge-secondary"><?=strtoupper($c->ambiente)?></span></td>
        <td>
            <a class="btn btn-primary" href="<?=url('manager/cuentas/editar/' . $c->id)?>">
                <i class="material-icons">edit</i> Editar
            </a>
            @if(is_null($c->deleted_at))
            <a class="btn btn-danger" href="<?=url('manager/cuentas/eliminar/' . $c->id)?>"
               onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')">
                <i class="material-icons">delete</i> Deshabilitar
            </a>
            @else
            <a class="btn btn-success" href="<?=url('manager/cuentas/habilitar/' . $c->id)?>"
               onclick="return confirm('¿Está seguro que desea habilitar esta cuenta?')">
                <i class="material-icons">delete</i> Habilitar
            </a>
            @endif
        </td>
    </tr>
    <?php endforeach ?>
    <tr class="table-success">
        <td>Total Procesos</td>
        <td><?=$procesos_activos?> </td>
         <td><a class="btn btn-success" href="<?=url('manager/estadisticas/cuentas/')?>">
                <i class="material-icons">visibility</i>Ver Estadisticas
            </a></td>

    </tr>
    </tbody>
</table>