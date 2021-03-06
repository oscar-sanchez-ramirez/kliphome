<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KlipHome</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ url('') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="{{ url('') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('')}}/img/favicon.ico">
  <!-- Custom styles for this template -->
  <link href="{{ url('') }}/css/grayscale.min.css" rel="stylesheet">

</head>
<body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="{{ url('/admin') }}">Home</a>
                        </li>
                    @else
                        @if(!Request::is('email-confirmation/*'))
                            <li class="nav-item">
                                <a class="nav-link js-scroll-trigger" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
        </div>
    </nav>

        @yield('content')
    <!-- Footer -->
    {{-- <footer class="bg-black small text-center text-white-50">
        <div class="container">
        Copyright &copy; KlipHome 2020
        </div>
    </footer> --}}


    <!-- Bootstrap core JavaScript -->
    <script src="{{ url('') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ url('') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="{{ url('') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="{{ url('') }}/js/grayscale.min.js"></script>
</body>
</html>
