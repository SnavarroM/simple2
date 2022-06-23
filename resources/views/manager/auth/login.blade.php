<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{  \Cuenta::seo_tags()->title .' - Autenticaci&oacute;n' }}</title>
    <meta name="description" content="{{ \Cuenta::seo_tags()->description }}">
    <meta name="keywords" content="{{ \Cuenta::seo_tags()->keywords }}">

    <!-- Styles -->
    <link href="{{ asset('css/manager.css') }}" rel="stylesheet">

    <meta name="google" content="notranslate"/>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{{asset('/img/favicon.png')}}">

    @yield('css')
    @if(env('RECAPTCHA3_SITE_KEY') != null)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA3_SITE_KEY') }}"></script>
    @else
        @if($errors->any())
            <script src='https://www.google.com/recaptcha/api.js'></script>
        @endif
    @endif
</head>
<body class="login">
<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Ingrese al Manager</div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('manager.login.submit') }}" id="form-login">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="usuario"
                                   class="col-form-label">Ingrese Usuario</label>

                            <input
                                    id="usuario"
                                    type="text"
                                    class="form-control{{ $errors->has('usuario') ? ' is-invalid' : '' }}"
                                    name="usuario"
                                    value="{{ old('usuario') }}"
                                    required
                                    autofocus
                            >

                            @if ($errors->has('usuario'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('usuario') }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-form-label">{{__('auth.password')}}</label>

                            <input
                                    id="password"
                                    type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password"
                                    required
                            >

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group row">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}> {{__('auth.remember_me')}}
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            @if($errors->any())
                                @if (env('RECAPTCHA3_SITE_KEY') != null)
                                    @include('shared.recaptcha3')
                                @else
                                    @include('shared.captcha')
                                @endif
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <a href="{{route('home')}}" class="btn btn-login btn-light float-left">
                                    {{__('auth.back')}}
                                </a>

                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-login btn-danger float-right">
                                    {{__('auth.enter')}}
                                </button>

                            </div><p>
                            <div class="col-6">
                             <a class="btn btn-link" href="{{ route('manager.password.email') }}">
                                    {{__('auth.forgot_password')}}
                                </a>
                            </div>  </p>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@if(env('RECAPTCHA3_SITE_KEY') != null)
    <script>const RC3_SITE_KEY = "{{ env('RECAPTCHA3_SITE_KEY') }}";</script>
    <script src="{{asset('/js/helpers/recaptcha3-login.js')}}"></script>
@endif
</body>
</html>