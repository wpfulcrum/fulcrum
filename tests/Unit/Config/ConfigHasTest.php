<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigHasTest extends ConfigTestCase
{
    public function testDoesNotHaveItem()
    {
        $config = new Config($this->testArray);

        $this->assertFalse($config->has('oof'));
        $this->assertFalse($config->has('baz'));
        $this->assertFalse($config->has('bar.baz.who.foobar'));

        // These are loaded from the defaults.
        $this->assertFalse($config->has('isEnabled'));
        $this->assertFalse($config->has('bar.baz.oof'));
        $this->assertFalse($config->has('foo.site'));
    }

    public function testHasItem()
    {
        $config = new Config($this->testArray);

        $this->assertTrue($config->has('foo'));
        $this->assertTrue($config->has('foo.platform'));
        $this->assertTrue($config->has('bar'));
        $this->assertTrue($config->has('bar.baz.who'));
        $this->assertTrue($config->has('bar.baz.someNumber'));
    }

    public function testHasItemWhenDefaultsGiven()
    {
        $config = new Config($this->testArray, $this->defaults);

        // These are loaded from testArray.
        $this->assertTrue($config->has('foo'));
        $this->assertTrue($config->has('foo.platform'));
        $this->assertTrue($config->has('bar'));
        $this->assertTrue($config->has('bar.baz.who'));
        $this->assertTrue($config->has('bar.baz.someNumber'));

        // These are loaded from the defaults.
        $this->assertTrue($config->has('isEnabled'));
        $this->assertTrue($config->has('bar.baz.oof'));
        $this->assertTrue($config->has('foo.site'));
    }
}
