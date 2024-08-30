<?php

namespace dorukyy\loginx\Http\Requests;

use dorukyy\loginx\LoginxFacade;
use dorukyy\loginx\SettingsFacade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'surname' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'min:4', 'max:255'],
            'username' => [
                'required_if:\dorukyy\loginx\Models\Setting::where(\'key\', \'SHOW_USERNAME_ON_REGISTER\')->first()?->value, 1',
                'unique:users,username'
            ],
            'reference_code' => [
                'required_if:\dorukyy\loginx\Models\Setting::where(\'key\', \'IS_REFERRAL_SYSTEM\')->first()?->value, 1',
                'exists:users,reference_code'
            ],
            'email_confirmation' => ['required', 'same:email'],
            'password' => $this->setPasswordRule(),
            'password_confirmation' => ['required', 'same:password'],
            'phone' => ['nullable', 'unique:users,phone'],
            'phone_code' => 'required_with:phone',
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

    public function prepareForValidation(): void
    {
        if ($this->input('phone')) {
            $this->merge([
                'phone' => $this->input('phone_code').$this->input('phone')
            ]);
        }

    }

}
