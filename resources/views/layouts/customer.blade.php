<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>OFFICE COFFEE</title>
    <link rel="shortcut icon" href="{{asset('img/AMEN.jpg')}}" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">




    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">



    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <!-- Toast notification-->
    <meta name="msapplication-config" content="/docs/4.4/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">

    @yield('css')

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .scrollTOP {
            overflow: hidden;

            position: fixed;
            /* Set the navbar to fixed position */
            top: 59px;
            /* Position the navbar at the top of the page */
            width: 100%;
            /* Full width */
        }

        .scrollBottom {}

        .cart {
            background-color: #f6c23e;
            width: 100%;
            height: 50px;
            overflow: hidden;

            position: fixed;
            /* Set the navbar to fixed position */
            bottom: 0px;
            /* Position the navbar at the top of the page */
        }

        .hidden {
            width: 100%;
            height: 105px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            position: relative;
        }

        #myProgress {
            width: 100%;
            background-color: white;
        }

        #myBar {
            width: 0%;
            height: 3px;
            background-color: #f6c23e;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        .nav-underline .nav-link:hover {
            color: #f6c23e;
        }

        .input-checked:checked {
            color: white;
            background-color: #f6c23e;
            border-color: #f6c23e;
        }

        .style {
            background-color: #f6c23e;
            border: none;
            color: white;
        }
    </style>
    <!-- Custom styles for this template -->
    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/offcanvas/">
</head>

<body>
    <?php

    ?>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-warning">
        <a href="{{URL::to('/')}}" style="width: 90%"><img src="{{asset('img/office_logo_1.png')}}" alt="" width="14%" height="auto"></a>
        <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{URL::to('/')}}">Trang chủ <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profiler')}}">Thông tin khách hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ordernow')}}">Đơn hàng gần nhất</a>
                        </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('notification')}}">Lịch sử giao dịch</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout')}}">Đăng xuất</a>
                </li>

            </ul>
        </div>
    </nav>
    @yield('categories')


    <main role="main" class="container-fluid" style="background-color: #d4d4d41f">

            @yield('customer')

    </main>

    @yield('showcart')


    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    @yield('script')
    <script>
        var i = 0;
        $('.nav-link').on('click', function(){
            if (i == 0) {
        i = 1;
        var elem = document.getElementById("myBar");
        var width = 0;
        var id = setInterval(frame, 10);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
                i = 0;
            } else {
                width++;
                elem.style.width = width + "%";
                elem.innerHTML = width  + "%";
        }
    }
  }
        });
    </script>

</body>

</html>
