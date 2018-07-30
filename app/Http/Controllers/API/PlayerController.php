<?php

namespace App\Http\Controllers\API;

use App\Ranking;
use App\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Ranking $ranking)
    {
        return $ranking->players()
            ->orderBy('score', 'desc')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ranking $ranking)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|min:3',
            'score' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $name = $request->get('name');
        $playerAlreadyExists = $ranking->players()
            ->where('name', $name)
            ->count() > 0;
        
        if ($playerAlreadyExists) {
            return response()->json([
                'error' => 'The name has already been taken.'
            ], 400);
        }

        try {
            return $ranking->players()->create([
                'name' => $name,
                'score' => $request->get('score', 0)
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking, Player $player)
    {
        return $player;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ranking $ranking, Player $player)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|min:3',
            'score' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->has('name')) {
            $playerAlreadyExists = $ranking->players()
                ->where('name', $request->get('name'))
                ->count() > 0;
            
            if ($playerAlreadyExists) {
                return response()->json([
                    'error' => 'The name has already been taken.'
                ], 400);
            }
        }

        try {
            $player->fill($request->all());
            $player->save();
            return ['message' => 'Player updated!'];
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking, Player $player)
    {
        try {
            $player->delete();
            return ['message' => 'Player deleted!'];
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
    }
}
