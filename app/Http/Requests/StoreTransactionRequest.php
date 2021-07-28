<?php

namespace App\Http\Requests;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
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
        $token = CryptoToken::find($this->input('crypto_token_id'));

        $quantityRule = ($token && $this->input('type')===CryptoTransaction::SELL) ? ['required', 'gt:0', 'lte:'.$token->balance()->getValue()] : ['required', 'gt:0'];

        $token->transactions->push(new CryptoTransaction([
            'crypto_token_id' => $token->id, 
            'quantity' => $this->input('quantity'),
            'price' => $this->input('price'),
            'type' => $this->input('type'),
            'time' => $this->input('time'),
        ]));
        
        $valid = ( $token->transactions->isValid() ) ? [ 'validtrans' => '' ] : [ 'validtrans' => new ValidTransactionsRule ];
        
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
            $valid,
        ];
    }
}
