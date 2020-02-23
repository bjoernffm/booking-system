@extends('layouts.app')

@section('content')
<form action="{{ action('SlotGeneratorController@storeStep1') }}" method="post">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h4 class="card-title">Create Slots (Step 1/2)</h4>
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
                                <div class="col-md-4 pr-1">
                                    <div class="form-group">
                                        <label for="slot_duration">Slot duration (min)</label>
                                        <input type="number" name="slot_duration" class="form-control" placeholder="20" value="{{ old('slot_duration') }}" min="1" />
                                    </div>
                                </div>
                                <div class="col-md-4 pr-1">
                                    <div class="form-group">
                                        <label for="idle_time">Idle time (min)</label>
                                        <input type="number" name="idle_time" class="form-control" placeholder="25" value="{{ old('idle_time') }}" min="0" />
                                    </div>
                                </div>
                                <div class="col-md-4 pl-1">
                                    <div class="form-group">
                                        <label for="offset">Offset (min)</label>
                                        <input type="number" name="offset" class="form-control" placeholder="5" value="{{ old('offset') }}" min="0" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Aircraft</label>
                                        <select name="aircrafts[]" class="form-control" multiple>
                                            @foreach ($aircrafts as $aircraft)
                                                <option value="{{ $aircraft->id }}" @if (in_array($aircraft->id, old('aircrafts', []))) selected @endif >{{ $aircraft->callsign }} ({{ $aircraft->aircraftType->manufacturer }} {{ $aircraft->aircraftType->designator }})</option>
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
