<?php

namespace Fulcrum\Tests\Unit\Container;

use Brain\Monkey\Functions;
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

    public function testContainerShouldNotFindValues()
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

    public function testShouldReturnUniqueIdFindingWhenInvalidItemKeys()
    {
        $container = new DIContainer([
            'foo' => [
                'bar' => [
                    'baz' => 'Hello World',
                ],
                'baz' => 'Fulcrum',
            ],
        ]);

        // Test for Issue #1.
        $this->assertFalse($container->has('bar', 'baz'));
        $this->assertFalse($container->has('bar', 'bar.baz'));
        $this->assertFalse($container->has('doesnotexist', 'bar.baz'));
    }

    public function testShouldAllowIntegerItemKeys()
    {
        $container = new DIContainer([
            'foo' => [
                '249' => [
                    'baz' => 'Hello World',
                ],
                '300' => 'Fulcrum',
            ],
        ]);

        $this->assertTrue($container->has('foo', '249'));
        $this->assertTrue($container->has('foo', 249));
        $this->assertTrue($container->has('foo', '249.baz'));
        $this->assertTrue($container->has('foo', 300));
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
            $this->assertTrue($container->has('foo', [249]));
        } catch (\InvalidArgumentException $exception) {
            $errorMessage = sprintf($errorMessage, 'foo', ucfirst(gettype([249])), print_r([249], true));
            $this->assertEquals($errorMessage, $exception->getMessage());
        }
    }
}
