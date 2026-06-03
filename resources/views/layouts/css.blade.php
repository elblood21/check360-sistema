<link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.css')}}">
<!-- ico-font-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/icofont.css')}}">
<!-- Themify icon-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/themify.css')}}">
<!-- Flag icon-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/flag-icon.css')}}">
<!-- Feather icon-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/feather-icon.css')}}">
<!-- Plugins css start-->
@yield('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/slick-theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/scrollbar.css')}}">
<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/bootstrap.css')}}">
<!-- App css-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
<link id="color" rel="stylesheet" href="{{asset('assets/css/color-1.css')}}" media="screen">
<link rel="stylesheet" href="{{ asset('assets/css/erp-theme.css') }}" media="screen">
<!-- Responsive css-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.css')}}">
<style>
    .switch input:not(:checked) + .switch-state.disabled {
        background-color:#a9a9a9!important;
        opacity:1!important;
    }
    
    /* Check 360 - Color principal azul global */
    :root {
        --theme-deafult: #0075cd !important;
        --theme-default: #0075cd !important;
        --bs-primary: #0075cd !important;
        --bs-blue: #0075cd !important;
    }
    
    .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary:not(:disabled):not(.disabled):active {
        background-color: #0075cd !important;
        border-color: #0075cd !important;
        color: #fff !important;
    }
    
    .bg-primary {
        background-color: #0075cd !important;
    }
    
    .text-primary {
        color: #0075cd !important;
    }
    
    .badge.bg-primary {
        background-color: #0075cd !important;
    }
    
    .form-check-input:checked {
        background-color: #0075cd !important;
        border-color: #0075cd !important;
    }
    
    .form-check-input:focus {
        border-color: #0075cd !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 117, 205, 0.25) !important;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #0075cd !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 117, 205, 0.25) !important;
    }
    
    .link-primary {
        color: #0075cd !important;
    }
    
    .link-primary:hover {
        color: #005fa6 !important;
    }
    
    /* Modo oscuro - Asegurar visibilidad de textos */
    [data-theme="dark"] {
        color-scheme: dark;
    }
    
    [data-theme="dark"] * {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    .simplebar-offset {
        top: 3rem !important;
    }

    .show-hide {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
    }

    @media (max-width: 991px) {
        .page-header .header-wrapper .nav-right .nav-menus li.profile-nav {
            display: block !important;
            padding: 0 !important;
            margin-right: 15px !important;
        }
        
        .page-header .header-wrapper .nav-right {
            width: auto !important;
            display: block !important;
        }

        .nav-menus {
            display: flex !important;
            align-items: center !important;
        }

        .profile-media .media-body {
            display: block !important;
            margin-left: 10px;
        }

        .profile-media .media-body span {
            display: block !important;
            font-weight: 500;
        }

        .header-logo-wrapper {
            display: block !important;
        }
    }
</style>
