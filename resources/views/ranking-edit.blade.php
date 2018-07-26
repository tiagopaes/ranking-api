@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1> Edit Ranking </h1>

            @include('layouts.alerts')
            
            <form action="{{ route('ranking.update', $ranking->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" placeholder="Enter name" required value="{{$ranking->name}}">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
