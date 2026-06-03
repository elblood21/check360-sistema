@forelse($restaurantes_disponibles ?? [] as $rest)
@php
    $imgs = $rest->imagenes;
    if(!is_array($imgs)) $imgs = [];
    $portada = count($imgs) > 0 ? $imgs[0] : asset('assets/images/dashboard/bg.jpg');
    $logo = $rest->logo ? $rest->logo : asset('assets/images/dashboard/avtar.jpg');
    
    // Generar gradiente para el badge según su tipo de cocina
    if ($rest->tipoCocina) {
        $pCol = $rest->tipoCocina->color_primary ?? '#0075cd';
        $sCol = $rest->tipoCocina->color_secondary ?? $pCol;
        $cuisineGradient = "linear-gradient(135deg, $pCol 0%, $sCol 100%)";
    } else {
        $cuisineGradient = "linear-gradient(135deg, #0075cd 0%, #005fa6 100%)";
    }
@endphp
<div class="col-xl-4 col-md-6 mb-4 restaurante-item" 
     data-nombre="{{ strtolower($rest->name) }}"
     data-cocina="{{ $rest->tipo_cocina_id }}"
     data-region="{{ $rest->ciudad ? $rest->ciudad->region_id : '' }}"
     data-ciudad="{{ $rest->ciudad_id }}"
     data-descuento="{{ $rest->porcentaje_descuento }}">
    
    <div class="card h-100 border-0 shadow-sm overflow-hidden position-relative card-restaurante">
        <!-- Imagen de Portada -->
        <div class="card-img-container" style="height: 180px; overflow: hidden; position: relative;">
            <div class="card-img-bg" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('{{ $portada }}'); background-size: cover; background-position: center;"></div>
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,0.6) 100%); z-index: 1;"></div>
            
            <!-- Badge Porcentaje Descuento -->
            <span class="badge bg-danger fs-6 fw-bold position-absolute" style="top: 15px; right: 15px; border-radius: 0.5rem; box-shadow: 0 4px 10px rgba(220,53,69,0.3); z-index: 2;">
                {{ $rest->porcentaje_descuento }}% OFF
            </span>

            <div class="position-absolute bottom-0 start-0 p-3 text-white" style="z-index: 2;">
                <span class="badge text-capitalize mb-1" style="background: {{ $cuisineGradient }}; color: white; border: none; font-weight: 600; padding: 5px 10px; border-radius: 6px;">
                    @if($rest->tipoCocina)
                        <i class="icofont {{ $rest->tipoCocina->icon ?? 'icofont-restaurant' }} me-1"></i>
                    @endif
                    {{ $rest->tipoCocina ? $rest->tipoCocina->name : 'Cocina' }}
                </span>
                <h5 class="fw-bold mb-0 text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $rest->name }}</h5>
            </div>
        </div>

        <!-- Cuerpo de la Tarjeta -->
        <div class="card-body p-3">
            <div class="d-flex align-items-center mb-3">
                <img src="{{ $logo }}" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                <div class="ms-3 text-dark">
                    <p class="mb-0 small text-muted"><i class="icofont icofont-location-pin text-primary"></i> {{ $rest->ciudad ? $rest->ciudad->nombre : 'N/A' }}, {{ $rest->ciudad && $rest->ciudad->region ? $rest->ciudad->region->numero : 'N/A' }}</p>
                    <p class="mb-0 small text-muted"><i class="icofont icofont-money text-success"></i> Ticket: {{ $rest->rango_ticket_promedio ?? 'N/A' }}</p>
                </div>
            </div>
            
            <!-- Redes Sociales -->
            <div class="d-flex gap-2 mb-3" style="position: relative; z-index: 3;">
                @if($rest->social_instagram)
                    <a href="{{ $rest->social_instagram }}" target="_blank" class="btn btn-xs btn-outline-danger px-2 py-1 small rounded"><i class="icofont icofont-social-instagram"></i> Instagram</a>
                @endif
                @if($rest->social_facebook)
                    <a href="{{ $rest->social_facebook }}" target="_blank" class="btn btn-xs btn-outline-primary px-2 py-1 small rounded"><i class="icofont icofont-social-facebook"></i> Facebook</a>
                @endif
                @if($rest->social_tiktok)
                    <a href="{{ $rest->social_tiktok }}" target="_blank" class="btn btn-xs btn-outline-dark px-2 py-1 small rounded"><i class="icofont icofont-play"></i> TikTok</a>
                @endif
            </div>

            <a href="{{ route('shopper.restaurante_detalle', encrypt($rest->id)) }}" class="btn btn-primary btn-block w-100 py-2 fw-bold stretched-link" 
                    style="border-radius: 0.5rem;">
                <i class="icofont icofont-eye"></i> Ver Carta
            </a>
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <i class="icofont icofont-restaurant text-muted" style="font-size: 5rem;"></i>
    <h5 class="mt-3 text-muted">No hay restaurantes activos disponibles en este momento.</h5>
</div>
@endforelse
