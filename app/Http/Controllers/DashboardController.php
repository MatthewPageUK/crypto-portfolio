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
        $tokens = CryptoToken::all();
        return view('dashboard', ['tokens' => $tokens]);
    }
}
