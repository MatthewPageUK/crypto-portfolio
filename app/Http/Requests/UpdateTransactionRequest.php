<?php

namespace App\Http\Requests;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
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
        $token = CryptoToken::find($this->input('crypto_token_id'));
        $transaction = $this->route('transaction');

        // If different token check both tokens maintain a valid balance..... !!!! todo
        
        /**
         * Filter out the current transaction
         */
        $filtered = $token->transactions->where('id', '!=', $transaction->id);

        /**
         * Push updated transaction
         */
        $filtered->push(new CryptoTransaction([
            'crypto_token_id' => $token->id, 
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
            'crypto_token_id' => [
                'required', 
                'exists:crypto_tokens,id',
            ],
            'quantity' => ['required', 'gt:0'],
            'price' => [
                'required', 
                'gte:0',
            ],
            'type' => [
                'required', 
                Rule::in(CryptoTransaction::BUY, CryptoTransaction::SELL),
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
