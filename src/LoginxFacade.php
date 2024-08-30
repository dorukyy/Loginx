<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\Http\Requests\RegisterRequest;
use dorukyy\loginx\Models\BlockedIp;
use dorukyy\loginx\Models\FailedLogin;
use dorukyy\loginx\Models\Setting;
use dorukyy\loginx\Models\Timeout;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

class LoginxFacade extends Facade
{

    /**
     * Static function to get the view path for the given name
     * @param  string  $name
     * @return \Illuminate\Config\Repository|\Illuminate\Foundation\Application|mixed|null
     */
    public static function getViewPath(string $name)
    {
        return config('loginx.views.'.$name);
    }

    /**
     * Static function to get the input text for the login form
     * It returns a string with the input types that are enabled
     * @return string
     */
    public static function getInputText(): string
    {
        $isLoginWithUsername = SettingsFacade::get('LOGIN_WITH_USERNAME');
        $isLoginWithEmail = SettingsFacade::get('LOGIN_WITH_EMAIL');
        $isLoginWithPhone = SettingsFacade::get('LOGIN_WITH_PHONE');

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

    /**
     * Static function to get user by input. The input can be username, email or phone. Function returns the user and
     *  the found type in an array.
     */
    public static function getUser(string $input): array
    {
        $isLoginWithUsername = SettingsFacade::get('LOGIN_WITH_USERNAME');
        $isLoginWithEmail = SettingsFacade::get('LOGIN_WITH_EMAIL');
        $isLoginWithPhone = SettingsFacade::get('LOGIN_WITH_PHONE');

        $query = User::query();

        if ($isLoginWithUsername) {
            $query->orWhere('username', $input);
        }
        if ($isLoginWithEmail) {
            $query->orWhere('email', $input);
        }
        if ($isLoginWithPhone) {
            $query->orWhere('phone', $input);
        }

        $user = $query->first();

        if ($user) {
            if ($isLoginWithUsername && $user->username === $input) {
                return ['user' => $user, 'foundType' => 'username'];
            }
            if ($isLoginWithEmail && $user->email === $input) {
                return ['user' => $user, 'foundType' => 'email'];
            }
            if ($isLoginWithPhone && $user->phone === $input) {
                return ['user' => $user, 'foundType' => 'phone'];
            }
        }

        return ['user' => null, 'foundType' => null];
    }

    /**
     * Static login function to be called from LoginController
     */
    public static function login(LoginRequest $request): array
    {
        $loginHandler = new LoginService($request);
        return $loginHandler->run();

    }

    /**
     * Static function to block the user
     * @param $userID
     * @param $blockedById
     * @param $reason
     * @param $until
     * @return void
     */
    public static function blockUser($userID, $blockedById = null, $reason = null, $until = null): void
    {
        $user = User::find($userID);
        $user->blocked_at = now();
        $user->blocked_by_id = $blockedById;
        $user->blocked_reason = $reason;
        $user->blocked_until = $until;
        $user->save();
    }

    /**
     * Static register function to be called from RegisterController
     * It returns an array with the status and the message
     * @param  RegisterRequest  $request
     * @return array
     */
    public static function register(RegisterRequest $request): array
    {
        $registerHandler = new RegisterService($request);
        return $registerHandler->run();
    }

    public static function isRecaptchaValid(?string $recaptchaResponse): bool
    {

        if ($recaptchaResponse == null) {
            return false;
        }

        $recaptchaSecretKey = Setting::where('key', 'RECAPTCHA_SECRET_KEY')->first()->value;
        $recaptchaServiceUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $response = Http::post($recaptchaServiceUrl, [
            'secret' => $recaptchaSecretKey,
            'response' => $recaptchaResponse,
        ]);

        return boolval($response->json()['success']);
    }

    /**
     * Function to verify the recaptcha for the given type
     * Type can be 'REGISTER', 'LOGIN', 'RESET_PASSWORD'
     * @param  string  $type
     * @param  string|null  $recaptchaResponse
     * @return bool
     */
    public static function verifyRecaptchaForType(string $type, ?string $recaptchaResponse): bool
    {
        if (!SettingsFacade::isRecaptchaKeysSet($type)) {
            return true;
        }

        return LoginxFacade::isRecaptchaValid($recaptchaResponse);
    }

    /**
     * Function to check if the given IP address is blocked
     * @param  string  $ip
     * @return bool
     */
    public static function isIpBlocked(string $ip): bool
    {
        $blockedIp = BlockedIp::where('ip', $ip)
            ->where(function ($query) {
                $query->where('blocked_until', null)
                    ->orWhere('blocked_until', '>', now());
            })
            ->first();
        if ($blockedIp) {
            return true;
        }
        return false;
    }

    public static function isIpTimeOut(?string $ip): array
    {
        $timeout = Timeout::where('ip', $ip)->where('unblocked_at', '>=', now())->first();
        if ($timeout) {
            return ['timeout' => true, 'unblockedAt' => $timeout->unblocked_at];
        }
        return ['timeout' => false, 'unblockedAt' => null];
    }

    /**
     * Function to block the IP address
     * @param $ip
     * @return void
     */
    public static function autoBlockIp($ip): void
    {
        if ($ip != null) {
            BlockedIp::create(
                [
                    'reason' => 'Auto blocked due to failed login attempts.',
                    'blocked_by' => 'Loginx',
                    'ip' => $ip,
                    'blocked_until' => now()->addMinutes(intval(SettingsFacade::getAutoBlockDuration()))->toDateTimeString(),
                ]
            );
        }

    }

    public static function isIpTryingToLoginWithDifferentUsers(?string $ip): bool
    {
        if ($ip == null) {
            return false;
        }
        $failedLogins = FailedLogin::where('ip', $ip)
            ->where('created_at', '>=', now()->subSeconds(SettingsFacade::getAutoBlockIpInSecs()))
            ->selectRaw('COALESCE(user_id, "null") as user_id_group')
            ->groupBy('user_id_group')
            ->get();


        if ($failedLogins->count() >= SettingsFacade::getAutoBlockFailedAttemptNumber()) {
            LoginxFacade::autoBlockIp($ip);
            return true;
        }
        return false;
    }

    public static function createFailedLogin(
        ?string $ip,
        ?string $userInput = null,
        ?string $foundType = null,
        ?int $userId = null
    ): void {
        FailedLogin::create([
            'ip' => $ip,
            'user_input' => $userInput,
            'is_found' => $foundType != null ? 1 : 0,
            'found_type' => $foundType,
            'user_id' => $userId,
        ]);

    }

    public static function createTimeout(string $ip, ?string $userId = null): void
    {
        if (!SettingsFacade::getIsTimeoutEnabled()) {
            return;
        }

        $timeouts = Timeout::where('ip', $ip)->where('unblocked_at', '>=', now())->get();
        if ($userId) {
            $timeoutsWithUser = Timeout::where('user_id', $userId)->where('unblocked_at', '>=', now())->get();
        }

        if ($timeouts->count() > 0 || ($userId && $timeoutsWithUser->count() > 0)) {
            return;
        }
        Timeout::create([
            'ip' => $ip,
            'user_id' => $userId,
            'unblocked_at' => now()->addSeconds(SettingsFacade::getTimeOutDuration())->toDateTimeString(),
        ]);
    }

    public static function getIpUnblockedAt(?string $ipAddress)
    {
        return BlockedIp::where('ip', $ipAddress)
            ->first()?->blocked_until;
    }

}
