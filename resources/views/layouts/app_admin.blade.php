<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="KlipHome">
    <meta name="author" content="789.mx">
    <meta name="keywords" content="Plomeria">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title Page-->
    <title>KlipHome</title>

    <!-- Fontfaces CSS-->
    <link href="{{ url('') }}/css/font-face.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ url('') }}/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ url('') }}/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="{{ url('') }}/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ url('') }}/css/theme.css" rel="stylesheet" media="all">

    @if(Request::is('messenger'))
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="{{ url('') }}/index.html">
                            <img src="{{ url('') }}/images/icon/kliphomelogo.png" height="50px" width="50px" alt="Klip Home Logo" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="{{ active_menu('admin')}}">
                            <a href="{{ url('') }}/admin"><i class="fas fa-tachometer-alt"></i>Inicio</a>
                        </li>
                        <li class="{{ active_menu('categorias')}} {{ active_menu('categorias/*')}}">
                            <a href="{{ url('') }}/categorias">
                                <i class="fas fa-table"></i>Categorías</a>
                        </li>
                        <li class="{{ active_menu('servicios')}} {{ active_menu('servicios/*')}}">
                            <a href="{{ url('') }}/servicios">
                                <i class="far fa-check-square"></i>Servicios</a>
                        </li>
                        <li class="{{ active_menu('ordenes')}} {{ active_menu('ordenes/*')}}">
                            <a href="{{ url('') }}/ordenes">
                                <i class="fas fa-calendar-alt"></i>Ordenes</a>
                        </li>
                        <li class="{{ active_menu('clientes')}} {{  active_menu('clientes/*')}}">
                            <a href="{{ url('') }}/clientes">
                                <i class="fas fa-users"></i>Clientes</a>
                        </li>
                        <li class="{{ active_menu('tecnicos')}} {{ active_menu('tecnicos/*')}}">
                            <a href="{{ url('') }}/tecnicos">
                                <i class="fas fa-users"></i>Técnicos</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="{{ url('') }}/#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{ url('') }}/login.html">Login</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/register.html">Register</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="{{ url('') }}/#">
                                <i class="fas fa-desktop"></i>UI Elements</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{ url('') }}/button.html">Button</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/typo.html">Typography</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="{{ url('') }}/#">
                    <img src="{{ url('') }}/images/icon/kliphomelogo.png" alt="Klip Home Logo" height="30%" width="30%" />
                </a>
            </div>
            @php
                function active_menu($url)
                {
                    return request()->is($url) ? 'active' : '';
                }
            @endphp
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="{{ active_menu('admin')}}">
                            <a href="{{ url('') }}/admin"><i class="fas fa-tachometer-alt"></i>Inicio</a>
                        </li>
                        {{-- <li>
                            <a href="{{ url('') }}/chart.html">
                                <i class="fas fa-chart-bar"></i>Charts</a>
                        </li> --}}
                        <li class="{{ active_menu('categorias')}} {{ active_menu('categorias/*')}}">
                            <a href="{{ url('') }}/categorias">
                                <i class="fas fa-table"></i>Categorías</a>
                        </li>
                        <li class="{{ active_menu('servicios')}} {{ active_menu('servicios/*')}}">
                            <a href="{{ url('') }}/servicios">
                                <i class="far fa-check-square"></i>Servicios</a>
                        </li>
                        <li class="{{ active_menu('ordenes')}} {{ active_menu('ordenes/*')}}">
                            <a href="{{ url('') }}/ordenes">
                                <i class="fas fa-calendar-alt"></i>Ordenes</a>
                        </li>
                        <li class="{{ active_menu('clientes')}} {{  active_menu('clientes/*')}}">
                            <a href="{{ url('') }}/clientes">
                                <i class="fas fa-users"></i>Clientes</a>
                        </li>
                        <li class="{{ active_menu('tecnicos')}} {{ active_menu('tecnicos/*')}}">
                            <a href="{{ url('') }}/tecnicos">
                                <i class="fas fa-users"></i>Técnicos</a>
                        </li>
                        {{-- <li class="has-sub">
                            <a class="js-arrow" href="{{ url('') }}/#">
                                <i class="fas fa-desktop"></i>Solicitudes</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="{{ url('') }}/button.html">Button</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="{{ url('') }}/typo.html">Typography</a>
                                </li>
                            </ul>
                        </li> --}}
                        {{-- <li>
                            <a href="{{ url('') }}/map.html"><i class="fas fa-bookmark"></i>Cupones</a>
                        </li> --}}
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                    <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-email"></i>
                                        <span class="quantity">1</span>
                                        <div class="email-dropdown js-dropdown">
                                            <div class="email__title">
                                                <p>You have 3 New Emails</p>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="{{ url('') }}/images/icon/avatar-06.jpg" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, 3 min ago</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="{{ url('') }}/images/icon/avatar-05.jpg" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, Yesterday</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="{{ url('') }}/images/icon/avatar-04.jpg" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, April 12,,2018</span>
                                                </div>
                                            </div>
                                            <div class="email__footer">
                                                <a href="{{ url('') }}/#">See all emails</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="noti__item js-item-menu">
                                        @php
                                            $notificaciones = Auth::user()->unreadNotifications->count();
                                            if($notificaciones > 9){
                                                $notificaciones = "9+";
                                            }if($notificaciones == 0){
                                                $notificaciones = "";
                                            }
                                        @endphp
                                        <i class="zmdi zmdi-notifications"></i>
                                        @if($notificaciones > 0)
                                        <span class="quantity">{{ $notificaciones }}</span>
                                        @endif
                                        <div class="notifi-dropdown js-dropdown">
                                            <div class="notifi__title">
                                                <p>Tienes {{ Auth::user()->unreadNotifications->count() }} Notificaciones</p>
                                            </div>
                                            @foreach(Auth::user()->unreadNotifications()->take(5)->get() as $notification)
                                            @if($notification->type == "App\Notifications\NotifyAcceptOrder")
                                                <div class="notifi__item">
                                                    <div class="bg-c1 img-cir img-40">
                                                        <i class="fa fa-wrench"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p>Un Técnico aceptó un trabajo</p>
                                                        <a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($notification->data["order_id"])) }}">Ver</a>
                                                        <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($notification->type == "App\Notifications\Database\NewQuotation")
                                                <div class="notifi__item">
                                                    <div class="bg-c1 img-cir img-40">
                                                        <i class="fa fa-wrench"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p>Una órden necesita una cotización</p>
                                                        <a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($notification->data["order_id"])) }}">Cotizar</a>
                                                        <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($notification->type == "App\Notifications\Database\QuotationCancelled")
                                                <div class="notifi__item">
                                                    <div class="bg-c1 img-cir img-40">
                                                        <i class="fa fa-wrench"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p>El usuario no aceptó la tarifa</p>
                                                        <a href="{{ url('') }}/ordenes/detalle-orden/{{ str_replace('"','',json_encode($notification->data["id"])) }}">Ver Órden</a>
                                                        <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($notification->type == "App\Notifications\NewFixerMan")
                                                <div class="notifi__item">
                                                    <div class="bg-c1 img-cir img-40">
                                                        <i class="fa fa-wrench"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p>Un nuevo técnico se registró</p>
                                                        <a href="{{ url('') }}/tecnicos">Ver</a>
                                                        <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @endforeach
                                            <div class="notifi__footer">
                                                <a href="#">Todas las Notificaciones</a>
                                                <a href="#">Marcar todas como leidas</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="{{ url('') }}/images/icon/avatar-01.jpg" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="{{ url('') }}/#">{{ Auth::user()->name }}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="{{ url('') }}/#">
                                                        <img src="{{ url('') }}/images/icon/avatar-01.jpg" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="{{ url('') }}/#">{{ Auth::user()->name }}</a>
                                                    </h5>
                                                    <span class="email">{{ Auth::user()->email }}</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{ url('') }}/#">
                                                        <i class="zmdi zmdi-account"></i>Cuenta</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="{{ url('') }}/#">
                                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="{{ url('') }}/#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="{{ url('') }}/#">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->
                <div id="app">
                    @yield('content')
                </div>
            <!-- MAIN CONTENT-->

            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{ url('') }}/vendor/jquery-3.2.1.min.js"></script>
    @if(Request::is('categorias'))
        <script src="{{ url('') }}/vendor/categories/subcategories.js"></script>
    @endif
    @if(Request::is('servicios'))
        <script src="{{ url('') }}/vendor/services/subservices.js"></script>
    @endif
    @if(Request::is('tecnicos'))
        <script src="{{ url('') }}/vendor/fixerman/fixerman.js"></script>
    @endif
    <!-- Bootstrap JS-->
    <script src="{{ url('') }}/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="{{ url('') }}/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="{{ url('') }}/vendor/slick/slick.min.js">
    </script>
    <script src="{{ url('') }}/vendor/wow/wow.min.js"></script>
    <script src="{{ url('') }}/vendor/animsition/animsition.min.js"></script>
    <script src="{{ url('') }}/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="{{ url('') }}/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="{{ url('') }}/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="{{ url('') }}/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="{{ url('') }}/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ url('') }}/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="{{ url('') }}/vendor/select2/select2.min.js"></script>

    <!-- Main JS-->
    <script src="{{ url('') }}/js/main.js"></script>

    @if(Request::is('messenger'))
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif

</body>

</html>
<!-- end document-->
