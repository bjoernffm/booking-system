@extends('layouts.app')

@section('content')
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
                <form action="{{ action('UserController@update', ['user' => $user->id]) }}" method="post">
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
                                        <input type="email" autocomplete="off" data-lpignore="true" name="email" class="form-control" value="{{ old('email', $user->email) }}" data-lpignore="true" required />
                                        @if ($user->email_verified_at != null)
                                        <small class="form-text text-success">
                                            verified <i class="fa fa-check"></i>
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <div class="row">
                                            <div class="col-md-4 pr-1">
                                                <select name="mobile_country" class="form-control pr-1">
                                                    @foreach ($countryMap as $number => $countries)
                                                        @foreach ($countries as $country)
                                                            <option value="{{$country}}" @if(old('mobile_country', $user->parsedNumber->getCountryCode()) == $number) selected @endif>+{{$number}} ({{$country}})</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8 pl-1">
                                                <input type="phone" autocomplete="off" data-lpignore="true" name="mobile" class="form-control pl-1" placeholder="0177 123 456" value="{{ old('mobile', $user->parsedNumber->getNationalNumber()) }}" required />
                                            </div>
                                        </div>
                                        @if ($user->mobile_verified_at != null)
                                        <small class="form-text text-success">
                                            verified <i class="fa fa-check"></i>
                                        </small>
                                        @endif
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
@endsection
