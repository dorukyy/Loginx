# Installation
To install the package, you can use the following command:

```bash
composer require dorukyy/loginx
```

After installing the package, you can publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="Dorukyy\Loginx\LoginxServiceProvider"
```

You can add the `LoginxUser` trait to your User model to use the package's features:


