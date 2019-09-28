@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="card card-user">
            <div class="card-header">
                <h5 class="card-title text-center">{{ $text }}</h5>
            </div>
            <div class="card-body text-center" style="min-height: 10px;">
                <a href="{{ $back_link }}" class="btn btn-secondary btn-round">Back</a>
                <form action="{{ $delete_link }}" method="post" style="display: inline;">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-round">Yes, delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection