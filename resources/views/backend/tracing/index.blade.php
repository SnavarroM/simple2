@extends('layouts.backend')

@section('title', 'Listado de Procesos')

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Seguimiento de Procesos</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6">
                        @if(in_array('super', explode(',', Auth::user()->rol)))
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Operaciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" onclick="return actualizarIdTramites();">Actualizar ID de
                                        Trámites</a>
                                </div>
                            </div><br>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('backend.tracing.index') }}" class="form">
                            <div class="row justify-content-end">
                                <div class="col-5">
                                    <input type="text" class="form-control" placeholder="Escribe el proceso a buscar aquí"
                                        name="process_search" value="{{ $process_search  }}">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">search</i>
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Proceso</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($procesos as $p)
                        @if(is_null((Auth::user()->procesos)) || in_array($p->id, explode(',', Auth::user()->procesos)))
                            <tr>
                                <td><?=$p->nombre?></td>
                                <td>
                                    <a class="btn btn-primary"
                                       href="{{route('backend.tracing.list', [$p->id])}}">
                                        <i class="material-icons">remove_red_eye</i> Ver seguimiento
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                
                @if(!empty($mensaje_roles))
                <div class="alert alert-primary" role="alert">
                    {{$mensaje_roles}}
                </div>
                @endif
                {{ $procesos->links('vendor.pagination.bootstrap-4') }}

            </div>
        </div>
    </div>
    <div id="modal" class="modal" tabindex="-1" role="dialog">
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function actualizarIdTramites() {
            $("#modal").load('{{route('backend.tracing.ajaxIdProcedure')}}');
            $("#modal").modal();
            return false;
        }
    </script>
@endsection