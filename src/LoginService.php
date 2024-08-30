<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\Models\FailedLogin;
use dorukyy\loginx\Models\Login;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public string $ipAddress;
    public ?string $foundType;
    public string $userInput;

    public ?User $user;
    public LoginRequest $request;

    /**
     * Create a new LoginService instance with the given login request.
     * @param  LoginRequest  $request
     */
    public function __construct(LoginRequest $request)
    {
        $this->ipAddress = $request->ip();
        $user = LoginxFacade::getUser($request->user_input) ?? null;
        $this->user = $user['user'] ?? null;
        $this->foundType = $user['foundType'] ?? null;
        $this->userInput = $request->user_input;
        $this->request = $request;
    }

    public function run(): array
    {
        if (LoginxFacade::isIpBlocked($this->ipAddress)) {
            return ['success' => false, 'message' => str_replace('{blocked_until}', LoginxFacade::getIpUnblockedAt($this->ipAddress),
                        config('loginx.messages.ipBlocked'))];
        }
        if (!LoginxFacade::verifyRecaptchaForType('LOGIN', $this->request['cf-turnstile-response'] ?? null)) {
            return ['success' => false, 'message' => config('loginx.messages.recaptchaFailed')];
        }
        if (LoginxFacade::isIpTryingToLoginWithDifferentUsers($this->ipAddress)) {
            return ['success' => false, 'message' => config('loginx.messages.ipBlockedNow')];
        }
        if (SettingsFacade::getIsTimeoutEnabled()) {
            $ipTimeout = LoginxFacade::isIpTimeOut($this->ipAddress);
            if ($ipTimeout['timeout']) {
                return [
                    'success' => false, 'message' => str_replace('{unblocked_at}', $ipTimeout['unblockedAt'],
                        config('loginx.messages.ipTimeout'))
                ];
            }
        }

        if ($this->checkFailedLoginsWithIp()) {
            return ['success' => false, 'message' => config('loginx.messages.maxFailedLoginIp')];
        }
        if ($this->user) {
            if ($this->user->isTimeout()) {
                return ['success' => false, 'message' => str_replace('{unblocked_at}', $this->user->getEndOfTimeout(),
                    config('loginx.messages.accountTimeout'))];
            }
            if ($this->checkFailedLoginsWithUser()) {
                return ['success' => false, 'message' => config('loginx.messages.maxFailedLoginAccount')];
            }
            if (!$this->isUserActivated()) {
                return ['success' => false, 'message' => config('loginx.messages.userNotActivated')];
            }
            if (password_verify($this->request->password, $this->user->password)) {
                $credentials = [
                    $this->foundType => $this->userInput,
                    'password' => $this->request->password,
                ];
                if (Auth::attempt($credentials, $this->request->filled('remember'))) {
                    if ($this->isUserBlocked()) {
                        return ['success' => false, 'message' => config('loginx.messages.accountBlocked')];
                    }
                    Login::create([
                        'ip' => $this->ipAddress,
                        'user_id' => $this->user->id,
                        'user_agent' => $this->request->userAgent(),
                        'headers' => json_encode($this->request->header()),
                        'user_input' => $this->userInput,
                        'found_type' => $this->foundType
                    ]);
                    return [
                        'success' => true, 'message' => config('loginx.messages.loginSuccess'),
                        'user' => $this->user
                    ];
                }

            } else {
                LoginxFacade::createFailedLogin($this->ipAddress, $this->userInput
                    , $this->foundType, $this->user->id);

                return ['success' => false, 'message' => config('loginx.messages.wrongPassword')];
            }
        } else {
            LoginxFacade::createFailedLogin($this->ipAddress, $this->userInput);
            return ['success' => false, 'message' => config('loginx.messages.userNotFound')];

        }
        return ['success' => false, 'message' => config('loginx.messages.error')];
    }

    private function checkFailedLoginsWithIp(): bool
    {
        if (!SettingsFacade::getIsTimeoutEnabled()) {
            return false;
        }
        $failedLogins = FailedLogin::where('ip', $this->ipAddress)
            ->where('created_at', '>=', now()->subSeconds(SettingsFacade::getTimeOutInSecs()))
            ->get();
        if ($failedLogins->count() >= SettingsFacade::getTimeOutAfterAttemptCount()) {
            LoginxFacade::createTimeout($this->ipAddress, $this->user->id ?? null);
            return true;
        }
        return false;
    }

    private function checkFailedLoginsWithUser(): bool
    {
        if (!SettingsFacade::getIsTimeoutEnabled()) {
            return false;
        }
        $failedLogins = FailedLogin::where('user_id', $this->user->id)
            ->where('created_at', '>=', now()->subSeconds(SettingsFacade::getTimeOutInSecs()))
            ->get();
        if ($failedLogins->count() >= SettingsFacade::getTimeOutAfterAttemptCount()) {
            LoginxFacade::createTimeout($this->ipAddress, $this->user->id);
            return true;
        }
        return false;
    }

    /**
     * Check if the user is blocked or not.
     * @return bool
     */
    private function isUserBlocked(): bool
    {
        if (!SettingsFacade::getIsUsersBlockable()) {
            return false;
        } else {
            return $this->user->isBlocked();
        }
    }

    private function isUserActivated(): bool
    {
        if (!SettingsFacade::getIsEmailActivation()) {
            return true;
        }
        return $this->user->isActivated();
    }

}
