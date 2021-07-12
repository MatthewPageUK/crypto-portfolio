<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTokenRequest;
use App\Models\CryptoToken;
use Illuminate\Http\Request;

class CryptoTokenController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addtoken');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTokenRequest $request)
    {
        $validatedData = $request->validated();

        CryptoToken::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Token created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CryptoToken  $token
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoToken $token)
    {
        return view('token', ['token' => $token]);
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
