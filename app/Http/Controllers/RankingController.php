<?php

namespace App\Http\Controllers;

use App\Ranking;
use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ranking-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Ranking::create([
            'name' => $request->get('name'),
            'user_id' => Auth::id()
        ]);
        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking)
    {
        $ranking->players = Player::where('ranking_id', $ranking->id)
            ->orderBy('score', 'desc')
            ->get();
        return view('ranking', [
            'ranking' => $ranking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('ranking-edit', [
            'ranking' => Ranking::where('id', $id)
                ->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ranking $ranking)
    {
        $ranking->name = $request->get('name');
        $ranking->save();
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking)
    {
        Ranking::destroy($ranking->id);
        return redirect('home');
    }
}
