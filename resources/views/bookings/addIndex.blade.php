@extends('layouts.app')

@section('content')
<style>
.spinner {
  margin: 100px auto 0;
  width: 70px;
  text-align: center;
}

.spinner > div {
  width: 18px;
  height: 18px;
  background-color: #333;

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.0.13/svg.min.js" integrity="sha256-lGl8w8KbUgg+8cFC38Erx5NRwynFuqFLvdwkdR9ggjM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
<div class="row" id="app">
    <div class="col-md-12">
        <div class="card card-user">
            <div class="card-header">
                <h4 class="card-title">Open Slots</h4>
            </div>
            <div class="card-body" id="test" style="min-height: 0;">
                <slot-strip></slot-strip>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header">
                <h4 class="card-title">Open Slots</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Aircraft</th>
                            <th>max. Load</th>
                            <th>Pilot</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($openSlots as $slot)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($slot->starts_on)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl</td>
                                <td>{{ \Carbon\Carbon::parse($slot->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl</td>
                                <td>
                                    <a href="{{ action('AircraftController@edit', ['aircraft' => $slot->aircraft_id]) }}">
                                        {{ $slot->aircraft_callsign }} / {{ $slot->aircraft_designator }}
                                    </a>
                                </td>
                                <td>
                                    {{ $slot->aircraft_load }}
                                </td>
                                <td>
                                    <a href="{{ action('UserController@edit', ['user' => $slot->pilot_id]) }}">
                                        {{ $slot->pilot_firstname }} {{ $slot->pilot_lastname }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ action('BookingController@create', ['slot_id' => $slot->id]) }}" class="btn btn-sm btn-outline-primary btn-round">Book</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.slot-available {
  fill: #6bd098;
  cursor: pointer;
}
.slot-available:hover {
  fill: #28a745;
}
.slot-booked {
  fill: #6c757d;
}
.slot-boarding {
  fill: #6c757d;
}
.slot-departed {
  fill: #6c757d;
}
.slot-landed {
  fill: #6c757d;
}
.row {
  fill: #eee;
}
</style>
@endsection
@section('javascript')

Vue.component("slot-strip", {
    data: function() {
        return {
            state: "loading",
            data: null
        }
    },
    methods: {
        initStrip: function() {
            var draw = SVG().addTo('#strip').size('100%', '100%');
            let options = { hour:"2-digit", minute:"2-digit" };

            // define colors for later fades
            let gradientWhite = draw.gradient('linear', function(add) {
              add.stop({ offset: 0.6, color: '#fff', opacity: 1 })
              add.stop({ offset: 1, color: '#fff', opacity: 0 })
            });
            let gradient = draw.gradient('linear', function(add) {
              add.stop({ offset: 0.6, color: '#eee', opacity: 1 })
              add.stop({ offset: 1, color: '#eee', opacity: 0 })
            });

            // draw the rows
            let y = 20;
            for(let i = 0; i < this.data.length; i++) {
                if (i%2 == 0) {
                    draw.rect("100%", 30).move(0, y+(i*30)+20).addClass('row');
                }
            }

            var group = draw.group();

            // draw the hour lines + text
            for(let i = 1; i < 48; i++) {
                let hour = (i-1)%24;
                if (hour < 10) {
                    hour = "0"+hour;
                }
                group.line(i*150, 0, i*150, 150).stroke({ color: '#ddd', width: 1, linecap: 'round' });
                group.plain(hour+" local").move((i*150)+5, 20).font({
                  family:   'Arial',
                  size:     10,
                  anchor:   'left',
                  fill: '#000'
                });
            }

            let callsigns = [];
            for(let i = 0; i < this.data.length; i++) {
                callsigns.push(this.data[i].callsign);

                let slots = this.data[i].slots;
                for(let j = 0; j < slots.length; j++) {
                    let start = new Date(slots[j].starts_on);
                    let end = new Date(slots[j].ends_on);
                    let itemOffset = (start.getTime()%86400000)/86400000;
                    let itemDuration = (((end-start)/1000)/3600);

                    let el = group.rect(itemDuration*150, 20).radius(4).move((itemOffset*24*150+300), (y+(i*30)+25)).addClass('slot-'+slots[j].status);

                    if(slots[j].status == "available") {
                        //el.attr('href', bookingUrl+"?slot_id="+slots[j].id);
                        el.click(() => {
                            window.location.href = "https://192.168.178.26/booking-system/bookings/create?slot_id="+slots[j].id;
                        });
                    }
                }
            }

            let offset = ((new Date()).getTime()%86400000)/86400000;
            group.transform({
              positionX: ((offset*-24*150)+50),
              //positionX: -2500,
              origin: 'top left'
            })

            setInterval(() => {
                let offset = ((new Date()).getTime()%86400000)/86400000;
                group.transform({
                  positionX: ((offset*-24*150)+50),
                  origin: 'top left'
                });
                clock.node.textContent=(new Date()).toLocaleString("de-DE", options);
            }, 1000);


            draw.rect(150, 40).move(0, 0).fill(gradientWhite);
            for(let i = 0; i < callsigns.length; i++) {
                if (i % 2 == 0) {
                    draw.rect(200, 30).move(0, y+(i*30)+20).fill(gradient);
                } else {
                    draw.rect(200, 30).move(0, y+(i*30)+20).fill(gradientWhite);
                }
                text = draw.plain(callsigns[i]).move(5, y+(i*30)+27).font({
                  family:   'Arial',
                  size:     14,
                  anchor:   'left',
                  fill: '#000'
                });
            }

            draw.line(200, 0, 200, 150).stroke({ color: '#666', width: 1.5, linecap: 'round' });

            draw.rect(46, 18).move(177, 0).fill("#666");
            let clock = draw.plain((new Date()).toLocaleString("de-DE", options)).move(182, 1).font({
                family:   'Arial',
                size:     14,
                anchor:   'center',
                fill: '#fff'
            });
        }
    },
    mounted: function() {
        axios.get('/booking-system/api/slots')
            .then((response) => {
                this.state = "done";
                this.data = response.data;
                this.initStrip();
            });
    },
    template: "<div><div class=\"spinner\" v-if=\"state=='loading'\"><div class=\"bounce1\"></div><div class=\"bounce2\"></div><div class=\"bounce3\"></div></div><div id=\"strip\"></div></div>"
});

var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!'
    }
})

let bookingUrl = "{{ action('BookingController@create') }}";

@endsection
