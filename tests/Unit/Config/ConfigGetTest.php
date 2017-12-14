<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigGetTest extends ConfigTestCase
{
    public function testGetShouldReturnValue()
    {
        $config = new Config($this->testArray);
        $this->assertEquals('WordPress', $config->get('foo.platform'));
        $this->assertEquals('Beans', $config->get('foo.theme'));
        $this->assertNull($config->get('foo.site'));
        $this->assertEquals('Tonya', $config->get('bar.baz.who'));
        $this->assertEquals(300, $config->get('bar.baz.someNumber'));

        $config = new Config($this->testArray, $this->defaults);
        $this->assertTrue($config->get('isEnabled'));
        $this->assertEquals([], $config->get('bar.baz.oof'));
        $this->assertEquals('wpfulcrum', $config->get('foo.site'));
    }

    public function testGetShouldReturnTheDefault()
    {
        $config = new Config($this->testArray);

        $this->assertNull($config->get('doesnotexist'));
        $this->assertFalse($config->get('doesnotexist', false));
        $this->assertEquals(10, $config->get('doesnotexist', 10));
        $this->assertEquals([], $config->get('doesnotexist', []));
    }

    public function testShouldReturnValuesForMany()
    {
        $config = new Config($this->testArray);

        $this->assertEquals([
            'foo.platform' => 'WordPress',
            'foo.theme'    => 'Beans',
        ], $config->get(['foo.platform' => '', 'foo.theme' => '']));

        $this->assertEquals([
            'foo.platform' => 'WordPress',
            'bar.baz'      => [
                'who'        => 'Tonya',
                'someNumber' => 300,
            ],
        ], $config->get(['foo.platform' => '', 'bar.baz' => '']));
    }
}
