<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.ga')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{  \Cuenta::seo_tags()->title }}</title>
    <meta name="description" content="{{ \Cuenta::seo_tags()->description }}">
    <meta name="keywords" content="{{ \Cuenta::seo_tags()->keywords }}">

    <!-- Styles -->
    <link href="{{ asset('css/'. getCuenta()['estilo']) }} " rel="stylesheet">

    <meta name="google" content="notranslate"/>

    <link rel="shortcut icon" href="{{ asset(\Cuenta::getAccountFavicon()) }}">
    <link href="{{ asset('css/component-chosen.css') }}" rel="stylesheet">

    @yield('css')

    @if(env('RECAPTCHA3_SITE_KEY') != null)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA3_SITE_KEY') }}"></script>
        <script>const RC3_SITE_KEY = "{{ env('RECAPTCHA3_SITE_KEY') }}";</script>
        <script src="{{asset('/js/helpers/recaptcha3-tramites.js')}}"></script>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
        var site_url = "";
        var base_url = "";

        // var onloadCallback = function () {
        //     if ($('#form_captcha').length) {
        //         grecaptcha.render("form_captcha", {
        //             sitekey: "{{env('RECAPTCHA_SITE_KEY')}}"
        //         });
        //     }
        // };

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
     <style type="text/css">{{ getCuenta()['personalizacion'] }}</style>
</head>
<body class="h-100">
<div id="app" class="h-100 d-flex flex-column" >
    @include('layouts.anuncios')
    @include(getCuenta()['header'])
    <!-- <div class="alert alert-warning" role="alert">
        Estamos realizando labores de mantenimiento en el sitio, presentará intermitencia en su funcionamiento.
    </div> -->

    <div class="main-container container pb-5">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                @if(isBandejaActive())
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ getBandejaActiveName() }}</li>
                        </ol>
                    </nav><br>
                @endif
            </div>
            <div class="col-xs-12 col-md-3">

                <ul class="simple-list-menu list-group d-none d-sm-block">
                    <a class="list-group-item list-group-item-action  {{isBandejaCategoryActive('home')}}"
                       href="{{route('home')}}">
                        <i class="material-icons">insert_drive_file</i> Trámites disponibles
                    </a>

                    @if(Auth::user()->registrado)
                        @php
                            $npendientes = getTotalAssigned();
                                //dd($npendientes);
                            $nsinasignar =getTotalUnnasigned();
                          //  dd($nsinasignar);
                           //  echo "<script>console.log(".json_encode($nsinasignar).")</script>";
                            $nparticipados = getTotalHistory();
                        @endphp
                         <a class="list-group-item list-group-item-action {{isBandejaCategoryActive('inbox')}}"
                           href="{{route('stage.inbox')}}">
                            <i class="material-icons">inbox</i> Bandeja de Entrada ({{$npendientes}})
                        </a>
                        @if(!Auth::user()->open_id)
                        <a class="list-group-item list-group-item-action {{isBandejaCategoryActive('sin_asignar')}}"
                            href="{{route('stage.unassigned')}}">
                            <i class="material-icons">assignment</i> Sin asignar @if ($nsinasignar) <img src="{{ asset('/img/cl-i-bell.png') }}"> @endif
                        </a>
                        @endif
                        <a class="list-group-item list-group-item-action {{isBandejaCategoryActive('historial')}}"
                           href="{{route('tramites.participados')}}">
                            <i class="material-icons">history</i> Historial de Trámites 
                        </a>
                       <!--  <a class="list-group-item list-group-item-action { {isset($sidebar) && strstr($sidebar, 'miagenda') ? 'active' : ''}}"
                           href="{ {route('agenda.miagenda')}}">
                            <i class="material-icons">date_range</i> Mi Agenda
                        </a> -->
                    @endif
                </ul>
            </div>

            <div class="col-xs-12 col-md-9">
                @include('components.messages')
                @yield('content')
                {!! isset($content) ? $content : '' !!}
            </div>

        </div>
    </div>
    @include(getCuenta()['footer'], ['metadata' => json_decode(getCuenta()['metadata'])])
</div>

@stack('script')

<!-- Scripts -->
<!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=es"></script> -->
<script src="{{ asset('js/helpers/grilla_datos_externos.js') }}"></script>

<script>
$(function () {
    $(document).ready(function(){
        $('#cierreSesion').click(function (){
            $.ajax({ url: 'https://accounts.claveunica.gob.cl/api/v1/accounts/app/logout', dataType: 'script' }) .always(function() {
                window.location.href = '/logout';
            });
        });
    });
});
</script>
</body>
</html>
