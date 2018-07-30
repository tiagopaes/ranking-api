<?php

namespace App\Http\Controllers;

use App\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Validator;

class RankingController extends Controller
{
    private $validationRules = [
        'name' => 'required|max:255|min:3'
    ];

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
        Validator::make($request->all(), $this->validationRules)->validate();
        try {
            $name = $request->get('name');
            $rankingAlreadyExists = Ranking::where('user_id', Auth::id())
                ->where('name', $name)
                ->count() > 0;

            if ($rankingAlreadyExists) {
                throw new Exception('The name has already been taken.');
            }
            Ranking::create([
                'name' => $name,
                'user_id' => Auth::id()
            ]);

            return redirect('home')->withSuccess('Ranking created!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking)
    {
        $ranking->players = $ranking->players()
            ->orderBy('score', 'desc')
            ->get();

        return view('ranking', [
            'ranking' => $ranking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit(Ranking  $ranking)
    {
        return view('ranking-edit', [
            'ranking' => $ranking
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
        Validator::make($request->all(), $this->validationRules)->validate();
        try {
            $name = $request->get('name');
            $rankingAlreadyExists = Ranking::where('user_id', Auth::id())
                ->where('name', $name)
                ->count() > 0;

            if ($rankingAlreadyExists) {
                throw new Exception('The name has already been taken.');
            }
            $ranking->name = $name;
            $ranking->save();
            return redirect('home')->withSuccess('Ranking updated!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking)
    {
        try {
            $ranking->players()->delete();
            $ranking->delete();
            return redirect('home')->withSuccess('Ranking deleted!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}
