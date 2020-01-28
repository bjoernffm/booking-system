@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-user">
            <div class="card-header">
                <h4 class="card-title">Open Slots</h4>
            </div>
            <div class="card-body" id="test" style="min-height: 0;">
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
// initialize SVG.js
var draw = SVG().addTo('#test').size('100%', '100%');



let data = [{
    "callsign": "DEDRP (190kg)",
    "slots": [
        {
            "id": "1",
            "status": "landed",
            "starts_on": "2020-01-13T16:50:00Z",
            "ends_on": "2020-01-13T17:10:00Z"
        },
        {
            "id": "2",
            "status": "departed",
            "starts_on": "2020-01-13T17:20:00Z",
            "ends_on": "2020-01-13T17:40:00Z"
        },
        {
            "id": "3",
            "status": "boarding",
            "starts_on": "2020-01-13T17:50:00Z",
            "ends_on": "2020-01-13T18:10:00Z"
        },
        {
            "id": "4",
            "status": "booked",
            "starts_on": "2020-01-13T18:20:00Z",
            "ends_on": "2020-01-13T18:40:00Z"
        },
        {
            "id": "5",
            "status": "available",
            "starts_on": "2020-01-13T18:50:00Z",
            "ends_on": "2020-01-13T19:10:00Z"
        }
    ]
}, {
    "callsign": "DELZC (220kg)",
    "slots": [
        {
            "id": "1",
            "status": "landed",
            "starts_on": "2020-01-13T17:00:00Z",
            "ends_on": "2020-01-13T17:20:00Z"
        },
        {
            "id": "2",
            "status": "departed",
            "starts_on": "2020-01-13T17:30:00Z",
            "ends_on": "2020-01-13T17:50:00Z"
        },
        {
            "id": "3",
            "status": "boarding",
            "starts_on": "2020-01-13T18:00:00Z",
            "ends_on": "2020-01-13T18:20:00Z"
        },
        {
            "id": "4",
            "status": "booked",
            "starts_on": "2020-01-13T18:30:00Z",
            "ends_on": "2020-01-13T18:50:00Z"
        },
        {
            "id": "5",
            "status": "available",
            "starts_on": "2020-01-13T19:00:00Z",
            "ends_on": "2020-01-13T19:20:00Z"
        }
    ]
}, {
    "callsign": "DEFVC (180kg)",
    "slots": [
        {
            "id": "1",
            "status": "landed",
            "starts_on": "2020-01-13T17:10:00Z",
            "ends_on": "2020-01-13T17:30:00Z"
        },
        {
            "id": "2",
            "status": "departed",
            "starts_on": "2020-01-13T17:40:00Z",
            "ends_on": "2020-01-13T18:00:00Z"
        },
        {
            "id": "3",
            "status": "boarding",
            "starts_on": "2020-01-13T18:10:00Z",
            "ends_on": "2020-01-13T18:30:00Z"
        },
        {
            "id": "4",
            "status": "booked",
            "starts_on": "2020-01-13T18:40:00Z",
            "ends_on": "2020-01-13T19:00:00Z"
        },
        {
            "id": "5",
            "status": "available",
            "starts_on": "2020-01-13T19:10:00Z",
            "ends_on": "2020-01-13T19:30:00Z"
        }
    ]
}];

let gradientWhite = draw.gradient('linear', function(add) {
  add.stop({ offset: 0.6, color: '#fff', opacity: 1 })
  add.stop({ offset: 1, color: '#fff', opacity: 0 })
});
let gradient = draw.gradient('linear', function(add) {
  add.stop({ offset: 0.6, color: '#eee', opacity: 1 })
  add.stop({ offset: 1, color: '#eee', opacity: 0 })
});

// draw pink square
let y = 20;
for(let i = 0; i < data.length; i++) {
    if (i%2 == 0) {
        draw.rect("100%", 30).move(0, y+(i*30)).addClass('row');
    }
}

var group = draw.group();

for(let i = 1; i < 48; i++) {
    let hour = (i-1)%24;
    if (hour < 10) {
        hour = "0"+hour;
    }
    group.line(i*150, 0, i*150, 150).stroke({ color: '#ddd', width: 1, linecap: 'round' });
    group.text(hour+" local").move((i*150)+5, 10).font({
      family:   'Arial',
      size:     10,
      anchor:   'left',
      leading:  '1.0em',
      fill: '#000'
    });
}

let callsigns = [];
for(let i = 0; i < data.length; i++) {
    callsigns.push(data[i].callsign);

    let slots = data[i].slots;
    for(let j = 0; j < slots.length; j++) {
        let start = new Date(slots[j].starts_on);
        let end = new Date(slots[j].ends_on);
        let itemOffset = (start.getTime()%86400000)/86400000;
        let itemDuration = (((end-start)/1000)/3600);

        let el = group.rect(itemDuration*150, 20).radius(4).move((itemOffset*24*150+200), (y+(i*30)+5)).addClass('slot-'+slots[j].status);
        if(slots[j].status == "available") {
            el.click(() => {
                alert(slots[j].id);
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
}, 1000);

draw.rect(150, 20).move(0, 0).fill(gradientWhite);
for(let i = 0; i < callsigns.length; i++) {
    if (i % 2 == 0) {
        draw.rect(200, 30).move(0, y+(i*30)).fill(gradient);
    } else {
        draw.rect(200, 30).move(0, y+(i*30)).fill(gradientWhite);
    }
    text = draw.text(callsigns[i]).move(5, y+(i*30)).font({
      family:   'Arial',
      size:     14,
      anchor:   'left',
      leading:  '1.8em',
      fill: '#000'
    });
}

draw.line(200, 0, 200, 150).stroke({ color: '#666', width: 1.5, linecap: 'round' });
@endsection
