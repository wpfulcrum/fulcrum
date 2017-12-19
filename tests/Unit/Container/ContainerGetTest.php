<?php

namespace Fulcrum\Tests\Unit\Container;

use Brain\Monkey\Functions;
use Fulcrum\Container\DIContainer;
use Fulcrum\Tests\Unit\UnitTestCase;

class ContainerGetTest extends UnitTestCase
{
    public function testGet()
    {
        $initialParameters = [
            'foo' => 'Hello World',
            'bar' => 10,
            'baz' => 'Fulcrum',
        ];
        $container         = new DIContainer($initialParameters);

        foreach ($initialParameters as $uniqueId => $value) {
            $this->assertEquals($value, $container->get($uniqueId));
        }
    }

    public function testShouldGetByDotNotation()
    {
        $container = new DIContainer([
            'foo' => [
                'bar' => [
                    'baz' => 'Hello World',
                ],
                'baz' => 'Fulcrum',
            ],
        ]);

        $this->assertEquals('Fulcrum', $container->get('foo', 'baz'));
        $this->assertEquals([
            'baz' => 'Hello World',
        ], $container->get('foo', 'bar'));
        $this->assertEquals('Hello World', $container->get('foo', 'bar.baz'));
    }

    public function testShouldGetByDotNotationWithIntKeys()
    {
        $data = [
            'foo' => [
                '249' => [
                    'baz' => 'Hello World',
                ],
                '300' => 'Fulcrum',
            ],
        ];
        $container = new DIContainer($data);

        $this->assertEquals(['baz' => 'Hello World'], $container->get('foo', 249));
        $this->assertEquals('Hello World', $container->get('foo', '249.baz'));
        $this->assertEquals('Fulcrum', $container->get('foo', 300));
    }

    public function testShouldReturnNullWhenItemKeyDoesNotExist()
    {
        $data = [
            'foo' => [
                '249' => [
                    'baz' => 'Hello World',
                ],
                '300' => 'Fulcrum',
            ],
        ];
        $container = new DIContainer($data);

        $this->assertNull($container->get('foo', '249.doesnotexist'));
        $this->assertNull($container->get('foo', '300.baz'));
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
            $this->assertTrue($container->get('foo', [249]));
        } catch (\InvalidArgumentException $exception) {
            $errorMessage = sprintf($errorMessage, 'foo', ucfirst(gettype([249])), print_r([249], true));
            $this->assertEquals($errorMessage, $exception->getMessage());
        }
    }
}
