
 <!-- latest jquery-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <!-- Bootstrap js-->
<script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/jquery/rut.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- scrollbar js-->
<script src="{{asset('assets/js/scrollbar/simplebar.js')}}"></script>
<script src="{{asset('assets/js/scrollbar/custom.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script id="menu" src="{{asset('assets/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('assets/js/header-slick.js') }}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="
https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js
"></script>

@if(Route::current()->getName() != 'popover') 
	<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
@endif

<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('assets/js/script.js')}}"></script>

<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	function notify(titulo,mensaje,tipo,duracion=2000) {

		$.notify({
			title:titulo,
			message:mensaje
		 },
		 {
			type:tipo,
			allow_dismiss:false,
			newest_on_top:false ,
			mouse_over:false,
			showProgressbar:false,
			spacing:10,
			timer:duracion,
			placement:{
			  from:'top',
			  align:'right'
			},
			offset:{
			  x:30,
			  y:30
			},
			delay:1000 ,
			z_index:10000,
			animate:{
			  enter:'animated bounce',
			  exit:'animated bounce'
		  }
		});

	}

	function completarNav(res,pagination) {
		paginationstring = "";
		if(res.last_page > 1) {
			var anteriorstring = "";
			var siguientestring = "";
			if(res.current_page == 1) {
				anteriorstring = "disabled";
			}
			if(res.last_page == pagination.current_page) {
				siguientestring = "disabled";
			}

			//primera pagina
			paginationstring += '<li class="page-item primerapagina '+anteriorstring+'">';
			if($(window).width() >= 768) paginationstring += '<a class="page-link" ><span aria-hidden="true">Primera</span>';
			else paginationstring += '<a class="page-link mobile" ><span aria-hidden="true"><<</span>';
			paginationstring += '</a>';
			paginationstring += '</li>';

			// boton anterior
			paginationstring += '<li class="page-item anterior '+anteriorstring+'">';
			if($(window).width() >= 768) paginationstring += '<a class="page-link" ><span aria-hidden="true">Anterior</span></a>';
			else paginationstring += '<a class="page-link mobile" ><span aria-hidden="true"><</span></a>';
			paginationstring += '</li>';

			$.each(getpaginacion(res.last_page), function(ipage, dpage) {
				var actualpage = "";
				if(dpage == pagination.current_page) { actualpage = "active"; }
				paginationstring += '<li data-page="'+dpage+'" class="page-item num '+actualpage+'"><a class="page-link">'+dpage+'</a></li>';
			});


			// boton siguiente
			paginationstring += '<li class="page-item siguiente '+siguientestring+'">';
			if($(window).width() >= 768) paginationstring += '<a class="page-link" ><span aria-hidden="true">Siguiente</span></a>';
			else paginationstring += '<a class="page-link mobile" ><span aria-hidden="true">></span></a>';
			paginationstring += '</li>';

			//ultima pagina
			paginationstring += '<li data-page="'+res.last_page+'" class="page-item ultimapagina '+siguientestring+'">';
			if($(window).width() >= 768) paginationstring += '<a class="page-link" ><span aria-hidden="true">Ultima</span>';
			else paginationstring += '<a class="page-link mobile" ><span aria-hidden="true">>></span>';
			paginationstring += '</a>';
			paginationstring += '</li>';
		}

		return paginationstring;
	}
	
	function getpaginacion(ultima) {

        var paginacionArray = [];
        var paginacionArrayUSE = [];

        for(var i = 1;i<=ultima;i++){
            paginacionArray.push(i);
        }
        console.log(paginacionArray);
        var pagesmin = jQuery.inArray(pagination.current_page, paginacionArray)-2;

        var pagesmax = jQuery.inArray(pagination.current_page, paginacionArray)+2;
        console.log(pagesmin);
        console.log(pagesmax);
        if(pagesmin < 1) {
            pagesmin++;
            pagesmax++;
            if(pagesmin < 1) {
            pagesmin = 1;
            pagesmax++;
        }
        }
        if(pagesmax > ultima) {
        pagesmax--;
        if(pagesmin > 1) { pagesmin--; }
        if(pagesmax > ultima) {
        pagesmax--;
        if(pagesmin > 1) { pagesmin--; }
        if(pagesmax > ultima) {
        pagesmax--; }
        if(pagesmax > ultima) {
        pagesmax--; }
        if(pagesmax > ultima) {
        pagesmax--; }
        }
        }
        for(var i = pagesmin;i<=pagesmax;i++) {
        paginacionArrayUSE.push(i);
        }
        return paginacionArrayUSE;
    }

    $(document).on('click', '.page-item.num', function() {
        detectSearch($(this).data('page'));
    });
    $(document).on('click', '.page-item.siguiente:not(.disabled)', function() {
        detectSearch("siguiente");
    });
    $(document).on('click', '.page-item.anterior:not(.disabled)', function() {
        detectSearch("anterior");
    });
    $(document).on('click', '.page-item.primerapagina:not(.disabled)', function() {
        detectSearch(1);
    });
    $(document).on('click', '.page-item.ultimapagina:not(.disabled)', function() {
        detectSearch($(this).data('page'));
    });

	$(document).on('keyup','.monto-input',function() {
        var p = $(this).val().replace(/\./g,' ').replace(/\D/g,'');
        $(this).val(formatPrecio(p));
    });

	function formatPrecio(monto) {
        monto = Math.ceil(monto).toString();
        var valor = "";
        if(monto.length > 0 && monto.length <= 3) {
          valor += monto;
        } else if(monto.length == 4) {
          valor += monto[0]+"."+monto.substr(1,3);
        } else if(monto.length == 5) {
          valor += monto.substr(0,2)+"."+monto.substr(2,5);
        } else if(monto.length == 6) {
          valor += monto.substr(0,3)+"."+monto.substr(3,5);
        } else if(monto.length == 7) {
          valor += monto[0]+"."+monto.substr(1,3)+"."+monto.substr(4,6);
        } else if(monto.length == 8) {
          valor += monto.substr(0,2)+"."+monto.substr(2,3)+"."+monto.substr(5,7);
        } else if(monto.length == 9) {
          valor += monto.substr(0,3)+"."+monto.substr(3,3)+"."+monto.substr(6,8);
        }
        return valor;
      }

	  // Password show/hide functionality
	  $(document).on('click', '.show-hide span', function () {
		var input = $(this).closest('.position-relative').find('input[type="password"], input[type="text"]');
		var icon = $(this).find('i');
		
		if (input.attr('type') === 'password') {
			input.attr('type', 'text');
			icon.removeClass('icofont-eye-blocked').addClass('icofont-eye-alt');
		} else {
			input.attr('type', 'password');
			icon.removeClass('icofont-eye-alt').addClass('icofont-eye-blocked');
		}
	  });

	  var cambiarPasswordMODAL = new bootstrap.Modal(document.getElementsByClassName('cambiarPasswordModal')[0], {})

	  $('.cambiarPasswordM').click(function() {
		cambiarPasswordMODAL.show();
	  });

	  $('.cambiarPasswordBtn').click(function() {
		var contra = $('#cambiarPasswordMN').val();
		var contra2 = $('#cambiarPasswordMC').val();

		if(contra != contra2) {
			notify('Advertencia','Las contraseñas no coinciden','danger');
            return false;
		}

		$.ajax({
			url:'{{route("usuarios.cambiarContra")}}',
			method:'POST',
			data:{contra:contra},
			success:function(res) {
				if(res.estado == 200) {
					notify('Exito','Contraseña cambiada con exito','primary');
					cambiarPasswordMODAL.hide();
            		return false;
				}
			}
		})

	  });
</script>

@if(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    var qrScannerTop = null;
    var qrDescGlobalTop = 0;

    $(document).on('click', '#btn-scan-qr-top', function() {
        $('#modal-qr-scan-top').modal('show');
    });

    $(document).ready(function() {
        $('#modal-qr-scan-top').on('shown.bs.modal', function () {
            startQrScannerTop();
        });

        $('#modal-qr-scan-top').on('hidden.bs.modal', function () {
            stopQrScannerTop();
            resetModalTop();
        });
    });

    function startQrScannerTop() {
        if (!qrScannerTop) { qrScannerTop = new Html5Qrcode("qr-reader-top"); }
        qrScannerTop.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, (decodedText) => {
            $('#codigo-qr-manual-top').val(decodedText);
            validarCuponTop();
        }).catch(err => { 
            console.error("Error al iniciar escáner:", err);
            let msg = "No se pudo acceder a la cámara.";
            if (err.name === 'NotAllowedError') msg = "Permiso de cámara denegado.";
            else if (err.name === 'NotFoundError') msg = "No se encontró una cámara disponible.";
            notify('Error', msg, 'danger');
        });
    }

    function stopQrScannerTop() {
        if (qrScannerTop && qrScannerTop.getState() === 2) { // 2 = SCANNING
            qrScannerTop.stop().catch(err => { console.warn("Error al detener:", err); });
        }
    }

    function resetModalTop() {
        $('#qr-confirm-area-top').addClass('d-none');
        $('#qr-input-area-top').removeClass('d-none');
        $('#codigo-qr-manual-top').val('');
        $('#qr-total-format-top').val('');
        $('#qr-total-val-top').val(0);
        $('#qr-switch-doc-top').prop('checked', false);
        $('#qr-doc-extra-top').addClass('d-none');
    }

    $(document).on('input', '#qr-total-format-top', function() {
        let value = $(this).val().replace(/\D/g, "");
        if (value === "") { $('#qr-total-val-top').val(0); $(this).val(""); }
        else {
            let num = parseInt(value);
            $('#qr-total-val-top').val(num);
            $(this).val(new Intl.NumberFormat('es-CL').format(num));
        }
        calcularTotalesTop();
    });

    $(document).on('change', '#qr-switch-doc-top', function() {
        if ($(this).is(':checked')) $('#qr-doc-extra-top').removeClass('d-none');
        else $('#qr-doc-extra-top').addClass('d-none');
    });

    function validarCuponTop() {
        let codigo = $('#codigo-qr-manual-top').val().trim();
        if (!codigo) return;
        
        stopQrScannerTop();
        $('#qr-input-area-top').addClass('d-none');

        $.ajax({
            url: '{{ route("restaurantes.canje.validar") }}',
            method: 'POST',
            data: { codigo: codigo, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.estado == 200) {
                    qrDescGlobalTop = res.porcentaje_descuento;
                    $('#qr-shopper-name-top').text(res.shopper_name);
                    $('#qr-desc-badge-top').text(res.porcentaje_descuento + '% Desc.');
                    $('#qr-visita-id-top').val(res.visita_id);
                    $('#qr-confirm-area-top').removeClass('d-none');
                    calcularTotalesTop();
                } else {
                    notify('Error', res.mensaje || 'Cupón no válido', 'danger');
                    resetModalTop();
                    startQrScannerTop();
                }
            },
            error: function() { notify('Error', 'Error de conexión', 'danger'); resetModalTop(); startQrScannerTop(); }
        });
    }

    function calcularTotalesTop() {
        var total = parseInt($('#qr-total-val-top').val()) || 0;
        var descuento = total * (qrDescGlobalTop / 100.0);
        var neto = total - descuento;
        $('#qr-res-desc-top').text('-$' + new Intl.NumberFormat('es-CL').format(Math.round(descuento)));
        $('#qr-res-neto-top').text('$' + new Intl.NumberFormat('es-CL').format(Math.round(neto)));
    }

    function confirmarCanjeTop() {
        var totalConsumo = parseInt($('#qr-total-val-top').val());
        if (totalConsumo <= 0) { notify('Error', 'Ingrese un monto', 'warning'); return; }

        $('#qr-btn-confirm-top').prop('disabled', true).text('...');

        $.ajax({
            url: '{{ route("restaurantes.canje.confirmar") }}',
            method: 'POST',
            data: {
                visita_id: $('#qr-visita-id-top').val(),
                total_consumo: totalConsumo,
                guardar_documento: $('#qr-switch-doc-top').is(':checked') ? 1 : 0,
                documento_tipo: $('#qr-doc-tipo-top').val(),
                documento_numero: $('#qr-doc-num-top').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.estado == 200) {
                    notify('¡Éxito!', 'Canje realizado', 'success');
                    $('#modal-qr-scan-top').modal('hide');
                    if (window.location.pathname.includes('resultados/encuestas-restaurante') || window.location.pathname.includes('canje') || window.location.pathname === '/' || window.location.pathname.includes('dashboard')) {
                        setTimeout(() => { location.reload(); }, 1000);
                    }
                } else {
                    $('#qr-btn-confirm-top').prop('disabled', false).text('CONFIRMAR CANJE');
                    notify('Error', res.mensaje, 'danger');
                }
            },
            error: function() { $('#qr-btn-confirm-top').prop('disabled', false).text('CONFIRMAR CANJE'); notify('Error', 'Error de red', 'danger'); }
        });
    }
</script>
<style>
    .input-premium-top {
        border: 2px solid #f1f5f9;
        border-radius: 10px;
        padding: 8px 12px;
        font-weight: 600;
        transition: border-color 0.2s;
    }
    .input-premium-top:focus {
        border-color: #0075cd;
        box-shadow: none;
    }
</style>
@endif

@yield('script')
{{-- @if(Route::current()->getName() == 'index') 
	<script src="{{asset('assets/js/layout-change.js')}}"></script>
@endif --}}

