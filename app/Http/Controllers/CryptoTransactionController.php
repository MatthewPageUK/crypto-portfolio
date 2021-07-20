<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Carbon\Carbon;
use Database\Seeders\CryptoTokenSeeder;
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
    public function edit(CryptoTransaction $transaction)
    {
        $tokens = CryptoToken::all()->sortBy('symbol');
        return view('edittransaction', ['transaction' => $transaction, 'tokens' => $tokens]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, CryptoTransaction $transaction)
    {
        $transaction->update($request->validated());
        return redirect()->route('token.show', $transaction->crypto_token_id)->with('success', 'Transaction updated');
    }

    /**
     * Remove the transaction resource from storage.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoTransaction $cryptoTransaction)
    {
        /**
         * Todo - formrequest and validate negative balance
         */
        $token_id = $cryptoTransaction->crypto_token_id;
        $cryptoTransaction->delete();

        return redirect()->route('token.show', ['token' => $token_id])->with('success', 'Transaction deleted');
    }
}
