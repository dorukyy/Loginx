<?php

return [

    //Paths
    "loginViewPath" => "loginx::login.show",
    "registerViewPath" => "loginx::register.show",

    //Redirect Routes
    "redirects" => [
        "loginSuccess" => "/",
        "loginFail" => "/login",
        "registerSuccess" => "/",
        "registerFail" => "/register",
    ],

    "views" => [
        "login" => "loginx::login.show",
        "register" => "loginx::register.show",
        "forgotPassword" => "loginx::forgot-password.index",
    ],

    "routes" => [
        'login' => 'login',
        'register' => 'register',
        'logout' => 'logout',
        'forgotPassword' => 'forgot-password',
        'resetPassword' => 'reset-password',

    ],



    "messages" => include __DIR__.'/messages.php',
];
