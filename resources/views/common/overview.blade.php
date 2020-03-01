@extends('layouts.app')

@section('content')
<script src="https://momentjs.com/downloads/moment.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="card card-user">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Slot</h2>
                        <dl class="row">
                            <dt class="col-sm-3">Status</dt>
                            <dd class="col-sm-9">
                                <span v-if="status==='booked'" class="badge badge-info">Booked</span>
                            </dd>
                            <dt class="col-sm-3">Scheduled</dt>
                            <dd class="col-sm-9">
                                <b>Today, 21.02.2020</b><br />
                                <div class="row">
                                    <div class="col-sm-4">
                                        Boarding
                                    </div>
                                    <div class="col-sm-3">
                                        {{ $slot->boarding_on->format('H:i') }} <abbr title="Local Time">LT</abbr>
                                    </div>
                                    <div class="col-sm-5">
                                        <small id="relativeTimeBoarding"></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <abbr title="Estimated Time of Departure">ETD</abbr>
                                    </div>
                                    <div class="col-sm-3">
                                        {{ $slot->starts_on->format('H:i') }} <abbr title="Local Time">LT</abbr>
                                    </div>
                                    <div class="col-sm-5">
                                        <small id="relativeTimeDeparture"></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <abbr title="Estimated Time of Arrival">ETA</abbr>
                                    </div>
                                    <div class="col-sm-3">
                                        {{ $slot->ends_on->format('H:i') }} <abbr title="Local Time">LT</abbr>
                                    </div>
                                    <div class="col-sm-5">
                                        <small id="relativeTimeLanding"></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        Duration
                                    </div>
                                    <div class="col-sm-8">
                                        20 Minutes
                                    </div>
                                </div>
                            </dd>
                            <dt class="col-sm-3">PAX</dt>
                            <dd class="col-sm-9">
                                <b>2 Total</b><br />
                                <div class="row">
                                    <div class="col-sm-4">
                                        Headsets
                                    </div>
                                    <div class="col-sm-8">
                                        <span class="badge badge-danger">2 small Headsets needed</span><br />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        Regular
                                    </div>
                                    <div class="col-sm-8">
                                        1
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        Discounted
                                    </div>
                                    <div class="col-sm-8">
                                        1
                                    </div>
                                </div>
                            </dd>
                            <dt class="col-sm-3">Aircraft</dt>
                            <dd class="col-sm-9">
                                <b>{{ $slot->aircraft->callsign }}</b><br />
                                <div class="row">
                                    <div class="col-sm-4">
                                        Model
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $slot->aircraft->aircraftType->designator }}<br />
                                        {{ $slot->aircraft->aircraftType->manufacturer }} {{ $slot->aircraft->aircraftType->model }}
                                    </div>
                                </div>
                            </dd>
                            <dt class="col-sm-3">Pilot</dt>
                            <dd class="col-sm-9">
                                <b>{{ $slot->pilot->firstname }} {{ $slot->pilot->lastname }}</b>
                                <div class="row">
                                    <div class="col-sm-4">
                                        Mobile
                                    </div>
                                    <div class="col-sm-8">
                                        +49 1590 1054183
                                    </div>
                                </div>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h2>Bookings</h2>
                        @foreach ($slot->bookings as $booking)
                        <dl class="row">
                            <dt class="col-sm-3">
                                {{ $booking->shortcode }}<br />
                                <small>
                                    Booking<br />
                                    <br />
                                    Issued:<br />
                                    21.02.2020<br />
                                    09:30 <abbr title="Local Time">LT</abbr><br />
                                    (2 hours ago)
                                </small>
                            </dt>
                            <dd class="col-sm-9">
                                @if ($booking->internal_information != null)
                                <div class="alert alert-danger" role="alert">
                                    <b>Internal Information:</b><br />
                                    {{ $booking->internal_information }}
                                </div>
                                @endif
                                <p>
                                    @if ($booking->mobile != null)
                                    <div class="row">
                                        <div class="col-sm-3">
                                            Mobile
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $booking->mobile }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-3">
                                            Price
                                        </div>
                                        <div class="col-sm-9">
                                            EUR 85,–
                                        </div>
                                    </div>
                                </p>
                                <dl class="row">
                                    @foreach ($booking->tickets as $ticket)
                                    <dt class="col-sm-3">{{ $ticket->shortcode }}<br /><small><abbr title="Electronic ticket">ETKT</abbr></small></dt>
                                    <dd class="col-sm-9">
                                        <b>{{ $ticket->firstname }} {{ $ticket->lastname }}</b><br />
                                        @if($ticket->small_headset == 1 or $ticket->type == "discounted")
                                            @if($ticket->small_headset == 1)
                                                <span class="badge badge-danger">Small Headset</span>
                                            @endif
                                            @if($ticket->type == "discounted")
                                                <span class="badge badge-info">Discounted</span>
                                            @endif
                                        <br />
                                        @endif
                                        Price: EUR 50,–
                                    </dd>
                                    @endforeach
                                </dl>
                            </dd>
                        </dl>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelector("#relativeTimeBoarding").innerHTML = moment("{{ $slot->boarding_on->toIso8601ZuluString() }}").fromNow();
    document.querySelector("#relativeTimeDeparture").innerHTML = moment("{{ $slot->starts_on->toIso8601ZuluString() }}").fromNow();
    document.querySelector("#relativeTimeLanding").innerHTML = moment("{{ $slot->ends_on->toIso8601ZuluString() }}").fromNow();

    setInterval(() => {
        document.querySelector("#relativeTimeBoarding").innerHTML = moment("{{ $slot->boarding_on->toIso8601ZuluString() }}").fromNow();
        document.querySelector("#relativeTimeDeparture").innerHTML = moment("{{ $slot->starts_on->toIso8601ZuluString() }}").fromNow();
        document.querySelector("#relativeTimeLanding").innerHTML = moment("{{ $slot->ends_on->toIso8601ZuluString() }}").fromNow();
    }, 10000);
</script>
@endsection