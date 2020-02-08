@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-user">
            <div class="card-header">
                <h4 class="card-title">Update Slot</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ action('SlotController@update', ['slot' => $slot->id]) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="starts_on">Start</label>
                                        <input type="datetime-local" name="starts_on" class="form-control" value="{{ old('starts_on', \Carbon\Carbon::parse($slot->starts_on)->setTimezone('Europe/Berlin')->format('Y-m-d\TH:i')) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="ends_on">End</label>
                                        <input type="datetime-local" name="ends_on" class="form-control" value="{{ old('ends_on', \Carbon\Carbon::parse($slot->ends_on)->setTimezone('Europe/Berlin')->format('Y-m-d\TH:i')) }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Aircraft</label>
                                        <select name="aircraft_id" class="form-control chosen-select">
                                            @foreach ($aircrafts as $aircraft)
                                                <option value="{{ $aircraft->id }}" @if (old('aircraft_id', $slot->aircraft_id) == $aircraft->id) selected @endif >{{ $aircraft->callsign }} {{ $aircraft->designator }} ({{ $aircraft->manufacturer }} {{ $aircraft->model }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>Flight number</label>
                                        <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number', $slot->flight_number) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label>Pilot</label>
                                        <select name="pilot_id" class="form-control chosen-select">
                                            @foreach ($pilots as $pilot)
                                                <option value="{{ $pilot->id }}" @if (old('aircraft_id', $slot->pilot_id) == $pilot->id) selected @endif >{{ $pilot->firstname }} {{ $pilot->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{ action('SlotController@index') }}" class="btn btn-default btn-round">Back</a>
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-round"
                                        @if ($slot->booking != null)
                                            disabled
                                            title="Slot is already booked"
                                        @endif
                                        >Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-user">
            <div class="card-header">
                <h4 class="card-title">History</h4>
            </div>
            <div class="card-body">
                <ul>
                @foreach ($slot->audits as $audit)
                    <li title="{{ stripslashes(json_encode($audit->new_values)) }}">Someone {{$audit->event}} this item at {{\Carbon\Carbon::parse($audit->created_at)->setTimezone('Europe/Berlin')->format('d.m.Y H:i')}}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
