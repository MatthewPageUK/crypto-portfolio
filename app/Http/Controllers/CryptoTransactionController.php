<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\CryptoToken;
use App\Models\CryptoTransaction;

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
        return view('addtransaction')
            ->with('token', $token)
            ->with('transType', CryptoTransaction::BUY);
    }
    /**
     * Show the form for creating a new sell transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function sell(CryptoToken $token)
    {
        return view('addtransaction')
            ->with('token', $token)
            ->with('transType', CryptoTransaction::SELL);
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

        return redirect()
            ->route('token.show', ['token' => $request['crypto_token_id']])
            ->with('success', 'Transaction created');
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

        return view('edittransaction')
            ->with('transaction', $transaction)
            ->with('tokens', $tokens);
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

        // replace old trans with new one in collection then validate

        // $filtered = $token->transactions->filter(function ($value, $key) use ($transaction) {
        //     return $value->id !== $transaction->id;
        // });

        // if( $filtered->validateTransactions() )
        // {
        //     $transaction->delete();
        //     return redirect()->route('token.show', ['token' => $token->id])->with('success', 'Transaction deleted');
        // }
        // else
        // {
        //     return redirect()->route('token.show', ['token' => $token->id])->with('failure', 'Transaction could not be deleted, negative balance error.');
        // }



        $transaction->update($request->validated());

        return redirect()
            ->route('token.show', $transaction->crypto_token_id)
            ->with('success', 'Transaction updated');
    }

    /**
     * Remove the transaction resource from storage.
     * 
     * Validates the transactions to ensure there are no negative balances
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoTransaction $transaction)
    {
        $token = CryptoToken::find($transaction->crypto_token_id);

        $filtered = $token->transactions->filter(function ($value, $key) use ($transaction) {
            return $value->id !== $transaction->id;
        });

        if( $filtered->validateTransactions() )
        {
            $transaction->delete();
            return redirect()
                ->route('token.show', ['token' => $token->id])
                ->with('success', 'Transaction deleted');
        }
        else
        {
            return redirect()
                ->route('token.show', ['token' => $token->id])
                ->with('failure', 'Transaction could not be deleted, negative balance error.');
        }

    }
}
