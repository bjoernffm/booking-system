@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header">
                <h4 class="card-title">Bookings</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Aircraft</th>
                            <th>Pilot</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $slot)
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
                                    {{ $slot->price }},- &euro;
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
                                    <a href="{{ action('BookingController@edit', ['booking' => $slot->booking_id]) }}" class="btn btn-sm btn-outline-primary btn-round btn-icon"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ action('BookingController@show', ['booking' => $slot->booking_id]) }}" class="btn btn-sm btn-outline-warning btn-round btn-icon"><i class="fa fa-barcode"></i></a>
                                    <a href="{{ action('BookingController@prepareDestroy', ['id' => $slot->booking_id]) }}" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></a>
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
