<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Carbon\Carbon;
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
     * Show the form for creating a new buy transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy(CryptoToken $token)
    {
        return view('addtransaction', ['token' => $token, 'transType' => 'buy']);
    }
    /**
     * Show the form for creating a new sell transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function sell(CryptoToken $token)
    {
        return view('addtransaction', ['token' => $token, 'transType' => 'sell']);
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        // If selling then don't allow more than current balance [TO-DO]
        // $token = CryptoToken::find($request->crypto_token_id);
        // $lessThanBalance = ($request->type==='sell') ? 'lte:'.$token->balance : '';
        // 'quantity' => ['required', 'gt:0', $lessThanBalance],

        $validatedData = $request->validated();

        CryptoTransaction::create($request->all());

        return redirect()->route('token.show', ['token' => $request['crypto_token_id']])->with('success', 'Transaction added');
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
     * Remove the transaction resource from storage.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoTransaction $cryptoTransaction)
    {
        $token_id = $cryptoTransaction->crypto_token_id;
        $cryptoTransaction->delete();

        return redirect()->route('token.show', ['token' => $token_id])->with('success', 'Transaction deleted');
    }
}
