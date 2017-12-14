<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;
use Fulcrum\Config\ConfigFactory;

class ConfigFactoryTest extends ConfigTestCase
{
    public function testCreateWhenGivenAnArray()
    {
        $this->assertInstanceOf(Config::class, ConfigFactory::create($this->testArray));

        $this->assertInstanceOf(Config::class, ConfigFactory::create($this->testArray, $this->defaults));
    }

    public function testCreateWhenGivenFilePath()
    {
        $this->assertInstanceOf(Config::class, ConfigFactory::create($this->testArrayPath));

        $this->assertInstanceOf(Config::class, ConfigFactory::create($this->testArrayPath, $this->defaultsPath));
    }

    public function testShouldReturnLoadConfig()
    {
        $actual = ConfigFactory::loadConfigFile($this->testArrayPath);
        $this->assertArrayHasKey('foo', $actual);
        $this->assertArrayHasKey('bar', $actual);
        $this->assertEquals([
            'aaa',
            'bbb',
        ], $actual['array']);
    }
}
