<?php

namespace Fulcrum\Tests\Unit\Foundation\Stubs;

use Fulcrum\Foundation\ServiceProvider\Provider;

class BadProviderStub extends Provider
{
    protected $hasDefaults = true;
    protected $defaultsLocation = 'doesnotexist/bad-filename.php';

    public function register(array $concreteConfig, $uniqueId)
    {
        // nothing here
    }

    public function getConcrete(array $config, $uniqueId = '')
    {
        // nothing here
    }

    public static function getDefaultsPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'doesnotexist/bad-filename.php';
    }
}
