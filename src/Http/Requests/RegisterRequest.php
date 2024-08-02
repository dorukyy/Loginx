<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'surname' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'min:4', 'max:255'],
            'username' => ['required', 'unique:users,username'],
            'email_confirmation' => ['required', 'same:email'],
            'password' => $this->setPasswordRule(),
            'password_confirmation' => ['required', 'same:password']
        ];
    }

    public function setPasswordRule(): Password
    {
        $passwordSettings = LoginxFacade::getPasswordSettings();
        $password = Password::min(8);
        $password->min($passwordSettings['minLength']);
        $password->max($passwordSettings['maxLength']);
        if ($passwordSettings['reqSpecial']) {
            $password->symbols();
        }
        if ($passwordSettings['reqNum']) {
            $password->numbers();
        }
        if ($passwordSettings['reqUppercase']) {
            $password->mixedCase();
        }
        return $password;
    }

}
