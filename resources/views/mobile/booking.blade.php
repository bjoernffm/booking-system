<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>{{ $booking->aircraft_callsign }} | {{ \Carbon\Carbon::parse($booking->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl</title>
        <style>
            .spinner {
              margin: 0px auto;
              width: 70px;
              text-align: center;
            }

            .spinner > div {
              width: 18px;
              height: 18px;
              background-color: #fff;

              border-radius: 100%;
              display: inline-block;
              -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
              animation: sk-bouncedelay 1.4s infinite ease-in-out both;
            }

            .spinner .bounce1 {
              -webkit-animation-delay: -0.32s;
              animation-delay: -0.32s;
            }

            .spinner .bounce2 {
              -webkit-animation-delay: -0.16s;
              animation-delay: -0.16s;
            }

            @-webkit-keyframes sk-bouncedelay {
              0%, 80%, 100% { -webkit-transform: scale(0) }
              40% { -webkit-transform: scale(1.0) }
            }

            @keyframes sk-bouncedelay {
              0%, 80%, 100% {
                -webkit-transform: scale(0);
                transform: scale(0);
              } 40% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
              }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid" id="app">
            <br />
            @if ($booking->internal_information !== null)
                <div class="alert alert-info" role="alert">
                  <b>Internal Information:</b><br />
                  {{ $booking->internal_information }}
                </div>
            @endif
            @if ($booking->small_headsets == 1)
                <div class="alert alert-warning" role="alert">
                    <b>1 small Headset</b> needed
                </div>
            @elseif ($booking->small_headsets > 1)
                <div class="alert alert-warning" role="alert">
                    <b>{{ $booking->small_headsets }} small Headsets</b> needed
                </div>
            @endif
            <dl class="row">
                <dt class="col-sm-3">SLOT</dt>
                <dd class="col-sm-9">
                    {{ $booking->aircraft_callsign }} / {{ $booking->aircraft_designator }} / {{ $booking->pilot_firstname }} {{ $booking->pilot_lastname }}<br />
                    {{ \Carbon\Carbon::parse($booking->starts_on)->format('d.m.Y') }} {{ \Carbon\Carbon::parse($booking->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl
                </dd>
                <dt class="col-sm-3">STATUS</dt>
                <dd class="col-sm-9">
                    <span v-if="status==='available'" class="badge badge-pill badge-success">Available</span>
                    <span v-if="status==='booked'" class="badge badge-pill badge-info">Booked</span>
                    <span v-if="status==='boarding'" class="badge badge-pill badge-warning">Boarding</span>
                    <span v-if="status==='departed'" class="badge badge-pill badge-info">Departed</span>
                    <span v-if="status==='departed'" class="badge badge-pill badge-secondary">Landed</span>
                </dd>
                <dt class="col-sm-3">PAX</dt>
                <dd class="col-sm-9">
                    @if(count($booking->passengers) == 1)
                         1 Passenger
                    @else
                         {{ count($booking->passengers) }} Passengers
                    @endif
                    <ul>
                        @foreach ($booking->passengers as $passenger)
                            <li>
                                {{ $passenger->firstname }} {{ $passenger->lastname }}
                                @if ($passenger->infoText != '')
                                    ({{ $passenger->infoText }})
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </dd>
            </dl>
            <button v-if="status=='booked'" v-on:click="set('boarding')" v-bind:disabled="loading===true" class="btn btn-success btn-lg btn-block" style="padding: 15px 0;">
                <div class="spinner" v-if="loading===true">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <span v-if="loading===false">
                    Start Boarding
                </span>
            </button>
            <button v-if="status=='boarding'" v-on:click="set('booked')" v-bind:disabled="loading===true" class="btn btn-warning btn-lg btn-block" style="padding: 15px 0;">
                <div class="spinner" v-if="loading===true">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <span v-if="loading===false">
                    Reset to Booked
                </span>
            </button>
            <hr />
            @if ($booking->pilot_mobile != null)
                <a href="tel:{{ $booking->pilot_mobile }}" class="btn btn-primary btn-lg btn-block" style="padding: 15px 0;">Contact Pilot</a>
            @endif
            <a href="tel:+491774147290" class="btn btn-info btn-lg btn-block" style="padding: 15px 0;">Contact Booking</a>

            @if ($booking->mobile !== null)
                <a href="tel:{{ $booking->mobile }}" class="btn btn-dark btn-lg btn-block" style="padding: 15px 0;">Contact Pax</a>
            @endif
        </div>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
        <script>
            var app = new Vue({
                el: "#app",
                data: {
                    status: "{{$booking->status}}",
                    loading: false
                },
                methods: {
                    set: function(status) {
                        this.loading = true;
                        axios.put(
                            "{{ action('ApiSlotController@update', ['slot' => $booking->slot_id]) }}",
                            {
                                status
                            }
                        ).then((response) => {
                            this.status = status;
                            this.loading = false;
                        });
                    }
                }
            });
        </script>
    </body>
</html>
