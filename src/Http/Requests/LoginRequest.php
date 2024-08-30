<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_input' => [
                'required',
                'min:3',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ]

        ];
    }

}
