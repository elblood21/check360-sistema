@extends('layouts.master-noauth')
@section('title', 'Check 360')

@section('css')
@endsection

@section('style')
<style>
    .login-split-page {
        min-height: 100vh;
        display: flex;
    }
    .login-image-side {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
    }
    @if(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
    .login-image-side {
        background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80');
    }
    @else
    .login-image-side {
        background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80');
    }
    @endif
    .login-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 4rem;
        color: white;
    }
    .login-form-side {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 3rem;
        background-color: var(--theme-default);
    }
    [data-theme="dark"] .login-form-side {
        background-color: #1e1e1e;
    }
    .login-form-container {
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    .login-form-container .logo {
        margin-bottom: 2rem;
        display: block;
    }
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row m-0 login-split-page">
        <!-- Left Side Image -->
        <div class="col-lg-6 p-0 d-none d-lg-block login-image-side">
            <div class="login-image-overlay">
                <h2 class="fw-bold mb-3">
                    @if(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
                        Evalúa tu restaurante y mejora la experiencia
                    @elseif(\App\Helpers\SubdominioHelper::esTipo('shopper'))
                        �?nete como Mistery Shopper y disfruta evaluando
                    @else
                        Bienvenido a Check 360
                    @endif
                </h2>
                <p class="fs-5 mb-0">La plataforma líder en evaluaciones de Mystery Shopping gastronómico.</p>
            </div>
        </div>

        <!-- Right Side Form -->
        <div class="col-lg-6 p-0 login-form-side bg-white">
            <div class="login-form-container">
                <a class="logo text-start" href="{{ route('index') }}">
                    <img class="img-fluid for-light" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="looginpage" style="width: 12rem;">
                    <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="looginpage" style="width: 12rem;">
                </a>
                
                <h3 class="fw-bold mb-2">Ingresa a tu cuenta</h3>
                <p class="text-muted mb-4">Con tu email y contraseña podrás acceder al sistema de Check 360</p>
                
                <form class="theme-form needs-validation" novalidate="">
                    <div class="form-group mb-3">
                        <label class="col-form-label fw-bold">Correo electrónico</label>
                        <input class="form-control form-control-lg" type="email" required="" id="email" placeholder="ejemplo@correo.com">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="col-form-label fw-bold">Contraseña</label>
                        <div class="position-relative">
                            <input class="form-control form-control-lg" type="password" id="pass" required="" placeholder="*********">
                            <div class="show-hide"><span><i class="icofont icofont-eye-blocked"></i></span></div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <button class="btn btn-primary btn-lg w-100" type="button" id="ingresar">Ingresar al Sistema</button>
                    </div>
                    
                    <div class="text-center">
                        <a href="/olvidaste" class="text-decoration-none mb-3 d-inline-block">¿Olvidaste tu contraseña?</a>
                        
                        <div class="mt-4 pt-4 border-top">
                            @if(\App\Helpers\SubdominioHelper::esTipo('shopper'))
                                <p class="text-muted mb-2">¿Aún no tienes cuenta?</p>
                                <a href="{{ route('shopper.registro') }}" class="btn btn-outline-primary w-100">Regístrate como Mistery Shopper</a>
                            @elseif(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
                                <p class="text-muted mb-2">¿Quieres evaluar tu local?</p>
                                <a href="{{ route('restaurante.registro') }}" class="btn btn-outline-primary w-100">Registra tu Restaurante</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#ingresar').click(function(e) {
            e.preventDefault();
            if(!validar()) return false;

            var btn = $(this);
            btn.prop('disabled', true).text('Ingresando...');

            $.ajax({
                url:"{{ route('login') }}",
                method:'POST',
                data:{correo:$('#email').val(),pass:$('#pass').val()},
                success:function(res) {
                    if(res.estado == 200) {
                        window.location.href=res.url;
                    } else if(res.estado == 404) {
                        notify("Advertencia", res.mensaje || "Usuario no existe en nuestro sistema", "danger");
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    } else if(res.estado == 500) {
                        notify("Advertencia", res.mensaje || "Usuario/Contraseña no coinciden", "danger");
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    } else if(res.estado == 501) {
                        notify("Advertencia", res.mensaje || "Usuario no autorizado a ingresar al sistema", "danger");
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    } else if(res.estado == 502) {
                        notify("Información", res.mensaje || "Se ha enviado un email con sus credenciales", "info", 5000);
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    } else if(res.estado == 503) {
                        notify("Información", res.mensaje || "Tu cuenta está pendiente de aprobación", "warning", 5000);
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    } else {
                        notify("Advertencia", res.mensaje || "Ocurrió un error", "danger");
                        btn.prop('disabled', false).text('Ingresar al Sistema');
                    }
                },
                error: function() {
                    notify("Error", "Error de conexión", "danger");
                    btn.prop('disabled', false).text('Ingresar al Sistema');
                }
            })
        });
        
        // Ejecutar con enter
        $('#pass, #email').keypress(function(e) {
            if(e.which == 13) {
                $('#ingresar').click();
            }
        });
    });    
    
    function validar() {
        if($('#email').val() == "") {
            notify("Advertencia","Debe completar el campo correo electronico","danger");
            return false;
        } else if($('#pass').val() == "") {
            notify("Advertencia","Debe completar el campo contraseña","danger");
            return false;
        }

        return true;
    }
 </script>
@endsection