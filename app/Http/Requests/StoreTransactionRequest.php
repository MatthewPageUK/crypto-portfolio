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
     * Get the validation rules that apply to the request.
     * 
     * Todo - future time validation before:xxxxx
     *
     * @return array
     */
    public function rules()
    {
        $token = Token::find($this->input('token_id'));

        $quantityRule = ($token && $this->input('type')===Transaction::SELL) ? ['required', 'gt:0', 'lte:'.$token->balance()->getValue()] : ['required', 'gt:0'];

        $token->transactions->push(new Transaction([
            'token_id' => $token->id, 
            'quantity' => $this->input('quantity'),
            'price' => $this->input('price'),
            'type' => $this->input('type'),
            'time' => $this->input('time'),
        ]));
        
        $valid = ( $token->transactions->isValid() ) ? [ 'validtrans' => '' ] : [ 'validtrans' => new ValidTransactionsRule ];
        
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
            $valid,
        ];
    }
}
