@extends('layouts.master')
@section('title', '¡Tu Cupón de Descuento!')

@section('style')
<style>
    .coupon-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 900px;
        margin: 0 auto;
    }
    
    .coupon-card::before, .coupon-card::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        background: #f1f5f9; /* Debe coincidir con el fondo del sitio */
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }
    
    .coupon-card::before {
        left: -15px;
        box-shadow: inset -5px 0 10px rgba(0,0,0,0.2);
    }
    
    .coupon-card::after {
        right: -15px;
        box-shadow: inset 5px 0 10px rgba(0,0,0,0.2);
    }

    .coupon-header {
        border-bottom: 2px dashed rgba(255, 255, 255, 0.15);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
    }
    
    .coupon-badge {
        background: linear-gradient(45deg, #f59e0b, #ef4444);
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        letter-spacing: 1px;
    }
    
    .discount-value {
        font-size: 5rem;
        font-weight: 900;
        line-height: 1;
        background: linear-gradient(to right, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 1.5rem 0 0.5rem 0;
        font-family: 'Outfit', 'Inter', sans-serif;
    }
    
    .coupon-body {
        padding: 2.5rem 2rem;
        text-align: center;
    }
    
    .barcode {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 320px;
    }
    
    .code-display {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 4px;
        color: #0f172a;
    }
    
    .btn-copy {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        cursor: pointer;
    }
    
    .btn-copy:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    
    .btn-copy:active {
        transform: translateY(0);
    }

    .instructions-list {
        text-align: left;
        color: #94a3b8;
        font-size: 1.05rem;
        background: transparent;
    }

    .instructions-list li {
        margin-bottom: 1.2rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .instructions-list li:last-child {
        margin-bottom: 0;
    }
    
    .instructions-list i {
        color: #10b981;
        margin-top: 0.3rem;
        font-size: 1.2rem;
    }
    
    @media (min-width: 992px) {
        .instructions-col {
            border-left: 2px dashed rgba(255, 255, 255, 0.15);
        }
    }
    
    #qrcode img {
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center g-4">
        <div class="col-12">
            <div class="coupon-card">
                <div class="row g-0 align-items-stretch">
                    <!-- Left side: Discount & QR -->
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <!-- Encabezado del Cupón -->
                        <div class="coupon-header">
                            <span class="coupon-badge">Mistery Shopper</span>
                            <div class="discount-value">
                                {{ $restaurante->porcentaje_descuento ?? 50 }}%
                            </div>
                            <h4 class="text-white font-weight-bold mb-1">{{ $restaurante->name }}</h4>
                            <p class="text-muted mb-0"><i class="fa fa-map-marker text-danger"></i> {{ $restaurante->direccion }}</p>
                        </div>
                        
                        <!-- Cuerpo del Cupón -->
                        <div class="coupon-body d-flex flex-column align-items-center flex-grow-1">
                            <!-- Código QR Premium -->
                            <div class="barcode p-4 d-flex flex-column align-items-center justify-content-center">
                                <div id="qrcode" class="mb-3 w-100 d-flex justify-content-center"></div>
                                <div class="code-display text-center" id="couponCode">{{ $visita->cupon_codigo }}</div>
                            </div>
                            </div>
                    </div>
                    
                    <!-- Right side: Instructions -->
                    <div class="col-12 col-lg-6 instructions-col d-flex flex-column justify-content-center p-4 p-lg-5">
                        <div class="instructions-list h-100 d-flex flex-column justify-content-center">
                            <h4 class="text-white mb-4"><i class="fa fa-info-circle text-warning mr-2"></i> Instrucciones de uso</h4>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Presenta este código al cajero/camarero al momento de pedir tu cuenta.</span>
                                </li>
                                <li>
                                    <i class="fa fa-check-circle"></i>
                                    <span>El cajero ingresará el código en la plataforma para aplicar tu <strong>descuento del {{ $restaurante->porcentaje_descuento ?? 50 }}%</strong>.</span>
                                </li>
                                <li>
                                    <i class="fa fa-check-circle"></i>
                                    <span>El descuento se aplica sobre el total de tu consumo registrado en el local.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    $(document).ready(function() {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $visita->cupon_codigo }}",
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });

    function copyCouponCode() {
        var codeText = document.getElementById("couponCode").innerText;
        navigator.clipboard.writeText(codeText).then(function() {
            var btnCopyText = document.getElementById("btnCopyText");
            btnCopyText.innerText = "¡Código Copiado!";
            notify('Éxito', 'Código de cupón copiado al portapapeles', 'success');
            setTimeout(function() {
                btnCopyText.innerText = "Copiar Código";
            }, 3000);
        }).catch(function(err) {
            console.error('Error al copiar el código: ', err);
            notify('Error', 'No se pudo copiar el código', 'danger');
        });
    }
</script>
@endsection