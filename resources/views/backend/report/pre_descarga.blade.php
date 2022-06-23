@extends('layouts.terminos_y_condiciones')


@section('css')
<style>
    .container-descarga {
        height: calc(100vh - 284px);
    }
    .centered-descarga {
    position: fixed; /* or absolute */
    top: calc(40% - 67px);
    left: calc(40% - 59px);

    }
</style>
@endsection

@section('content')
    <div class="container container-descarga">
        <div class="row align-items-center">
            <div class="centered-descarga">
                <div class="col text-center">
                    <h2 class="pb-3">Ahora puedes descargar tu reporte</h2>
                    <button
                        class="btn btn-lg btn-primary"
                        id="descargar-reporte"
                        data-filename="{{ $file_name }}"
                        data-reportlist="{{ route('backend.report') }}"
                        data-url="{{ route('backend.report.descargar_archivo', [$user_id, $job_id, $file_name]) }}">
                        Descarga aquí
                    </button>
                </div>
            </div>
        </div>
        
    </div>
@endsection


@section('script')
<script>
$(document).ready(function(){

    $("#descargar-reporte").on("click", async function(event) {
        event.preventDefault();
        await descargarReporte({
            url: $(this).data("url"),
            filename: $(this).data("filename"),
            reportlist: $(this).data("reportlist"),
            downloadBtn: $(this)
        });
    });

    const descargarReporte = async (params) => {
        params.downloadBtn.html("Descargando...");
        params.downloadBtn.attr("disabled", true);

        fetch(params.url)
        .then(resp => {
            if (!resp.ok) {
                throw new Error('El archivo ya no existe');
            }
            return resp.blob()
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = params.filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .then(async redirected => {
            await setTimeout(() => {
                params.downloadBtn.html("Descarga aquí");
                params.downloadBtn.attr("disabled", false);
            }, 2000);
        })
        .catch((error) => {
            console.error(error);
            result = window.confirm(
                "El reporte que intenta descargar ya no existe. " +
                "Recuerde que se puede descargar solo una vez, " +
                "si el error persiste intente abrir el enlace desde " +
                "su correo nuevamente o vuelva a generar el reporte. \n\n" +
                "Volver a listado de procesos?"
            );
            if (result) {
                location.href = params.reportlist;
            }
            params.downloadBtn.html("Descarga aquí");
            params.downloadBtn.attr("disabled", false);
        });
    };
});
</script>
@endsection

<!-- @section('gost_section')
<div style="margin-bottom:-15%;"></div>
@endsection -->