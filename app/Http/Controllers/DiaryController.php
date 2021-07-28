<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DiaryController extends Controller
{
    /**
     * Display the diary page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::all()->sortBy('time');
        
        return view('diary')
            ->with('transactions', $transactions);
    }
}
