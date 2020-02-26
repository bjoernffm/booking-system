<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title></title>
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
            <dl class="row">
                <dt class="col-sm-3">SLOT</dt>
                <dd class="col-sm-9">
                    {{ $slot->aircraft->callsign }} / {{ $slot->aircraft->aircraftType->designator }} / {{ $slot->pilot->firstname }} {{ $slot->pilot->lastname }}<br />
                    {{ \Carbon\Carbon::parse($slot->starts_on)->format('d.m.Y') }} {{ \Carbon\Carbon::parse($slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl
                </dd>
                <dt class="col-sm-3">STATUS</dt>
                <dd class="col-sm-9">
                    <span v-if="status==='available'" class="badge badge-pill badge-success">Available</span>
                    <span v-if="status==='booked'" class="badge badge-pill badge-info">Booked</span>
                    <span v-if="status==='boarding'" class="badge badge-pill badge-warning">Boarding</span>
                    <span v-if="status==='departed'" class="badge badge-pill badge-info">Departed</span>
                    <span v-if="status==='departed'" class="badge badge-pill badge-secondary">Landed</span>
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
        </div>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
        <script>
            var app = new Vue({
                el: "#app",
                data: {
                    status: "{{$slot->status}}",
                    loading: false
                },
                methods: {
                    set: function(status) {
                        this.loading = true;
                        axios.put(
                            "{{ action('ApiSlotController@update', ['slot' => $slot->id]) }}",
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
