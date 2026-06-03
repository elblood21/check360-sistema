@extends('layouts.master')
@section('title', 'Visitas Realizadas')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<style>
    .badge-paid { background-color: rgba(39, 174, 96, 0.1); color: #27ae60; border: 1px solid #27ae60; font-weight: 700; }
    .badge-id { background-color: #f8f9fa; color: #333; font-weight: 700; letter-spacing: 0.5px; border: 1px solid #dee2e6; }
    .text-amount { font-family: 'Courier New', Courier, monospace; font-weight: bold; }
    .status-pill { padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .table thead th { border-top: none; background: #f9fafb; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #6b7280; }
</style>
@endsection

@section('breadcrumb-title')
<h3>Historial de Visitas Finalizadas</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Visitas</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-primary">Registro de Auditoría</h5>
                            <p class="mb-0 text-muted small">Listado de visitas con encuestas finalizadas y detalles de canje.</p>
                        </div>
                        <button class="btn btn-primary btn-sm rounded-pill px-3 actualizar" title="Actualizar listado">
                            <i class="fa fa-refresh me-1"></i> Refrescar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Referencia</th>
                                    <th>Finalización</th>
                                    <th>Cupón</th>
                                    <th class="text-end">Monto Consumo</th>
                                    <th class="text-end">Monto Descuento</th>
                                    <th class="text-end pe-4">Total Pagado</th>
                                </tr>
                            </thead>
                            <tbody id="visitastabla">
                                <!-- Cargado vía AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3">
                    <nav class="d-flex">
                        <ul class="pagination m-auto me-0"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
        var data = [];
        var pagination = {'current_page': 1};
        getData();

        $('.actualizar').click(function() {
            getData();
        });

        function getData() {
            $('#visitastabla').html('<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2 text-muted">Buscando registros certeros...</p></td></tr>');
            
            $.ajax({
                url:'{{route("resultados.getdata_restaurante")}}',
                method:'POST',
                data:{
                    page:pagination.current_page
                },
                success:function(res) {
                    if (res.estado == 403) {
                        window.location.href = "{{ route('dashboard') }}";
                        return;
                    }

                    data = res.data;
                    completarData();

                    pagination.total = res.total;
                    pagination.from = res.from;
                    pagination.to = res.to;
                    if(res.from == null) { pagination.from = 0; pagination.to = 0; }
                    pagination.last_page = res.last_page;

                    $('ul.pagination').html(completarNav(res,pagination));
                },
                error: function() {
                    $('#visitastabla').html('<tr><td colspan="6" class="text-center py-5 text-danger"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><br>Error al conectar con el servidor.</td></tr>');
                }
            })
        }

        function detectSearch(pagina) {
            if(pagina == 'siguiente') pagination['current_page'] = Number(pagination['current_page']) + 1;
            else if(pagina == 'anterior') pagination['current_page'] = Number(pagination['current_page']) - 1;
            else if(pagina) pagination['current_page'] = pagina;
            getData();
        }

        function completarData() {
            $('#visitastabla').html('');
            if (data.length === 0) {
                $('#visitastabla').html('<tr><td colspan="6" class="text-center py-5"><i class="fa fa-info-circle fa-2x text-muted mb-2"></i><br><span class="text-muted">No se registran visitas finalizadas aún.</span></td></tr>');
                return;
            }

            var toappend = "";
            $.each(data,function(i,d) {
                toappend += "<tr>";
                
                // Referencia
                toappend += '<td class="ps-4"><span class="badge badge-id">' + d.id_ref + '</span></td>';
                
                // Fecha de Finalización (Survey completion)
                toappend += '<td><div class="small fw-bold">' + d.fecha_finalizada.split(' ')[0] + '</div><div class="text-muted small">' + d.fecha_finalizada.split(' ')[1] + '</div></td>';

                // Cupón Status
                var cuponHtml = '';
                if(d.cupon_canjeado) {
                    cuponHtml = '<span class="status-pill" style="background: #e6fffa; color: #047481; border: 1px solid #047481;"><i class="fa fa-check-circle me-1"></i>Canjeado</span>';
                } else {
                    cuponHtml = '<span class="status-pill" style="background: #fff5f5; color: #c53030; border: 1px solid #c53030;"><i class="fa fa-clock-o me-1"></i>Pendiente</span>';
                }
                toappend += '<td>' + cuponHtml + ' <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">' + (d.cupon_codigo || '-') + '</small></td>';

                // Monto Total Consumo
                toappend += '<td class="text-end text-amount">$' + new Intl.NumberFormat('es-CL').format(d.monto_total) + '</td>';

                // Monto Descuento
                toappend += '<td class="text-end text-amount text-danger">-$' + new Intl.NumberFormat('es-CL').format(d.descuento_aplicado) + '</td>';

                // Total Pagado Final
                toappend += '<td class="text-end pe-4"><span class="badge badge-paid" style="font-size: 0.9rem;">$' + new Intl.NumberFormat('es-CL').format(d.total_pagado) + '</span></td>';

                toappend += "</tr>";
            });
            $('#visitastabla').html(toappend);
        }
    </script>
@endsection
