<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'address' =>[
                'required',
            ],
            'district' =>[
                'required',
            ],
            'city' =>[
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Bạn đang bỏ trống ô nào đó',
        ];
    }
}
