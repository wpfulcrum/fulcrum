<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigRemoveTest extends ConfigTestCase
{
    public function testShouldUnsetParameter()
    {
        $config = new Config($this->testArray, $this->defaults);

        $this->assertTrue($config->has('foo.theme'));
        $config->remove('foo.theme');
        $this->assertFalse($config->has('foo.theme'));

        $this->assertTrue($config->has('bar.baz.who'));
        $config->remove('bar.baz.who');
        $this->assertFalse($config->has('bar.baz.who'));

        $this->assertTrue($config->has('array'));
        $config->remove('array');
        $this->assertFalse($config->has('array'));
    }
}
