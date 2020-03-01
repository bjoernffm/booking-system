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
        <style>
            .blurred {
                filter: blur(10px) grayscale(50%);
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            <div class="sidebar" data-color="white" data-active-color="primary">
                <!--
                Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
                -->
                <div class="logo">
                    <a href="http://www.creative-tim.com" class="simple-text logo-mini">
                        <div class="logo-image-small">
                            <img src="https://www.fvl-online.de/files/2013fvl/img/fvllogo.png">
                        </div>
                    </a>
                    <a href="http://www.creative-tim.com" class="simple-text logo-normal">
                        Booking System
                    </a>
                </div>
                <div class="sidebar-wrapper">
                    <ul class="nav">
                        <li @if (isset($title) and $title == 'Dashboard') class="active" @endif>
                            <a href="{{ action('HomeController@index') }}">
                                <i class="nc-icon nc-bank"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li @if (isset($title) and $title == 'Add Booking') class="active" @endif>
                            <a href="{{ action('BookingController@addIndex') }}">
                                <i class="nc-icon nc-simple-add"></i>
                                <p>Add Booking</p>
                            </a>
                        </li>
                        <li @if (isset($title) and $title == 'Bookings') class="active" @endif>
                            <a href="{{ action('BookingController@index') }}">
                                <i class="nc-icon nc-money-coins"></i>
                                <p>Bookings</p>
                            </a>
                        </li>
                        <li @if (isset($title) and $title == 'Slots') class="active" @endif>
                            <a href="{{ action('SlotController@index') }}">
                                <i class="nc-icon nc-bullet-list-67"></i>
                                <p>Slots</p>
                            </a>
                        </li>
                        <li @if (isset($title) and $title == 'Aircraft') class="active" @endif>
                            <a href="{{ action('AircraftController@index') }}">
                                <i class="nc-icon nc-spaceship"></i>
                                <p>Aircraft</p>
                            </a>
                        </li>
                        <li @if (isset($title) and $title == 'Users') class="active" @endif>
                            <a href="{{ action('UserController@index') }}">
                                <i class="nc-icon nc-single-02"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-panel" style="position: relative;">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                    <div class="container-fluid">
                        <div class="navbar-wrapper">
                            <div class="navbar-toggle">
                                <button type="button" class="navbar-toggler">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </div>
                            <a class="navbar-brand" href="#pablo">
                                @if (isset($title))
                                    {{ $title }}
                                @endif
                            </a>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navigation">
                            <form>
                                <div class="input-group no-border" id="searchbox">
                                </div>
                            </form>
                            <ul class="navbar-nav">
                                <li class="nav-item btn-rotate dropdown">
                                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                        <p>
                                            <span class="d-lg-none d-md-block">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                                        </p>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                                               onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                                               Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <div id="hits" class="content" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 100;">
                    <h1>
                        Search Result
                        <i class="nc-icon nc-simple-remove pull-right" style="cursor: pointer;" id="hitsCloseButton"></i>
                    </h1>
                    <div id="hitsResult"></div>
                </div>

                <div id="content" class="content">
                    @yield('content')
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
        <!--<script src="{{ env('ROOT_PATH') }}/js/app.js" type="text/javascript"></script>-->
        <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
        <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.0.0/dist/algoliasearch-lite.umd.js" integrity="sha256-MfeKq2Aw9VAkaE9Caes2NOxQf6vUa8Av0JqcUXUGkd0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.0.0/dist/instantsearch.production.min.js" integrity="sha256-6S7q0JJs/Kx4kb/fv0oMjS855QTz5Rc2hh9AkIUjUsk=" crossorigin="anonymous"></script>

        <script>
        $(document).ready(function() {
            $(".uppercaseInput").keypress((e) => {
                var charInput = e.keyCode;
                if((charInput >= 97) && (charInput <= 122)) { // lowercase
                    if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                        var newChar = charInput - 32;
                        var start = e.target.selectionStart;
                        var end = e.target.selectionEnd;
                        e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
                        e.target.setSelectionRange(start+1, start+1);
                        e.preventDefault();
                    }
                }
            });
            const searchClient = algoliasearch('9XRNPC6UIT', '4dbd0e04e9e4a769b4de2caf3062475d');

            const search = instantsearch({
              indexName: 'item',
              searchClient,
            });

            // 1. Create a render function
            const renderHits = (renderOptions, isFirstRender) => {
                const {
                    hits,
                    results,
                    widgetParams,
                } = renderOptions;

                document.querySelector("#hitsResult").innerHTML = `
                    <div class="row">
                        ${hits
                            .map(
                            (item) => {
                                let subtitle;
                                let link;

                                if (item.entity === "App\\Aircraft") {
                                    subtitle = "<small>Aircraft</small>";
                                    link = "{{ env('ROOT_PATH') }}/aircraft/"+item.item.id+"/edit";
                                } else if (item.entity === "App\\User") {
                                    subtitle = "<small>User</small>";
                                    link = "{{ env('ROOT_PATH') }}/users/"+item.item.id+"/edit";
                                } else if (item.entity === "App\\Slot") {
                                    subtitle = "<small>Slot</small>";
                                    link = "{{ env('ROOT_PATH') }}/overview?bySlotId="+item.item.id;
                                } else if (item.entity === "App\\Booking") {
                                    subtitle = "<small>Booking</small>";
                                    link = "{{ env('ROOT_PATH') }}/overview?byBookingId="+item.item.id;
                                } else if (item.entity === "App\\Ticket") {
                                    subtitle = "<small><abbr title=\"Electronic Ticket\">ETKT</abbr></small>";
                                    link = "{{ env('ROOT_PATH') }}/overview?byTicketId="+item.item.id;
                                }

                                return `<a href="${ link }" class="col-sm-3">
                                            <div class="card">
                                                <div class="card-body">
                                                 ${ item.title }<br />
                                                 <small>${ subtitle }</small>
                                                </div>
                                            </div>
                                        </a>`;
                                }
                            )
                        .join('')}
                    </div>`;
            }

            const customHits = instantsearch.connectors.connectHits(
                renderHits
            );

            $("#hitsCloseButton").click(function() {
                $("#hits").hide();
                $("#content").removeClass("blurred");
                $("#searchbox").find("input").val("");
            });

            search.addWidgets([
                instantsearch.widgets.searchBox({
                    container: "#searchbox",
                    placeholder: "Search ...",
                    autofocus: false,
                    showSubmit: false,
                    showReset: false,
                    cssClasses: {
                        input: [
                          "form-control"
                        ],
                    },
                    queryHook(query, search) {
                        $("#hits").show();
                        $("#content").addClass("blurred");
                        console.log('searching');
                        search(query);
                    },
                }),

                customHits({})
            ]);

            search.start();
            @yield('javascript')
        });
        </script>
    </body>
</html>
