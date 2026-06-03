@extends('layouts.master')
@section('title', ($tipo_cocina ? 'Editar' : 'Nuevo')." tipo de cocina")

@section('css')
    
@endsection

@section('style')
    
@endsection

@section('breadcrumb-title')
<h3>{{$tipo_cocina ? 'Editar' : 'Nuevo'}} tipo de cocina</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('tipos_cocina.lista')}}'">Tipos de Cocina</li>
<li class="breadcrumb-item">{{$tipo_cocina ? 'Editar' : 'Nuevo'}}</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="name">Nombre del Tipo de Cocina (*)</label>
                    <input value="{{$tipo_cocina ? $tipo_cocina->name : ''}}" class="form-control btn-square" id="name" placeholder="Ej: Italiana, Japonesa, Parrilla..." style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold" for="icon">Icono (IcoFont)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 8px 0 0 8px;">
                            <i id="icon-preview" class="{{$tipo_cocina && $tipo_cocina->icon ? $tipo_cocina->icon : 'icofont icofont-restaurant'}} fs-5 text-primary"></i>
                        </span>
                        <input value="{{$tipo_cocina ? $tipo_cocina->icon : 'icofont icofont-restaurant'}}" class="form-control btn-square border-start-0" id="icon" placeholder="icofont icofont-food-cart" style="border-radius: 0 8px 8px 0;">
                    </div>
                    <small class="text-muted">Usa clases de <a href="https://icofont.com/icons" target="_blank">IcoFont</a> (ej: icofont icofont-pizza)</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold" for="color_primary">Color Principal</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="color" class="form-control form-control-color border-0 p-0" id="color_primary" value="{{$tipo_cocina ? $tipo_cocina->color_primary : '#0075cd'}}" title="Elige el color principal" style="width: 50px; height: 45px; border-radius: 8px; cursor: pointer;">
                        <input type="text" class="form-control btn-square" id="color_primary_hex" value="{{$tipo_cocina ? $tipo_cocina->color_primary : '#0075cd'}}" placeholder="#0075cd" style="border-radius: 8px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold" for="color_secondary">Color Secundario (Degradado)</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="color" class="form-control form-control-color border-0 p-0" id="color_secondary" value="{{$tipo_cocina ? $tipo_cocina->color_secondary : '#005fa6'}}" title="Elige el color secundario" style="width: 50px; height: 45px; border-radius: 8px; cursor: pointer;">
                        <input type="text" class="form-control btn-square" id="color_secondary_hex" value="{{$tipo_cocina ? $tipo_cocina->color_secondary : '#005fa6'}}" placeholder="#005fa6" style="border-radius: 8px;">
                    </div>
                </div>

                <!-- Preview Visual -->
                <div class="col-12 mt-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Vista Previa en App</label>
                    <div class="p-4 border rounded-3 d-flex justify-content-center bg-light">
                        <div id="visual-preview" class="d-flex flex-column align-items-center justify-content-center text-center shadow-sm" 
                             style="width: 100px; height: 100px; border-radius: 20px; background: #ffffff; color: #2b2b2b; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);">
                            <div id="preview-icon-wrapper" class="mb-2 d-flex align-items-center justify-content-center" 
                                 style="width: 46px; height: 46px; border-radius: 50%; background: rgba(0, 117, 205, 0.1); color: #0075cd;">
                                <i id="preview-icon-element" class="{{$tipo_cocina && $tipo_cocina->icon ? $tipo_cocina->icon : 'icofont icofont-restaurant'}} fs-4"></i>
                            </div>
                            <span id="preview-name" class="fw-bold" style="font-size: 0.8rem;">{{$tipo_cocina ? $tipo_cocina->name : 'Nombre'}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end mt-5 gap-2">
                    @if(!$tipo_cocina)
                    <button class="btn btn-outline-success px-4 crearYQuedarseButton" type="button" style="border-radius: 10px;">Crear y quedarse</button>
                    @endif
                    <button class="btn btn-primary px-4 submitButton" type="button" style="border-radius: 10px;">
                        <i class="icofont icofont-save me-2"></i>{{$tipo_cocina ? 'Guardar Cambios' : 'Crear Tipo de Cocina'}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function updateVisualPreview() {
        var name = $('#name').val() || 'Nombre';
        var icon = $('#icon').val() || 'icofont icofont-restaurant';
        var color = $('#color_primary').val();
        
        $('#preview-name').text(name);
        $('#preview-icon-element').attr('class', icon + ' fs-4');
        $('#preview-icon-wrapper').css({
            'color': color,
            'background': 'rgba(' + hexToRgb(color) + ', 0.1)'
        });
        $('#icon-preview').attr('class', icon + ' fs-5').css('color', color);
    }

    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        if(!result) return "0, 117, 205";
        return parseInt(result[1], 16) + ", " + parseInt(result[2], 16) + ", " + parseInt(result[3], 16);
    }

    $('#name, #icon').on('input', updateVisualPreview);
    
    $('#color_primary').on('input', function() {
        $('#color_primary_hex').val($(this).val());
        updateVisualPreview();
    });
    
    $('#color_primary_hex').on('input', function() {
        var val = $(this).val();
        if(/^#[0-9A-F]{6}$/i.test(val)) {
            $('#color_primary').val(val);
            updateVisualPreview();
        }
    });

    $('#color_secondary').on('input', function() {
        $('#color_secondary_hex').val($(this).val());
    });
    
    $('#color_secondary_hex').on('input', function() {
        var val = $(this).val();
        if(/^#[0-9A-F]{6}$/i.test(val)) {
            $('#color_secondary').val(val);
        }
    });

    $(document).ready(function() {
        updateVisualPreview();
    });

    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$tipo_cocina ? route('tipos_cocina.update') : route('tipos_cocina.store')}}";
        $.ajax({
            url:url,
            method:'POST',
            data:{
                name:$('#name').val(),
                icon:$('#icon').val(),
                color_primary:$('#color_primary').val(),
                color_secondary:$('#color_secondary').val(),
                id:"{{$tipo_cocina ? $tipo_cocina->id : ''}}"
            },
            success:function(res) {
                if(res.estado == 200) {
                    window.location.href = "{{route('tipos_cocina.lista')}}";
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error',res.mensaje,'danger');
                }
            },
            error:function() {
                $('.submitButton').prop('disabled',false);
                notify('Error','Error al procesar la solicitud','danger');
            }
        });
    });

    $(document).on('click','.crearYQuedarseButton', function() {
        if(!validar()) return false;
        $('.crearYQuedarseButton').prop('disabled',true);

        $.ajax({
            url: "{{route('tipos_cocina.store')}}",
            method:'POST',
            data:{
                name:$('#name').val(),
                icon:$('#icon').val(),
                color_primary:$('#color_primary').val(),
                color_secondary:$('#color_secondary').val()
            },
            success:function(res) {
                if(res.estado == 200) {
                    notify('Éxito', res.mensaje || 'Tipo de cocina creado correctamente', 'success');
                    $('#name').val('');
                    $('.crearYQuedarseButton').prop('disabled',false);
                    $('#name').focus();
                    updateVisualPreview();
                } else {
                    $('.crearYQuedarseButton').prop('disabled',false);
                    notify('Error',res.mensaje || 'Error','danger');
                }
            },
            error:function() {
                $('.crearYQuedarseButton').prop('disabled',false);
                notify('Error','Error al procesar la solicitud','danger');
            }
        });
    });

    function validar() {
        if($('#name').val() == '') {
            notify('Error','Debe ingresar el nombre del tipo de cocina','danger');
            return false;
        }
        return true;
    }
</script>
@endsection
