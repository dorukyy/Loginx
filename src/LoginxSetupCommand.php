<?php

namespace dorukyy\loginx;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LoginxSetupCommand extends Command
{
    protected $signature = 'loginx:setup';
    protected $description = 'Setup the Loginx package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Filesystem $filesystem): void
    {
        $this->info('Setting up Loginx package...');

        // Call the methods from LoginxServiceProvider
        $serviceProvider = new LoginxServiceProvider(app());

        try {
            $serviceProvider->setControllers($filesystem);
            $this->info('Controllers set successfully.');

            $serviceProvider->getMigrationsConflictsAndDelete($filesystem);
            $this->info('Migration conflicts resolved.');

            $serviceProvider->publishAll();
            $this->info('All resources published successfully.');

            $serviceProvider->createLoginxTables();
            $this->info('Loginx tables created successfully.');

            $serviceProvider->addLoginxSeederToGlobalSeeder($filesystem);
            $this->info('Loginx seeder added to global seeder.');

        } catch (\Exception $e) {
            $this->error('Error during setup: ' . $e->getMessage());
        }

        $this->info('Loginx package setup completed.');
    }
}
