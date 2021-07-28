<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\CryptoToken;
use App\Models\CryptoTransaction;

class CryptoTransactionController extends Controller
{
    /**
     * Show the form for creating a new buy transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy(CryptoToken $token)
    {
        return view('transaction-add')
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
        return view('transaction-add')
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
        CryptoTransaction::create( $request->validated() );

        return redirect()
            ->route('token.show', ['token' => $request['crypto_token_id']])
            ->with('success', 'Transaction created');
    }

    /**
     * Display the specified transaction.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoTransaction $transaction)
    {
        return view('transaction')
            ->with('transaction', $transaction);
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CryptoTransaction $transaction)
    {
        $tokens = CryptoToken::all()->sortBy('symbol');

        return view('transaction-edit')
            ->with('transaction', $transaction)
            ->with('tokens', $tokens);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, CryptoTransaction $transaction)
    {
        $transaction->update( $request->validated() );

        return redirect()
            ->route('token.show', $transaction->crypto_token_id)
            ->with('success', 'Transaction updated');
    }

    /**
     * Validates the transactions to ensure there are no negative 
     * balance errors and removes the transaction from storage.
     *
     * @param  \App\Models\CryptoTransaction  $cryptoTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoTransaction $transaction)
    {
        $token = CryptoToken::find($transaction->crypto_token_id);
        /**
         * Remove this transaction from the list
         */
        $filtered = $token->transactions->where('id', '!=', $transaction->id);

        /**
         * Validate the transactions list
         */
        if( $filtered->isValid() )
        {
            $transaction->delete();

            /**
             * Check redirect back does not go to the transaction show page
             */
            if( redirect()->back()->getTargetUrl() === route('transaction.show', $transaction) )
            {
                return redirect()
                    ->route('token.show', $token)
                    ->with('success', 'Transaction deleted');
            }
            else
            {
                return redirect()
                    ->back()
                    ->with('success', 'Transaction deleted');
            }
        }
        else
        {
            /**
             * Redirect to where we came from with an error message
             */
            return redirect()
                ->back()
                ->with('failure', 'Transaction could not be deleted, negative balance error.');
        }

    }
}
