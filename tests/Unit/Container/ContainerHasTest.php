<?php

namespace Fulcrum\Tests\Unit\Container;

use Fulcrum\Container\DIContainer;
use Fulcrum\Tests\Unit\UnitTestCase;

class ContainerHasTest extends UnitTestCase
{
    public function testHas()
    {
        $initialParameters = [
            'foo' => 'Hello World',
            'bar' => 10,
            'baz' => 'Fulcrum',
        ];

        $container = new DIContainer($initialParameters);

        $this->assertTrue($container->has('foo'));
        $this->assertTrue($container->has('bar'));
        $this->assertTrue($container->has('baz'));
    }

    public function testContainerShouldHaveAfterAddingValues()
    {
        $container = new DIContainer();

        $container['fulcrum'] = 'some value';
        $this->assertTrue($container->has('fulcrum'));

        $container['some_number'] = 52;
        $this->assertTrue($container->has('some_number'));

        $container['some_array'] = [
            'foo' => 'foobar',
        ];
        $this->assertTrue($container->has('some_array'));
    }

    public function testContainerShouldNotValues()
    {
        $initialParameters = [
            'foo' => 'Hello World',
            'bar' => 10,
            'baz' => 'Fulcrum',
        ];

        $container = new DIContainer($initialParameters);

        $this->assertFalse($container->has('foobar'));
        $this->assertFalse($container->has('barbaz'));
        $this->assertFalse($container->has('zab'));

        $container['fulcrum']     = 'some value';
        $container['some_number'] = 52;
        $container['some_array']  = [
            'foo' => 'foobar',
        ];

        $this->assertFalse($container->has('doesnotexist'));
    }

    public function testShouldCheckDeeply()
    {
        $container = new DIContainer([
            'foo' => [
                'bar' => [
                    'baz' => 'Hello World',
                ],
                'baz' => 'Fulcrum',
            ],
        ]);

        $this->assertTrue($container->has('foo', 'baz'));
        $this->assertTrue($container->has('foo', 'bar'));
        $this->assertTrue($container->has('foo', 'bar.baz'));
    }
}
