@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<form action="{{ action('BookingController@store') }}" method="post" id="bookingApp">
    @csrf
    <input type="hidden" name="slot_id" value="{{ $slot->id }}">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Details</h5>
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
                    <h6>PAX</h6>
                    <div>
                        <div class="row" v-for="(passenger, index) in passengers">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Firstname</label>
                                    <input type="text" autocomplete="off" data-lpignore="true" v-on:keyup="managePax" v-bind:required="passenger.firstname != '' || passenger.lastname != ''" v-model="passenger.firstname" v-bind:name="'pax['+index+'][firstname]'" tabindex="0" class="form-control" placeholder="John" />
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Lastname</label>
                                    <input type="text" autocomplete="off" data-lpignore="true" v-on:keyup="managePax" v-bind:required="passenger.firstname != '' || passenger.lastname != ''" v-model="passenger.lastname" v-bind:name="'pax['+index+'][lastname]'" tabindex="0" class="form-control" placeholder="Doe" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Specialties</label>
                                    <div>
                                        <input type="checkbox" tabindex="-1" v-bind:disabled="passenger.firstname == '' && passenger.lastname == ''" v-model="passenger.discounted" v-bind:name="'pax['+index+'][discounted]'" value="yes">
                                        <span style="cursor: pointer;" v-if="passenger.firstname != '' || passenger.lastname != ''" v-on:click="passenger.discounted = !passenger.discounted">Discounted</span>
                                        <span v-if="passenger.firstname == '' && passenger.lastname == ''">Discounted</span>
                                    </div>
                                    <div>
                                        <input type="checkbox" tabindex="-1" v-bind:disabled="passenger.firstname == '' && passenger.lastname == ''" v-model="passenger.small_headset" v-bind:name="'pax['+index+'][small_headset]'" value="yes">
                                        <span style="cursor: pointer;" v-if="passenger.firstname != '' || passenger.lastname != ''" v-on:click="passenger.small_headset = !passenger.small_headset">Small Headset</span>
                                        <span v-if="passenger.firstname == '' && passenger.lastname == ''">Small Headset</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group" v-if="passenger.firstname != '' || passenger.lastname != ''">
                                    <button type="button" tabindex="-1" v-on:click="removePax(index)" style="margin: 27px 0 0 -20px;" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>Other</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-Mail-Address</label>
                                <input type="email" name="email" autocomplete="off" data-lpignore="true" class="form-control" value="{{ old('email') }}" placeholder="john@doe.com" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile Phone</label>
                                <div class="row">
                                    <div class="col-md-3 pr-1">
                                        <select name="mobile_country" class="form-control pr-1">
                                            @foreach ($countryMap as $number => $countries)
                                                <option value="+{{$number}}" @if(old('mobile_country', '+49') == '+'.$number) selected @endif>+{{$number}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-9 pl-1">
                                        <input type="phone" value="{{ old('mobile') }}" autocomplete="off" data-lpignore="true" name="mobile" class="form-control  pl-1" placeholder="0177 123 456" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Internal Information</label>
                                <textarea name="internal_information" class="form-control textarea">{{ old('internal_information') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                     Max. load <b>{{ $slot->aircraft_load }} KG</b>
                    </div>
                    <dl>
                        <dt>SLOT</dt>
                        <dd style="padding-left: 20px;">
                            {{ $slot->aircraft_callsign }} / {{ $slot->aircraft_designator }} / {{ $slot->pilot_firstname }} {{ $slot->pilot_lastname }}<br />
                            {{ \Carbon\Carbon::parse($slot->starts_on)->format('d.m.Y') }} {{ \Carbon\Carbon::parse($slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl
                        </dd>
                        <dt>PAX</dt>
                        <dd style="padding-left: 20px;" v-html="summary.pax"></dd>
                        <dt>Price</dt>
                        <dd style="padding-left: 20px;"><h4 style="margin: 0;">@{{ summary.price }},- &euro;</h4></dd>
                    </dl>
                    <hr />
                    <button type="submit" id="createBookingButton" class="btn btn-primary btn-round btn-block" v-bind:disabled="summary.price == 0">Create Booking</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>

let oldPax = {!! json_encode(old('pax')) !!};
let passengerData = [{
    firstname: "",
    lastname: "",
    discounted: false,
    small_headset: false
}];

if (oldPax !== null) {
    for(let i = 0; i < oldPax.length; i++) {
        if (typeof oldPax[i].discounted === "undefined") {
            oldPax[i].discounted = false;
        } else {
            oldPax[i].discounted = true;
        }

        if (typeof oldPax[i].small_headset === "undefined") {
            oldPax[i].small_headset = false;
        } else {
            oldPax[i].small_headset = true;
        }

        if (oldPax[i].firstname === null) {
            oldPax[i].firstname = "";
        }

        if (oldPax[i].lastname === null) {
            oldPax[i].lastname = "";
        }
    }
    passengerData = oldPax;
}

let app = new Vue({
    el: '#bookingApp',
    data: {
        passengers: passengerData
    },
    methods: {
        managePax: function () {
            if(this.passengers.length > 1) {
                this.passengers = this.passengers.filter(function (el) {
                    return (el.firstname != "" || el.lastname != "");
                });
            }

            if (this.passengers.length == 0 || this.passengers[this.passengers.length-1].firstname != "" || this.passengers[this.passengers.length-1].lastname != "") {
                this.passengers.push({
                    firstname: "",
                    lastname: "",
                    discounted: false,
                    small_headset: false
                });
            }
        },
        removePax: function (index) {
            this.passengers.splice(index, 1);
        }
    },
    computed: {
        summary: function () {
            let numberDiscounted = 0;
            let numberRegular = 0;
            let numberPax = 0;
            let numberSmallHeadsets = 0;

            for(let i = 0; i < this.passengers.length; i++) {
                if (this.passengers[i].firstname != "" || this.passengers[i].lastname != "") {
                    if (this.passengers[i].discounted == true) {
                        numberDiscounted++;
                    } else {
                        numberRegular++;
                    }

                    if (this.passengers[i].small_headset == true) {
                        numberSmallHeadsets++;
                    }

                    numberPax++;
                }
            }

            let paxLine = "";
            let regularLine = "";
            let discountedLine = "";

            if (numberPax === 0) {
                paxLine = "No Passengers";
            } else if (numberPax === 1) {
                paxLine = "1 Passenger";
            } else {
                paxLine = numberPax+" Passengers";
            }

            if (numberPax !== numberRegular) {
                if (numberRegular === 0) {
                    regularLine = "<span class=\"text-danger\">No Regular?</span>";
                } else if (numberRegular === 1) {
                    regularLine = "1 Regular";
                } else {
                    regularLine = numberRegular+" Regular";
                }
                if (numberDiscounted === 1) {
                    discountedLine = "1 Discounted";
                } else {
                    discountedLine = numberDiscounted+" Discounted";
                }

                paxLine += " ("+regularLine+", "+discountedLine+")"
            }

            if (numberSmallHeadsets === 0) {
                paxLine += "<br />No special Headsets needed";
            } else if (numberSmallHeadsets === 1) {
                paxLine += "<br /><span class=\"text-info\">1 small Headset needed</span>";
            } else {
                paxLine += "<br /><span class=\"text-info\">"+numberSmallHeadsets+" small Headsets needed</span>";
            }

            return {
                price: (numberRegular*{{ env('PRICE_ADULT') }})+(numberDiscounted*{{ env('PRICE_CHILD') }}),
                pax: paxLine
            };
        }
    }
});
</script>
@endsection