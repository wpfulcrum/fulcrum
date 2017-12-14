<?php

namespace Fulcrum\Tests\Unit\Foundation\ServiceProvider;

use Brain\Monkey\Functions;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Tests\Unit\Foundation\Stubs\ConcreteStub;
use Fulcrum\Tests\Unit\Foundation\Stubs\FooProviderStub;
use Fulcrum\Tests\Unit\UnitTestCase;
use Mockery;

class ServiceProviderRegisterTest extends UnitTestCase
{
    protected $fulcrumMock;
    protected static $config = [
        'autoload' => false,
        'config'   => [
            'foo' => 'bar',
        ],
    ];

    protected function setUp()
    {
        parent::setUp();
        Functions\when('__')->justReturn('');
        $this->fulcrumMock = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function testShouldQueueConcrete()
    {
        $stub     = new FooProviderStub($this->fulcrumMock);
        $concrete = $stub->register(self::$config, 'foo');

        $this->assertTrue(is_array($concrete));
        $this->assertFalse($concrete['autoload']);
        $this->assertTrue(is_callable($concrete['concrete']));
        $this->assertSame($concrete, $stub->queued['foo']);
    }

    public function testShouldRegisterConcreteAndReturnNull()
    {
        $stub            = new FooProviderStub($this->fulcrumMock);
        $stub->skipQueue = true;

        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn(null);

        $concrete = $stub->register(self::$config, 'foo');

        $this->assertNull($concrete);
        $this->assertContains('foo', $stub->uniqueIds);
    }

    public function testShouldRegisterConcreteAndReturnIt()
    {
        $stub               = new FooProviderStub($this->fulcrumMock);
        $stub->skipQueue    = true;
        $config             = self::$config;
        $config['autoload'] = true;
        $expected           = new ConcreteStub(
            ConfigFactory::create($config['config'])
        );

        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($expected);

        $concrete = $stub->register($config, 'foo');

        $this->assertInstanceOf(ConcreteStub::class, $concrete);
        $this->assertSame($expected, $concrete);
        $this->assertEquals('bar', $concrete->config->foo);
    }
}
