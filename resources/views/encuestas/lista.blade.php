@extends('layouts.master')
@section('title', 'Encuestas')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Encuestas</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Encuestas</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-12 project-list">
        <div class="card">
           <div class="row">
              <div class="col-md-6">
                
              </div>
              <div class="col-md-6">
                 <div class="form-group mb-0 me-0"></div>
                 <a class="btn btn-secondary actualizar" title="Actualizar listado">Actualizar</a>
              </div>
           </div>
        </div>
     </div>

    <div class="card">
        <div class="card-header">
            <h5>Encuestas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-lg">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Preguntas</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encuestas as $encuesta)
                        <tr>
                            <td>
                                @if($encuesta->tipo == 'entrada')
                                    <span class="badge badge-primary">Entrada</span>
                                @elseif($encuesta->tipo == 'salida')
                                    <span class="badge badge-success">Salida</span>
                                @else
                                    <span class="badge badge-warning">Perfil Shopper</span>
                                @endif
                            </td>
                            <td>{{ $encuesta->nombre ?? 'N/A' }}</td>
                            <td>{{ $encuesta->descripcion ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $encuesta->preguntas->count() }} preguntas</span>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="{{ route('encuestas.ver_preguntas', encrypt($encuesta->id)) }}" class="btn btn-sm btn-primary">
                                            <i class="icofont icofont-file-text"></i> Preguntas
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay encuestas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('.actualizar').click(function() {
        location.reload();
    });
</script>
@endsection

