@extends('layouts.app')

@section('content')
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
                                    <a href="{{ action('AircraftController@edit', ['id' => $slot->aircraft_id]) }}">
                                        {{ $slot->aircraft_callsign }} / {{ $slot->aircraft_designator }}
                                    </a>
                                </td>
                                <td>
                                    {{ $slot->aircraft_load }}
                                </td>
                                <td>
                                    <a href="{{ action('UserController@edit', ['id' => $slot->pilot_id]) }}">
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
@endsection
