<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('manager/usuarios') ?>">Usuarios Backend</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
    </ol>
</nav>

<form class="ajaxForm" method="post" action="<?= url('manager/usuarios/editar_form/' . $usuario->id) ?>">
    {{csrf_field()}}
    <fieldset>
        <legend><?= $title ?></legend>
        <div class="validacion"></div>
        <label>Correo Electrónico</label>
        <input type="text" name="email" value="<?=$usuario->email?>" class="form-control col-3"/>
        <label>Contraseña</label>
        <input type="password" name="password" value="" class="form-control col-3"/>
        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation" value="" class="form-control col-3"/>
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= $usuario->nombre?>" class="form-control col-3"/>
        <label>Apellidos</label>
        <input type="text" name="apellidos" value="<?= $usuario->apellidos?>" class="form-control col-3"/>
        <label>Cuenta</label>
        <select id="cuenta_id" name="cuenta_id" class="form-control col-3">
            <?php foreach($cuentas as $c):?>
            @if(is_null($c->deleted_at))
                <option value="<?=$c->id?>" <?=$c->id == $usuario->cuenta_id ? 'selected' : ''?>><?=$c->nombre?></option>
            @endif
            <?php endforeach ?>
        </select>
        <label>Rol</label>
        <?php
        $roles = array("super", "modelamiento", "seguimiento", "gestion", "desarrollo", "configuracion");
        $longitud = count($roles);

        $valores = isset($usuario->rol) ? explode(",", $usuario->rol) : [];
        ?>
        <select id="rol" name="rol[]" class="form-control col-6" multiple>
            <?php
            for($o = 0; $o < $longitud; $o++){
            ?>
            <option value="<?= $roles[$o] ?>" <?=  isset($usuario) && in_array($roles[$o], $valores) ? 'selected' : ''?> > <?= $roles[$o] ?> </option>
            <?php
            }
            ?>
        </select>

        @if(isset($procesos))
        <div class="form-group" id="div_procesos">
            <label for="procesos">Procesos para hacer seguimiento</label>
            <select class="form-control col-3" id="procesos" name="procesos[]" data-placeholder="Seleccione los procesos" multiple>
               
            </select>
            @if($errors->has('procesos'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('procesos') }}</strong>
                </div>
            @endif
        </div>
        @else
            <div class="form-group" id="div_procesos" style="display:none">
                <label for="procesos">Procesos para hacer seguimiento</label>
                <select class="form-control col-3" id="procesos" name="procesos[]" data-placeholder="Seleccione los procesos" multiple>
                </select>
            </div>
        @endif

        <div class="text-muted form-text">
            <ul>
                <li>super: Tiene todos los privilegios del sistema.</li>
                <li>modelamiento: Permite modelar y diseñar el funcionamiento del trámite.</li>
                <li>seguimiento: Permite hacer seguimiento de los tramites.</li>
                <li>gestion: Permite acceder a reportes de gestion con privilegio de visualiación.</li>
                <li>desarrollo: Permite acceder a la API de desarrollo, para la ingtegracion con plataformas externas.</li>
                <li>configuracion: Permite configurar los usuarios y grupos de usuarios que tienen acceso al sistema.</li>
            </ul>
        </div>
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
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a class="btn btn-light" href="<?= url('manager/usuarios') ?>">Cancelar</a>
        </div>
    </fieldset>
</form>

@section('scripts')
<script>
    $(".chosen").chosen({disable_search_threshold: 10});

    $(document).ready(function(){
    
        $("#rol option").on("click",function() {
            var foo = [];
            $('#rol :selected').each(function(i, selected){ 
                foo.push($.trim($(selected).text()));
            });
            if (inArray("reportes", foo)) {
                $("#div_procesos").show();
            } else {
                $("#div_procesos").hide();
            }
        });

        function inArray(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle){
                    return true
                }else if(haystack[i] == "gestion"){
                    return true
                }else if(haystack[i] == "seguimiento"){
                    return true
                }else if(haystack[i] == "modelamiento"){
                    return true
                }
            }
            return false;
        }
        
        var defaultCuenta = $('#cuenta_id').val()!="" ?  $('#cuenta_id').val() : 1;
        var url_procesos='{{asset("/manager/usuarios/procesos")}}';
        var procesos_selected = {!! isset($procesos_selected) ? str_replace("'", "\'", json_encode($procesos_selected)) : 'false' !!};
        updateProcesos(defaultCuenta, procesos_selected);

        $('#cuenta_id').on('change', function() {
            $('#procesos').empty();
            updateProcesos($(this).val());
        });

        function updateProcesos(cuenta_id, procesos_selected){
            $.ajax({
                method: "get",
                url: url_procesos+"/"+cuenta_id+"",
                success: function(data) {
                    var procesos = $('#procesos');
                    if(data){
                        $.each(data, function(idx, el){
                            procesos.append("<option value=\""+el.id+"\" >"+el.nombre+"</option>");
                        });
                    }

                    if (typeof procesos_selected !== 'undefined' && procesos_selected.length > 0) {
                        $("#procesos").val(procesos_selected);
                    }
                }
            });
        }
    });
</script>
@endsection