<?php

namespace dorukyy\loginx;

use dorukyy\loginx\database\seeders\LoginxSeeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LoginxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('loginx', function () {
            return new LoginxFacade;
        });

        //Check if User model exists and create if not
        if (!class_exists('App\Models\User')) {
            // Create User model and migration
            Artisan::call('make:model User -m');
            $this->publishes([
                __DIR__.'/../stubs/User.stub' => app_path('Models/User.php'),
            ], 'user-model');

            // Create User table
        }

        // Check if users table exists and add blocked_at, blocked_until, blocked_reason, blocked_by_id columns
        if (Schema::hasTable('users')) {
            $this->createUserColumns();
        }


        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'loginx'
        );

        // Ensure auth.php file exists and append import statement to web.php
        $filesystem = $this->app->make(Filesystem::class);
        $authFilePath = base_path('routes/auth.php');
        $webFilePath = base_path('routes/web.php');
        $requireStatement = "\nrequire base_path('routes/auth.php');";

        if (!$filesystem->exists($authFilePath)) {
            $filesystem->put($authFilePath, $filesystem->get(__DIR__.'/../routes/web.php'));
        }

        // Append the import statement for auth.php to the project's routes/web.php if it doesn't already exist
        if (!str_contains($filesystem->get($webFilePath), $requireStatement)) {
            $filesystem->append($webFilePath, $requireStatement);
        }

        //move controllers to app/Http/Controllers
        $filesystem->copyDirectory(__DIR__.'/Http/Controllers', app_path('Http/Controllers'));

    }

    public function boot(Filesystem $filesystem): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish the controllers
        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], 'loginx-controllers');

        // Publish the seeders
        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'seeders');

        $this->publishes([
            __DIR__.'/../database/seeders/LoginxSeeder.php' => database_path('seeders/LoginxSeeder.php'),
        ], 'seeders');

        // Load Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'loginx');

        $this->createLoginxTables();

    }

    public function createUserColumns(): void
    {
        if (!Schema::hasColumn('users', 'blocked_at')) {
            Schema::table('users', function ($table) {
                $table->timestamp('blocked_at')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'blocked_until')) {
            Schema::table('users', function ($table) {
                $table->timestamp('blocked_until')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'blocked_reason')) {
            Schema::table('users', function ($table) {
                $table->string('blocked_reason')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'blocked_by_id')) {
            Schema::table('users', function ($table) {
                $table->foreignId('blocked_by_id')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function ($table) {
                $table->string('username')->nullable();
            });
        }
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function ($table) {
                $table->string('phone')->nullable();
            });
        }
    }

    public function createLoginxTables(): void
    {
        // If Settings table is not created
        if (!Schema::hasTable('loginx_settings')) {
            Artisan::call('migrate',
                ['--path' => 'vendor/dorukyy/loginx/database/migrations/2024_08_26_100000_create_settings_table.php']);
        }

        // If Settings are not seeded
        if (Schema::hasTable('loginx_settings') && DB::table('loginx_settings')->count() == 0) {
            Artisan::call('db:seed', ['--class' => LoginxSeeder::class]);
        }

    }
}
