@extends('layouts.master-noauth')

@section('title', 'Solicitud Pendiente')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="mb-4 text-primary">Solicitud en Revisión</h2>
            <p class="lead mb-3">
                Tu solicitud de registro está pendiente de aprobación por parte de nuestro equipo administrativo.
            </p>
            <p>
                Te notificaremos por correo electrónico una vez que tu cuenta haya sido aceptada.<br>
                Si tienes dudas, puedes contactarnos a <a href="mailto:abogados@check360.cl">abogados@check360.cl</a>.
            </p>
            <div class="mt-4">
                <a href="{{ route('desconectarse') }}" class="btn btn-outline-secondary"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('desconectarse') }}" method="GET" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection