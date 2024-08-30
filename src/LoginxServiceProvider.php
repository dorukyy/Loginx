<?php

namespace dorukyy\loginx;

use dorukyy\loginx\database\seeders\LoginxSeeder;
use dorukyy\loginx\Models\Setting;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LoginxServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */

    public function register(): void
    {
        $this->app->bind('loginx', function () {
            return new LoginxFacade;
        });


        $filesystem = $this->app->make(Filesystem::class);

        $authFilePath = base_path('routes/auth.php');
        $webFilePath = base_path('routes/web.php');
        $requireStatement = "\nrequire base_path('routes/auth.php');";

        $filesystem->put($authFilePath, $filesystem->get(__DIR__.'/../routes/web.php'));

        // Append the import statement for auth.php to the project's routes/web.php if it doesn't already exist
        if (!str_contains($filesystem->get($webFilePath), $requireStatement)) {
            $filesystem->append($webFilePath, $requireStatement);
        }

        $this->handleUserModelAndMigration($filesystem);
        //move controllers to app/Http/Controllers
        $filesystem->copyDirectory(__DIR__.'/Http/Controllers', app_path('Http/Controllers/Auth'));


        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'loginx'
        );

    }

    public function boot(Filesystem $filesystem): void
    {

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //If any of the migrations exist in the project have 'password_resets' table, delete it
        $migrations = collect($filesystem->files(database_path('migrations')))->filter(function ($file) {
            return str_contains($file->getFilename(), 'password_resets');
        });

        foreach ($migrations as $migration) {
            $filesystem->delete($migration);
        }

        //run the migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');


        // Publish the controllers
        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], 'loginx-controllers');

        // Publish the seeders
        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'seeders');


        // Publish the seeders
        $this->publishes([
            __DIR__.'/../database/seeders/LoginxSeeder.php' => database_path('seeders/LoginxSeeder.php'),
        ], 'seeders');

        // Load Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'loginx');

        // Publish the views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/loginx'),
        ], 'loginx-views');

        //publish css
        $this->publishes([
            __DIR__.'/../resources/css' => public_path('css'),
        ], 'loginx-css');

        //get all settings from consts.php and create if they are not exist
        $this->createLoginxTables();

        $this->addLoginxSeederToGlobalSeeder($filesystem);

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
        $migrations = [
            '2024_08_26_100000_create_blocked_ips_table.php',
            '2024_08_26_100000_create_blocked_mail_providers_table.php',
            '2024_08_26_100000_create_countries_table.php',
            '2024_08_26_100000_create_failed_logins_table.php',
            '2024_08_26_100000_create_logins_table.php',
            '2024_08_26_100000_create_mail_activation_tokens_table.php',
            '2024_08_26_100000_create_password_reset_tokens_table.php',
            '2024_08_26_100000_create_permissions_table.php',
            '2024_08_26_100000_create_register_requests_table.php',
            '2024_08_26_100000_create_reset_password_requests_table.php',
            '2024_08_26_100000_create_roles_table.php',
            '2024_08_26_100000_create_settings_table.php',
            '2024_08_26_100000_create_timeouts_table.php',
            '2024_08_26_100000_create_timezones_table.php',
            '2024_08_26_100000_create_users_permissions_table.php',
            '2024_08_26_100000_create_users_roles_table.php',
        ];

        foreach ($migrations as $migration) {
            if (!Schema::hasTable($migration)) {
                Artisan::call('migrate',
                    ['--path' => 'vendor/dorukyy/loginx/database/migrations/'.$migration]);
            }
        }

    }

    private function handleUserModelAndMigration(Filesystem $filesystem): void
    {
        $filesystem->copy(__DIR__.'/Models/User.php', app_path('Models/User.php'));
        //find the user migration of the project
        $userMigration = collect($filesystem->files(database_path('migrations')))->filter(function ($file) {
            return str_contains($file->getFilename(), 'create_users_table');
        })->first();
        //overwrite the user migration
        $filesystem->copy(__DIR__.'/../database/migrations_to_move/2024_08_26_100000_create_users_table.php',
            $userMigration);

        //move factories to database/factories
        $filesystem->copyDirectory(__DIR__.'/database/factories_to_move', database_path('factories'));
    }

    // Add the LoginxSeeder to the DatabaseSeeder without using str_replace
    public function addLoginxSeederToGlobalSeeder(Filesystem $filesystem): void
    {
        $globalSeeder = database_path('seeders/DatabaseSeeder.php');
        $content = $filesystem->get($globalSeeder);
        $code = "\t\t\$loginxSeeder = new LoginxSeeder();\n\t\t\$loginxSeeder->run();";
        $importStatement = "use dorukyy\\loginx\\database\\seeders\\LoginxSeeder;";

        // Check if the LoginxSeeder is already present
        if (!str_contains($content, 'LoginxSeeder')) {
            // Find the position of the first '{' after 'public function run()'
            $position = strpos($content, '{', strpos($content, 'public function run()'));
            if ($position !== false) {
                // Insert the code after the '{' character, followed by a blank line
                $content = substr($content, 0, $position + 1).PHP_EOL.PHP_EOL.$code.substr($content, $position + 1);
            }
        }

        // Check if the import statement is already present
        if (!str_contains($content, $importStatement)) {
            // Ensure the namespace declaration is at the top
            if (!str_contains($content, 'namespace Database\\Seeders;')) {
                $content = "<?php\n\nnamespace Database\\Seeders;\n\n".$importStatement.PHP_EOL.substr($content, 6);
            } else {
                // Add the import statement after the namespace declaration
                $namespacePosition = strpos($content,
                        'namespace Database\\Seeders;') + strlen('namespace Database\\Seeders;');
                $content = substr($content, 0, $namespacePosition).PHP_EOL.PHP_EOL.$importStatement.substr($content,
                        $namespacePosition);
            }
        }

        $filesystem->put($globalSeeder, $content);
    }

}
