<?php

namespace App\Http\Controllers;

use App\Models\CryptoToken;

class DashboardController extends Controller
{
    /**
     * Display the dashboard homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokens = CryptoToken::all()->sortBy('symbol');
        
        return view('dashboard')
            ->with('tokens', $tokens);
    }
}
