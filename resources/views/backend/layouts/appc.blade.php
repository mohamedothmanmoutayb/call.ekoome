<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="tivo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Tivo admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('public/ECOM HUB icon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('public/ECOM HUB icon.png')}}" type="image/x-icon">
    <title>EcomHub - Platform Ecommerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/font-awesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/feather-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/vector-map.css')}}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('public/assets/css/color-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/responsive.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @yield('css')

    <style>
      .btn {
        font-weight: 600;
        border-radius: 7px !important;
      }
      .sidebar-list:hover{
        background-color: #0055ff;
        border-radius: 7px !important;
      }
      .btn-primary:hover{
        background-color: #013fbd !important;
        border-color: #013fbd !important;
      }
      .simplebar-content{
        padding: 24px 20px;margin: -6px;
      }
    </style>
  </head>
  <body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"> </div>
      <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          <div class="header-logo-wrapper col-auto p-0">
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            <div class="logo-header-main"><a href="{{ route('home')}}"><img class="img-fluid for-light img-100" src="{{ asset('public/assets/images/logo/logo2.png')}}" alt=""><img class="img-fluid for-dark" src="{{ asset('public/assets/images/logo/logo.png')}}" alt=""></a></div>
          </div>
          <div class="left-header col horizontal-wrapper ps-0">
            <div class="left-menu-header">
            </div>
          </div>
          <div class="nav-right col-1 pull-right right-header p-0">
            <ul class="nav-menus">
              <li>
                <div class="mode"><i class="fa fa-moon-o"></i></div>
              </li>
              <li class="maximize"><a href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize-2"></i></a></li>
              
              <li class="profile-nav onhover-dropdown">
                <div class="account-user"><i data-feather="user"></i></div>
                <ul class="profile-dropdown onhover-show-div">
                  <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" aria-expanded="false"><i class="ti ti-logout me-2 ti-sm"></i><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form> 
                    <i data-feather="log-in"> </i><span>Logout</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName"></div>
            </div>
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
        </div>
      </div>
      <!-- Page Header Ends-->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper">
          <div>
            <div class="logo-wrapper">
              <a href="{{ route('home')}}">
                <img class="img-fluid for-light" src="{{ asset('public/ECOM HUB whiyte lue.png')}}" alt="">
              </a>
              <div class="back-btn"><i data-feather="grid"></i></div>
              <div class="toggle-sidebar icon-box-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            </div>
            <div class="logo-icon-wrapper">
              <a href="{{ route('home')}}">
                <div class="icon-box-sidebar"><i data-feather="grid"></i></div>
              </a>
            </div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="pin-title sidebar-list">
                    <h6>Pinned</h6>
                  </li>
                  <hr>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('home')}}"><i data-feather="home"> </i><span>Dashboard</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.index')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-location-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 18l-2 -4l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-3.251 9.003"></path><path d="M19 22v-6"></path><path d="M22 19l-3 -3l-3 3"></path></svg><span>Leads</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.another')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-phone-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 21l18 -18"></path><path d="M5.831 14.161a15.946 15.946 0 0 1 -2.831 -8.161a2 2 0 0 1 2 -2h4l2 5l-2.5 1.5c.108 .22 .223 .435 .345 .645m1.751 2.277c.843 .84 1.822 1.544 2.904 2.078l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a15.963 15.963 0 0 1 -10.344 -4.657"></path></svg><span>No Answer</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.noanswer')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-headphones-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 3l18 18"></path><path d="M4 13m0 2a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2z"></path><path d="M17 13h1a2 2 0 0 1 2 2v1m-.589 3.417c-.361 .36 -.86 .583 -1.411 .583h-1a2 2 0 0 1 -2 -2v-3"></path><path d="M4 15v-3c0 -2.21 .896 -4.21 2.344 -5.658m2.369 -1.638a8 8 0 0 1 11.287 7.296v3"></path></svg><span>List No Answer</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.calllater')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-device-landline-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M20 3h-2a2 2 0 0 0 -2 2v14a2 2 0 0 0 2 2h2a2 2 0 0 0 2 -2v-14a2 2 0 0 0 -2 -2z"></path><path d="M16 4h-11a3 3 0 0 0 -3 3v10a3 3 0 0 0 3 3h11"></path><path d="M12 8h-6v3h6z"></path><path d="M12 14v.01"></path><path d="M9 14v.01"></path><path d="M6 14v.01"></path><path d="M12 17v.01"></path><path d="M9 17v.01"></path><path d="M6 17v.01"></path></svg><span>Call Later</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.leadduplicated')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-copy-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19.414 19.415a2 2 0 0 1 -1.414 .585h-8a2 2 0 0 1 -2 -2v-8c0 -.554 .225 -1.055 .589 -1.417m3.411 -.583h6a2 2 0 0 1 2 2v6"></path><path d="M16 8v-2a2 2 0 0 0 -2 -2h-6m-3.418 .59c-.36 .36 -.582 .86 -.582 1.41v8a2 2 0 0 0 2 2h2"></path><path d="M3 3l18 18"></path></svg><span>Duplicated</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.leadhorzone')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-zoom-out-area" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M13 15h4"></path><path d="M15 15m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0"></path><path d="M22 22l-3 -3"></path><path d="M6 18h-1a2 2 0 0 1 -2 -2v-1"></path><path d="M3 11v-1"></path><path d="M3 6v-1a2 2 0 0 1 2 -2h1"></path><path d="M10 3h1"></path><path d="M15 3h1a2 2 0 0 1 2 2v1"></path></svg><span>Out of Area</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.leadwrong')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-address-book-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 4h10a2 2 0 0 1 2 2v10m-.57 3.399c-.363 .37 -.87 .601 -1.43 .601h-10a2 2 0 0 1 -2 -2v-12"></path><path d="M10 16h6"></path><path d="M11 11a2 2 0 0 0 2 2m2 -2a2 2 0 0 0 -2 -2"></path><path d="M4 8h3"></path><path d="M4 12h3"></path><path d="M4 16h3"></path><path d="M3 3l18 18"></path></svg><span>Wrong Data</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.outofstock')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-box-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17.765 17.757l-5.765 3.243l-8 -4.5v-9l2.236 -1.258m2.57 -1.445l3.194 -1.797l8 4.5v8.5"></path><path d="M14.561 10.559l5.439 -3.059"></path><path d="M12 12v9"></path><path d="M12 12l-8 -4.5"></path><path d="M3 3l18 18"></path></svg><span>Out of Stock</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.canceledbysystem')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-device-desktop-cancel" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12.5 16h-8.5a1 1 0 0 1 -1 -1v-10a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v7.5"></path><path d="M7 20h5"></path><path d="M9 16v4"></path><path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path><path d="M17 21l4 -4"></path></svg><span>Canceled By System</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('leads.leadcanceled')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-device-ipad-cancel" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v7"></path><path d="M9 18h3"></path><path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path><path d="M17 21l4 -4"></path></svg><span>Canceled</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href=""><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icons-tabler-outline icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M13 5h8"></path><path d="M13 9h5"></path><path d="M13 15h8"></path><path d="M13 19h5"></path><path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path><path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path></svg><span>Black List</span></a></li>
                  @if(Auth::user()->id_role != "3")
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="javascript:void(0)">
                      <svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-headset" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 14v-3a8 8 0 1 1 16 0v3" /><path d="M18 19c0 1.657 -2.686 3 -6 3" /><path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" /><path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" /></svg><span class="lan">Call Center Manager</span></a>
                    <ul class="sidebar-submenu">
                      <li><a class="lan" href="{{ route('Staff.index') }}">Staff</a></li>
                      <li><a class="lan" href="{{ route('products.index') }}">Products</a></li>
                    </ul>
                  </li>
                
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('callcenter.index')}}"><svg xmlns="http://www.w3.org/2000/svg" class="menu-icon icon icon-tabler icon-tabler-chart-pie" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a2 2 0 0 1 -2 -2v-7a.9 .9 0 0 0 -1 -.8"></path><path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5"></path></svg><span>Statistics</span></a></li>
                  @endif
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          
          <!-- Container-fluid starts-->
          <div class="container-fluid dashboard-2">
            <div class="row">  
              @yield('content')
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 p-0 footer-left">
                <p class="mb-0">Copyright © 2024 Palace Agency. All rights reserved.</p>
              </div>
              <div class="col-md-6 p-0 footer-right">
                <p class="mb-0">Hand-crafted & made with <i class="fa fa-heart font-danger"></i></p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('public/assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('public/assets/js/scrollbar/simplebar.js')}}"></script>
    <script src="{{ asset('public/assets/js/scrollbar/custom.js')}}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('public/assets/js/config.js')}}"></script>
    <script src="{{ asset('public/assets/js/sidebar-menu.js')}}"></script>
    <script src="{{ asset('public/assets/js/prism/prism.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/clipboard/clipboard.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/counter/jquery.waypoints.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/counter/jquery.counterup.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/counter/counter-custom.js')}}"></script>
    <script src="{{ asset('public/assets/js/custom-card/custom-card.js')}}"></script>
    <script src="{{ asset('public/assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{ asset('public/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('public/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('public/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
    <script src="{{ asset('public/assets/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{ asset('public/assets/js/notify/index.js')}}"></script>
    <script src="{{ asset('public/assets/js/typeahead/handlebars.js')}}"></script>
    <script src="{{ asset('public/assets/js/typeahead/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('public/assets/js/typeahead/typeahead.custom.js')}}"></script>
    <script src="{{ asset('public/assets/js/typeahead-search/handlebars.js')}}"></script>
    <script src="{{ asset('public/assets/js/typeahead-search/typeahead-custom.js')}}"></script>
    <script src="{{ asset('public/assets/js/dashboard/dashboard_2.js')}}"></script>
    <!-- Template js-->
    <script src="{{ asset('public/assets/js/script.js')}}"></script>
    <!-- login js-->
      @yield('script')
  </body>
</html>
