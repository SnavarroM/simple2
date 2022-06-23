@extends('layouts.app')

@section('script_header')
    @if(env('RECAPTCHA3_SITE_KEY') != null)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA3_SITE_KEY') }}"></script>
    @else
        @if($errors->any())
            <script src='https://www.google.com/recaptcha/api.js'></script>
        @endif
    @endif
@endsection

@section('content')
    <div class="container login">
        <div class="row justify-content-md-center mt-5">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">{{__('auth.login')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="form-login">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="email"
                                       class="col-form-label text-lg-right">{{__('auth.email')}}</label>

                                <input
                                        id="email"
                                        type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                >

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-form-label text-lg-right">{{__('auth.password')}}</label>

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

                            @if($errors->any())
                                @if (env('RECAPTCHA3_SITE_KEY') != null)
                                    <div class="form-group row">
                                        @include('shared.recaptcha3')
                                    </div>
                                @else
                                    <div class="col-6">
                                        @include('shared.captcha')
                                    </div>
                                @endif
                            @endif

                            <div class="form-group row">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}> {{__('auth.remember_me')}}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{__('auth.forgot_password')}}
                                </a>
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
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('script')
    @if(env('RECAPTCHA3_SITE_KEY') != null)
        <script>const RC3_SITE_KEY = "{{ env('RECAPTCHA3_SITE_KEY') }}";</script>
        <script src="{{asset('/js/helpers/recaptcha3-login.js')}}"></script>
    @endif
@endsection