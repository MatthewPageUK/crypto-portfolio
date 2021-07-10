<?php

namespace App\Http\Controllers;

use App\Models\CryptoToken;
use Illuminate\Http\Request;

class CryptoTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokens = CryptoToken::all();
        return view('portfolio', ['tokens' => $tokens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('Hello');
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
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoToken $cryptoToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function edit(CryptoToken $cryptoToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CryptoToken $cryptoToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoToken $cryptoToken)
    {
        //
    }
}
