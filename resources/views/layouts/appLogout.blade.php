<!--
=========================================================
 Paper Dashboard 2 - v2.0.0
=========================================================

 Product Page: https://www.creative-tim.com/product/paper-dashboard-2
 Copyright 2019 Creative Tim (https://www.creative-tim.com)
 Licensed under MIT (https://github.com/creativetimofficial/paper-dashboard/blob/master/LICENSE)

 Coded by Creative Tim

=========================================================

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

    <head>
        <link rel="shortcut icon" href="https://www.fvl-online.de/files/2013fvl/img/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon.png" />
        <link rel="apple-touch-icon" sizes="57x57" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="60x60" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-60x60.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="https://www.fvl-online.de/files/2013fvl/img/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="favicon.ico">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            FVL BOOKING SYSTEM
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
        <!-- CSS Files -->
        <link href="{{ env('ROOT_PATH') }}/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{ env('ROOT_PATH') }}/assets/css/paper-dashboard.css?v=2.0.0" rel="stylesheet" />
        <link href="{{ env('ROOT_PATH') }}/css/chosen.css" rel="stylesheet" />
    </head>

    <body>
        <div class="wrapper">
            <div class="main-panel" style="width: 100%; clear: both;">
                <div class="content">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>

                <footer class="footer footer-black footer-white">
                    <div class="container-fluid">
                        <div class="row">
                            <nav class="footer-nav">
                                <ul>
                                    <li>
                                        <a href="https://www.fvl-online.de/de/" target="_blank">FVL Online</a>
                                    </li>
                                    <li>
                                        <a href="https://www.resi.de/" target="_blank">Resi</a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="credits ml-auto">
                                <span class="copyright">
                                    &copy;
                                    <script>
                                    document.write(new Date().getFullYear())
                                    </script>, made with <i class="fa fa-heart heart"></i> by Creative Tim
                                </span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!--   Core JS Files   -->
        <script src="{{ env('ROOT_PATH') }}/assets/js/core/jquery.min.js"></script>
        <script src="{{ env('ROOT_PATH') }}/assets/js/core/popper.min.js"></script>
        <script src="{{ env('ROOT_PATH') }}/assets/js/core/bootstrap.min.js"></script>
        <!--<script src="{{ env('ROOT_PATH') }}/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>-->
        <!-- Chart JS -->
        <script src="{{ env('ROOT_PATH') }}/assets/js/plugins/chartjs.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="{{ env('ROOT_PATH') }}/assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ env('ROOT_PATH') }}/assets/js/paper-dashboard.js" type="text/javascript"></script>
        <script src="{{ env('ROOT_PATH') }}/js/app.js" type="text/javascript"></script>
        <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    </body>
</html>