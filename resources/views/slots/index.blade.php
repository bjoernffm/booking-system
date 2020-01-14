@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header">
                <h4 class="card-title">Overview</h4>
            </div>
            <div class="card-body">
                <div>
                    <a href="{{ action('SlotController@create') }}" class="btn btn-sm btn-primary pull-right">create Slot</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Aircraft</th>
                            <th>Pilot</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($slots as $slot)
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
                                    <a href="{{ action('UserController@edit', ['user' => $slot->pilot_id]) }}">
                                        {{ $slot->pilot_firstname }} {{ $slot->pilot_lastname }}
                                    </a>
                                </td>
                                <td>
                                    @if ($slot->status == 'available')
                                        <span class="badge badge-pill badge-success">Available</span>
                                    @elseif ($slot->status == 'booked')
                                        <span class="badge badge-pill badge-info">Booked</span>
                                    @elseif ($slot->status == 'boarding')
                                        <span class="badge badge-pill badge-warning">Boarding</span>
                                    @elseif ($slot->status == 'departed')
                                        <span class="badge badge-pill badge-info">Departed</span>
                                    @elseif ($slot->status == 'landed')
                                        <span class="badge badge-pill badge-secondary">Landed</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($slot->status == 'available')
                                        <a href="{{ action('SlotController@edit', ['slot' => $slot->id]) }}" class="btn btn-sm btn-outline-primary btn-round btn-icon"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ action('SlotController@prepareDestroy', ['id' => $slot->id]) }}" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <a href="{{ action('SlotController@create') }}" class="btn btn-sm btn-primary pull-right">create Slot</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
