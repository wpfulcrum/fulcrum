<?php

namespace Fulcrum\Tests\Unit\Container;

use Fulcrum\Container\DIContainer;
use Fulcrum\Tests\Unit\UnitTestCase;

class ContainerTest extends UnitTestCase
{
    public function testInitialParametersShouldLoad()
    {
        $initialParameters = [
            'foo' => 'Hello World',
            'bar' => 10,
            'baz' => 'Fulcrum',
        ];

        $container = new DIContainer($initialParameters);

        $this->assertEquals('Hello World', $container['foo']);
        $this->assertEquals(10, $container['bar']);
        $this->assertEquals('Fulcrum', $container['baz']);

        foreach ($initialParameters as $uniqueId => $value) {
            $this->assertEquals($value, $container[$uniqueId]);
        }
    }

    public function testAddingValuesToContainer()
    {
        $container = new DIContainer();

        $container['fulcrum'] = 'some value';
        $this->assertEquals('some value', $container['fulcrum']);

        $container['some_number'] = 52;
        $this->assertEquals(52, $container['some_number']);

        $container['some_array'] = [
            'foo' => 'foobar',
        ];
        $this->assertEquals([
            'foo' => 'foobar',
        ], $container['some_array']);
    }

    public function testAddingClosureToContainer()
    {
        $container = new DIContainer();

        $container['some_closure'] = function () {
            return new \stdClass();
        };

        $this->assertInstanceOf('stdClass', $container['some_closure']);
    }
}
