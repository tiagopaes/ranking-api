<?php

namespace App\Http\Controllers\API;

use App\Ranking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Validator;

class RankingController extends Controller
{
    private $validationRules = [
        'name' => 'required|unique:rankings|max:255|min:3'
    ];

    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->rankings()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            return Ranking::create([
                'name' => $request->get('name'),
                'user_id' => $request->user()->id
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
    public function show(Ranking $ranking)
    {
        return $ranking;
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
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $ranking->name = $request->get('name');
            $ranking->save();
            return ['message' => 'Ranking updated!'];
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
    public function destroy(Ranking $ranking)
    {
        try {
            $ranking->players()->delete();
            $ranking->delete();
            return ['message' => 'Ranking deleted!'];
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
    }
}
