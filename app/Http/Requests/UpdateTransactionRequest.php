<?php

namespace App\Http\Requests;

use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidTransactionsRule;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $isValid = true;
        $transaction = $this->route('transaction');
        $token = Token::find($transaction->token_id);
        
        /**
         * Filter out the current transaction
         */
        $filtered = $token->transactions->where('id', '!=', $transaction->id);

        /**
         * Selected a different token so we need to push this transaction to that 
         * list.
         */
        if( $this->input('token_id') !== $token->id )
        {
            $newToken = Token::find( $this->input('token_id') );
            $newToken->transactions->push(new Transaction([
                'token_id' => $newToken->id, 
                'quantity' => $this->input('quantity'),
                'price' => $this->input('price'),
                'type' => $this->input('type'),
                'time' => $this->input('time'),
            ])); 

            $isValid = $newToken->transactions->isValid();
        }
        else
        {
            /**
             * Push updated transaction
             */
            $filtered->push(new Transaction([
                'token_id' => $token->id, 
                'quantity' => $this->input('quantity'),
                'price' => $this->input('price'),
                'type' => $this->input('type'),
                'time' => $this->input('time'),
            ])); 
        }

        $isValid = ( ! $isValid ) ? false : $filtered->isValid();

        /**
         * Validate the transactions
         */
        $this->merge(['validtransactions' => $isValid]);

    }

    /**
     * Update transaction rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token_id' => [
                'required', 
                'exists:tokens,id',
            ],
            'quantity' => ['required', 'gt:0'],
            'price' => [
                'required', 
                'gte:0',
            ],
            'type' => [
                'required', 
                Rule::in(Transaction::BUY, Transaction::SELL),
            ],
            'time' => [
                'required', 
                'date', 
                'before:'.now()->format('Y-m-d\TH:i:s'),
            ],
            'validtransactions' => [
                'required',
                new ValidTransactionsRule,
            ],
        ];
    }
}
