@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1> New Ranking </h1>
            <form method="POST" action="{{ route('ranking.store') }}">
                @csrf
                <div class="form-group">
                <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" placeholder="Enter name" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
