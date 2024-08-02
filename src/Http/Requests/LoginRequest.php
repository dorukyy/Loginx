<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_input' => 'required',
            'password' => ['required',
                'string',

            ]

        ];
    }

}
