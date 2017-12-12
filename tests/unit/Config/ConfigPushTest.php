<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigPushTest extends ConfigTestCase
{
    public function testShouldPushNewElements()
    {
        $config = new Config($this->testArray);

        $array = $config->array;
        $config->push('array', 'abcd');

        $this->assertNotEquals($array, $config->array);
        $array[] = 'abcd';
        $this->assertEquals($array, $config->array);
        $this->assertEquals('abcd', $config->array[2]);
        $this->assertEquals('abcd', $config->get('array.2'));


        $array = $config->foo;
        $config->push('foo', 'xyz');

        $this->assertNotEquals($array, $config->foo);
        $array[] = 'xyz';
        $this->assertEquals($array, $config->foo);
        $this->assertEquals('xyz', $config->foo[0]);
        $this->assertEquals('xyz', $config->get('foo.0'));
    }
}
