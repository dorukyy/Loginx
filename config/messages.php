<?php

use Illuminate\Support\HtmlString;

return [
    //Login
    "loginSuccess" => "Login Successful",
    "loginFail" => "Login Failed",
    "userNotFound" => "The user is not found.",
    "wrongPassword" => "The password is incorrect.",

    //Register
    "registerSuccess" => "Register Successful",
    "registerFail" => "Register Failed",

    //Security
    "ipBlocked" => "Your IP is blocked until {blocked_until}. Please try again later or contact the administrator.",
    "maxFailedLoginIp" => "You have reached the maximum failed login attempts. Please try again later.",
    "maxFailedLoginAccount" => "You have reached the maximum failed login attempts. You have timeout until {unblocked_at}. Please try again later.",
    "accountTimeout" => "Your account has timeout until {unblocked_at}. Please try again later.",
    "ipTimeout" => "You have timeout until {unblocked_at}. Please try again later.",
    "accountBlocked" => "Your account is blocked. Please contact the administrator.",
    "accountUnblockedAt" => "Your account is blocked. You can try again at {blocked_until}.",
    "ipBlockedNow" => "You have reached the maximum failed login attempts to different accounts. Your IP is blocked now.",
    "maxFailedRegisterIp" => "You have reached the maximum failed registration attempts. Please try again later.",
    "mailProviderBlocked" => "Mail provider is blocked. Please try with another email provider.",

    //Forgot Password
    "emailNotFound" => "This email is not found.",
    "invalidToken" => "This token is invalid.",
    "forgotPasswordMailSent" => "Password reset link sent!",
    "emailOrTokenIsNotFound" => "This email or token is not found.",
    "tokenIsInvalid" => "This token is invalid.",
    "tokenIsExpired" => "This token is expired.",
    "tokenUsed" => "This token is already used.",
    "passwordResetSuccess" => "Password reset successful.",

    "recaptchaFailed" => "Recaptcha verification failed. Please try again.",


    //Error
    "error" => "An error occurred. Please try again later.",

    //Activation
    "activationMailSent" => "Activation mail sent. Please check your email.",
    "activationMailAlreadySent" => "Activation mail already sent. Please check your email. If you didn't receive it, you can resend it after 3 minutes.",
    "accountActivated" => "Your account is activated. You can login now.",
    "userNotActivated" => new HtmlString("Your account is not activated. Please check your email for activation mail or click the button below to resend it.<br><a href='/activation/resend'>Resend Activation Mail</a>"),

];

