<?php

namespace dorukyy\loginx;

use dorukyy\loginx\Models\Setting;
use Illuminate\Support\Facades\Facade;

class SettingsFacade extends Facade
{
    /**
     * Get given setting's value.
     */
    public static function get(?string $key = null)
    {
        if ($key === null) {
            return Setting::all();
        }
        return Setting::where('key', $key)->first()?->value;
    }

    /**
     * Static function to get all password settings
     * @return array
     */
    public static function getPasswordSettings(): array
    {
        return [
            'minLength' => self::get('PASSWORD_MIN_LEN'),
            'maxLength' => self::get('PASSWORD_MAX_LEN'),
            'reqSpecial' => self::get('PASSWORD_REQ_SPECIAL'),
            'reqNum' => self::get('PASSWORD_REQ_NUM'),
            'reqUppercase' => self::get('PASSWORD_REQ_UPPER_CASE'),
        ];
    }

    /**
     * Static function to get timeout in seconds settings
     * @return int
     */
    public static function getTimeOutInSecs(): int
    {
        return intval(self::get('TIMEOUT_IN_X_SECS'));
    }

    /**
     * Static function to get if the referral system is enabled
     * @return bool
     */
    public static function getIsReferralSystem(): bool
    {
        return boolval(self::get('IS_REFERRAL_SYSTEM'));
    }

    /**
     * Static function to get the timeout duration
     * @return int
     */
    public static function getTimeoutDuration(): int
    {
        return intval(self::get('TIMEOUT_DURATION'));
    }

    /**
     * Static function to get the attempt count before to get timeout
     * @return int
     */
    public static function getTimeOutAfterAttemptCount(): int
    {
        return intval(self::get('TIMEOUT_AFTER_X_TRY'));
    }

    public static function getTimeOutAfterAttemptCountForIp(): int
    {
        return intval(self::get('TIMEOUT_AFTER_X_TRY_FOR_IP'));
    }

    public static function getAutoBlockFailedAttemptNumber(): int
    {
        return intval(self::get('AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_COUNT'));
    }

    public static function getIsUsersBlockable(): bool
    {
        return boolval(self::get('IS_USERS_BLOCKABLE'));
    }

    public static function getIsEmailActivation(): bool
    {
        return boolval(self::get('IS_MAIL_ACTIVATION'));
    }

    public static function getIsTimeoutEnabled(): bool
    {
        return boolval(self::get('TIMEOUT_ENABLED'));
    }

    public static function getMaxRegisterFailedAttempts(): int
    {
        return intval(self::get('MAX_REGISTER_ATTEMPTS'));
    }

    public static function getRegisterTimeoutDuration(): int
    {
        return intval(self::get('REGISTER_TIMEOUT'));
    }

    public static function getAutoBlockDuration(): int
    {
        return intval(self::get('AUTO_BLOCK_IP_DURATION_MIN'));
    }

    public static function getAutoBlockIpInSecs(): int
    {
        return intval(self::get('AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_IN_SECS'));
    }

    public static function isRecaptchaKeysSet(string $type): bool
    {
        $type = strtoupper($type);
        if ($type != 'LOGIN' && $type != 'REGISTER' && $type != 'RESET') {
            return false;
        }
        return self::get('RECAPTCHA_SECRET_KEY') != null &&
            self::get('RECAPTCHA_SITE_KEY') != null &&
            self::get('SHOW_RECAPTCHA_ON_'.$type) == true;
    }

    // View Data Settings

    /**
     * Static function to get the view data for the login page
     * @return array
     */
    public static function getLoginViewData(): array
    {
        return [
            'showRecaptcha' => self::isRecaptchaKeysSet('LOGIN'),
            'recaptchaSiteKey' => self::get('RECAPTCHA_SITE_KEY'),
        ];
    }

    /**
     * Static function to get view data for the register page
     * @return array
     */
    public static function getRegisterViewData(): array
    {
        return [
            'isReferralSystem' => self::get('IS_REFERRAL_SYSTEM'),
            'showUsername' => self::get('SHOW_USERNAME_ON_REGISTER'),
            'showPhone' => self::get('SHOW_PHONE_ON_REGISTER'),
            'showCountry' => self::get('SHOW_COUNTRY_ON_REGISTER'),
            'showCity' => self::get('SHOW_CITY_ON_REGISTER'),
            'showAddress' => self::get('SHOW_ADDRESS_ON_REGISTER'),
            'showBirthdate' => self::get('SHOW_BIRTHDATE_ON_REGISTER'),
            'showTimezones' => self::get('SHOW_TIMEZONES_ON_REGISTER'),
            'showRecaptcha' => self::get('SHOW_RECAPTCHA_ON_REGISTER'),
            'recaptchaSiteKey' => self::get('RECAPTCHA_SITE_KEY'),
        ];
    }

    /**
     * Static function to get view data for the forgot password page
     * @return array
     */
    public static function getForgotPasswordViewData(): array
    {
        return [
            'showRecaptcha' => self::isRecaptchaKeysSet('RESET'),
            'recaptchaSiteKey' => self::get('RECAPTCHA_SITE_KEY'),
        ];
    }

    public static function set(string $key, $value)
    {
        $setting = Setting::where('key', $key)->first();
        if($setting){
            $setting->update(['value' => $value]);
        }
    }

    public static function getActivationViewData(): array
    {
        return [
            'showRecaptcha' => self::isRecaptchaKeysSet('ACTIVATION'),
            'recaptchaSiteKey' => self::get('RECAPTCHA_SITE_KEY'),
        ];
    }

    public static function getActivationTokenDuration(): int
    {
        return intval(self::get('ACTIVATION_TOKEN_DURATION'));

    }

}

