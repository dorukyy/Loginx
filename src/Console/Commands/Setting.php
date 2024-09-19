<?php

namespace dorukyy\loginx\Console\Commands;

use dorukyy\loginx\SettingsFacade;
use Illuminate\Console\Command;

class Setting extends Command
{
    protected $signature = 'loginx:setting {args*}';
    protected $description = 'Setting the Loginx package';
    protected array $args = [
        'disable' => [
            'mail-verification' => [
                'key' => 'IS_MAIL_ACTIVATION', 'value' => false, 'message' => 'Mail verification is disabled.'
            ],
            'ip-blocking' => ['key' => 'CHECK_IP_BLOCK', 'value' => false, 'message' => 'IP blocking is disabled.'],
            'timeout' => ['key' => 'TIMEOUT_ENABLED', 'value' => false, 'message' => 'Timeout is disabled.'],
        ],
        'enable' => [
            'mail-verification' => [
                'key' => 'IS_MAIL_ACTIVATION', 'value' => true, 'message' => 'Mail verification is enabled.'
            ],
            'ip-blocking' => ['key' => 'CHECK_IP_BLOCK', 'value' => true, 'message' => 'IP blocking is enabled.'],
            'timeout' => ['key' => 'TIMEOUT_ENABLED', 'value' => true, 'message' => 'Timeout is enabled.'],
        ],
        'timeout' => [
            'set' => ['key' => 'TIMEOUT_DURATION', 'message' => 'Timeout is set to __duration__ seconds.']
        ],

    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $args = $this->argument('args');
        $args = array_map('strtolower', $args);
        $args = array_map('trim', $args);
        $args = array_filter($args);
        $args = array_unique($args);

        if (empty($args)) {
            $this->info('No arguments provided.');
            return;
        }
        if (count($args) === 2) {
            if (isset($this->args[$args[0]][$args[1]])) {
                $this->setSetting($this->args[$args[0]][$args[1]]['key'], $this->args[$args[0]][$args[1]]['value']);
                $this->info($this->args[$args[0]][$args[1]]['message']);
            } else {
                $this->info('Invalid arguments provided.');
            }
        }
        if (count($args) === 3) {
            if (isset($this->args[$args[0]])) {
                $this->setSetting($this->args[$args[0]][$args[1]]['key'], $args[2]);
                $this->info(str_replace('__duration__', $args[2], $this->args[$args[0]][$args[1]]['message']));
            } else {
                $this->info('Invalid arguments provided.');
            }
        }

    }

    private function setSetting($key, $value): void
    {
        SettingsFacade::set($key, $value);
        $this->info('Setting updated successfully.');
    }

}
