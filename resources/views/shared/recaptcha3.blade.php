<div style="width:100%;">
    @if ($errors->has('recaptcha3'))
        <div class="alert alert-danger" role="alert">
            No es posible continuar con la solicitud
        </div>
    @endif
</div>