<?php

namespace Modularity;

use Modularity\Upgrade;
use WP_CLI;

class WpCli {
    public function __construct()
    {
        add_action('cli_init', function () {
            if (defined('WP_CLI') && WP_CLI) {
                if (function_exists('acf')) {
                    WP_CLI::add_command('modularity', Upgrade::class);
                } else {
                    WP_CLI::error('ACF is not available.');
                }
            }
        });
    }
}