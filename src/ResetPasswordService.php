<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\SetNewPasswordRequest;
use dorukyy\loginx\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;

class ResetPasswordService
{
    private string $email;
    private string $ipAddress;
    private ?string $password;
    private ?string $passwordConfirmation;
    private SetNewPasswordRequest $request;
    private PasswordResetToken $token;

    public function __construct(SetNewPasswordRequest $request)
    {
        $this->email = $request->email;
        $this->token = PasswordResetToken::where('email', $this->email)->first();
        $this->ipAddress = $request->ip();
        $this->request = $request;
        $this->password = $request->password ?? null;
        $this->passwordConfirmation = $request->password_confirmation ?? null;
    }

    public function run(): array
    {
        if ($this->email == null) {
            return ['status' => 'failed', 'message' => config('loginx.messages.emailOrTokenIsNotFound')];
        }
        if (LoginxFacade::isIpBlocked($this->ipAddress)) {
            return ['status' => 'failed', 'message' => config('loginx.messages.ipBlocked')];
        }
        if (!LoginxFacade::verifyRecaptchaForType('RESET', $this->request['cf-turnstile-response'] ?? null)) {
            return ['status' => 'failed', 'message' => config('loginx.messages.recaptchaFailed')];
        }

        if (!$this->isTokenValid()) {
            return ['message' => config('loginx.messages.tokenIsInvalid'), 'status' => 0];
        }

        if ($this->isTokenExpired()) {
            return ['message' => config('loginx.messages.tokenIsExpired'), 'status' => 0];
        }

        if ($this->isTokenUsed()) {
            return ['message' => config('loginx.messages.tokenUsed'), 'status' => 0];
        }

        if ($this->isEmailValid()) {
            return ['message' => config('loginx.messages.userNotFound'), 'status' => 0];
        }
        if (!$this->isPasswordValid()) {
            return ['message' => config('loginx.messages.passwordsNotMatch'), 'status' => 0];
        }
        try {
            $this->setNewPassword();
            return ['message' => config('loginx.messages.passwordChanged'), 'status' => 1];
        } catch (\Exception $e) {
            return ['message' => config('loginx.messages.error'), 'status' => 0];
        }
    }

    private function isTokenExpired(): bool
    {
        return $this->request->token < now();
    }

    private function isTokenValid(): bool
    {
        return $this->token->token && $this->request->email;
    }

    private function isTokenUsed(): bool
    {
        return $this->token->is_used;
    }

    public function isEmailValid(): bool
    {
        return !User::where('email', $this->email)->first();
    }

    public function isPasswordValid(): bool
    {
        return $this->password == $this->passwordConfirmation;
    }

    public function setNewPassword(): void
    {
        DB::beginTransaction();
        $this->setTokenUsed();
        $user = User::where('email', $this->email)->first();
        $user->password = bcrypt($this->password);
        $user->save();
        DB::commit();

    }

    public function setTokenUsed(): void
    {
        $this->token->is_used = true;
        $this->token->used_at = now();
        $this->token->save();
    }

}
