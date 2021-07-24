<?php

namespace App\Http\Requests;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $token = CryptoToken::find($this->input('crypto_token_id'));
        $transaction = $this->route('transaction');

        if($transaction->crypto_token_id !== $token->id) 
        {
            // selected a different token, balance from token is ok
            $balance = $token->balance();
        }
        else
        {
            // selected same token, balance needs to be adjusted to remove existing transaction that is being edited
            $balance = $token->balance() + ( ($transaction->isBuy()) ? -$transaction->quantity->getValue() : $transaction->quantity->getValue() );
        }

        $quantityRule = ($token && $this->input('type')===CryptoTransaction::SELL) ? ['required', 'gt:0', 'lte:'.$balance] : ['required', 'gt:0'];

        return [
            'crypto_token_id' => ['required', 'exists:crypto_tokens,id'],
            'quantity' => $quantityRule,
            'price' => ['required', 'gte:0'],
            'type' => ['required', Rule::in(CryptoTransaction::BUY, CryptoTransaction::SELL)],
            'time' => ['required', 'date', 'before:'.now()->format('Y-m-d\TH:i:s')],
        ];
    }
}
