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
     * Update token rules.
     *
     * @return array
     */
    public function rules()
    {
        $token = $this->route('token');

        return [
            'symbol' => [ 
                'required',
                Rule::unique('tokens')->where('deleted_at', NULL)->ignore($token->id), 
                'max:25',
                'alpha_num',
            ],
            'name' => [
                'required',
                Rule::unique('tokens')->where('deleted_at', NULL)->ignore($token->id),
                'max:100',                
                'min:3',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
        ];
    }
}
