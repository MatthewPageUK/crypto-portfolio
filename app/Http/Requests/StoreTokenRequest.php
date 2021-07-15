<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'symbol' => [ 
                'required',
                Rule::unique('crypto_tokens')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                }), 
                'max:25',
            ],

            'name' => [
                'required',
                Rule::unique('crypto_tokens')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                }),
                'max:100',
            ],
        ];
    }
}
