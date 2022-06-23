@extends('layouts.backend')

@section('title', $title)

@section('content')
    <div class="container-fluid mt-3">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('backend.procesos.index')}}">Listado de Procesos</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$proceso->nombre}}</li>
            </ol>
        </nav>

        @include('backend.process.nav')

        <form class="ajaxForm" method="POST"
              action="<?= route('backend.document.edit_form', ($edit ? [$documento->id] : '')) ?>">
            {{csrf_field()}}
            <fieldset>
                <legend>Crear Documento</legend>
                <div class="validacion"></div>
                @if (!$edit)
                    <input type="hidden" name="proceso_id" value="<?= $proceso->id ?>"/>
                @endif
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control col-2"
                       value="<?= $edit ? $documento->nombre : '' ?>"/><br>

                <label>Tipo de documento</label>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="tipo_blanco" name="tipo"
                           value="blanco" <?= !$edit || ($edit && $documento->tipo) == 'blanco' ? 'checked' : '' ?> />
                    <label for="tipo_blanco" class="form-check-label">
                        En blanco
                    </label>
                </div>
                <div class="form-check">
                    <input type="radio" name="tipo" class="form-check-input" id="tipo_certificado"
                           value="certificado" <?= $edit && $documento->tipo == 'certificado' ? 'checked' : '' ?> />
                    <label for="tipo_certificado" class="form-check-label">
                        Certificado
                    </label>
                </div>
                </label>

                <div id="certificadoArea" class="mt-2"><br>
                    <label>Títulos</label>
                    <input class="form-control col-5" type="text" name="titulo"
                           value="<?= $edit ? $documento->titulo : '' ?>" placeholder="Ej: Certificado de Educación"/><br>
                    <label>Subtítulo</label>
                    <input class="form-control col-5" type="text" name="subtitulo"
                           value="<?= $edit ? $documento->subtitulo : '' ?>" placeholder="Ej: Certificado Gratuito"/><br>
                    <label>Servicio que emite el documento</label>
                    <input class="form-control col-5" type="text" name="servicio"
                           value="<?= $edit ? $documento->servicio : '' ?>"
                           placeholder="Ej: Ministerio Secretaría General de la Presidencia"/><br>
                    <label>URL al sitio web del servicio</label>
                    <input class="form-control col-5" type="text" name="servicio_url"
                           value="<?= $edit ? $documento->servicio_url : '' ?>"
                           placeholder="Ej: http://www.minsegpres.gob.cl"/><p><br>
                    <label>Logo del Servicio (Opcional)</label>
                    <div id="file-uploader-logo"></div>
                    <input type="hidden" name="logo" value="<?= $edit ? $documento->logo : '' ?>"/>
                    <div id="logoPreview">
                    <img class="logo"
                         src="<?= $edit && $documento->logo ? url('backend/uploader/logo_certificado_get/' . $documento->logo) : '#' ?>"
                         alt="" width="200"/><p>
                        <a href="#">Quitar Logo</a>
                    </div>         
                    <p><label>Imagen del timbre (Opcional)</label>
                    <div id="file-uploader-timbre"></div>
                    <input type="hidden" name="timbre" value="<?= $edit ? $documento->timbre : '' ?>"/>
                    <div id="timbrePreview">
                       <img class="timbre"
                         src="<?= $edit && $documento->timbre ? url('backend/uploader/timbre_get/' . $documento->timbre) : '#' ?>"
                         alt="" width="200"/><p>
                        <a href="#">Quitar Timbre</a>
                    </div>
                    <!--sello de agua-->
                     <p><label>Sello de Agua (Opcional)</label><br>
                     <label><b>Soporta formato .jpg y .png, se recomienda utilizar imagen de máx 40kb</b></label>
                    <div id="file-uploader-sello-agua"></div>
                    <input type="hidden" name="sello-agua" value="<?= $edit ? $documento->sello_agua : '' ?>"/>
                    <div id="sello-aguaPreview">
                        <img class="sello-agua"
                        src="<?= $edit && $documento->sello_agua ? url('backend/uploader/sello_agua_get/' . $documento->sello_agua) : '#' ?>"
                        alt="" width="200"/><p>
                        <a href="#">Quitar Sello</a>
                     <p>
                    </div>
                        <!--fin sello-->

                        <label>Nombre de la persona que firma (Opcional)</label>
                    <input class="form-control col-5" type="text" name="firmador_nombre"
                           value="<?= $edit ? $documento->firmador_nombre : '' ?>" placeholder="Ej: Juan Perez"/>
                    <label>Cargo de la persona que firma (Opcional)</label>
                    <input class="form-control col-5" type="text" name="firmador_cargo"
                           value="<?= $edit ? $documento->firmador_cargo : '' ?>" placeholder="Ej: Jefe de Servicio"/>
                    <label>Servicio al que pertenece la persona que firma (Opcional)</label>
                    <input class="form-control col-5" type="text" name="firmador_servicio"
                           value="<?= $edit ? $documento->firmador_servicio : '' ?>"
                           placeholder="Ej: Ministerio Secretaría General de la Presidencia"/>
                    <label>Imagen de la firma</label>
                    <div id="file-uploader"></div>
                    <input type="hidden" name="firmador_imagen"
                           value="<?= $edit ? $documento->firmador_imagen : '' ?>"/>
                    <div id="firmaPreview" class="<?=$edit && $documento->firmador_imagen ? '' : 'hidden'?>">
                        <img src="<?= $edit && $documento->firmador_imagen ? url('backend/uploader/firma_get/' . $documento->firmador_imagen) : '#' ?>"
                             alt="firma" width="200"/>
                        <a href="#">Quitar</a>
                    </div>

                    <label>Numero de dias de validez (Dejar en blanco para periodo ilimitado, 0 para no mostrar
                        validez)</label>
                    <div class="form-group form-inline">
                        <input class="form-control col-1" type="text" name="validez"
                               value="<?= $edit ? $documento->validez : '' ?>"
                               placeholder="Ej: 90"/>
                        <label class="checkbox ml-1 mr-2">
                            <input type="checkbox" name="validez_habiles"
                                   value="1" <?=$edit && $documento->validez_habiles ? 'checked' : ''?> />
                            Hábiles
                        </label>
                    </div>
                </div>

                <label>Tamaño de la Página</label>
                <div class="form-check">
                    <input type="radio" name="tamano" class="form-check-input" id="tamano_legal"
                           value="legal" <?= $edit && $documento->tamano == 'legal' ? 'checked' : '' ?> />
                    <label for="tamano_legal" class="form-check-label">Carta</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="tamano" class="form-check-input" id="tamano_letter"
                           value="letter" <?= !$edit || ($edit && $documento->tamano) == 'letter' ? 'checked' : '' ?> />
                    <label for="tamano_letter" class="form-check-label">Oficio</label>
                </div>

                <label>Contenido</label>
                <textarea name="contenido" class="form-control col-5"
                          rows="20"><?= $edit ? $documento->contenido : '' ?></textarea>
                <div class="form-text text-muted">
                    <ul>
                        <li>Para incluir un salto de página puede
                            usar: <?=htmlspecialchars('<br pagebreak="true" />')?></li>
                    </ul>
                </div>

                @if($proceso->Cuenta->HsmConfiguraciones->count())
                    <label>Firma Electronica Avanzada (HSM)</label><br>
                    <select class="form-control col-5" name="hsm_configuracion_id">
                        <option value="">No firmar con HSM</option>
                        @foreach ($proceso->Cuenta->HsmConfiguraciones as $h)
                            @if($h->estado == 1) 
                            <option value="<?= $h->id ?>" <?= $edit && $documento->hsm_configuracion_id == $h->id ? 'selected' : '' ?>>
                                Firmar con <?= $h->nombre ?> (<?= $h->rut ?>)</option>
                            @endif
                        @endforeach
                    </select>
                @endif

                <div class="form-actions">
                    <a class="btn btn-light" href="<?= route('backend.document.list', [$proceso->id]) ?>">Cancelar</a>
                    <input class="btn btn-primary" type="submit" value="Guardar"/>
                </div>
            </fieldset>
        </form>

    </div>
