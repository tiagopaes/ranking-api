<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ranking;
use App\Player;
use Exception;
use Validator;

class PlayerController extends Controller
{
    private $validationRules = [
        'name' => 'required|max:255'
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
    public function create(Ranking $ranking)
    {
        return view('player-create', [
            'ranking' => $ranking
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ranking $ranking)
    {
        Validator::make($request->all(), $this->validationRules)->validate();
        try {
            $name = $request->get('name');
            $players = Player::where('ranking_id', $ranking->id)
                ->where('name', $name)
                ->count();
            
            if ($players) {
                throw new Exception('The name has already been taken.');
            }

            $ranking->players()->create([
                'name' => $name,
                'score' => 0
            ]);

            return redirect(route('ranking.show', $ranking->id))
                ->withSuccess('Player created!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
