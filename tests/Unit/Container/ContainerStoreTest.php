<?php

namespace Fulcrum\Tests\Unit\Container;

use Brain\Monkey\Functions;
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

        $this->assertEquals(10, $container->get('foo', 'baz'));
        $container->store('foo', 47, 'baz');
        $this->assertEquals(47, $container->get('foo', 'baz'));

        $this->assertEquals('Hi there', $container->get('foo', 'bar.baz'));
        $container->store('foo', 'Hello World', 'bar.baz');
        $this->assertEquals('Hello World', $container->get('foo', 'bar.baz'));
    }

    public function testShouldDeeplyStoreWhenDotNotationWithInt()
    {
        $container = new DIContainer([
            'foo' => [
                '249' => ['baz' => 'Hello World'],
                '300' => 'Fulcrum',
            ],
        ]);

        $this->assertEquals('Fulcrum', $container->get('foo', 300));
        $container->store('foo', 47, 300);
        $this->assertEquals(47, $container->get('foo', 300));

        $this->assertEquals('Hello World', $container->get('foo', '249.baz'));
        $container->store('foo', 'Heya', '249.baz');
        $this->assertEquals('Heya', $container->get('foo', '249.baz'));
    }

    public function testShouldDeeplyStoreWhenDoesNotExist()
    {
        $container = new DIContainer();

        $container->store('foo', 47, 'baz');
        $this->assertEquals(47, $container->get('foo', 'baz'));
        $container->store('foo', 19, 'baz');
        $this->assertEquals(19, $container->get('foo', 'baz'));

        $container->store('foo', 'Hello World', 'bar.baz');
        $this->assertEquals('Hello World', $container->get('foo', 'bar.baz'));
        $container->store('foo', 'Changing it', 'bar.baz');
        $this->assertEquals('Changing it', $container->get('foo', 'bar.baz'));

        $container->store('fooint', 47, 300);
        $this->assertEquals(47, $container->get('fooint', 300));

        $container->store('fooint', 'Heya', '249.baz');
        $this->assertEquals('Heya', $container->get('fooint', '249.baz'));
    }

    public function testShouldThrowErrorWhenItemKeysInvalidType()
    {
        $errorMessage = 'The item key(s), given for "%s" unique ID, is(are) an invalid data type. '.
                        'String or integer are required. %s given: %s';
        Functions\when('__')
            ->justReturn($errorMessage);

        $container = new DIContainer([
            'foo' => [
                '249' => [
                    'baz' => 'Hello World',
                ],
                '300' => 'Fulcrum',
            ],
        ]);

        try {
            $this->assertTrue($container->store('foo', 'some value', [249]));
        } catch (\InvalidArgumentException $exception) {
            $errorMessage = sprintf($errorMessage, 'foo', ucfirst(gettype([249])), print_r([249], true));
            $this->assertEquals($errorMessage, $exception->getMessage());
        }
    }
}
