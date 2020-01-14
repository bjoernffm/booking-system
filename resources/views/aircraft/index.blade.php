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
                    <a href="{{ action('AircraftController@create') }}" class="btn btn-sm btn-primary pull-right">create Aircraft</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>Callsign</th>
                            <th>Designator</th>
                            <th>Description</th>
                            <th>max. Pax Load</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($aircrafts as $aircraft)
                            <tr>
                                <td>
                                    <a href="{{ action('AircraftController@edit', ['aircraft' => $aircraft->id]) }}">{{ $aircraft->callsign }}</a>
                                </td>
                                <td title="{{ $aircraft->manufacturer }} {{ $aircraft->model }}">{{ $aircraft->designator }}</td>
                                <td>{{ $aircraft->engine_count }}x {{ $aircraft->engine_type }} / {{ $aircraft->description }}</td>
                                <td>{{ $aircraft->load }} kg</td>
                                <td>
                                    <a href="{{ action('AircraftController@edit', ['aircraft' => $aircraft->id]) }}" class="btn btn-sm btn-outline-primary btn-round btn-icon"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ action('AircraftController@prepareDestroy', ['id' => $aircraft->id]) }}" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <a href="{{ action('AircraftController@create') }}" class="btn btn-sm btn-primary pull-right">create Aircraft</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
