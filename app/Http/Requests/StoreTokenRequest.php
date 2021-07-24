<?php

namespace App\Http\Requests;

use App\Models\CryptoToken;
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
        $table = (new CryptoToken())->getTable();

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
