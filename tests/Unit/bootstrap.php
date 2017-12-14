<?php

namespace Fulcrum\Tests\Unit\Custom\PostType;

if (version_compare(phpversion(), '5.6.0', '<')) {
    die('Whoops, PHP 5.6 or higher is required.');
}

define('FULCRUM_TESTS_DIR', __DIR__);
define('FULCRUM_ROOT_DIR', dirname(dirname(FULCRUM_TESTS_DIR)) . DIRECTORY_SEPARATOR);

require_once FULCRUM_TESTS_DIR . DIRECTORY_SEPARATOR .'UnitTestCase.php';

/**
 * Time to load Composer's autoloader.
 */
$vendorPath = FULCRUM_ROOT_DIR . 'vendor' . DIRECTORY_SEPARATOR;
if (!file_exists($vendorPath . 'autoload.php')) {
    die('Whoops, we need Composer before we start running tests.  Please type: `composer install`.');
}
require_once $vendorPath . 'autoload.php';
unset($vendorPath);
