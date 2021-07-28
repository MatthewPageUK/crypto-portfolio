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
        $token = Token::find($this->input('token_id'));
        $transaction = $this->route('transaction');

        // If different token check both tokens maintain a valid balance..... !!!! todo
        
        /**
         * Filter out the current transaction
         */
        $filtered = $token->transactions->where('id', '!=', $transaction->id);

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

        /**
         * Validate the transactions
         */
        $this->merge(['validtransactions' => $filtered->isValid()]);

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
