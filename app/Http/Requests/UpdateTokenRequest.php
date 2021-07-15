<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTokenRequest extends FormRequest
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
        $token = $this->route('token');

        return [
            'symbol' => [ 
                'required',
                Rule::unique('crypto_tokens')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })->ignore($token->id), 
                'max:25',
                'alpha_num',
            ],

            'name' => [
                'required',
                Rule::unique('crypto_tokens')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })->ignore($token->id),
                'max:100',
            ],
        ];
    }
}
