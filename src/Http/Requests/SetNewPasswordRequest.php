<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use dorukyy\loginx\SettingsFacade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class SetNewPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => $this->setPasswordRule(),
            'password_confirmation' => ['required', 'same:password'],

        ];
    }



    public function setPasswordRule(): Password
    {
        $passwordSettings = SettingsFacade::getPasswordSettings();
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

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('password') == $this->input('username')) {
                $validator->errors()->add('password', 'The password cannot be the same as the username.');
            }
            if ($this->input('password') == $this->input('email')) {
                $validator->errors()->add('password', 'The password cannot be the same as the email.');
            }
        });
    }

}
