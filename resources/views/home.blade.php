@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Rankings</h1>

            @include('layouts.alerts')
            
            <div class="text-right">
                <a href="{{ route('ranking.create') }}" class="btn btn-primary">
                    New Ranking
                </a>
            </div>

            <br>
            <br>
            
            @if (count($rankings) == 0)
                <p> There is no rankings yet! </p>
            @else

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">#</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankings as $ranking)
                            <tr>
                                <td>
                                    <a href="{{ route('ranking.show', $ranking->id) }}" class="btn btn-link">
                                        {{ $ranking->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('ranking.edit', $ranking->id) }}" class="btn btn-outline-primary">
                                        Edit
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('ranking.destroy', $ranking->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
