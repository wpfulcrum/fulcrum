<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigMergeTest extends ConfigTestCase
{
    public function testMergeShouldMergeNewItems()
    {
        $config = new Config($this->testArray);

        $config->merge([
            'barbaz' => [
                'foo' => 1,
            ],
            'foobar' => 500,
        ]);

        $this->assertTrue($config->has('barbaz'));
        $this->assertEquals([
            'foo' => 1,
        ], $config->get('barbaz'));
        $this->assertTrue($config->has('barbaz.foo'));
        $this->assertEquals(1, $config['barbaz.foo']);
        $this->assertTrue($config->has('foobar'));
        $this->assertEquals(500, $config['foobar']);
    }

    public function testMergeShouldOverwriteExistingItems()
    {
        $config = new Config($this->testArray, $this->defaults);

        $config->merge([
            'foo'       => [
                'platform' => ['JavaScript', 'PHP', 'CSS', 'SQL'],
                'metadata' => 'foobar',
                'numPosts' => 10,
            ],
            'isLoading' => false,
        ]);

        $this->assertTrue($config->has('foo.metadata'));
        $this->assertEquals('foobar', $config->get('foo.metadata'));
        $this->assertTrue($config->has('isLoading'));
        $this->assertFalse($config->get('isLoading'));
        $this->assertFalse($config->has('foobar'));
        $this->assertSame(['JavaScript', 'PHP', 'CSS', 'SQL'], $config['foo.platform']);
    }
}
