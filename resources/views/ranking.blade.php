@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1> {{ $ranking->name }} </h1>

            <div class="text-right">
                <a href="{{ route('ranking.player.create', $ranking->id) }}" class="btn btn-primary">
                    New Player
                </a>
            </div>

            <br>
            <br>
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Player</th>
                        <th scope="col">Score</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($ranking->players as $index => $player)
                        <tr>
                            <th scope="row"> {{ ($index + 1) }}</th>
                            <td> {{ $player->name }} </td>
                            <td> {{ $player->score }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
