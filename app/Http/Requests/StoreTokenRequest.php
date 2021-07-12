<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTokenRequest extends FormRequest
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
            'symbol' => ['required', 'alpha_num', 'unique:crypto_tokens', 'max:25'],
            'name' => ['required', 'unique:crypto_tokens', 'max:100'],
        ];
    }
}
