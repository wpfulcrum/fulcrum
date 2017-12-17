<?php

namespace Fulcrum\Tests\Unit\Container;

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
}
