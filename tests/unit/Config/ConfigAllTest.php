<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigAllTest extends ConfigTestCase
{
    public function testShouldReturnAllItems()
    {
        $config = new Config($this->testArray);
        $this->assertEquals($this->testArray, $config->all());

        $config = new Config($this->defaults);
        $this->assertEquals($this->defaults, $config->all());

        $config = new Config($this->testArray, $this->defaults);
        $this->assertArrayHasKey('foo', $config->all());
        $this->assertArrayHasKey('bar', $config->all());
        $this->assertArrayHasKey('isEnabled', $config->all());
    }
}
