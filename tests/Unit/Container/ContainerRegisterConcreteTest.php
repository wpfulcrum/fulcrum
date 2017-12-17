<?php

namespace Fulcrum\Tests\Unit\Container;

use Brain\Monkey\Functions;
use Fulcrum\Container\Exception\InvalidConcreteException;
use Fulcrum\Container\DIContainer;
use Fulcrum\Tests\Unit\UnitTestCase;

class ContainerRegisterConcreteTest extends UnitTestCase
{
    public function testThrowsErrorForEmptyConcrete()
    {
        $container = new DIContainer();

        $errorMessage = 'Invalid concrete configuration. The "concrete" cannot be empty.';
        Functions\when('__')
            ->justEcho($errorMessage);

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([], 'foo');
        $this->assertFalse($container->has('foo'));

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([
            'concrete' => '',
        ], 'foo');
        $this->assertFalse($container->has('foo'));

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([
            'autoload' => false,
            'concrete' => null,
        ], 'foo');
        $this->assertFalse($container->has('foo'));

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([
            'autoload' => false,
            'concrete' => false,
        ], 'foo');
        $this->assertFalse($container->has('foo'));
    }

    public function testThrowsErrorWhenConcreteIsNotCallable()
    {
        $container = new DIContainer();

        $errorMessage = 'The specified concrete is not callable';
        Functions\when('__')
            ->justEcho($errorMessage);

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([
            'concrete' => 'thisisnotcallablefunction',
        ], 'foo');
        $this->assertFalse($container->has('foo'));

        $this->expectException(InvalidConcreteException::class);
        $this->expectOutputString($errorMessage);
        $container->registerConcrete([
            'concrete' => 42,
        ], 'foo');
        $this->assertFalse($container->has('foo'));
    }

    public function testConcreteShouldNotAutoloaded()
    {
        $container = new DIContainer();

        $concreteConfig = [
            'autoload' => false,
            'concrete' => function () {
                return (object) ['bar' => 'some value'];
            },
        ];

        $this->assertNull($container->registerConcrete($concreteConfig, 'foo'));
        $this->assertTrue($container->has('foo'));
    }

    public function testShouldInstantiateOutOfContainer()
    {
        $container = new DIContainer();

        $concreteConfig = [
            'autoload' => false,
            'concrete' => function () {
                return (object) ['bar' => 'some value'];
            },
        ];

        $this->assertNull($container->registerConcrete($concreteConfig, 'foo'));
        $this->assertTrue($container->has('foo'));

        // Now instantiate it.
        $foo = $container['foo'];
        $this->assertInstanceOf('stdClass', $foo);
        $this->assertEquals('some value', $foo->bar);
    }

    public function testConcreteShouldAutoload()
    {
        $container = new DIContainer();

        $concreteConfig = [
            'autoload' => true,
            'concrete' => function () {
                $instance      = new \stdClass();
                $instance->bar = 'some value';

                return $instance;
            },
        ];

        $foo = $container->registerConcrete($concreteConfig, 'foo');

        $this->assertTrue($container->has('foo'));
        $this->assertInstanceOf('stdClass', $foo);
        $this->assertEquals('some value', $foo->bar);
    }
}
