@extends('layouts.procedure')
@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <h1 class="main-title">
                    <img class="ico-title-terms" src="{{ asset('/img/ico-terminos.svg') }}" alt="Icono">
                    Error de cuenta
                </h1><p>
                
            </div>

            <div class="col" >
                Lo sentimos, no tienes autorizado el acceso a los trámites disponibles. <p>
                Favor verificar lo siguiente : 
                 <li>Revisa el correo que estás usando para acceder o</li>
                 <li>    Contacta al coordinador de tu institución para que revise si tienes autorizado el acceso al listado de trámites disponibles.</li>
               
            </div>
        </div>          
    </div>
@endsection
