<?php

namespace App\Http\Requests;

use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidTransactionsRule;

class StoreTransactionRequest extends FormRequest
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

    /**
     * Push the new transaction to the list and validate them
     */
    protected function prepareForValidation()
    {
        // todo make a copy of the transactions and test on that ... not the original
        $token = Token::find($this->input('token_id'));
        $token->transactions->push(new Transaction([
            'token_id' => $token->id, 
            'quantity' => $this->input('quantity'),
            'price' => $this->input('price'),
            'type' => $this->input('type'),
            'time' => $this->input('time'),
        ]));

        /**
         * Validate the transactions
         */
        $this->merge(['validtransactions' => $token->transactions->isValid()]);
    }    

    /**
     * Get the validation rules that apply to the request.
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
