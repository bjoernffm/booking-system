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
                                    <input type="text" autocomplete="off" data-lpignore="true" v-on:keyup="managePax" v-model="passenger.firstname" v-bind:name="'pax['+index+'][firstname]'" class="form-control" placeholder="John">
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Lastname</label>
                                    <input type="text" autocomplete="off" data-lpignore="true" v-on:keyup="managePax" v-model="passenger.lastname" v-bind:name="'pax['+index+'][lastname]'" class="form-control" placeholder="Doe">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Specialties</label>
                                    <div>
                                        <input type="checkbox" v-bind:disabled="passenger.firstname == '' && passenger.lastname == ''" v-model="passenger.child" v-bind:name="'pax['+index+'][discounted]'" value="yes">
                                        <span style="cursor: pointer;" v-if="passenger.firstname != '' || passenger.lastname != ''" v-on:click="passenger.child = !passenger.child">Discounted</span>
                                        <span v-if="passenger.firstname == '' && passenger.lastname == ''">Discounted</span>
                                    </div>
                                    <div>
                                        <input type="checkbox" v-bind:disabled="passenger.firstname == '' && passenger.lastname == ''" v-model="passenger.small_headset" v-bind:name="'pax['+index+'][small_headset]'" value="yes">
                                        <span style="cursor: pointer;" v-if="passenger.firstname != '' || passenger.lastname != ''" v-on:click="passenger.small_headset = !passenger.small_headset">Small Headset</span>
                                        <span v-if="passenger.firstname == '' && passenger.lastname == ''">Small Headset</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group" v-if="passenger.firstname != '' || passenger.lastname != ''">
                                    <button type="button" v-on:click="removePax(index)" style="margin: 27px 0 0 -20px;" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>Other</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-Mail-Address</label>
                                <input type="email" name="email" autocomplete="off" data-lpignore="true" class="form-control" placeholder="john@doe.com" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile Phone</label>
                                <div class="row">
                                    <div class="col-md-3 pr-1">
                                        <select name="mobile_country" class="form-control pr-1">
                                            <option value="US">+1</option>
                                            <option value="AG">+1</option>
                                            <option value="AI">+1</option>
                                            <option value="AS">+1</option>
                                            <option value="BB">+1</option>
                                            <option value="BM">+1</option>
                                            <option value="BS">+1</option>
                                            <option value="CA">+1</option>
                                            <option value="DM">+1</option>
                                            <option value="DO">+1</option>
                                            <option value="GD">+1</option>
                                            <option value="GU">+1</option>
                                            <option value="JM">+1</option>
                                            <option value="KN">+1</option>
                                            <option value="KY">+1</option>
                                            <option value="LC">+1</option>
                                            <option value="MP">+1</option>
                                            <option value="MS">+1</option>
                                            <option value="PR">+1</option>
                                            <option value="SX">+1</option>
                                            <option value="TC">+1</option>
                                            <option value="TT">+1</option>
                                            <option value="VC">+1</option>
                                            <option value="VG">+1</option>
                                            <option value="VI">+1</option>
                                            <option value="RU">+7</option>
                                            <option value="KZ">+7</option>
                                            <option value="EG">+20</option>
                                            <option value="ZA">+27</option>
                                            <option value="GR">+30</option>
                                            <option value="NL">+31</option>
                                            <option value="BE">+32</option>
                                            <option value="FR">+33</option>
                                            <option value="ES">+34</option>
                                            <option value="HU">+36</option>
                                            <option value="IT">+39</option>
                                            <option value="VA">+39</option>
                                            <option value="RO">+40</option>
                                            <option value="CH">+41</option>
                                            <option value="AT">+43</option>
                                            <option value="GB">+44</option>
                                            <option value="GG">+44</option>
                                            <option value="IM">+44</option>
                                            <option value="JE">+44</option>
                                            <option value="DK">+45</option>
                                            <option value="SE">+46</option>
                                            <option value="NO">+47</option>
                                            <option value="SJ">+47</option>
                                            <option value="PL">+48</option>
                                            <option value="DE" selected>+49</option>
                                            <option value="PE">+51</option>
                                            <option value="MX">+52</option>
                                            <option value="CU">+53</option>
                                            <option value="AR">+54</option>
                                            <option value="BR">+55</option>
                                            <option value="CL">+56</option>
                                            <option value="CO">+57</option>
                                            <option value="VE">+58</option>
                                            <option value="MY">+60</option>
                                            <option value="AU">+61</option>
                                            <option value="CC">+61</option>
                                            <option value="CX">+61</option>
                                            <option value="ID">+62</option>
                                            <option value="PH">+63</option>
                                            <option value="NZ">+64</option>
                                            <option value="SG">+65</option>
                                            <option value="TH">+66</option>
                                            <option value="JP">+81</option>
                                            <option value="KR">+82</option>
                                            <option value="VN">+84</option>
                                            <option value="CN">+86</option>
                                            <option value="TR">+90</option>
                                            <option value="IN">+91</option>
                                            <option value="PK">+92</option>
                                            <option value="AF">+93</option>
                                            <option value="LK">+94</option>
                                            <option value="MM">+95</option>
                                            <option value="IR">+98</option>
                                            <option value="SS">+211</option>
                                            <option value="MA">+212</option>
                                            <option value="EH">+212</option>
                                            <option value="DZ">+213</option>
                                            <option value="TN">+216</option>
                                            <option value="LY">+218</option>
                                            <option value="GM">+220</option>
                                            <option value="SN">+221</option>
                                            <option value="MR">+222</option>
                                            <option value="ML">+223</option>
                                            <option value="GN">+224</option>
                                            <option value="CI">+225</option>
                                            <option value="BF">+226</option>
                                            <option value="NE">+227</option>
                                            <option value="TG">+228</option>
                                            <option value="BJ">+229</option>
                                            <option value="MU">+230</option>
                                            <option value="LR">+231</option>
                                            <option value="SL">+232</option>
                                            <option value="GH">+233</option>
                                            <option value="NG">+234</option>
                                            <option value="TD">+235</option>
                                            <option value="CF">+236</option>
                                            <option value="CM">+237</option>
                                            <option value="CV">+238</option>
                                            <option value="ST">+239</option>
                                            <option value="GQ">+240</option>
                                            <option value="GA">+241</option>
                                            <option value="CG">+242</option>
                                            <option value="CD">+243</option>
                                            <option value="AO">+244</option>
                                            <option value="GW">+245</option>
                                            <option value="IO">+246</option>
                                            <option value="AC">+247</option>
                                            <option value="SC">+248</option>
                                            <option value="SD">+249</option>
                                            <option value="RW">+250</option>
                                            <option value="ET">+251</option>
                                            <option value="SO">+252</option>
                                            <option value="DJ">+253</option>
                                            <option value="KE">+254</option>
                                            <option value="TZ">+255</option>
                                            <option value="UG">+256</option>
                                            <option value="BI">+257</option>
                                            <option value="MZ">+258</option>
                                            <option value="ZM">+260</option>
                                            <option value="MG">+261</option>
                                            <option value="RE">+262</option>
                                            <option value="YT">+262</option>
                                            <option value="ZW">+263</option>
                                            <option value="NA">+264</option>
                                            <option value="MW">+265</option>
                                            <option value="LS">+266</option>
                                            <option value="BW">+267</option>
                                            <option value="SZ">+268</option>
                                            <option value="KM">+269</option>
                                            <option value="SH">+290</option>
                                            <option value="TA">+290</option>
                                            <option value="ER">+291</option>
                                            <option value="AW">+297</option>
                                            <option value="FO">+298</option>
                                            <option value="GL">+299</option>
                                            <option value="GI">+350</option>
                                            <option value="PT">+351</option>
                                            <option value="LU">+352</option>
                                            <option value="IE">+353</option>
                                            <option value="IS">+354</option>
                                            <option value="AL">+355</option>
                                            <option value="MT">+356</option>
                                            <option value="CY">+357</option>
                                            <option value="FI">+358</option>
                                            <option value="AX">+358</option>
                                            <option value="BG">+359</option>
                                            <option value="LT">+370</option>
                                            <option value="LV">+371</option>
                                            <option value="EE">+372</option>
                                            <option value="MD">+373</option>
                                            <option value="AM">+374</option>
                                            <option value="BY">+375</option>
                                            <option value="AD">+376</option>
                                            <option value="MC">+377</option>
                                            <option value="SM">+378</option>
                                            <option value="UA">+380</option>
                                            <option value="RS">+381</option>
                                            <option value="ME">+382</option>
                                            <option value="XK">+383</option>
                                            <option value="HR">+385</option>
                                            <option value="SI">+386</option>
                                            <option value="BA">+387</option>
                                            <option value="MK">+389</option>
                                            <option value="CZ">+420</option>
                                            <option value="SK">+421</option>
                                            <option value="LI">+423</option>
                                            <option value="FK">+500</option>
                                            <option value="BZ">+501</option>
                                            <option value="GT">+502</option>
                                            <option value="SV">+503</option>
                                            <option value="HN">+504</option>
                                            <option value="NI">+505</option>
                                            <option value="CR">+506</option>
                                            <option value="PA">+507</option>
                                            <option value="PM">+508</option>
                                            <option value="HT">+509</option>
                                            <option value="GP">+590</option>
                                            <option value="BL">+590</option>
                                            <option value="MF">+590</option>
                                            <option value="BO">+591</option>
                                            <option value="GY">+592</option>
                                            <option value="EC">+593</option>
                                            <option value="GF">+594</option>
                                            <option value="PY">+595</option>
                                            <option value="MQ">+596</option>
                                            <option value="SR">+597</option>
                                            <option value="UY">+598</option>
                                            <option value="CW">+599</option>
                                            <option value="BQ">+599</option>
                                            <option value="TL">+670</option>
                                            <option value="NF">+672</option>
                                            <option value="BN">+673</option>
                                            <option value="NR">+674</option>
                                            <option value="PG">+675</option>
                                            <option value="TO">+676</option>
                                            <option value="SB">+677</option>
                                            <option value="VU">+678</option>
                                            <option value="FJ">+679</option>
                                            <option value="PW">+680</option>
                                            <option value="WF">+681</option>
                                            <option value="CK">+682</option>
                                            <option value="NU">+683</option>
                                            <option value="WS">+685</option>
                                            <option value="KI">+686</option>
                                            <option value="NC">+687</option>
                                            <option value="TV">+688</option>
                                            <option value="PF">+689</option>
                                            <option value="TK">+690</option>
                                            <option value="FM">+691</option>
                                            <option value="MH">+692</option>
                                            <option value="KP">+850</option>
                                            <option value="HK">+852</option>
                                            <option value="MO">+853</option>
                                            <option value="KH">+855</option>
                                            <option value="LA">+856</option>
                                            <option value="BD">+880</option>
                                            <option value="TW">+886</option>
                                            <option value="MV">+960</option>
                                            <option value="LB">+961</option>
                                            <option value="JO">+962</option>
                                            <option value="SY">+963</option>
                                            <option value="IQ">+964</option>
                                            <option value="KW">+965</option>
                                            <option value="SA">+966</option>
                                            <option value="YE">+967</option>
                                            <option value="OM">+968</option>
                                            <option value="PS">+970</option>
                                            <option value="AE">+971</option>
                                            <option value="IL">+972</option>
                                            <option value="BH">+973</option>
                                            <option value="QA">+974</option>
                                            <option value="BT">+975</option>
                                            <option value="MN">+976</option>
                                            <option value="NP">+977</option>
                                            <option value="TJ">+992</option>
                                            <option value="TM">+993</option>
                                            <option value="AZ">+994</option>
                                            <option value="GE">+995</option>
                                            <option value="KG">+996</option>
                                            <option value="UZ">+998</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 pl-1">
                                        <input type="phone" autocomplete="off" data-lpignore="true" name="mobile" class="form-control  pl-1" placeholder="0177 123 456" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Internal Information</label>
                                <textarea name="internal_information" class="form-control textarea"></textarea>
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
var app = new Vue({
    el: '#bookingApp',
    data: {
        passengers: [{
            firstname: "",
            lastname: "",
            child: false,
            small_headset: false
        }]
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
                    child: false,
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
            let numberChildren = 0;
            let numberAdults = 0;
            let numberPax = 0;
            let numberSmallHeadsets = 0;

            for(let i = 0; i < this.passengers.length; i++) {
                if (this.passengers[i].firstname != "" || this.passengers[i].lastname != "") {
                    if (this.passengers[i].child == true) {
                        numberChildren++;
                    } else {
                        numberAdults++;
                    }

                    if (this.passengers[i].small_headset == true) {
                        numberSmallHeadsets++;
                    }

                    numberPax++;
                }
            }

            let paxLine = "";
            let adultLine = "";
            let childLine = "";

            if (numberPax === 0) {
                paxLine = "No Passengers";
            } else if (numberPax === 1) {
                paxLine = "1 Passenger";
            } else {
                paxLine = numberPax+" Passengers";
            }

            if (numberPax !== numberAdults) {
                if (numberAdults === 0) {
                    adultLine = "<span class=\"text-danger\">No Regular?</span>";
                } else if (numberAdults === 1) {
                    adultLine = "1 Regular";
                } else {
                    adultLine = numberAdults+" Regular";
                }
                if (numberChildren === 1) {
                    childLine = "1 Discounted";
                } else {
                    childLine = numberChildren+" Discounted";
                }

                paxLine += " ("+adultLine+", "+childLine+")"
            }

            if (numberSmallHeadsets === 0) {
                paxLine += "<br />No special Headsets needed";
            } else if (numberSmallHeadsets === 1) {
                paxLine += "<br /><span class=\"text-info\">1 small Headset needed</span>";
            } else {
                paxLine += "<br /><span class=\"text-info\">"+numberSmallHeadsets+" small Headsets needed</span>";
            }

            return {
                price: (numberAdults*{{ env('PRICE_ADULT') }})+(numberChildren*{{ env('PRICE_CHILD') }}),
                pax: paxLine
            };
        }
    }
});
</script>
@endsection