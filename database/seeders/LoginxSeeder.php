<?php

namespace dorukyy\loginx\database\seeders;

use dorukyy\loginx\Models\BlockedMailProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $settings = include(__DIR__.'/../../config/consts.php');
        foreach ($settings as $key => $value) {
            DB::table('loginx_settings')->insert([
                'key' => $key,
                'value' => $value,
            ]);
        }
        $this->seedBlockedMailProviders();
    }

    private function seedBlockedMailProviders(): void
    {
        $mailProviders = json_decode(file_get_contents(__DIR__.'/../../config/blocked_mail_providers.json'), true);
        foreach ($mailProviders["providers"] as $mailProvider) {
            BlockedMailProvider::create([
                'url' => $mailProvider
            ]);
        }
    }
}
