@extends('layouts.app')

@section('content')
<form action="{{ action('SlotGeneratorController@storeStep1') }}" method="post">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card card-user">
                <div class="card-header">
                    <h4 class="card-title">Create Slots (Step 2/2)</h4>
                </div>
                <div class="card-body" style="overflow-x: scroll;">
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
                    <table class="table">
                        <tr>
                        @foreach ($table[0] as $col)
                            <th colspan="2">
                                {{ $col['aircraftObject']->callsign }} - {{ $col['aircraftObject']->aircraftType->designator }}
                            </th>
                        @endforeach
                        <tr>
                        @foreach ($table as $row)
                        <tr>
                            @foreach ($row as $col)
                            <td>
                                <div style="width: 90px; font-size: 8pt;">
                                {{ $col['flight_number'] }}<br />
                                {{ $col['starts_at']->setTimezone('Europe/Berlin')->format('H:i') }} - {{ $col['ends_at']->setTimezone('Europe/Berlin')->format('H:i') }}
                                </div>
                            </td>
                            <td style="border-right: 1px solid #dee2e6;">
                                <select name="pilot[{{ $col['flight_number'] }}]" class="form-control col_{{$col['aircraft']}}" style="width: 160px; font-size: 8pt; height: 30px;">
                                    @foreach ($pilots as $pilot)
                                        <option value="{{ $pilot->id }}" @if (old('pilot_id') == $pilot->id) selected @endif >{{ $pilot->firstname }} {{ $pilot->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
