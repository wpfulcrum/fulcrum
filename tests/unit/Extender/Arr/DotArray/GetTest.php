<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;
use Fulcrum\Tests\Unit\Extender\Arr\Stubs\ArrayAccessStub;
use Fulcrum\Tests\Unit\Extender\Arr\Stubs\DeveloperStub;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class GetTest extends UnitTestCase
{
    protected $data;
    protected $developerObject;
    protected $arrayAccess;

    public function setUp()
    {
        $this->data = [
            'user_id'   => 504,
            'name'      => 'Bob Jones',
            'social'    => [
                'twitter' => '@bobjones',
            ],
            'languages' => [
                'php'        => [
                    'procedural' => true,
                    'oop'        => false,
                ],
                'javascript' => true,
                'ruby'       => false,
            ],
        ];

        $this->developerObject = new DeveloperStub($this->data);
        $this->arrayAccess     = new ArrayAccessStub($this->data);
    }

    public function testGetNullKey()
    {
        $this->assertEquals($this->data, DotArray::get($this->data, null));
    }

    public function testGetDefault()
    {
        $this->assertEquals('foo', DotArray::get($this->data, '', 'foo'));
        $this->assertEquals(null, DotArray::get($this->data, '', null));
        $this->assertEquals('bar', DotArray::get($this->data, 'email', 'bar'));
        $this->assertEquals('no_sql', DotArray::get($this->data, 'languages.sql', 'no_sql'));
        $this->assertEquals([], DotArray::get($this->data, 'languages.javascript.oop', []));
    }

    public function testGetDefaultAPI()
    {
        $this->assertEquals('foo', array_get($this->data, '', 'foo'));
        $this->assertEquals(null, array_get($this->data, '', null));
        $this->assertEquals('bar', array_get($this->data, 'email', 'bar'));
        $this->assertEquals('no_sql', array_get($this->data, 'languages.sql', 'no_sql'));
        $this->assertEquals([], array_get($this->data, 'languages.javascript.oop', []));
    }

    public function testGetArray()
    {
        $this->assertEquals('Bob Jones', DotArray::get($this->data, 'name'));
        $this->assertEquals([
            'php'        => [
                'procedural' => true,
                'oop'        => false,
            ],
            'javascript' => true,
            'ruby'       => false,
        ], DotArray::get($this->data, 'languages'));
    }

    public function testGetArrayAPI()
    {
        $this->assertEquals('Bob Jones', array_get($this->data, 'name'));
        $this->assertEquals([
            'php'        => [
                'procedural' => true,
                'oop'        => false,
            ],
            'javascript' => true,
            'ruby'       => false,
        ], array_get($this->data, 'languages'));
    }

    public function testGetArrayDotNotation()
    {
        $data = [
            'array'       => [
                'aaa',
                'zzz',
            ],
            'arrayNested' => [
                'foo' => [
                    'bar' => 'baz',
                    'foobar',
                ],
            ],
        ];
        $this->assertSame(
            [
                'bar' => 'baz',
                'foobar',
            ],
            DotArray::get($data, 'arrayNested.foo')
        );
        $this->assertSame('foobar', DotArray::get($data, 'arrayNested.foo.0'));

        $this->assertSame(
            [
                'bar' => 'baz',
                'foobar',
            ],
            array_get($data, 'arrayNested.foo')
        );
        $this->assertSame('foobar', array_get($data, 'arrayNested.foo.0'));
    }

    public function testGetArrayDotNotationAPI()
    {
        $this->assertTrue(array_get($this->data, 'languages.javascript'));
        $this->assertTrue(true === array_get($this->data, 'languages.javascript'));
        $this->assertTrue(false === array_get($this->data, ['languages', 'ruby']));
    }

    public function testGetArrayArrayOfKeys()
    {
        $this->assertEquals('@bobjones', DotArray::get($this->data, ['social', 'twitter']));
        $this->assertTrue(true === DotArray::get($this->data, ['languages', 'javascript']));
        $this->assertTrue(false === DotArray::get($this->data, ['languages', 'ruby']));
    }

    public function testGetArrayArrayOfKeysAPI()
    {
        $this->assertEquals('@bobjones', array_get($this->data, ['social', 'twitter']));
        $this->assertTrue(true === array_get($this->data, ['languages', 'javascript']));
        $this->assertTrue(false === array_get($this->data, ['languages', 'ruby']));
    }

    public function testGetArrayDotNotationToIndex()
    {
        $this->assertTrue(DotArray::get($this->data, 'languages.javascript'));
        $this->assertTrue(true === DotArray::get($this->data, 'languages.javascript'));
        $this->assertTrue(false === DotArray::get($this->data, ['languages', 'ruby']));
    }

    public function testGetObject()
    {
        $this->assertEquals('Bob Jones', DotArray::get($this->developerObject, 'name'));
        $this->assertEquals(
            [
                'php'        => [
                    'procedural' => true,
                    'oop'        => false,
                ],
                'javascript' => true,
                'ruby'       => false,
            ],
            DotArray::get($this->developerObject, 'languages')
        );
        $this->assertEquals(504, DotArray::get($this->developerObject, 'user_id'));
    }

    public function testGetObjectAPI()
    {
        $this->assertEquals('Bob Jones', array_get($this->developerObject, 'name'));
        $this->assertEquals(
            [
                'php'        => [
                    'procedural' => true,
                    'oop'        => false,
                ],
                'javascript' => true,
                'ruby'       => false,
            ],
            array_get($this->developerObject, 'languages')
        );
        $expected = 504;
        $this->assertEquals($expected, array_get($this->developerObject, 'user_id'));
    }

    public function testGetObjectDotNotation()
    {
        $this->assertTrue(DotArray::get($this->developerObject, 'languages.javascript'));
        $this->assertTrue(true === DotArray::get($this->developerObject, 'languages.javascript'));
        $this->assertTrue(false === DotArray::get($this->developerObject, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', DotArray::get($this->developerObject, 'social.twitter'));
    }

    public function testGetObjectDotNotationAPI()
    {
        $this->assertTrue(array_get($this->developerObject, 'languages.javascript'));
        $this->assertTrue(true === array_get($this->developerObject, 'languages.javascript'));
        $this->assertTrue(false === array_get($this->developerObject, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', array_get($this->developerObject, 'social.twitter'));
    }

    public function testGetObjectArrayOfKeys()
    {
        $this->assertEquals('@bobjones', DotArray::get($this->developerObject, ['social', 'twitter']));
        $this->assertTrue(true === DotArray::get($this->developerObject, ['languages', 'javascript']));
        $this->assertTrue(false === DotArray::get($this->developerObject, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', DotArray::get($this->developerObject, ['social', 'twitter']));
    }

    public function testGetObjectArrayOfKeysAPI()
    {
        $this->assertEquals(
            '@bobjones',
            array_get($this->developerObject, ['social', 'twitter'])
        );
        $this->assertTrue(true === array_get($this->developerObject, ['languages', 'javascript']));
        $this->assertTrue(false === array_get($this->developerObject, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', array_get($this->developerObject, ['social', 'twitter']));
    }

    public function testGetArrayAccess()
    {
        $this->assertEquals('Bob Jones', DotArray::get($this->arrayAccess, 'name'));
        $this->assertEquals(
            [
                'php'        => [
                    'procedural' => true,
                    'oop'        => false,
                ],
                'javascript' => true,
                'ruby'       => false,
            ],
            DotArray::get($this->arrayAccess, 'languages')
        );
        $expected = 504;
        $this->assertEquals($expected, DotArray::get($this->arrayAccess, 'user_id'));

        $this->assertNull(DotArray::get($this->arrayAccess, 'email'));
        $this->arrayAccess['email'] = 'bobjones@gmail.com';
        $this->assertEquals('bobjones@gmail.com', DotArray::get($this->arrayAccess, 'email'));
        $this->arrayAccess->offsetUnset('email');
        $this->assertNull(DotArray::get($this->arrayAccess, 'email'));
    }

    public function testGetArrayAccessAPI()
    {
        $this->assertEquals('Bob Jones', array_get($this->arrayAccess, 'name'));
        $this->assertEquals(
            [
                'php'        => [
                    'procedural' => true,
                    'oop'        => false,
                ],
                'javascript' => true,
                'ruby'       => false,
            ],
            array_get($this->arrayAccess, 'languages')
        );
        $expected = 504;
        $this->assertEquals($expected, array_get($this->arrayAccess, 'user_id'));

        $this->assertNull(array_get($this->arrayAccess, 'email'));
        $this->arrayAccess['email'] = 'bobjones@gmail.com';
        $this->assertEquals('bobjones@gmail.com', array_get($this->arrayAccess, 'email'));
        $this->arrayAccess->offsetUnset('email');
        $this->assertNull(array_get($this->arrayAccess, 'email'));
    }

    public function testGetArrayAccessDotNotation()
    {
        $this->assertTrue(DotArray::get($this->arrayAccess, 'languages.javascript'));
        $this->assertTrue(true === DotArray::get($this->arrayAccess, 'languages.javascript'));
        $this->assertTrue(false === DotArray::get($this->arrayAccess, 'languages.php.oop'));
        $this->assertTrue(false === DotArray::get($this->arrayAccess, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', DotArray::get($this->arrayAccess, 'social.twitter'));

        $this->assertNull(DotArray::get($this->arrayAccess, 'languages.c'));

        $this->assertNull(DotArray::get($this->arrayAccess, 'social.facebook'));
        $social                      = $this->arrayAccess['social'];
        $social['facebook']          = 'BobJones';
        $this->arrayAccess['social'] = $social;
        $this->assertEquals('BobJones', DotArray::get($this->arrayAccess, 'social.facebook'));
    }

    public function testGetArrayAccessDotNotationAPI()
    {
        $this->assertTrue(array_get($this->arrayAccess, 'languages.javascript'));
        $this->assertTrue(true === array_get($this->arrayAccess, 'languages.javascript'));
        $this->assertTrue(false === array_get($this->arrayAccess, 'languages.php.oop'));
        $this->assertTrue(false === array_get($this->arrayAccess, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', array_get($this->arrayAccess, 'social.twitter'));

        $this->assertNull(array_get($this->arrayAccess, 'languages.c'));

        $this->assertNull(array_get($this->arrayAccess, 'social.facebook'));
        $social                      = $this->arrayAccess['social'];
        $social['facebook']          = 'BobJones';
        $this->arrayAccess['social'] = $social;
        $this->assertEquals('BobJones', array_get($this->arrayAccess, 'social.facebook'));
    }

    public function testGetArrayAccessArrayOfKeys()
    {
        $this->assertEquals('@bobjones', DotArray::get($this->arrayAccess, ['social', 'twitter']));
        $this->assertTrue(true === DotArray::get($this->arrayAccess, ['languages', 'javascript']));
        $this->assertTrue(false === DotArray::get($this->arrayAccess, ['languages', 'php', 'oop']));
        $this->assertTrue(false === DotArray::get($this->arrayAccess, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', DotArray::get($this->arrayAccess, ['social', 'twitter']));

        $this->assertNull(DotArray::get($this->arrayAccess, ['languages', 'c']));
        $languages                      = $this->arrayAccess['languages'];
        $languages['c']                 = 'yup';
        $this->arrayAccess['languages'] = $languages;
        $this->assertEquals('yup', DotArray::get($this->arrayAccess, ['languages', 'c']));
    }

    public function testGetArrayAccessArrayOfKeysAPI()
    {
        $this->assertEquals('@bobjones', array_get($this->arrayAccess, ['social', 'twitter']));
        $this->assertTrue(true === array_get($this->arrayAccess, ['languages', 'javascript']));
        $this->assertTrue(false === array_get($this->arrayAccess, ['languages', 'php', 'oop']));
        $this->assertTrue(false === array_get($this->arrayAccess, ['languages', 'ruby']));
        $this->assertEquals('@bobjones', array_get($this->arrayAccess, ['social', 'twitter']));

        $this->assertNull(array_get($this->arrayAccess, ['languages', 'c']));
        $languages                      = $this->arrayAccess['languages'];
        $languages['c']                 = 'yup';
        $this->arrayAccess['languages'] = $languages;
        $this->assertEquals('yup', array_get($this->arrayAccess, ['languages', 'c']));
    }
}
