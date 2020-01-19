@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<div class="row">
    <div class="col-md-8">
        <div class="card card-user">
            <div class="card-header">
                <h4 class="card-title">Update User</h4>
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
                <form action="{{ action('UserController@update', ['user' => $user->id]) }}" method="post" id="bookingApp">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="firstname">Firstname</label>
                                        <input type="text" autocomplete="off" data-lpignore="true" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" data-lpignore="true" required />
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="lastname">Lastname</label>
                                        <input type="text" autocomplete="off" data-lpignore="true" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" data-lpignore="true" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="email">E-Mail</label>
                                        <input type="email" v-model="email" autocomplete="off" data-lpignore="true" name="email" class="form-control" data-lpignore="true" required />
                                        <small v-if="showEmailVerified" class="form-text text-success">
                                            verified <i class="fa fa-check"></i>
                                        </small>
                                        <small v-if="!showEmailVerified" class="form-text text-primary">
                                            User will get an email for verification
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <div class="row">
                                            <div class="col-md-4 pr-1">
                                                <select v-model="mobileCountry" class="form-control pr-1">
                                                    @foreach ($countryMap as $number => $countries)
                                                        <option value="+{{$number}}">+{{$number}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8 pl-1">
                                                <input type="text" v-model="mobile" autocomplete="off" data-lpignore="true" class="form-control pl-1" placeholder="0177 123 456" required />
                                            </div>
                                        </div>
                                        <small v-if="showMobileVerified" class="form-text text-success">
                                            verified <i class="fa fa-check"></i>
                                        </small>
                                        <small v-if="!showMobileVerified" class="form-text text-primary">
                                            User will get a sms for verification
                                        </small>
                                        <input type="hidden" v-model="mobileFinal" autocomplete="off" data-lpignore="true" name="mobile" class="form-control pl-1" placeholder="0177 123 456" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{ action('UserController@index') }}" class="btn btn-default btn-round">Back</a>
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-round"
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
                @foreach ($user->audits as $audit)
                    @if($audit->user != null)
                        <li title="{{ stripslashes(json_encode($audit->new_values)) }}">{{$audit->user->firstname}} {{$audit->user->lastname}} {{$audit->event}} this item at {{\Carbon\Carbon::parse($audit->created_at)->setTimezone('Europe/Berlin')->format('d.m.Y H:i')}}</li>
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
var app = new Vue({
    el: '#bookingApp',
    data: {
        email: "{{ old('email', $user->email) }}",
        emailOriginal: "{{ old('email', $user->email) }}",
        emailVerified: {{ ($user->hasVerifiedEmail()) ? "true" : "false" }},
        mobileCountry: "+{{ old('mobile_country', $user->parsedNumber->getCountryCode()) }}",
        mobileCountryOriginal: "+{{ old('mobile_country', $user->parsedNumber->getCountryCode()) }}",
        mobile: "{{ old('mobile', $user->parsedNumber->getNationalNumber()) }}",
        mobileOriginal: "{{ old('mobile', $user->parsedNumber->getNationalNumber()) }}",
        mobileVerified: {{ ($user->hasVerifiedMobile()) ? "true" : "false" }}
    },
    computed: {
        showEmailVerified: function () {
            if (this.emailVerified == true && this.email == this.emailOriginal) {
                return true;
            } else {
                return false;
            }
        },
        showMobileVerified: function () {
            if (this.mobileVerified == true && this.mobileCountry == this.mobileCountryOriginal && this.mobile == this.mobileOriginal) {
                return true;
            } else {
                return false;
            }
        },
        mobileFinal: function () {
            return this.mobileCountry + this.mobile;
        }
    }
});
</script>
@endsection
