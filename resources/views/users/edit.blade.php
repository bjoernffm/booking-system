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
                <form action="{{ action('UserController@update', ['user' => $user->id]) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="firstname">Firstname</label>
                                        <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="lastname">Lastname</label>
                                        <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="email">E-Mail</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required />
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
                                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" required />
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
                    <li title="{{ stripslashes(json_encode($audit->new_values)) }}">Someone {{$audit->event}} this item at {{\Carbon\Carbon::parse($audit->created_at)->setTimezone('Europe/Berlin')->format('d.m.Y H:i')}}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
