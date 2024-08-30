<?php

return [
    //Users
    "IS_USERS_BLOCKABLE" => true,  // It should be true if you want to block users

    //Login Settings
    "LOGIN_WITH_USERNAME" => true,
    "LOGIN_WITH_EMAIL" => true,
    "LOGIN_WITH_PHONE" => false,

    //Register Settings
    "IS_MAIL_ACTIVATION" => true,
    "SHOW_USERNAME_ON_REGISTER" => true,
    "SHOW_PHONE_ON_REGISTER" => true,
    "MAX_REGISTER_ATTEMPTS" => 20,
    "SHOW_TIMEZONES_ON_REGISTER" => true,
    "SHOW_COUNTRY_ON_REGISTER" => true,
    "SHOW_BIRTHDATE_ON_REGISTER" => true,
    "SHOW_CITY_ON_REGISTER" => false,
    "SHOW_ADDRESS_ON_REGISTER" => false,
    "SHOW_RECAPTCHA_ON_REGISTER" => false,
    "SHOW_RECAPTCHA_ON_LOGIN" => false,
    "SHOW_RECAPTCHA_ON_ACTIVATION" => false,
    "ACTIVATION_TOKEN_DURATION" => 900, // 15 minutes
    "RECAPTCHA_SITE_KEY" => "",
    "RECAPTCHA_SECRET_KEY" => "",

    //Password Settings
    "PASSWORD_MIN_LEN" => 8,
    "PASSWORD_MAX_LEN" => 255,
    "PASSWORD_REQ_UPPER_CASE" => true,
    "PASSWORD_REQ_NUM" => true,
    "PASSWORD_REQ_SPECIAL" => true,

    //Reset Password Settings
    "RESET_TOKENS_CAN_BE_USED_FROM_DIFF_IP" => false, //If true, the reset tokens can be used from different IP addresses
    "RESET_PASSWORD_TOKEN_DURATION" => 900, // 15 minutes
    "AUTO_BLOCK_IP_ON_RESET_DIFF_ACCTS_PASS" => true,
    "AUTO_BLOCK_IP_ON_RESET_DIFF_ACCTS_PASS_COUNT" => 5,
    "AUTO_BLOCK_IP_ON_RESET_DIFF_ACCTS_PASS_IN_SECS" => 180,
    "SHOW_RECAPTCHA_ON_RESET" => true,

    //IP Address Settings
    "CHECK_IP_BLOCK" => true,  //It should be true if you want to block IP addresses

    //Timeout Settings
    "TIMEOUT_ENABLED" => true,
    "TIMEOUT_AFTER_X_TRY" => 5,
    "TIMEOUT_IN_X_SECS" => 300,
    "TIMEOUT_DURATION" => 900,
    "REGISTER_TIMEOUT" => 60,

    //Auto Block IP
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT" => true,  // If true, it will block the IP if the failed login attempts are from different accounts
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_COUNT" => 3,  // If the failed login attempts are from different accounts, it will block the IP after this count
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_IN_SECS" => 180,
    "AUTO_BLOCK_IP_DURATION_MIN" => 52560000, // 100 years

    //Referral
    "IS_REFERRAL_SYSTEM" => true,

];
