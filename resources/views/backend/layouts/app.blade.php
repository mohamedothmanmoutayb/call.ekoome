<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/images/logos/favicon.png') }}" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/libs/select2/dist/css/select2.min.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Iconify CDN -->
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </Link>
    @yield('css')
    <style>
        @media (max-width: 600px) {
            #errorBox {
                width: 90%;
                left: 1%;

            }

            /* .contain{
                padding : 0px !important;
                padding-top: 15px !important;
                width: 108% !important;
            } */

        }

        .table-responsive {
            min-height: 500px;
        }

        .mini-nav-item .submenu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding-left: 0;
            margin: 0;
            transition: max-height 0.4s ease, opacity 0.3s ease;
        }


        .mini-nav-item.open .submenu {
            max-height: 500px;
            opacity: 1;
        }


        .mini-nav-item .submenu .mini-nav-item {
            margin-top: 6px;
            text-align: center;
        }
    </style>
    <title>EKOOME SOLUTION APP</title>

</head>

<body>
    <!-- Toast -->
    {{-- <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body hstack align-items-start gap-6">
      <i class="ti ti-alert-circle fs-6"></i>
      <div>
        <h5 class="text-white fs-3 mb-1">Welcome to MatDash</h5>
        <h6 class="text-white fs-2 mb-0">Easy to costomize the Template!!!</h6>
      </div>
      <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div> --}}
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('public/assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        <!-- Sidebar Start -->
        <aside class="side-mini-panel with-vertical">
            <!-- ---------------------------------- -->
            <!-- Start Vertical Layout Sidebar -->
            <!-- ---------------------------------- -->
            <div class="iconbar">
                <div>
                    <div class="mini-nav">
                        <div class="brand-logo d-flex align-items-center justify-content-center">
                            <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                            </a>
                        </div>
                        <ul class="mini-nav-ul" data-simplebar>

                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Dashboards -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-1">
                                <a href="{{ route('home') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Dashboard">
                                    <iconify-icon icon="solar:layers-line-duotone" class="ti"></iconify-icon>
                                </a>
                            </li>

                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Pages -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-3">
                                <a href="{{ route('leads.index') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Leads">
                                    <iconify-icon icon="solar:cart-3-outline" class="ti"></iconify-icon>
                                </a>
                            </li>
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Forms  -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-4">
                                <a href="{{ route('leads.another') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="No Answer">
                                    <iconify-icon icon="solar:call-cancel-linear" class="ti"></iconify-icon>
                                </a>
                            </li>

                            <li>
                                <span class="sidebar-divider lg"></span>
                            </li>


                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            @if (Auth::user()->id_role == '4')
                                <li class="mini-nav-item" id="mini-5">
                                    <a href="{{ route('leads.noanswer') }}" data-bs-toggle="tooltip"
                                        data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                        data-bs-title="List No Answer">
                                        <iconify-icon icon="solar:call-cancel-linear" class="ti"></iconify-icon>
                                    </a>
                                </li>
                            @endif
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Charts -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-6">
                                <a href="{{ route('leads.calllater') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Call Later">
                                    <iconify-icon icon="solar:clock-square-line-duotone" class="ti"></iconify-icon>
                                </a>
                            </li>

                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Components -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-8">
                                <a href="{{ route('leads.listconfirmed') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Confirmed">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </a>
                            </li>


                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <!-- Auth -->
                            <!-- --------------------------------------------------------------------------------------------------------- -->
                            <li class="mini-nav-item" id="mini-9">
                                <a href="{{ route('leads.leadduplicated') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Duplicated">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </a>
                            </li>
                            <li class="mini-nav-item" id="mini-9">
                                <a href="{{ route('suivi.index') }}" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                    data-bs-title="Suivi">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </a>
                            </li>
                            <li>
                                <span class="sidebar-divider lg"></span>
                            </li>
                            <!-- --------------------------------------------------------------------------------------------------------- -->

                            @if (Auth::user()->id_role != '3')
                                <!-- Process Submenu -->
                                <li class="mini-nav-item has-submenu" id="mini-ccm">
                                    <a href="javascript:void(0)" class="submenu-toggle" data-bs-toggle="tooltip"
                                        data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                        data-bs-title="Process">
                                        <iconify-icon icon="f7:placemark" class="ti"></iconify-icon> </a>

                                    <ul class="submenu" style="background-color: #f4f0ff;">
                                        <!-- Divider -->
                                        <li>
                                            <span class="sidebar-divider lg"
                                                style="display:block; height:4px; background-color:#2c3e50; margin: 0; border-radius:2px;">
                                            </span>
                                        </li>

                                        <li class="mini-nav-item">
                                            <a href="{{ route('products.productfordelivred') }}"
                                                data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Pick">
                                                <iconify-icon icon="mdi:truck-delivery-outline"
                                                    class="fs-7"></iconify-icon>
                                            </a>
                                        </li>

                                        <li class="mini-nav-item">
                                            <a href="{{ route('products.pack') }}" data-bs-toggle="tooltip"
                                                data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Pack">
                                                <iconify-icon icon="mdi:package-variant-closed"
                                                    class="fs-7"></iconify-icon>
                                            </a>
                                        </li>

                                        <li class="mini-nav-item">
                                            <a href="{{ route('leads.orders.deliveryman') }}"
                                                data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Order Delivery Man">
                                                <iconify-icon icon="mdi:account-tie-outline"
                                                    class="fs-7"></iconify-icon>
                                            </a>
                                        </li>

                                        <li class="mini-nav-item">
                                            <a href="{{ route('products.ship') }}" data-bs-toggle="tooltip"
                                                data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Ship">
                                                <iconify-icon icon="mdi:truck-fast-outline"
                                                    class="fs-7"></iconify-icon>
                                            </a>
                                        </li>



                                        <!-- Divider -->
                                        <li>
                                            <span class="sidebar-divider lg"
                                                style="display:block; height:4px; background-color:#2c3e50; margin: 0; border-radius:2px;">
                                            </span>
                                        </li>
                                    </ul>
                                </li>
                                <!-- Call Center Manager -->
                                <li class="mini-nav-item has-submenu" id="mini-ccm">
                                    <a href="javascript:void(0)" class="submenu-toggle" data-bs-toggle="tooltip"
                                        data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                        data-bs-title="Call Center Manager">
                                        <iconify-icon icon="solar:tuning-square-2-line-duotone"
                                            class="ti"></iconify-icon>
                                    </a>

                                    <ul class="submenu" style="background-color: #f4f0ff;">
                                        <!-- Divider -->
                                        <li>
                                            <span class="sidebar-divider lg"
                                                style="display:block; height:4px; background-color:#2c3e50; margin: 0; border-radius:2px;">
                                            </span>
                                        </li>

                                        <!-- Staff -->
                                        <li class="mini-nav-item">
                                            <a href="{{ route('Staff.index') }}" data-bs-toggle="tooltip"
                                                data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Staff">
                                                <iconify-icon icon="mdi:account-group-outline"
                                                    class="ti"></iconify-icon>
                                            </a>
                                        </li>

                                        <!-- Products -->
                                        <li class="mini-nav-item">
                                            <a href="{{ route('products.index') }}" data-bs-toggle="tooltip"
                                                data-bs-custom-class="custom-tooltip"
                                                style="background-color: #f4f0ff;" data-bs-placement="right"
                                                data-bs-title="Products">
                                                <iconify-icon icon="mdi:package-variant-closed"
                                                    class="ti"></iconify-icon>
                                            </a>
                                        </li>



                                        <!-- Divider -->
                                        <li>
                                            <span class="sidebar-divider lg"
                                                style="display:block; height:4px; background-color:#2c3e50; margin: 0; border-radius:2px;">
                                            </span>
                                        </li>
                                    </ul>
                                </li>
                                <!-- Statistics -->
                                <li class="mini-nav-item">
                                    <a href="{{ route('analyses.index') }}" data-bs-toggle="tooltip"
                                        data-bs-custom-class="custom-tooltip" data-bs-placement="right"
                                        data-bs-title="Statistics">
                                        <iconify-icon icon="solar:folder-line-duotone" class="ti"></iconify-icon>
                                    </a>
                                </li>
                            @endif

                        </ul>












                    </div>
                    <div class="sidebarmenu">
                        <!-- <div class="brand-logo d-flex align-items-center nav-logo">
              <a href="../horizontal/index.html" class="text-nowrap logo-img">
              </a>

            </div> -->























                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-error.html" class="sidebar-link">
                                <iconify-icon icon="solar:bug-minimalistic-line-duotone"></iconify-icon>
                                <span class="hide-menu">Error</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-login.html" class="sidebar-link">
                                <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                                <span class="hide-menu">Side Login</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-login2.html" class="sidebar-link">
                                <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                                <span class="hide-menu">Boxed Login</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-register.html" class="sidebar-link">
                                <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                                <span class="hide-menu">Side Register</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-register2.html" class="sidebar-link">
                                <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                                <span class="hide-menu">Boxed Register</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-forgot-password.html" class="sidebar-link">
                                <iconify-icon icon="solar:password-outline"></iconify-icon>
                                <span class="hide-menu">Side Forgot Pwd</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-forgot-password2.html" class="sidebar-link">
                                <iconify-icon icon="solar:password-outline"></iconify-icon>
                                <span class="hide-menu">Boxed Forgot Pwd</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-two-steps.html" class="sidebar-link">
                                <iconify-icon icon="solar:siderbar-line-duotone"></iconify-icon>
                                <span class="hide-menu">Side Two Steps</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-two-steps2.html" class="sidebar-link">
                                <iconify-icon icon="solar:siderbar-line-duotone"></iconify-icon>
                                <span class="hide-menu">Boxed Two Steps</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-maintenance.html" class="sidebar-link">
                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                <span class="hide-menu">Maintenance</span>
                            </a>
                        </li>

                        </ul>
                        </nav>

                        <!-- ---------------------------------- -->
                        <!-- Docs & Other -->
                        <!-- ---------------------------------- -->
                        <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-10" data-simplebar>
                            <ul class="sidebar-menu" id="sidebarnav">
                                <li class="nav-small-cap">
                                    <span class="hide-menu">Documentation</span>
                                </li>
                                <li class="sidebar-item">
                                    <a href="../docs/index.html" class="sidebar-link">
                                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                        <span class="hide-menu">Getting Started</span>
                                    </a>
                                </li>
                                <li>
                                    <span class="sidebar-divider"></span>
                                </li>
                                <li class="nav-small-cap">
                                    <span class="hide-menu">Multi level</span>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)"
                                        aria-expanded="false">
                                        <iconify-icon icon="solar:align-left-line-duotone"></iconify-icon>
                                        <span class="hide-menu">Menu Level</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse first-level">
                                        <li class="sidebar-item">
                                            <a href="javascript:void(0)" class="sidebar-link">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Level 1</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                                aria-expanded="false">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Level 1.1</span>
                                            </a>
                                            <ul aria-expanded="false" class="collapse two-level">
                                                <li class="sidebar-item">
                                                    <a href="javascript:void(0)" class="sidebar-link">
                                                        <span class="icon-small"></span>
                                                        <span class="hide-menu">Level 2</span>
                                                    </a>
                                                </li>
                                                <li class="sidebar-item">
                                                    <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                                        aria-expanded="false">
                                                        <span class="icon-small"></span>
                                                        <span class="hide-menu">Level 2.1</span>
                                                    </a>
                                                    <ul aria-expanded="false" class="collapse three-level">
                                                        <li class="sidebar-item">
                                                            <a href="javascript:void(0)" class="sidebar-link">
                                                                <span class="icon-small"></span>
                                                                <span class="hide-menu">Level 3</span>
                                                            </a>
                                                        </li>
                                                        <li class="sidebar-item">
                                                            <a href="javascript:void(0)" class="sidebar-link">
                                                                <span class="icon-small"></span>
                                                                <span class="hide-menu">Level 3.1</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="sidebar-divider"></span>
                                </li>
                                <li class="nav-small-cap">
                                    <span class="hide-menu">More Options</span>
                                </li>
                                <li class="sidebar-item">
                                    <div class="sidebar-link">
                                        <span class="round-10 rounded-circle d-block bg-primary"></span>
                                        <span class="hide-menu">Applications</span>
                                    </div>
                                </li>
                                <li class="sidebar-item">
                                    <div class="sidebar-link">
                                        <span class="round-10 rounded-circle d-block bg-secondary"></span>
                                        <span class="hide-menu">Form Options</span>
                                    </div>
                                </li>
                                <li class="sidebar-item">
                                    <div class="sidebar-link">
                                        <span class="round-10 rounded-circle d-block bg-danger"></span>
                                        <span class="hide-menu">Table Variations</span>
                                    </div>
                                </li>
                                <li class="sidebar-item">
                                    <div class="sidebar-link">
                                        <span class="round-10 rounded-circle d-block bg-warning"></span>
                                        <span class="hide-menu">Charts Selection</span>
                                    </div>
                                </li>
                                <li class="sidebar-item">
                                    <div class="sidebar-link">
                                        <span class="round-10 rounded-circle d-block bg-success"></span>
                                        <span class="hide-menu">Widgets</span>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </aside>
        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            <header class="topbar">
                <div class="with-vertical">
                    <!-- ---------------------------------- -->
                    <!-- Start Vertical Layout Header -->
                    <!-- ---------------------------------- -->
                    <nav class="navbar navbar-expand-lg p-0">
                        <ul class="navbar-nav">
                            <li class="nav-item d-flex d-xl-none">
                                <a class="nav-link nav-icon-hover-bg rounded-circle  sidebartoggler "
                                    id="headerCollapse" href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone"
                                        class="fs-6"></iconify-icon>
                                </a>

                            </li>




                        </ul>

                        <div class="d-block d-lg-none py-9 py-xl-0 w-0">
                        </div>

                        <!-- ------------------------------- -->
                        <!-- start notification Dropdown mobile -->
                        <!-- ------------------------------- -->
                        <div class="d-lg-none position-fixed top-0 p-3  d-flex align-items-center gap-2 gap-2"
                            style="z-index: 1050; right: 48px;">
                            <li id="notification-toggle-mobile"
                                class=" notification-toggle nav-icon-hover-bg rounded-circle">
                                <a class="load-notifications nav-link position-relative" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <div style="position: relative; padding-top: -2px;">
                                        <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"
                                            style="margin-top: 3px;"></iconify-icon>
                                        <!-- Always render the badge element but hide it when empty -->

                                    </div>
                                    <span id="notification-count-mobile"
                                        class="notification-count position-absolute top-0 start-100 translate-middle badge rounded-circle"
                                        style="margin-left: -2px; margin-top: 1px; padding: 3px 6px; background-color: #dc3545; color: white; display: {{ $notifications->count() > 0 ? 'inline-block' : 'none' }};">
                                        {{ $notifications->count() > 99 ? '+99' : $notifications->count() }}
                                    </span>
                                </a>
                                <audio id="mysoundclip2" src="{{ asset('public/notification.mp3') }}"
                                    preload="auto"></audio>
                                <div id="notification-dropdown-mobile"
                                    class=" notification-dropdown dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up position-absolute"
                                    style="display: none; margin-right: 260%; top: 90%;  width: 300px;"
                                    aria-labelledby="drop2">
                                    <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                        <span id="notif-count-badge-mobile"
                                            class="notif-count-badge badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                            {{ $notifications->count() > 99 ? '+99' : $notifications->count() }}
                                            new
                                        </span>

                                    </div>


                                    @include('backend.notifications.notifictionbar', [
                                        'notifications' => $notifications,
                                    ])




                                    {{--   heyyyyyyyyyyyyy  --}}

                                </div>




                                <div class="px-7 py-4 text-center text-muted notif-not-found"
                                    style="{{ $notifications->count() > 0 ? 'display: none;' : '' }}">
                                    No notifications found.
                                </div>

                                <div class="py-6 px-7" style="margin-bottom: -20px;">
                                    <a href="javascript:void(0)" id="mark-all-read2"
                                        class="btn btn-secondary w-100 mark-all-read"
                                        style="{{ $notifications->count() > 0 ? '' : 'display: none;' }}">
                                        Mark All as Read
                                    </a>
                                </div>

                                <div class="py-6 px-7 mb-1">
                                    <a href=" {{ route('notifications.index') }}  " class="btn btn-primary w-100">See
                                        All Notifications</a>
                                </div>


                            </li>
                        </div>
                        <!-- ------------------------------- -->
                        <!-- end notification Dropdown -->
                        <!-- ------------------------------- -->
                        {{-- <img src="{{ asset('public/logo.png')}}" alt="matdash-img" /> --}}
                        <a class="navbar-toggler p-0 border-0 nav-icon-hover-bg rounded-circle"
                            href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
                        </a>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between">
                                <ul
                                    class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                                    <li class="nav-item dropdown">
                                        <a href="javascript:void(0)"
                                            class="nav-link nav-icon-hover-bg rounded-circle d-flex d-lg-none align-items-center justify-content-center"
                                            type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                            aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:sort-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                    </li>

                                    <!-- ------------------------------- -->
                                    <!-- start notification Dropdown -->
                                    <!-- ------------------------------- -->
                                    {{-- <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link position-relative" href="javascript:void(0)"
                                            id="drop2" aria-expanded="false">
                                            <iconify-icon icon="solar:bell-bing-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop2">
                                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5
                                                    new</span>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                        <iconify-icon
                                                            icon="solar:widget-3-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                            <span class="d-block fs-2">9:30 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">Just
                                                            see the my new admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                        <iconify-icon
                                                            icon="solar:calendar-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Event today</h6>
                                                            <span class="d-block fs-2">9:15 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">Just a
                                                            reminder that you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                        <iconify-icon
                                                            icon="solar:settings-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Settings</h6>
                                                            <span class="d-block fs-2">4:36 PM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">You can
                                                            customize this template as you want</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                                                        <iconify-icon
                                                            icon="solar:widget-4-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                            <span class="d-block fs-2">9:30 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">Just
                                                            see the my new admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                        <iconify-icon
                                                            icon="solar:calendar-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Event today</h6>
                                                            <span class="d-block fs-2">9:15 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">Just a
                                                            reminder that you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                    <span
                                                        class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                        <iconify-icon
                                                            icon="solar:settings-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Settings</h6>
                                                            <span class="d-block fs-2">4:36 PM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11">You can
                                                            customize this template as you want</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="py-6 px-7 mb-1">
                                                <button class="btn btn-primary w-100">See All Notifications</button>
                                            </div>

                                        </div>
                                    </li> --}}
                                    <!-- ------------------------------- -->
                                    <!-- end notification Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start profile Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="javascript:void(0)" id="drop1"
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center gap-2 lh-base">
                                                <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}"
                                                    class="rounded-circle" width="35" height="35"
                                                    alt="matdash-img" />
                                                <iconify-icon icon="solar:alt-arrow-down-bold"
                                                    class="fs-2"></iconify-icon>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop1">
                                            <div class="position-relative px-4 pt-3 pb-2">
                                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                                                    <img src="../assets/images/profile/user-1.jpg"
                                                        class="rounded-circle" width="56" height="56"
                                                        alt="matdash-img" />
                                                    <div>
                                                        <h5 class="mb-0 fs-12">{{ Auth::user()->name }} <span
                                                                class="text-success fs-11">Pro</span>
                                                        </h5>
                                                        <p class="mb-0 text-dark">
                                                            {{ Auth::user()->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="message-body">
                                                    <a href="{{ route('profil') }}"
                                                        class="p-2 dropdown-item h6 rounded-1">
                                                        My Profile
                                                    </a>
                                                    <a href="{{ route('logout') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                        class="p-2 dropdown-item h6 rounded-1">
                                                        <form id="logout-form" action="{{ route('logout') }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                        Sign Out
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end profile Dropdown -->
                                    <!-- ------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <!-- ---------------------------------- -->
                    <!-- End Vertical Layout Header -->
                    <!-- ---------------------------------- -->

                    <!-- ------------------------------- -->
                    <!-- apps Dropdown in Small screen -->
                    <!-- ------------------------------- -->


                </div>
                <div class="app-header with-horizontal">
                    <nav class="navbar navbar-expand-xl container-fluid p-0">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item d-flex d-xl-none">
                                <a class="nav-link sidebartoggler nav-icon-hover-bg rounded-circle"
                                    id="sidebarCollapse" href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone"
                                        class="fs-7"></iconify-icon>
                                </a>
                            </li>
                            <li class="nav-item d-none d-xl-flex align-items-center">
                                <a href="../horizontal/index.html" class="text-nowrap nav-link">
                                    <img src="{{ asset('public/logo.png') }}" alt="matdash-img" width="200" />
                                </a>
                            </li>
                            <li class="nav-item d-none d-xl-flex align-items-center nav-icon-hover-bg rounded-circle">
                                <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <iconify-icon icon="solar:magnifer-linear" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ul>
                        <div class="d-block d-xl-none">
                            <a href="../horizontal/index.html" class="text-nowrap nav-link">
                                <img src="../assets/images/logos/logo.svg" alt="matdash-img" />
                            </a>
                        </div>
                        <a class="navbar-toggler nav-icon-hover p-0 border-0 nav-icon-hover-bg rounded-circle"
                            href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="p-2">
                                <i class="ti ti-dots fs-7"></i>
                            </span>
                        </a>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                                <ul
                                    class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                                    <li class="nav-item dropdown">
                                        <a href="javascript:void(0)"
                                            class="nav-link nav-icon-hover-bg rounded-circle d-flex d-lg-none align-items-center justify-content-center"
                                            type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                            aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:sort-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle moon dark-layout"
                                            href="javascript:void(0)">
                                            <iconify-icon icon="solar:moon-line-duotone"
                                                class="moon fs-6"></iconify-icon>
                                        </a>
                                        <a class="nav-link nav-icon-hover-bg rounded-circle sun light-layout"
                                            href="javascript:void(0)" style="display: none">
                                            <iconify-icon icon="solar:sun-2-line-duotone"
                                                class="sun fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="nav-item d-block d-xl-none">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle" href="javascript:void(0)"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <iconify-icon icon="solar:magnifer-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->


                                    <!-- ------------------------------- -->
                                    <!-- start notification Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li id="notification-toggle"
                                        class=" notification-toggle nav-icon-hover-bg rounded-circle">
                                        <a class="load-notifications nav-link position-relative"
                                            href="javascript:void(0)" aria-expanded="false">
                                            <div style="position: relative; padding-top: -2px;">
                                                <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"
                                                    style="margin-top: 3px;"></iconify-icon>
                                                <!-- Always render the badge element but hide it when empty -->

                                            </div>
                                            <span id="notification-count"
                                                class="notification-count position-absolute top-0 start-100 translate-middle badge rounded-circle"
                                                style="margin-left: -11px; margin-top: 9px; padding: 3px 6px; background-color: #dc3545; color: white; display: {{ $notifications->count() > 0 ? 'inline-block' : 'none' }};">
                                                {{ $notifications->count() > 99 ? '+99' : $notifications->count() }}
                                            </span>

                                        </a>
                                        <audio id="mysoundclip2" src="{{ asset('public/notification.mp3') }}"
                                            preload="auto"></audio>
                                        <div id="notification-dropdown"
                                            class="notification-dropdown dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up mt-3"
                                            style="display: none; margin-right: 22%;" aria-labelledby="drop2">
                                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                                <span id="notif-count-badge"
                                                    class="notif-count-badge badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                                    {{ $notifications->count() > 99 ? '+99' : $notifications->count() }}
                                                    new
                                                </span>

                                            </div>


                                            @include('backend.notifications.notifictionbar', [
                                                'notifications' => $notifications,
                                            ])




                                            {{-- heyyyyyyyyyyyyyyyy --}}

                                        </div>
                                        <div class="px-7 py-4 text-center text-muted notif-not-found"
                                            style="{{ $notifications->count() > 0 ? 'display: none;' : '' }}">
                                            No notifications found.
                                        </div>

                                        <div class="py-6 px-7" style="margin-bottom: -20px;">
                                            <a href="javascript:void(0)" id="mark-all-read"
                                                class="btn btn-secondary w-100 mark-all-read"
                                                style="{{ $notifications->count() > 0 ? '' : 'display: none;' }}">
                                                Mark All as Read
                                            </a>
                                        </div>

                                        <div class="py-6 px-7 mb-1">
                                            <a href=" {{ route('notifications.index') }}  "
                                                class="btn btn-primary w-100">See All Notifications</a>
                                        </div>

                            </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end notification Dropdown -->
                            <!-- ------------------------------- -->

                            <!-- ------------------------------- -->
                            <!-- start profile Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                    <div class="d-flex align-items-center gap-2 lh-base">
                                        <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}"
                                            class="rounded-circle" width="35" height="35"
                                            alt="matdash-img" />
                                        <iconify-icon icon="solar:alt-arrow-down-bold" class="fs-2"></iconify-icon>
                                    </div>
                                </a>
                                <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop1">
                                    <div class="position-relative px-4 pt-3 pb-2">
                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                                            <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}"
                                                class="rounded-circle" width="56" height="56"
                                                alt="matdash-img" />
                                            <div>
                                                <h5 class="mb-0 fs-12">{{ Auth::user()->name }} <span
                                                        class="text-success fs-11">Pro</span>
                                                </h5>
                                                <p class="mb-0 text-dark">
                                                    {{ Auth::user()->email }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="message-body">
                                            <a href="{{ route('profil') }}" class="p-2 dropdown-item h6 rounded-1">
                                                My Profile
                                            </a>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="p-2 dropdown-item h6 rounded-1">
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                                Sign Out
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end profile Dropdown -->
                            <!-- ------------------------------- -->
                            </ul>
                        </div>
                </div>
                </nav>

        </div>
        </header>
        <!--  Header End -->

        <aside class="left-sidebar with-horizontal">
            <!-- Sidebar scroll-->
            <div>
                <!-- Sidebar navigation-->
                <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                    <ul id="sidebarnav">
                        <!-- ============================= -->
                        <!-- Home -->
                        <!-- ============================= -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <!-- =================== -->
                        <!-- Dashboard -->
                        <!-- =================== -->
                        <li class="sidebar-item">
                            <a class="sidebar-link " href="{{ route('home') }}" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:layers-line-duotone" class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <!-- =================== -->
                        <!-- Icon -->
                        <!-- =================== -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('leads.index') }}" aria-expanded="false">
                                <span class="rounded-3">
                                    <iconify-icon icon="solar:cart-3-outline" class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Leads</span>
                            </a>
                        </li>

                        @if (Auth::user()->id_role == '3')
                            <!-- ============================= -->
                            <!-- Orders -->
                            <!-- ============================= -->
                            <li class="sidebar-item">
                                <a class="sidebar-link two-column" href="{{ route('leads.another') }}"
                                    aria-expanded="false">
                                    <span>
                                        <iconify-icon icon="solar:call-cancel-linear" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">No Answer</span>
                                </a>
                            </li>
                        @endif
                        <!-- ============================= -->
                        <!-- Stores -->
                        <!-- ============================= -->
                        @if (Auth::user()->id_role == '4')
                            <li class="sidebar-item">
                                <a class="sidebar-link " href="{{ route('leads.noanswer') }}" aria-expanded="false">
                                    <span>
                                        <iconify-icon icon="solar:call-cancel-linear" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">List No Answer</span>
                                </a>
                            </li>
                        @endif
                        <!-- ============================= -->
                        <!-- =================== -->
                        <!-- UI Elements -->
                        <!-- =================== -->
                        <li class="sidebar-item mega-dropdown">
                            <a class="sidebar-link" href="{{ route('leads.calllater') }}" aria-expanded="false">
                                <span class="rounded-3">
                                    <iconify-icon icon="solar:clock-square-line-duotone"
                                        class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Call Later</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link two-column " href="{{ route('leads.listconfirmed') }}"
                                aria-expanded="false">
                                <span class="rounded-3">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Confirmed</span>
                            </a>
                        </li>
                        <!-- ============================= -->
                        <!-- Users -->
                        <!-- =================== -->
                        <li class="sidebar-item">
                            <a class="sidebar-link two-column " href="{{ route('leads.leadduplicated') }}"
                                aria-expanded="false">
                                <span class="rounded-3">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Duplicated</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link two-column " href="{{ route('suivi.index') }}"
                                aria-expanded="false">
                                <span class="rounded-3">
                                    <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                                </span>
                                <span class="hide-menu">Suivi</span>
                            </a>
                        </li>
                        <!-- ============================= -->
                        <!-- Tables -->
                        <!-- Bootstrap Table -->
                        <!-- =================== -->
                        @if (Auth::user()->id_role != '3')
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span class="rounded-3">
                                        <iconify-icon icon="f7:placemark" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Proccess</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('products.productfordelivred') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Pick</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('products.pack') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Pack</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('leads.orders.deliveryman') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Order Delivery Man</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('products.ship') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Ship</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span class="rounded-3">
                                        <iconify-icon icon="solar:tuning-square-2-line-duotone"
                                            class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Call Center Manager</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('Staff.index') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Staff</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('products.index') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Products</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('analyses.index') }}" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Statistics</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link " href="{{ route('whatsapp-template.index') }}" aria-expanded="false">
                                    <span class="rounded-3">
                                        <iconify-icon icon="f7:person-2" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Whatsapp</span>
                                </a>
                            </li>
                            <!-- ============================= -->
                            <!-- Charts -->
                            <!-- ============================= -->
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

        <div class="body-wrapper">
            <div class="row container-fluid contain">
                @yield('content')
            </div>
        </div>

        <script>
            function handleColorTheme(e) {
                document.documentElement.setAttribute("data-color-theme", e);
            }
        </script>
    </div>

    <!--  Search Bar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <input type="search" class="form-control" placeholder="Search here" id="search" />
                    <a href="javascript:void(0)" data-bs-dismiss="modal" class="lh-1">
                        <i class="ti ti-x fs-5 ms-3"></i>
                    </a>
                </div>
                <div class="modal-body message-body" data-simplebar="">
                    <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                    <ul class="list mb-0 py-2">
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Analytics</span>
                                <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">eCommerce</span>
                                <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">CRM</span>
                                <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard3</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Contacts</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Posts</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Detail</span>
                                <span
                                    class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Shop</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Modern</span>
                                <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Dashboard</span>
                                <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Contacts</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Posts</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Detail</span>
                                <span
                                    class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                            <a href="javascript:void(0)">
                                <span class="text-dark fw-semibold d-block">Shop</span>
                                <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script>
        $('input[name="date"]').daterangepicker();
    </script>
    <script src="{{ asset('public/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/theme/app.horizontal.init.js') }}"></script>
    <script src="{{ asset('public/assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('public/assets/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/theme/sidebarmenu.js') }}"></script>

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('public/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/dashboards/dashboard1.js') }}"></script>
    <script src="{{ asset('public/assets/libs/fullcalendar/index.global.min.js') }}"></script>

    <!-- This Page JS -->
    <script src="{{ asset('public/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/forms/select2.init.js') }}"></script>
    <script src="{{ asset('public/assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>


    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>








    <script>
        document.querySelectorAll(".submenu-toggle").forEach(toggle => {
            toggle.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.closest(".mini-nav-item");

                document.querySelectorAll(".mini-nav-item.has-submenu").forEach(item => {
                    if (item !== parent) item.classList.remove("open");
                });

                parent.classList.toggle("open");
            });
        });
    </script>

    <script>
        window.user = @json(auth()->user());

        let audioUnlocked = false;

        const unlockAudio = function() {
            if (!audioUnlocked) {
                const audio = document.getElementById("mysoundclip2");
                if (audio) {
                    audio.play().then(() => {
                        audio.pause();
                        audio.currentTime = 0;
                        audioUnlocked = true;
                    }).catch((error) => {
                        console.warn("Audio autoplay was blocked:", error);
                    });
                }
            }
        };

        document.addEventListener('DOMContentLoaded', unlockAudio);
        document.addEventListener('mousemove', unlockAudio, {
            once: true
        });
        document.addEventListener('click', unlockAudio, {
            once: true
        });
        document.addEventListener('touchstart', unlockAudio, {
            once: true
        });


        document.addEventListener('DOMContentLoaded', function() {
            $('.load-notifications').on('click', function() {
                $('.notif-list').load('{{ route('notifications.list') }}');
            });
        });





        Pusher.logToConsole = true;
        var pusherKey = "{{ env('PUSHER_APP_KEY') }}";
        var pusherCluster = "{{ env('PUSHER_APP_CLUSTER') }}";
        var userId = @json(auth()->id());

        var pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            forceTLS: true
        });


        var channel = pusher.subscribe('user.' + userId);



        pusher.connection.bind('connected', function() {

            console.log('Connected to Pusher');

        });
        channel.bind('Notification', function(data) {

            console.log("Notification received:", data);
            if (data.type === 'returned') {
                toastr.warning(data.message, 'Returned', {
                    positionClass: 'toast-top-right',
                    timeOut: 3000,
                    progressBar: true,
                });
            }

            if (data.type === 'rejected') {
                toastr.error(data.message, 'Rejected', {
                    positionClass: 'toast-top-right',
                    timeOut: 3000,
                    progressBar: true,
                });
            }

            if (user.id_role === 4) {
                if (data.type === 'canceled') {
                    toastr.error(data.message, 'Canceled', {
                        positionClass: 'toast-top-right',
                        timeOut: 3000,
                        progressBar: true,
                    });
                }
            }



            var audio = document.getElementById("mysoundclip2");

            $.get('{{ route('notifications.get') }}', function(response) {
                console.log("Notification sound setting:", response.sound);

                if (!response || response.sound === true || response.sound === 1 || response.sound ===
                    undefined || response.sound === null) {
                    if (audio) {
                        audio.play().catch(function(error) {
                            console.warn("Audio playback failed:", error);
                        });
                    }
                }
            });

            const badge = document.getElementById('notification-count');
            const countBadge = document.getElementById('notif-count-badge');
            const notifList = document.getElementById('notif-list');


            const badgeM = document.getElementById('notification-count-mobile');
            const countBadgeM = document.getElementById('notif-count-badge-mobile');




            if (!badge) {
                badge = document.createElement('span');
                badge.id = 'notification-count';
                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-circle';
                badge.style.marginLeft = '-11px';
                badge.style.padding = '3px 6px';
                badge.style.backgroundColor = '#dc3545';
                badge.style.color = 'white';


                // const notifLink = document.getElementById('drop2');
                // if (notifLink) {
                //     notifLink.appendChild(badge);
                // }
            }


            if (!badgeM) {
                badge = document.createElement('span');
                badge.id = 'notification-count-mobile';
                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-circle';
                badge.style.marginLeft = '-11px';
                badge.style.padding = '3px 6px';
                badge.style.backgroundColor = '#dc3545';
                badge.style.color = 'white';


                // const notifLink = document.getElementById('drop2');
                // if (notifLink) {
                //     notifLink.appendChild(badge);
                // }
            }

            const currentValue = badge.innerText.trim();


            let newValue;
            if (currentValue === '+99') {
                newValue = '+99';
            } else {
                const currentNumber = parseInt(currentValue) || 0;
                newValue = currentNumber + 1 > 99 ? '+99' : currentNumber + 1;
            }


            badge.innerText = newValue;
            badge.style.display = 'inline-block';

            badgeM.innerText = newValue;
            badgeM.style.display = 'inline-block';

            if (countBadge) {
                countBadge.innerText = `${newValue} new`;
            }

            if (countBadgeM) {
                countBadgeM.innerText = `${newValue} new`;
            }

            let iconHtml = '';
            console.log(data.payload.source);
            switch (data.payload.source) {
                case 'lightfunnels':
                    iconHtml = `<img src="/public/plateformes/lightlogo.png" style="width: 24px; height: 24px;">`;
                    break;
                case 'youcan':
                    iconHtml = `<img src="/public/youcanlogo2.webp" style="width: 24px; height: 24px;">`;
                    break;
                case 'woocommerce':
                    iconHtml =
                        `<img src="/public/plateformes/woocommerce-logo.png" style="width: 24px; height: 24px;">`;
                    break;
                default:
                    iconHtml = `<iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>`;
            }



            const time = new Date(data.time).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });



            const notificationItem = document.querySelector(`.notification-item[data-index="${index}"]`);
            if (notificationItem) {
                notificationItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }

            const unreadCount = document.querySelectorAll('.notification-item[data-is-read="0"]').length;
            if (unreadCount > 0) {
                countBadge.innerText = `${unreadCount} new`;
            } else {
                countBadge.innerText = '';
            }



        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("notification-toggle");
            const dropdown = document.getElementById("notification-dropdown");

            toggleBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display === "block";
                dropdown.style.display = isVisible ? "none" : "block";
                if (!isVisible) {

                }
            });


            document.addEventListener("click", function() {
                dropdown.style.display = "none";
            });


            dropdown.addEventListener("click", function(e) {
                e.stopPropagation();
            });
        });

        function closeDropdown() {
            document.getElementById("notification-dropdown").style.display = "none";

        }
    </script>
    {{-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^    Mobile ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("notification-toggle-mobile");
            const dropdown = document.getElementById("notification-dropdown-mobile");

            toggleBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display === "block";
                dropdown.style.display = isVisible ? "none" : "block";
                if (!isVisible) {

                }
            });


            document.addEventListener("click", function() {
                dropdown.style.display = "none";
            });


            dropdown.addEventListener("click", function(e) {
                e.stopPropagation();
            });
        });

        function closeDropdown() {
            document.getElementById("notification-dropdown-mobile").style.display = "none";
        }
    </script>

    {{-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^  End  Mobile ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ --}}
    <script>
        $(document).ready(function() {
            $('.mark-all-read').on('click', function() {
                let unreadNotificationIds = [];

                $('.notification-item').each(function() {
                    if ($(this).data('is-read') == 0) {
                        unreadNotificationIds.push($(this).data('notification-id'));
                    }
                });

                if (unreadNotificationIds.length > 0) {
                    $.ajax({
                        url: '{{ route('notifications.markAllAsRead') }}',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            notifications: unreadNotificationIds
                        },
                        success: function(response) {
                            $('.notif-list').html('');
                            $('.notification-count').text('0');
                            $('.notification-count').hide();
                            $('.notif-count-badge').text('0 new');

                        },
                        error: function(xhr, status, error) {
                            alert('Something went wrong, please try again.');
                        }
                    });
                } else {
                    alert('No unread notifications to mark as read.');
                }
            });


        });

        // $(document).ready(function() {
        //     $('#mark-all-read2').on('click', function() {
        //         let unreadNotificationIds = [];

        //         $('.notification-item').each(function() {
        //             if ($(this).data('is-read') == 0) {
        //                 console.log("1");
        //                 unreadNotificationIds.push($(this).data('notification-id'));
        //             }
        //         });

        //         if (unreadNotificationIds.length > 0) {
        //             $.ajax({
        //                 url: '{{ route('notifications.markAllAsRead') }}',
        //                 method: 'POST',
        //                 data: {
        //                     _token: $('meta[name="csrf-token"]').attr('content'),
        //                     notifications: unreadNotificationIds
        //                 },
        //                 success: function(response) {
        //                     console.log("succ");
        //                     $('#notif-list').html('');
        //                     $('.notification-count').text('0');
        //                     $('.notification-count').hide();
        //                     $('.notif-count-badge').text('0 new');

        //                 },
        //                 error: function(xhr, status, error) {
        //                     alert('Something went wrong, please try again.');
        //                 }
        //             });
        //         } else {
        //             alert('No unread notifications to mark as read.');
        //         }
        //     });


        // });

        $(document).ready(function() {
            $('.notification-toggle > a').on('click', function() {
                $.ajax({
                    url: "{{ route('notifications.fetch') }}",
                    method: "GET",
                    success: function(response) {
                        if (response.count > 0) {
                            console.log('yeeh')
                            $('.mark-all-read').show();
                            $('.notif-not-found').hide();
                        } else {
                            console.log('yeehhhhhh')
                            $('.mark-all-read').hide();
                            $('.notif-not-found').show();
                        }

                        $('#notification-dropdown').show();
                    },
                    error: function() {
                        console.error('Failed to fetch notifications');
                    }
                });
            });
        });
    </script>

    @yield('script')
</body>

</html>
