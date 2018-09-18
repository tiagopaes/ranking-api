<?php

namespace App\Http\Controllers;

use App\Ranking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('Showing home page for user: ' . Auth::id());

        return view('home', [
            'rankings' => Ranking::where('user_id', Auth::id())->get()
        ]);
    }
}
