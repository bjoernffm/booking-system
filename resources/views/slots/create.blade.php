@extends('layouts.app')

@section('content')
<form action="{{ action('SlotController@store') }}" method="post">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h4 class="card-title">Create Slot</h4>
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
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="starts_on">Start</label>
                                        <input type="datetime-local" name="starts_on" class="form-control" value="{{ old('starts_on') }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="ends_on">End</label>
                                        <input type="datetime-local" name="ends_on" class="form-control" value="{{ old('ends_on') }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>Aircraft</label>
                                        <select name="aircraft_id" class="form-control chosen-select">
                                            @foreach ($aircrafts as $aircraft)
                                                <option value="{{ $aircraft->id }}" @if (old('aircraft_id') == $aircraft->id) selected @endif >{{ $aircraft->callsign }} ({{ $aircraft->manufacturer }} {{ $aircraft->designator }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label>Pilot</label>
                                        <select name="pilot_id" class="form-control chosen-select">
                                            @foreach ($pilots as $pilot)
                                                <option value="{{ $pilot->id }}" @if (old('pilot_id') == $pilot->id) selected @endif >{{ $pilot->firstname }} {{ $pilot->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{ action('SlotController@index') }}" class="btn btn-default btn-round">Back</a>
                                    <button type="submit" class="btn btn-primary btn-round">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
