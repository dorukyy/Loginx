# Introduction
This is a simple login system that uses a database to store user information for laravel. Everything needed for a login system is included in this package. You can use this package to create an Authentication system for your project. This package is not suitable for use in large projects. It is a simple package that can be used in small projects. If you want to use it in a large project, you can develop it further.

# Requirements
- Laravel 8.x
- PHP 7.3 or higher

# Installation
You can install the package via composer:

```bash
composer require dorukyy/loginx
```

The package will automatically register itself.


# Features

- Login
- Register
- Ip Blocking for Login
- Timeout when failed attempts
- Auto-block IP address after trying to access different accounts

# Configuration
You can change the configuration of the package by publishing the configuration file.

```bash
php artisan vendor:publish --provider="dorukyy\loginx\LoginxServiceProvider"
```

Almost everything in the package can be editable from database. You can change the configuration of the package by changing the values in the configuration file.

# Usage
## Variables
- "AUTO_BLOCK_IP_DURATION_MIN" : The duration of the IP block in minutes.
- "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT" : If the user tries to log in with different accounts, loginx will block the IP Address.
- "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_COUNT" : The number of different accounts that the user can try to log in with.
- "AUTO_BLOCK_IP_WHEN_FAILED_LOGINS_DIFFERENT_ACCOUNT_IN_SECS" : The duration of trying to log in with different accounts in seconds.
- "CHECK_IP_BLOCK" : If the IP block is enabled.
- "IS_USERS_BLOCKABLE" : If the user is blockable.
- "LOGIN_WITH_EMAIL" : If the user can log in with email.
- "LOGIN_WITH_USERNAME" : If the user can log in with username.
- "LOGIN_WITH_PHONE" : If the user can log in with phone.
- "PASSWORD_MAX_LEN" : The maximum length of the password.
- "PASSWORD_MIN_LEN" : The minimum length of the password.
- "PASSWORD_REQ_NUMBER" : If the password requires a number.
- "PASSWORD_REQ_SPECIAL" : If the password requires a special character.
- "PASSWORD_REQ_UPPER_CASE" : If the password requires an uppercase letter.
- "TIMEOUT_ACCOUNT_AFTER_X_TRY" : The number of failed login attempts before the timeout.
- "TIMEOUT_ACCOUNT_IN_X_SECS" : The duration of the timeout in seconds.
- "TIMEOUT_DURATION" : The duration of the timeout in seconds. // Default  900 = 5 minutes


# Disclaimer
This package is not suitable for use in large projects. It is a simple package that can be used in small projects. If you want to use it in a large project, you can develop it further. Also using this package requires table and model changes. Using this package will overwrite your User model and create a new table. If you want to use this package, you should be aware of these changes.

# Contributing
If you have any ideas or suggestions to improve the package, please let me know. Feel free to contribute to the package.
