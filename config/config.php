<?php

return [

    //Paths
    "loginViewPath" => "loginx::login.show",
    "registerViewPath" => "loginx::register.show",


    "loginRoute" => "/login",
    "registerRoute" => "/register",

    //Redirect Paths
    "loginSuccessRedirect" => "/",
    "loginFailRedirect" => "/login",
    "registerSuccessRedirect" => "/",
    "registerFailRedirect" => "/register",

    //Messages
    "loginSuccessMessage" => "Login Successful",
    "loginFailMessage" => "Login Failed",
    "registerSuccessMessage" => "Register Successful",
    "registerFailMessage" => "Register Failed",
    "ip_blocked_message" => "Your IP is blocked until {blocked_until}. Please try again later.",
    "max_failed_login_ip_message" => "You have reached the maximum failed login attempts. Please try again later.",
    "max_failed_login_account_message" => "You have reached the maximum failed login attempts. You have timeout until {unblocked_at}. Please try again later.",
    "account_timeout_message" => "Your account has timeout until {unblocked_at}. Please try again later.",
    "ip_timeout_message" => "You have timeout until {unblocked_at}. Please try again later.",
    "account_blocked_message" => "Your account is blocked. Please contact the administrator.",
    "account_unblocked_at_message" => "Your account is blocked. You can try again at {blocked_until}.",
    "ip_blocked_now_message" => "You have reached the maximum failed login attempts to different accounts. Your IP is blocked now.",
    "max_failed_register_ip_message" => "You have reached the maximum failed registration attempts. Please try again later.",
    "mail_provider_blocked_message" => "Mail provider is blocked. Please try with another email provider.",
    ];
