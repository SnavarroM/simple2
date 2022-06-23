<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Unauthorized - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="google" content="notranslate"/>

    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="{{asset('/img/favicon.png')}}">

</head>
<body class="page-error">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-2">
            <h2 class="status-error" style="margin-right: 9px;font-size:45px;margin-bottom: -65px;">
            <!-- &#128695; -->
            &#128683;
            </h2>
            <img class="error-icon" src="{{ asset('/img/error_icon.svg') }}" alt="Error 500 icon">
        </div>
        <div class="col-8">
            <h1>Usuario temporalmente deshabilitado</h1>
            <p>
            Tu cuenta ha sido deshabilitada por no uso, debes contactar al 
            administrador de la cuenta para que te la habilite nuevamente.
            </p>
            <a href="{{ route('home') }}" class="btn btn-simple btn-error-500 btn-primary">
                <i class="material-icons">arrow_left</i>
                Volver al Home
            </a>
        </div>
    </div>
</div>
</body>
</html>