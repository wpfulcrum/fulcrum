<?php

namespace Fulcrum\Extender;

if (!defined('FULCRUM_UNIT_TESTS_RUNNING')) {
    autoloadWPFiles();
}

/**
 * Autoload the WP files.
 *
 * @since 3.1.6
 *
 * @return void
 */
function autoloadWPFiles()
{
    require_once __DIR__.'/WP/api.php';
    require_once __DIR__.'/WP/helpers.php';
}
