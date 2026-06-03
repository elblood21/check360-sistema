@extends('layouts.master')
@section('title', 'Canje de Cupón de Descuento')

@section('style')
<style>
    .canje-card {
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }
    
    .search-btn {
        background: #0075cd;
        border: none;
        color: white;
        border-radius: 0 10px 10px 0;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        background: #005fa6;
    }
    
    .confirm-btn {
        background: #27ae60;
        border: none;
        color: white;
        border-radius: 10px;
        font-weight: 700;
        padding: 0.8rem 2rem;
        transition: all 0.3s ease;
    }
    
    .confirm-btn:hover {
        background: #219150;
        transform: translateY(-1px);
    }

    .badge-discount {
        background: rgba(0, 117, 205, 0.1);
        color: #0075cd;
        font-size: 1.2rem;
        font-weight: 800;
        padding: 0.4rem 1rem;
        border-radius: 8px;
        display: inline-block;
    }

    .billing-summary {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.2rem;
        border: 1px solid #e2e8f0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .summary-label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.9rem;
    }

    .summary-value {
        font-weight: 700;
        color: #0f172a;
    }

    .summary-value.total {
        color: #27ae60;
        font-size: 1.5rem;
    }

    /* Switch Style */
    .form-switch .form-check-input {
        width: 2.5em;
        cursor: pointer;
    }

    .input-premium {
        border: 2px solid #f1f5f9;
        border-radius: 10px;
        padding: 10px 15px;
        font-weight: 600;
        transition: border-color 0.2s;
    }
    .input-premium:focus {
        border-color: #0075cd;
        box-shadow: none;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Canje de Cupón</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Canje</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-6 col-lg-8">
            
            <!-- Buscador -->
            <div class="card canje-card p-4 mb-4" id="buscadorCard">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold mb-0 text-primary">VALIDAR CÓDIGO</h6>
                    <button class="btn btn-link btn-sm text-muted p-0" onclick="toggleScanner()"><i class="fa fa-qrcode"></i> Escanear</button>
                </div>
                
                <div id="qr-reader-container" class="mb-3 d-none">
                    <div id="qr-reader" style="width: 100%; border-radius: 12px; overflow: hidden;"></div>
                    <button class="btn btn-sm btn-danger w-100 mt-2" onclick="stopScanner()">Cerrar</button>
                </div>

                <form id="buscarForm" onsubmit="event.preventDefault(); buscarCupon();">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg text-uppercase" id="codigoCupon" placeholder="CK360XXXXXX" style="border-radius: 10px 0 0 10px; font-weight: 700; border: 2px solid #0075cd;">
                        <button type="submit" class="btn search-btn px-4">BUSCAR</button>
                    </div>
                </form>
            </div>
            
            <!-- Detalle de Cobro -->
            <div class="card canje-card p-4 d-none" id="detalleCanjeCard">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success-subtle p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #e6f7ee;">
                        <i class="fa fa-check text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bold" id="shopperName">-</h6>
                        <span class="text-muted small">Cupón válido disponible</span>
                    </div>
                    <div class="ms-auto">
                        <div class="badge-discount" id="porcentajeDescuento">0%</div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label font-weight-bold small text-muted">TOTAL CONSUMO BRUTO</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-2 border-end-0" style="border-radius: 10px 0 0 10px; border-color: #f1f5f9;">$</span>
                        <input type="text" class="form-control form-control-lg input-premium border-start-0" id="totalConsumoFormat" placeholder="0" style="border-color: #f1f5f9;">
                        <input type="hidden" id="totalConsumo" value="0">
                    </div>
                    <span class="text-muted small mt-1 d-block">Monto total antes de aplicar el descuento.</span>
                </div>

                <!-- Switch para Documento -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="switchDocumento" onchange="toggleDocumento()">
                    <label class="form-check-label small fw-bold text-muted" for="switchDocumento">¿Registrar número de boleta o factura?</label>
                </div>

                <div id="documentoExtra" class="row g-2 mb-4 d-none">
                    <div class="col-5">
                        <select class="form-select input-premium" id="documento_tipo">
                            <option value="boleta">Boleta</option>
                            <option value="factura">Factura</option>
                        </select>
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control input-premium" id="documento_numero" placeholder="N° de documento">
                    </div>
                </div>
                
                <div class="billing-summary mb-4">
                    <div class="summary-row">
                        <span class="summary-label">Monto Bruto:</span>
                        <span class="summary-value" id="brutoLabel">$0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Descuento (<span id="descuentoPorcentajeLabel">0</span>%):</span>
                        <span class="summary-value text-danger" id="descuentoLabel">-$0</span>
                    </div>
                    <hr class="my-2" style="opacity: 0.1;">
                    <div class="summary-row">
                        <span class="summary-label text-dark" style="font-size: 1rem;">TOTAL A COBRAR:</span>
                        <span class="summary-value total" id="netoLabel">$0</span>
                    </div>
                </div>
                
                <div class="text-center">
                    <input type="hidden" id="visitaIdEncrypted">
                    <button type="button" class="btn btn-lg confirm-btn w-100" id="btnConfirmar" onclick="confirmarCanje()">
                        CONFIRMAR CANJE
                    </button>
                    <button class="btn btn-link btn-sm mt-3 text-muted" onclick="reiniciarPanel()">Cancelar</button>
                </div>
            </div>
            
            <!-- ?xito -->
            <div class="card canje-card p-5 text-center d-none" id="exitoCanjeCard">
                <i class="fa fa-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                <h4 class="font-weight-bold mb-2">¡Canje Exitoso!</h4>
                <p class="text-muted mb-4 small">El descuento ha sido aplicado correctamente.</p>
                
                <div class="billing-summary mb-4" style="max-width: 300px; margin: 0 auto;">
                    <div class="summary-row">
                        <span class="summary-label small">Descuento:</span>
                        <span class="summary-value text-danger" id="exitoDescuentoLabel">-$0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label text-dark">Pagado:</span>
                        <span class="summary-value total" id="exitoNetoLabel">$0</span>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="reiniciarPanel()">
                    Validar otro cupón
                </button>
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    var descuentoPorcentajeGlobal = 0;
    var html5QrCode = null;

    // Formateo de moneda en tiempo real
    $('#totalConsumoFormat').on('input', function() {
        let value = $(this).val().replace(/\D/g, ""); // Solo números
        if (value === "") {
            $('#totalConsumo').val(0);
            $(this).val("");
        } else {
            let num = parseInt(value);
            $('#totalConsumo').val(num);
            $(this).val(new Intl.NumberFormat('es-CL').format(num));
        }
        calcularTotales();
    });

    function toggleDocumento() {
        if ($('#switchDocumento').is(':checked')) {
            $('#documentoExtra').removeClass('d-none');
        } else {
            $('#documentoExtra').addClass('d-none');
        }
    }

    function toggleScanner() {
        $('#qr-reader-container').toggleClass('d-none');
        if (!$('#qr-reader-container').hasClass('d-none')) {
            startScanner();
        } else {
            stopScanner();
        }
    }

    function startScanner() {
        if (!html5QrCode) { html5QrCode = new Html5Qrcode("qr-reader"); }
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
            $('#codigoCupon').val(decodedText);
            stopScanner();
            $('#qr-reader-container').addClass('d-none');
            buscarCupon();
        }).catch((err) => { notify('Error', 'No se pudo acceder a la cámara', 'danger'); });
    }

    function stopScanner() {
        if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
    }

    function buscarCupon() {
        var codigo = document.getElementById("codigoCupon").value.trim();
        if (codigo === "") { notify('Advertencia', 'Ingrese un código', 'warning'); return; }
        
        $("#buscarForm button").prop('disabled', true).text('...');
        
        $.ajax({
            url: '{{ route("restaurantes.canje.validar") }}',
            method: 'POST',
            data: { codigo: codigo, _token: '{{ csrf_token() }}' },
            success: function(res) {
                $("#buscarForm button").prop('disabled', false).text('BUSCAR');
                if (res.estado == 200) {
                    notify('¡Válido!', 'Cupón encontrado', 'success');
                    descuentoPorcentajeGlobal = res.porcentaje_descuento;
                    document.getElementById("visitaIdEncrypted").value = res.visita_id;
                    document.getElementById("shopperName").innerText = res.shopper_name;
                    document.getElementById("porcentajeDescuento").innerText = res.porcentaje_descuento + "%";
                    document.getElementById("descuentoPorcentajeLabel").innerText = res.porcentaje_descuento;
                    
                    $('#totalConsumoFormat').val("");
                    $('#totalConsumo').val(0);
                    calcularTotales();
                    
                    $("#detalleCanjeCard").removeClass('d-none');
                    $("#buscadorCard").addClass('d-none');
                } else {
                    notify('Error', res.mensaje || 'Cupón no válido', 'danger');
                }
            },
            error: function() {
                $("#buscarForm button").prop('disabled', false).text('BUSCAR');
                notify('Error', 'Error de conexión', 'danger');
            }
        });
    }

    function calcularTotales() {
        var total = parseInt(document.getElementById("totalConsumo").value) || 0;
        var descuento = total * (descuentoPorcentajeGlobal / 100.0);
        var neto = total - descuento;

        document.getElementById("brutoLabel").innerText = "$" + formatNumber(total);
        document.getElementById("descuentoLabel").innerText = "-$" + formatNumber(descuento);
        document.getElementById("netoLabel").innerText = "$" + formatNumber(neto);
    }

    function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function confirmarCanje() {
        var totalConsumo = parseInt(document.getElementById("totalConsumo").value);
        if (totalConsumo <= 0) { notify('Error', 'Ingrese un monto válido', 'warning'); return; }

        $("#btnConfirmar").prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>...');

        $.ajax({
            url: '{{ route("restaurantes.canje.confirmar") }}',
            method: 'POST',
            data: {
                visita_id: document.getElementById("visitaIdEncrypted").value,
                total_consumo: totalConsumo,
                guardar_documento: $('#switchDocumento').is(':checked') ? 1 : 0,
                documento_tipo: $('#documento_tipo').val(),
                documento_numero: $('#documento_numero').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.estado == 200) {
                    notify('¡Éxito!', 'Canje realizado', 'success');
                    document.getElementById("exitoDescuentoLabel").innerText = "-$" + res.descuento;
                    document.getElementById("exitoNetoLabel").innerText = "$" + res.pagado;
                    $("#detalleCanjeCard").addClass('d-none');
                    $("#exitoCanjeCard").removeClass('d-none');
                } else {
                    $("#btnConfirmar").prop('disabled', false).text('CONFIRMAR CANJE');
                    notify('Error', res.mensaje, 'danger');
                }
            },
            error: function() {
                $("#btnConfirmar").prop('disabled', false).text('CONFIRMAR CANJE');
                notify('Error', 'Error de servidor', 'danger');
            }
        });
    }

    function reiniciarPanel() {
        $('#codigoCupon').val("");
        $("#buscadorCard").removeClass('d-none');
        $("#exitoCanjeCard").addClass('d-none');
        $("#detalleCanjeCard").addClass('d-none');
        $('#switchDocumento').prop('checked', false);
        $('#documentoExtra').addClass('d-none');
    }
</script>
@endsection
