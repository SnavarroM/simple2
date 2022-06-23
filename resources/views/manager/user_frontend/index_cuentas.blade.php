<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
    </ol>
</nav>

<table class="table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Nombre largo</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cuentas as $c):?>
    <tr>
        <td><?=$c->nombre?></td>
        <td><?=$c->nombre_largo?></td>
        <td><a class="btn btn-info" href="<?=url('manager/frontend/'.$c->id.'/usuarios/')?>">
                <i class="material-icons">visibility</i>Ver Usuarios Frontend
            </a></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>