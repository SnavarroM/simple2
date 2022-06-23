@extends('layouts.backend')

@section('title', 'Gestión')

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Gestión</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <form action="{{ route('backend.report') }}" class="form">
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

                <br>

                <table class="table">
                    <thead>
                    <th width="90%">Proceso</th>
                    <th width="10%"></th>
                    </thead>
                    <tbody>
                        @foreach($procesos as $p)
                            @if ($p->activo == '1')
                                @if(is_null((Auth::user()->procesos)))
                                    <tr>
                                        <td>{{$p->nombre}}</td>
                                        <td>
                                            <a href="{{route('backend.report.list', [$p->id])}}"
                                            class="btn btn-primary">
                                                <i class="material-icons">remove_red_eye</i> Ver Reportes
                                            </a>
                                        </td>
                                    </tr>
                                @elseif( in_array( $p->id,explode(',',Auth::user()->procesos)))
                                    <tr>
                                        <td>{{$p->nombre}}</td>
                                        <td>
                                            <a href="{{route('backend.report.list', [$p->id])}}"
                                            class="btn btn-primary">
                                                <i class="material-icons">remove_red_eye</i> Ver Reportes
                                            </a>
                                        </td>
                                    </tr>
                                @endif
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
@endsection