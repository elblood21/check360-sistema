<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="#" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="{{ route('index')}}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo_check360.png') }}" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    
    <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
      <ul class="nav-menus">

        @php
          $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
          $user = Auth::guard($guard)->user();
          $restaurante = ($guard == 'restaurante' && $user) ? $user->restaurante : null;
        @endphp

        @if($restaurante)
          <li class="p-0 me-3 d-flex align-items-center">
            <a href="javascript:void(0)" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" id="btn-scan-qr-top" title="Escanear QR" style="width: 34px; height: 34px; border: none; padding: 0;">
                <i class="fa fa-qrcode text-white" style="font-size: 16px;"></i>
            </a>
          </li>
        @endif

        <li class="profile-nav onhover-dropdown pe-0 py-0">
          <div class="media profile-media d-flex align-items-center">
            @if($restaurante)
              <img class="rounded-circle border me-2" src="{{ $restaurante->logo ? asset($restaurante->logo) : asset('assets/images/dashboard/avtar.jpg') }}" alt="" style="width: 36px !important; height: 36px !important; min-width: 36px !important; min-height: 36px !important; object-fit: cover; aspect-ratio: 1/1;">
            @endif
            <div class="media-body text-start"><span>{{$user ? $user->name : 'Usuario'}}</span>
              <p class="mb-0 font-roboto">
                @if($guard == 'web' && $user && $user->perfil)
                  {{$user->perfil->name}}
                @elseif($guard == 'shopper')
                  Mistery Shopper
                @elseif($guard == 'restaurante')
                  Restaurante
                @else
                  Usuario
                @endif
                <i class="middle fa fa-angle-down"></i>
              </p>
            </div>
          </div>
          <ul class="profile-dropdown onhover-show-div">
            <li><a style="font-size: 0.6rem;" href="#" id="toggleTheme"><i data-feather="moon"> </i><span>Tema claro/oscuro</span></a></li>
            <li><a style="font-size: 0.6rem;" class="cambiarPasswordM"><i data-feather="log-in"> </i><span>Cambiar contraseña</span></a></li>
            <li><a style="font-size: 0.6rem;" href="/desconectarse"><i data-feather="log-in"> </i><span>Desconectarse</span></a></li>
          </ul>
        </li>
      </ul>
</div>

</div>
<script>
  (function(){
    var key = 'erp-theme';
    try {
      var saved = localStorage.getItem(key);
      if (saved === 'dark') document.documentElement.setAttribute('data-theme','dark');
    } catch(e) {}
    document.addEventListener('DOMContentLoaded', function(){
      var t = document.getElementById('toggleTheme');
      if (!t) return;
      t.addEventListener('click', function(e){
        e.preventDefault();
        var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark':'light';
        var next = cur === 'dark' ? 'light' : 'dark';
        if (next === 'dark') document.documentElement.setAttribute('data-theme','dark');
        else document.documentElement.removeAttribute('data-theme');
        try { localStorage.setItem(key, next); } catch(e) {}
      });
    });
  })();
  </script>
</div>


<div class="modal fade cambiarPasswordModal" tabindex="-1" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
           <h4 class="modal-title" id="mySmallModalLabel">Cambiar password</h4>
           <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="cambiarPasswordMN">Nueva contraseña</label>
            <div class="position-relative">
              <input type="password" class="form-control form-control-sm btn-square" id="cambiarPasswordMN" placeholder="Contraseña" data-bs-original-title="" title="">
              <div class="show-hide"><span><i class="icofont icofont-eye-blocked"></i></span></div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="cambiarPasswordMC">Confirmar contraseña</label>
            <div class="position-relative">
              <input type="password" class="form-control form-control-sm btn-square" id="cambiarPasswordMC" placeholder="Contraseña" data-bs-original-title="" title="">
              <div class="show-hide"><span><i class="icofont icofont-eye-blocked"></i></span></div>
            </div>
          </div>
          <div class="mt-3 d-flex">
              <button class="w-100 btn btn-primary m-auto cambiarPasswordBtn" type="button" data-bs-original-title="" title="">Cambiar contraseña</button>
          </div>
        </div>
     </div>
  </div>
</div>

@if(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
<!-- Modal QR Scan Top -->
<div class="modal fade" id="modal-qr-scan-top" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary">Escanear Cupón</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="qr-reader-top" style="width: 100%; border-radius: 15px; overflow: hidden; background: #f8f9fa;"></div>
                
                <div class="mt-4" id="qr-input-area-top">
                    <label class="form-label small fw-bold text-muted">O INGRESA EL CÓDIGO MANUALMENTE</label>
                    <div class="input-group">
                        <input type="text" id="codigo-qr-manual-top" class="form-control text-uppercase fw-bold" placeholder="CK360XXXXXX" style="border-radius: 10px 0 0 10px; border: 2px solid #0075cd;">
                        <button class="btn btn-primary px-3" onclick="validarCuponTop()" style="border-radius: 0 10px 10px 0;">VALIDAR</button>
                    </div>
                </div>

                <!-- Area de confirmación -->
                <div id="qr-confirm-area-top" class="d-none mt-3">
                    <hr>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success-subtle p-2 rounded-circle me-2" style="background: #e6f7ee; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-check text-success"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold" id="qr-shopper-name-top">-</h6>
                            <span class="badge bg-primary text-white" id="qr-desc-badge-top" style="font-size: 0.7rem;">0% Desc.</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">TOTAL CONSUMO BRUTO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #f1f5f9;">$</span>
                            <input type="text" class="form-control input-premium-top border-start-0" id="qr-total-format-top" placeholder="0" style="border-color: #f1f5f9;">
                            <input type="hidden" id="qr-total-val-top" value="0">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="qr-switch-doc-top">
                        <label class="form-check-label small fw-bold text-muted" for="qr-switch-doc-top">¿Registrar Boleta/Factura?</label>
                    </div>

                    <div id="qr-doc-extra-top" class="row g-2 mb-3 d-none">
                        <div class="col-5">
                            <select class="form-select form-select-sm" id="qr-doc-tipo-top">
                                <option value="boleta">Boleta</option>
                                <option value="factura">Factura</option>
                            </select>
                        </div>
                        <div class="col-7">
                            <input type="text" class="form-control form-control-sm" id="qr-doc-num-top" placeholder="N°">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Descuento:</span>
                            <span class="text-danger fw-bold" id="qr-res-desc-top">-$0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total a Cobrar:</span>
                            <span class="text-success fw-bold fs-5" id="qr-res-neto-top">$0</span>
                        </div>
                    </div>

                    <input type="hidden" id="qr-visita-id-top">
                    <button class="btn btn-success w-100 py-2 fw-bold" onclick="confirmarCanjeTop()" id="qr-btn-confirm-top">CONFIRMAR CANJE</button>
                    <button class="btn btn-link btn-sm w-100 mt-2 text-muted" onclick="resetModalTop(); startQrScannerTop();">Volver a escanear</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
