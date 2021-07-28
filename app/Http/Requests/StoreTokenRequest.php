<?php

namespace App\Http\Requests;

use App\Models\Token;
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
     * Store token rules.
     *
     * @return array
     */
    public function rules()
    {
        $table = (new Token())->getTable();

        return [
            'symbol' => [ 
                'required',
                Rule::unique($table)->where('deleted_at', NULL),
                'max:25',
                'alpha_num',
            ],
            'name' => [
                'required',
                Rule::unique($table)->where('deleted_at', NULL),
                'max:100',
                'min:3',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
        ];
    }
}
