@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header">
                <h4 class="card-title">Overview</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>Name</th>
                            <th>E-Mail</th>
                            <th>Mobile</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ action('UserController@edit', ['user' => $user->id]) }}">{{ $user->firstname }} {{ $user->lastname }}</a>
                                </td>
                                <td>
                                    @if ($user->email_verified_at != null)
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        <i class="fa fa-check"></i>
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </td>
                                <td>
                                    @if ($user->mobile_verified_at != null)
                                        <a href="tel:{{ $user->mobile }}">{{ $user->mobile_formatted }}</a>
                                        <i class="fa fa-check"></i>
                                    @else
                                        {{ $user->mobile_formatted }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ action('UserController@edit', ['user' => $user->id]) }}" class="btn btn-sm btn-outline-primary btn-round btn-icon"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger btn-round btn-icon"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
