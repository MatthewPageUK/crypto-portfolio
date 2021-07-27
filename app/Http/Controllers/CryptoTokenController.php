<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\UpdateTokenRequest;
use App\Models\CryptoToken;

class CryptoTokenController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('token-add');
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

        return redirect()
            ->route('dashboard')
            ->with('success', __('New token created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CryptoToken  $token
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoToken $token)
    {
        return view('token')
            ->with('token', $token);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function edit(CryptoToken $token)
    {
        return view('token-edit')
            ->with('token', $token);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTokenRequest $request, CryptoToken $token)
    {
        $token->update($request->validated());

        return redirect()
            ->route('token.show', $token)
            ->with('success', __('Token updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CryptoToken  $cryptoToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoToken $token)
    {
        $token->transactions()->delete();
        $token->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', __('Token deleted'));
    }
}
