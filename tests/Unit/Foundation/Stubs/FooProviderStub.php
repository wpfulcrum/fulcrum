<?php

namespace Fulcrum\Tests\Unit\Foundation\Stubs;

use Fulcrum\Foundation\ServiceProvider\Provider;

class FooProviderStub extends Provider
{
    protected $hasDefaults = true;
    protected $defaultsLocation = 'fixtures/foo-defaults.php';
    public $skipQueue = false;

    public function getConcrete(array $config, $uniqueId = '')
    {
        return [
            'autoload' => $config['autoload'],
            'concrete' => function () use ($config) {
                return new ConcreteStub(
                    $this->createConfig($config['config'])
                );
            },
        ];
    }

    protected function getConcreteDefaultStructure()
    {
        return [
            'autoload' => false,
            'config'   => '',
            'foobar'   => [
                'bar' => 'baz',
            ],
        ];
    }
}
