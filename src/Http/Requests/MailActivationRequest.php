<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use Illuminate\Foundation\Http\FormRequest;

class MailActivationRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'email' => ['required','exists:users,email','email'],
        ];
    }
    public function messages(): array
    {
        return [
            'email.exists' => 'This email is not found.',
        ];
    }



}
