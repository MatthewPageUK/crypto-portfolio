<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Token;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Show the form for creating a new buy transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy(Token $token)
    {
        return view('transaction-add')
            ->with('token', $token)
            ->with('transType', Transaction::BUY);
    }
    /**
     * Show the form for creating a new sell transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function sell(Token $token)
    {
        return view('transaction-add')
            ->with('token', $token)
            ->with('transType', Transaction::SELL);
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        Transaction::create( $request->validated() );

        return redirect()
            ->route('token.show', ['token' => $request['token_id']])
            ->with('success', 'Transaction created');
    }

    /**
     * Display the specified transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('transaction')
            ->with('transaction', $transaction);
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $tokens = Token::all()->sortBy('symbol');

        return view('transaction-edit')
            ->with('transaction', $transaction)
            ->with('tokens', $tokens);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update( $request->validated() );

        return redirect()
            ->route('token.show', $transaction->token_id)
            ->with('success', 'Transaction updated');
    }

    /**
     * Validates the transactions to ensure there are no negative
     * balance errors and removes the transaction from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $token = Token::find($transaction->token_id);
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
