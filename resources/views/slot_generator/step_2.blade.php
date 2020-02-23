@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://momentjs.com/downloads/moment.js"></script>
<form action="{{ action('SlotGeneratorController@storeStep2') }}" method="post" id="bookingApp">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card card-user">
                <div class="card-header">
                    <h4 class="card-title pull-left">Create Slots (Step 2/2)</h4>
                    <input type="submit" value="Create Slots" class="btn btn-success pull-right">
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
                            <th v-for="(aircraft, index) in aircrafts">
                                @{{ aircraft.name }}
                            </th>
                        </tr>
                        <tr>
                            <td v-for="(col, colIndex) in table" style="vertical-align: top;">
                                <table v-for="(slot, slotIndex) in col.slots" style="border: 0; padding: 0; margin: 0;">
                                <tr style="border: 0;">
                                <td style="border: 0; padding: 0 0 13px 0; margin: 0;">
                                    <div style="width: 90px; font-size: 8pt;">
                                        @{{ slot.flight_number }}<br />
                                        @{{ formatDateTime(slot.starts_at, "HH:mm") }} - @{{ formatDateTime(slot.ends_at, "HH:mm") }}
                                        <input type="hidden" v-bind:name="'slots['+slot.flight_number+'][flight_number]'" v-bind:value="slot.flight_number" />
                                        <input type="hidden" v-bind:name="'slots['+slot.flight_number+'][starts_at]'" v-bind:value="slot.starts_at" />
                                        <input type="hidden" v-bind:name="'slots['+slot.flight_number+'][ends_at]'" v-bind:value="slot.ends_at" />
                                        <input type="hidden" v-bind:name="'slots['+slot.flight_number+'][aircraft_id]'" v-bind:value="col.aircraft.id" />
                                    </div>
                                </td>
                                <td style="border: 0; padding: 0 13px 13px 0; margin: 0;">
                                    <select v-bind:name="'slots['+slot.flight_number+'][pilot_id]'" class="form-control" v-on:change="assignPilot(colIndex, slotIndex)" v-model="slot.pilot_id" style="width: 160px; font-size: 8pt; height: 30px;">
                                        <option value="" disabled selected hidden>Select Pilot</option>
                                        <option value="suspend">--- Suspend Slot ---</option>
                                        @foreach ($pilots as $pilot)
                                            <option value="{{ $pilot->id }}" @if (old('pilot_id') == $pilot->id) selected @endif >{{ $pilot->firstname }} {{ $pilot->lastname }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script>

let table = {!! json_encode($table) !!};

let app = new Vue({
    el: '#bookingApp',
    data: {
        table
    },
    methods: {
        assignPilot: function(colIndex, slotIndex) {
            let val = this.table[colIndex].slots[slotIndex].pilot_id;

            for(let i = slotIndex+1; i < this.table[colIndex].slots.length; i++) {
                this.table[colIndex].slots[i].pilot_id = val;
            }
        },
        formatDateTime: function(date, format) {
            return moment(date).format(format);
        }
    },
    computed: {
        aircrafts: function () {
            let list = [];

            for(let i = 0; i < this.table.length; i++) {
                list.push(this.table[i].aircraft);
            }

            return list;
        }
    }
});
</script>
@endsection
