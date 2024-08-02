<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\Models\Setting;
use Illuminate\Support\Facades\Facade;

class LoginxFacade extends Facade
{
    /*
     * Static function to get all password settings
     */
    public static function getPasswordSettings(): array
    {
        return [
            'minLength' => Setting::where('key', 'PASSWORD_MIN_LEN')->first()->value,
            'maxLength' => Setting::where('key', 'PASSWORD_MAX_LEN')->first()->value,
            'reqSpecial' => Setting::where('key', 'PASSWORD_REQ_SPECIAL')->first()->value,
            'reqNum' => Setting::where('key', 'PASSWORD_REQ_NUM')->first()->value,
            'reqUppercase' => Setting::where('key', 'PASSWORD_REQ_UPPER_CASE')->first()->value,
        ];
    }

    public static function getLoginViewPath()
    {
        return config('loginx.loginViewPath');
    }

    public static function getInputText(): string
    {
        $isLoginWithUsername = Setting::where('key', 'LOGIN_WITH_USERNAME')->first()->value;
        $isLoginWithEmail = Setting::where('key', 'LOGIN_WITH_EMAIL')->first()->value;
        $isLoginWithPhone = Setting::where('key', 'LOGIN_WITH_PHONE')->first()->value;

        //Show the user input field according to the settings
        $inputText = '';

        if ($isLoginWithUsername) {
            $inputText = 'Username';
        }
        if ($isLoginWithEmail) {
            $inputText .= ($inputText ? ' / ' : '').'Email';
        }
        if ($isLoginWithPhone) {
            $inputText .= ($inputText ? ' / ' : '').'Phone';
        }

        return $inputText;

    }

    public static function getUser($user_input): array
    {
        $isLoginWithUsername = Setting::where('key', 'LOGIN_WITH_USERNAME')->first()->value;
        $isLoginWithEmail = Setting::where('key', 'LOGIN_WITH_EMAIL')->first()->value;
        $isLoginWithPhone = Setting::where('key', 'LOGIN_WITH_PHONE')->first()->value;


        $user = null;
        $found = null;

        if ($isLoginWithUsername) {
            $user = User::where('username', $user_input)->first();
            $found = 'username';
        }
        if ($isLoginWithEmail) {
            $user = User::where('email', $user_input)->first();
            $found = 'email';
        }
        if ($isLoginWithPhone) {
            $user = User::where('phone', $user_input)->first();
            $found = 'phone';
        }

        return ['user' => $user, 'foundType' => $found];
    }

    public static function getTimeOutInSecs(): int
    {
        return intval(Setting::where('key', 'TIMEOUT_ACCOUNT_IN_X_SECS')->first()->value);
    }

    public static function getTimeOutDuration(): int
    {
        return intval(Setting::where('key', 'TIMEOUT_DURATION')->first()->value);

    }

    public static function getTimeOutAfterAttemptCount()
    {
        return Setting::where('key', 'TIMEOUT_ACCOUNT_AFTER_X_TRY')->first()->value;
    }

    public static function getAutoBlockFailedAttemptNumber()
    {
        return Setting::where('key', 'AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_COUNT')->first()->value;
    }

    public static function getAutoBlockDuration()
    {
        return Setting::where('key', 'AUTO_BLOCK_IP_DURATION_MIN')->first()->value;
    }

    public static function getAutoBlockIpInSecs()
    {
        return Setting::where('key', 'AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_IN_SECS')->first()->value;
    }


    /*
     * Static login function to be called from LoginController
     */
    public static function login(LoginRequest $request): array
    {
        $loginHandler = new LoginHandler($request);
        return $loginHandler->login();

    }

    public static function getRegisterView()
    {
        return config('loginx.registerViewPath');
    }

    public static function isUsersBlockable()
    {
        return Setting::where('key', 'IS_USERS_BLOCKABLE')->first()->value;
    }

    public static function blockUser($userID, $blockedById=null, $reason=null, $until = null): void
    {
        $user = User::find($userID);
        $user->blocked_at = now();
        $user->blocked_by_id = $blockedById;
        $user->blocked_reason = $reason;
        $user->blocked_until = $until;
        $user->save();
    }

    public static function getMaxRegisterAttempts(): int
    {
        return intval(Setting::where('key', 'MAX_REGISTER_ATTEMPTS')->first()->value);
    }

    public static function getRegisterTimeout(): int
    {
        return intval(Setting::where('key', 'REGISTER_TIMEOUT')->first()->value);
    }

    public static function register(Http\Requests\RegisterRequest $request): array
    {
        $registerHandler = new RegisterHandler($request);
        return $registerHandler->register();
    }

}
