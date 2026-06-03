@extends('layouts.master-noauth')

@section('title', 'Esperando Aprobación')

@section('style')
<style>
    body {
        background-color: #f4f6f9;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="icofont icofont-clock-time text-warning" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Cuenta en Revisión</h2>
                    <p class="lead text-muted">Hola <strong>{{ $shopper->name ?? Auth::guard('shopper')->user()->name ?? 'Usuario' }}</strong>,</p>
                    <p class="mb-4">Tu perfil ha sido enviado correctamente. Nuestro equipo está revisando tu información para validar tu perfil como Mistery Shopper.</p>
                    
                    <div class="alert alert-warning border-warning mb-4" role="alert">
                        <p class="mb-0"><i class="icofont icofont-info-circle mr-2"></i> Recibirás una notificación por correo electrónico una vez que tu cuenta haya sido aprobada y activada.</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('desconectarse') }}" class="btn btn-primary px-4 py-2 rounded-pill">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
