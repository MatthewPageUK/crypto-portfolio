<?php

namespace App\Http\Controllers;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Illuminate\Http\Request;

class CryptoTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy(CryptoToken $token)
    {
        dd($token);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sell(CryptoToken $token)
    {
        dd($token);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoTransaction $cryptoTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CryptoTransaction $cryptoTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CryptoTransaction $cryptoTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoTransaction $cryptoTransaction)
    {
        //
    }
}
