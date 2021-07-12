<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array
     */
    public function rules()
    {
        return [
            'crypto_token_id' => ['required', 'exists:crypto_tokens,id'],
            'quantity' => ['required', 'gt:0'],
            'price' => ['required', 'gte:0'],
            'type' => ['required', Rule::in('buy', 'sell')],
            'time' => ['required', 'date'],
        ];
    }
}
