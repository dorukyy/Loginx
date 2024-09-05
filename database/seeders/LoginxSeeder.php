<?php

namespace dorukyy\loginx\database\seeders;

use dorukyy\loginx\Enums\Country;
use dorukyy\loginx\Enums\Timezone;
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
        $this->seedSettings();
        $this->seedBlockedMailProviders();
//        $this->seedTimeZones();
        $this->seedCountries();
    }

    /**
     * Seed Blocked Mail Providers list
     * @return void
     */
    private function seedBlockedMailProviders(): void
    {
        $mailProviders = json_decode(file_get_contents(__DIR__.'/../../config/blocked_mail_providers.json'), true);
        foreach ($mailProviders["providers"] as $mailProvider) {
            BlockedMailProvider::create([
                'url' => $mailProvider
            ]);
        }
    }

    /**
     * Seed Timezones
     * @return void
     */
    private function seedTimeZones(): void
    {
        $timeZones = Timezone::data();
        foreach ($timeZones as $timeZone) {
            \dorukyy\loginx\Models\Timezone::create([
                'label' => $timeZone['label'],
                'tz_code' => $timeZone['tzCode'],
                'name' => $timeZone['name'],
                'utc' => $timeZone['utc']
            ]);
        }

    }

    /**
     * Seed Countries
     * @return void
     */
    private function seedCountries(): void
    {
        $countries = Country::data();
        foreach ($countries as $country) {
            \dorukyy\loginx\Models\Country::create($country);
        }
    }

    /**
     * Seed Loginx Settings from config/consts.php
     * @return void
     */
    private function seedSettings(): void
    {
        $settings = include(__DIR__.'/../../config/consts.php');
        foreach ($settings as $key => $value) {
            DB::table('loginx_settings')->insert([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }

}
