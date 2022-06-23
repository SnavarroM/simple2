<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('manager/usermanager') ?>">Usuarios Manager</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
    </ol>
</nav>

<form class="ajaxForm" name="f1" method="post" action="<?= url('manager/usermanager/editar_form/' . $usuario->id) ?>" >
    {{csrf_field()}}
    <fieldset>
        <legend><?= $title ?></legend>
        <div class="validacion"></div>
        <label>Nombre</label>
        <p><input type="text" name="nombre" value="<?= $usuario->nombre?>" class="form-control col-3"/></p>
        <label>Apellidos</label>
        <p><input type="text" name="apellidos" value="<?= $usuario->apellidos?>" class="form-control col-3"/></p>
        <label>Ingrese Nombre Usuario</label>
        <p><input type="text" name="usuario" value="<?=$usuario->usuario?>" class="form-control col-3"/></p>
        <label>Correo Electrónico</label>
        <p><input type="email" name="email" value="<?=$usuario->email?>" class="form-control col-3"/></p>
        <label>Contraseña</label>
        <p><input type="password" name="password" value="" class="form-control col-3"/></p>
        <label>Confirmar contraseña</label>
        <p><input type="password" name="password_confirmation" value="" class="form-control col-3"/></p>
        <div class="form-group">
            <br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_disabled"
                    {{ $usuario->is_disabled ? 'checked' : ''}}
                    id="is_disabled">
                <label class="form-check-label" for="is_disabled">
                    ¿Marcar este usuario como deshabilitado?
                </label>
            </div>
            <br>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit" onClick="validaUser()">Guardar</button>
            <a class="btn btn-light" href="<?= url('manager/usermanager') ?>">Cancelar</a>
        </div>
    </fieldset>
</form>
<script src="{{ asset('js/app.js') }}"></script>