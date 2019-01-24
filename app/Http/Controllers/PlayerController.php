<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ranking;
use Exception;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
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
    public function create(Ranking $ranking)
    {
        Log::info('Showing create player form for user: ' . Auth::id());

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
            $playerAlreadyExists = $ranking->players()
                ->where('name', $name)
                ->count() > 0;

            if ($playerAlreadyExists) {
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
}
