<?php

namespace Modularity;

use Modularity\Upgrade;
use WP_CLI;

class WpCli {
    public function __construct()
    {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('modularity', Upgrade::class);
        }
    }
}