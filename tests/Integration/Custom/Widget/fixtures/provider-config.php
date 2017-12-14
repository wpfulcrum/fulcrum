<?php

use Fulcrum\Config\ConfigFactory;

return [
    'Fulcrum\Tests\Integration\Custom\Widget\Stubs\FooWidget' => [
        'autoload' => false,
        'concrete' => function () {
            return ConfigFactory::create(__DIR__ . '/foo-widget-config.php');
        },
    ],
];
