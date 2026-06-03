<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('dashboard')}}"><img style="width: 10rem;margin-top: -1rem;margin-left: 1rem;" class="img-fluid for-light" src="{{ asset('assets/images/logo/logo_check360.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_check360.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
       <div class="logo-icon-wrapper" style="width: 6rem;padding-left: 0rem;padding-right: 0rem;"><a href="{{ route('dashboard')}}"><img style="height: 5rem;object-fit: contain;" class="img-fluid" src="{{ asset('assets/images/logo/icono_check360.png') }}" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>

        @php
          $mostrarMenu = true;
          if (\App\Helpers\SubdominioHelper::esTipo('shopper')) {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if ($shopper && $shopper->aprobado == 0) {
              $mostrarMenu = false;
            }
          }
        @endphp

        @if($mostrarMenu)
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
              <li class="sidebar-list"><a id="onb-dashboard" class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard')}}">
                  <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-dashboard"></i>
                  <span>Dashboard</span></a>
              </li>

              @if(\App\Helpers\SubdominioHelper::esTipo('restaurante'))
              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{ route('resultados.encuestas_restaurante')}}">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-calendar"></i>
                <span>Visitas</span></a>
              </li>
              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{ route('restaurantes.usuarios.lista')}}">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-users"></i>
                <span>Usuarios</span></a>
              </li>
              @endif

              @if(\App\Helpers\SubdominioHelper::esTipo('sistema') || \App\Helpers\SubdominioHelper::esTipo('shopper'))
              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{ route('visitas.lista')}}">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-calendar"></i>
                <span>Visitas</span></a>
              </li>
              @endif

              @if(\App\Helpers\SubdominioHelper::esTipo('sistema'))
              <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-restaurant"></i>
                <span>Restaurantes</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{ route('restaurantes.lista')}}">Ver Restaurantes</a></li>
                  <li><a href="{{ route('restaurantes.usuarios_admin.lista')}}">Usuarios</a></li>
                </ul>
              </li>
              @endif

              @if(\App\Helpers\SubdominioHelper::esTipo('sistema'))
              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{ route('shoppers.lista')}}">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-users-social"></i>
                <span>Mistery Shoppers</span></a>
              </li>
              @endif

              @if(!\App\Helpers\SubdominioHelper::esTipo('shopper') && !\App\Helpers\SubdominioHelper::esTipo('restaurante'))
              <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <i style="font-size: 1.2rem;position: relative;top: 0.1rem;" class="icofont icofont-settings"></i>
                <span>Configuraciones</span></a>
                <ul class="sidebar-submenu">
                  @if(\App\Helpers\SubdominioHelper::esTipo('sistema'))
                  <li><a href="{{ route('encuestas.lista')}}">Preguntas encuestas</a></li>
                  <li><a href="{{ route('dimensiones.lista')}}">Dimensiones</a></li>
                  <li><a href="{{ route('tiposcocina.lista')}}">Tipos de Cocina</a></li>
                  @endif
                  <li><a id="onb-config-usuarios" href="{{ route('usuarios.lista')}}">Gestión Usuarios</a></li>
                </ul>
              </li>
              @endif

          </ul>
        </div>
        @endif

        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
