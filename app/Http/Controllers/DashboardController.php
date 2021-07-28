<?php

namespace App\Http\Controllers;

use App\Models\Token;

class DashboardController extends Controller
{
    /**
     * Display the dashboard homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokens = Token::all()->sortBy('symbol');
        
        return view('dashboard')
            ->with('tokens', $tokens);
    }
}
