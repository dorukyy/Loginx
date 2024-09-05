<?php

namespace dorukyy\loginx;

use dorukyy\loginx\database\seeders\LoginxSeeder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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


        //move controllers to app/Http/Controllers
        $this->setControllers($filesystem);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'loginx');


        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'loginx'
        );
    }

    //create a command to set the package up
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LoginxSetupCommand::class,
            ]);
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

    /**
     * @throws FileNotFoundException
     */
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
                // Insert the code
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

    public function getMigrationsConflictsAndDelete(Filesystem $filesystem): void
    {
        $migrationNames = [
            'create_users_table',
            'create_password_reset_tokens_table',
            'create_mail_activation_tokens_table',
            'create_blocked_ips_table',
            'create_blocked_mail_providers_table',
            'create_countries_table',
            'create_failed_logins_table',
            'create_logins_table',
        ];

        foreach ($migrationNames as $migrationName) {
            if (collect($filesystem->files(database_path('migrations')))->contains('create_'.$migrationName.'.php')) {
                $fullName = collect($filesystem->files(database_path('migrations')))->where('name',
                    'create_'.$migrationName.'.php')->first();
                $filesystem->delete($fullName);
            }
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function setControllers(Filesystem $filesystem): void
    {
        //if controller.php exists in app/Http/Controllers, delete it
        if ($filesystem->exists(app_path('Http/Controllers/Controller.php'))) {
            $filesystem->delete(app_path('Http/Controllers/Controller.php'));
        }
        $filesystem->copyDirectory(__DIR__.'/Http/Controllers', app_path('Http/Controllers'));

        $requireStatement = "\nrequire base_path('routes/auth.php');";

        $filesystem->put(base_path('routes/auth.php'), $filesystem->get(__DIR__.'/../routes/web.php'));

        // Append the import statement for auth.php to the project's routes/web.php if it doesn't already exist
        if (!str_contains($filesystem->get(base_path('routes/web.php')), $requireStatement)) {
            $filesystem->append(base_path('routes/web.php'), $requireStatement);
        }

    }

    public function publishAll(): void
    {
        //Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Publish the controllers
        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], 'loginx-controllers');

        // Publish the views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/loginx'),
        ], 'loginx-views');

        //publish css
        $this->publishes([
            __DIR__.'/../resources/css' => public_path('css'),
        ], 'loginx-css');

        // Publish the seeders
        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'seeders');


        // Publish the LoginxSeeder
        $this->publishes([
            __DIR__.'/../database/seeders/LoginxSeeder.php' => database_path('seeders/LoginxSeeder.php'),
        ], 'seeders');
    }
}
