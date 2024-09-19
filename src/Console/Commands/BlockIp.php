<?php

namespace dorukyy\loginx\Console\Commands;

use dorukyy\loginx\Models\BlockedIp;
use Illuminate\Console\Command;

class BlockIp extends Command
{
    protected $signature = 'loginx:block-ip {ip}';
    protected $description = 'Block an IP address';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $ip = $this->argument('ip');

        if (BlockedIp::where('ip', $ip)->exists()) {
            $this->error($ip . ' is already blocked.');
            return;
        }else{
            BlockedIp::create(['ip' => $ip]);
        }

        $this->info($ip . ' is blocked successfully.');
    }

}
