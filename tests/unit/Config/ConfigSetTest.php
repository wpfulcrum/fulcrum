<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Config\Config;

class ConfigSetTest extends ConfigTestCase
{
    public function testSetChangesValue()
    {
        $config = new Config($this->testArray, $this->defaults);

        // Able to change the value and data type.
        $originalValue = $config->foo;
        $newValue      = 'Software development is fun!';
        $this->assertTrue(is_array($config->foo));

        $config->foo = $newValue;
        $this->assertTrue($config->has('foo'));
        $this->assertTrue(is_string($config->foo));
        $this->assertNotEquals($originalValue, $config->foo);
        $this->assertEquals($newValue, $config->foo);
        $this->assertEquals($newValue, $config->get('foo'));
        $this->assertEquals($config->foo, $config->get('foo'));

        $newValue = 'Beans rocks!';
        $config->offsetSet('bar.baz.oof', $newValue);
        $this->assertTrue($config->has('bar.baz.oof'));
        $this->assertEquals($newValue, $config->bar['baz']['oof']);
        $this->assertEquals($newValue, $config->get('bar.baz.oof'));
        $this->assertEquals($config->bar['baz']['oof'], $config->get('bar.baz.oof'));

        $newValue = 'Know the fundamentals first';
        $config->set('bar.baz.who', $newValue);
        $this->assertTrue($config->has('bar.baz.who'));
        $this->assertEquals($newValue, $config->bar['baz']['who']);
        $this->assertEquals($newValue, $config->get('bar.baz.who'));

        // Able to change the value and data type.
        $originalValue = $config->bar['baz'];
        $newValue      = 'Overwriting the array';
        $this->assertTrue(is_array($config->bar['baz']));
        $config->bar['baz'] = 'Overwriting the array';
        $config->set('bar.baz', $newValue);
        $this->assertTrue($config->has('bar.baz'));
        $this->assertTrue(is_string($config->bar['baz']));
        $this->assertNotEquals($originalValue, $config->get('bar.baz'));
        $this->assertEquals($newValue, $config->bar['baz']);
        $this->assertEquals($config->get('bar.baz'), $config->bar['baz']);
    }

    /**
     * Test that when attempting to directly overwrite a nested element via it's property,
     * i.e. $config->arrayKey[level2Key].
     *
     * We are testing this to show how not to change nested elements.  Rather, we should use
     * dot notation.
     *
     * Why? Why doesn't it work?  Because $config->arrayKey[level2Key] does not call offsetSet().
     */
    public function testCannotOverwriteValueWhenDirect()
    {
        $config = new Config($this->testArray, $this->defaults);

        $originalValue = $config->bar['baz'];
        $this->assertTrue(is_array($originalValue));

        $config->bar['baz'] = 'Overwriting the array';

        $this->assertTrue($config->has('bar.baz'));

        // Test that it did not overwrite the array with a string.
        $this->assertFalse(is_string($config->bar['baz']));
        $this->assertNotEquals('Overwriting the array', $config->bar['baz']);

        // Test that it remains unchanged.
        $this->assertTrue(is_array($config->bar['baz']));
        $this->assertEquals($originalValue, $config->bar['baz']);
        $this->assertEquals($originalValue, $config->get('bar.baz'));
        $this->assertEquals($config->bar['baz'], $config->get('bar.baz'));
    }

    public function testSetAddsNewItems()
    {
        $config = new Config($this->testArray);

        $config->who = 'John';
        $this->assertTrue($config->has('who'));
        $this->assertEquals('John', $config->who);

        $config->offsetSet('bar.baz.newProperty', 'Beans rocks!');
        $this->assertTrue($config->has('bar.baz.newProperty'));
        $this->assertEquals('Beans rocks!', $config->bar['baz']['newProperty']);

        $config->set('foo.description', 'Know the fundamentals first');
        $this->assertTrue($config->has('foo.description'));
        $this->assertEquals('Know the fundamentals first', $config->foo['description']);
    }
}
