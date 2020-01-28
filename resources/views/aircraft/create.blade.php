@extends('layouts.app')

@section('content')
<div class="row" id="app">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header">
                <h4 class="card-title">Create Aircraft</h4>
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
                <form action="{{ action('AircraftController@store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-3 pr-1">
                                    <div class="form-group">
                                        <label>Callsign</label>
                                        <input type="text" name="callsign" class="form-control uppercaseInput" value="{{ old('callsign') }}" placeholder="e.g. DEBBI" />
                                    </div>
                                </div>
                                <div class="col-md-5 px-1">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="type" class="form-control chosen-select">
                                            @foreach ($aircraft_types as $type)
                                                <option value="{{ $type->id }}" @if (old('type') == $type->id) selected @endif >{{ $type->designator }} ({{ $type->manufacturer }} {{ $type->model }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-1">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">max. Pax load (kg)</label>
                                        <input type="number" name="load" class="form-control" value="{{ old('load') }}" placeholder="e.g. 200">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{ action('AircraftController@index') }}" class="btn btn-default btn-round">Back</a>
                                    <button type="submit" class="btn btn-primary btn-round">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