@endsection
@section('script')
    <script src="{{asset('js/helpers/fileuploader.js')}}"></script>
    <script>
        $(document).ready(function () {
            handleRadio();
            $("input[name=tipo]").change(handleRadio);

            function handleRadio() {
                var value = $("input[name=tipo]:checked").val();
                if (value == "blanco") {
                    $("#certificadoArea").hide();
                } else {
                    $("#certificadoArea").show();
                }
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $("input[name=validez]").keyup(function () {
                if ($(this).val().length > 0) {
                    $("input[name=validez_habiles]").prop("disabled", false);
                } else {
                    $("input[name=validez_habiles]").prop("disabled", true);
                }
            }).keyup();
        });
    </script>
    <script>
        $(document).ready(function () {
            var uploader = new qq.FileUploader({
                params: {_token: '{{csrf_token()}}'},
                element: document.getElementById('file-uploader'),
                action: '/backend/uploader/firma',
                onComplete: function (id, filename, respuesta) {
                    $("input[name=firmador_imagen]").val(respuesta.file_name);
                    $("#firmaPreview").show();
                    $("#firmaPreview img").attr("src", "/backend/uploader/firma_get/" + respuesta.file_name);
                }
            });

            $("#firmaPreview a").click(function () {
                $("input[name=firmador_imagen]").val("");
                $("#firmaPreview").hide();
                $("#firmaPreview img").attr("src", "#");
                return false;
            });
        });
    </script>
    <script>
        var uploader = new qq.FileUploader({
            params: {_token: '{{csrf_token()}}'},
            element: document.getElementById('file-uploader-timbre'),
            action: '/backend/uploader/timbre',
            onComplete: function (id, filename, respuesta) {
                $("input[name=timbre]").val(respuesta.file_name);
                $("#timbrePreview").show();
                $("timbrePreview img.timbre").attr("src", "/backend/uploader/timbre_get/" + respuesta.file_name);
            }
        });

        $("#timbrePreview a").click(function () {
                $("input[name=timbre]").val("");
                $("#timbrePreview").hide();
                $("#timbrePreview img.timbre").attr("src", "#");
                return false;
            });
    </script>
    <script>
        var uploader = new qq.FileUploader({
            params: {_token: '{{csrf_token()}}'},
            element: document.getElementById('file-uploader-logo'),
            action: '/backend/uploader/logo_certificado',
            onComplete: function (id, filename, respuesta) {
                $("input[name=logo]").val(respuesta.file_name);
                $("#logoPreview").show();
                $("img.logo").attr("src", "/backend/uploader/logo_certificado_get/" + respuesta.file_name);
            }
        });

        $("#logoPreview a").click(function () {
                $("input[name=logo]").val("");
                $("#logoPreview").hide();
                $("#logoPreview img.logo").attr("src", "#");
                return false;
            });
    </script>
     <script>
        var uploader = new qq.FileUploader({
            params: {_token: '{{csrf_token()}}'},
            element: document.getElementById('file-uploader-sello-agua'),
            action: '/backend/uploader/sello_agua',
            onComplete: function (id, filename, respuesta) {
                $("input[name=sello-agua]").val(respuesta.file_name);
                $("#sello-aguaPreview").show();
                $("img.sello-agua").attr("src", "/backend/uploader/sello_agua_get/" + respuesta.file_name);
            }
        });

        $("#sello-aguaPreview a").click(function () {
                $("input[name=sello-agua]").val("");
                $("#sello-aguaPreview").hide();
                $("#sello-aguaPreview img.sello-agua").attr("src", "#");
                return false;
            });
    </script>
@endsection