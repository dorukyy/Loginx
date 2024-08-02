<?php

return [
    //Login Settings
    "LOGIN_WITH_USERNAME" => false,
    "LOGIN_WITH_EMAIL" => true,
    "LOGIN_WITH_PHONE" => false,

    //Register
    "MAX_REGISTER_ATTEMPTS" => 20,
    "REGISTER_TIMEOUT" => 60, // 0 means no timeout


    //Password Settings
    "PASSWORD_MIN_LEN" => 8,
    "PASSWORD_MAX_LEN" => 255,
    "PASSWORD_REQ_UPPER_CASE" => true,
    "PASSWORD_REQ_NUM" => true,
    "PASSWORD_REQ_SPECIAL" => true,


    //IP Address Settings
    "CHECK_IP_BLOCK" => true,

    "TIMEOUT_ACCOUNT_AFTER_X_TRY" => 5,
    "TIMEOUT_ACCOUNT_IN_X_SECS" => 300,
    "TIMEOUT_DURATION" => 900,           // 0 means no timeout

    //Auto Block IP
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT" => true,
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_COUNT" => 3,
    "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_IN_SECS" => 180,
    "AUTO_BLOCK_IP_DURATION_MIN" => 52560000, // 100 years


    //Users
    "IS_USERS_BLOCKABLE" => true,

];
