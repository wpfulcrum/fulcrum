<?php

namespace Fulcrum\Tests\Unit\Container;

use Fulcrum\Container\DIContainer;
use Fulcrum\Tests\Unit\UnitTestCase;

class ContainerStoreTest extends UnitTestCase
{
    public function testShouldStoreWhenNotDotNotation()
    {
        $container = new DIContainer();

        $container->store('foo', 'Hello World');
        $this->assertEquals('Hello World', $container->get('foo'));

        $container->store('bar', 'Fulcrum Rocks!');
        $this->assertEquals('Fulcrum Rocks!', $container->get('bar'));

        $container->store('foo.bar', 'This unique key has a dot.');
        $this->assertEquals('This unique key has a dot.', $container->get('foo.bar'));
    }

    public function testShouldDeeplyStoreWhenDotNotation()
    {
        $container = new DIContainer([
            'foo' => [
                'bar' => [
                    'baz' => 'Hi there',
                ],
                'baz' => 10,
            ],
        ]);

        $this->assertEquals(10, $container->get('foo')['baz']);
        $container->store('foo', 47, 'baz');
        $this->assertEquals(47, $container->get('foo')['baz']);

        $this->assertEquals('Hi there', $container->get('foo')['bar']['baz']);
        $container->store('foo', 'Hello World', 'bar.baz');
        $this->assertEquals('Hello World', $container->get('foo')['bar']['baz']);
    }

    public function testShouldDeeplyStoreWhenDoesNotExist()
    {
        $container = new DIContainer();

        $container->store('foo', 47, 'baz');
        $this->assertEquals(47, $container->get('foo')['baz']);
        $container->store('foo', 19, 'baz');
        $this->assertEquals(19, $container->get('foo')['baz']);

        $container->store('foo', 'Hello World', 'bar.baz');
        $this->assertEquals('Hello World', $container->get('foo')['bar']['baz']);
        $container->store('foo', 'Changing it', 'bar.baz');
        $this->assertEquals('Changing it', $container->get('foo')['bar']['baz']);
    }
}
