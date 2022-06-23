<div class="row">
    <div class="col-md-12">
        <h2>Trámites {{ $categoria->nombre }}</h2>
        <a href="#" class="btn btn-primary preventDoubleRequest" style="float: right;" onclick="window.history.back();">
            <i class="material-icons align-middle">keyboard_backspace</i> Volver
        </a>

        <div class="row">
            @foreach($listadoProcesos as $proceso)
                @if(is_null($proceso->ocultar_front) || !$proceso->ocultar_front)
                    <div class="{{$login ? 'col-md-6' : 'col-md-4' }} item">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="media">
                                    @if($proceso->icon_ref)
                                        <img src="<?= asset('img/icon/' . $proceso->icon_ref) ?>"
                                                class="img-service">
                                    @else
                                        <i class="icon-archivo"></i>
                                    @endif
                                    <div class="media-body">
                                        <p class="card-text">
                                        @if($proceso->nombre_frontend)
                                            {{$proceso->nombre_frontend}}
                                        @else
                                            {{$proceso->nombre}}
                                        @endif
                                        </p>
                                        <p>{{$proceso->descripcion}}</p>
                                        @if(!is_null($proceso->url_informativa))
                                            <p><a href="{{$proceso->url_informativa}}" target="_blank" style="text-decoration: underline;font-size: 14px;">Mas información</a></p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($proceso->ficha_informativa)
                                <a class="card-footer" href="{{ route('tramites.pre_inicio', [$proceso->id]) }}">
                                    Iniciar trámite
                                </a>
                            @else
                                <a href="{{
                                            $proceso->canUsuarioIniciarlo(Auth::user()->id) ? route('tramites.iniciar',  [$proceso->id]) :
                                        (
                                            $proceso->getTareaInicial()->acceso_modo == 'claveunica' ? route('login.claveunica').'?redirect='.route('tramites.iniciar', [$proceso->id]) :
                                            route('login').'?redirect='.route('tramites.iniciar', $proceso->id)
                                        )
                                        }}"
                                    class="{{$proceso->getTareaInicial()->acceso_modo == 'claveunica'? 'btn-cu btn-m btn-fw btn-color-estandar' : 'card-footer'}}">
                                    @if ($proceso->canUsuarioIniciarlo(Auth::user()->id))
                                        Iniciar trámite
                                    @else
                                        @if ($proceso->getTareaInicial()->acceso_modo == 'claveunica')
                                            <span class="cl-claveunica"></span>
                                            <span class="texto">{{__('auth.login_claveunica')}}</span>
                                        @else
                                            <i class="material-icons">person</i> Autenticarse
                                            <span class="float-right">&#8594;</span>
                                        @endif
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
