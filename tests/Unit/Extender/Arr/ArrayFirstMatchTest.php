<?php

namespace Fulcrum\Tests\Unit\Extender\Arr;

use Fulcrum\Tests\Unit\Extender\Arr\Stubs\CallbackStub;
use Fulcrum\Tests\Unit\UnitTestCase;

class ArrayFirstMatchTest extends UnitTestCase
{
    protected $users;
    protected $dataSet;

    public function setUp()
    {
        parent::setUp();

        $this->users = $this->users = require __DIR__ . '/fixtures/user-data.php';

        if (!class_exists(__NAMESPACE__ . '/Stubs/CallbackStub.php')) {
            require_once __DIR__ . '/Stubs/CallbackStub.php';
        }

        $this->dataSet = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5,
            'f' => 6,
            'g' => 7,
            'h' => 8,
        ];
    }

    public function testDefaultValue()
    {
        $this->assertNull(array_get_first_match($this->dataSet, function ($key, $value) {
            $threshold = 0;
            return $value < $threshold;
        }));

        $callback = function ($key, $value) {
            $threshold = 10;
            return $value > $threshold;
        };

        $this->assertFalse(array_get_first_match($this->dataSet, $callback, false));

        $this->assertEquals([], array_get_first_match($this->dataSet, function ($key, $value) {
            return $value % 10 === 0;
        }, []));
    }

    public function testClosure()
    {
        $this->assertEquals(2, array_get_first_match($this->dataSet, function ($key, $value) {
            return $value % 2 === 0;
        }));

        $this->assertEquals(3, array_get_first_match($this->dataSet, function ($key, $value) {
            return $value % 3 === 0;
        }));

        $callback = function ($key, $value) {
            $threshold = 600;
            return $key > $threshold;
        };
        $expected = [
            'user_id'    => 601,
            'name'       => 'Bob Jones',
            'has_access' => false,
        ];
        $this->assertEquals($expected, array_get_first_match($this->users, $callback));

        $callback = function ($key, $value) {
            if (!isset($value['has_access'])) {
                return false;
            }

            return !$value['has_access'];
        };
        $expected = [
            'user_id'    => 102,
            'name'       => 'Sally',
            'email'      => 'sally@foo.com',
            'has_access' => false,
        ];
        $this->assertEquals($expected, array_get_first_match($this->users, $callback));
    }

    public function testCallback()
    {
        $expected = 2;
        $this->assertEquals(
            $expected,
            array_get_first_match(
                $this->dataSet,
                __NAMESPACE__ . '\Stubs\isEvenCallback'
            )
        );
        $expected = 3;
        $this->assertEquals(
            $expected,
            array_get_first_match(
                $this->dataSet,
                __NAMESPACE__ . '\Stubs\isDivisibleBy3Callback'
            )
        );
        $expected = [
            'user_id'    => 102,
            'name'       => 'Sally',
            'email'      => 'sally@foo.com',
            'has_access' => false,
        ];
        $this->assertEquals(
            $expected,
            array_get_first_match(
                $this->users,
                __NAMESPACE__ . '\Stubs\doesNotHaveAccess'
            )
        );
    }

    public function testObjectCallback()
    {
        $callback = new CallbackStub();
        $expected = 2;
        $this->assertEquals($expected, array_get_first_match($this->dataSet, [$callback, 'isEven']));

        $data = [
            501,
            'Sally',
            'foo' => ['bar', 'baz'],
            'apple',
            'baseball',
        ];
        $this->assertEquals(
            ['bar', 'baz'],
            array_get_first_match($data, [$callback, 'isAssociative'])
        );
    }

    public function testStaticCallback()
    {
        $callback = new CallbackStub();
        $expected = 2;
        $this->assertEquals(
            $expected,
            array_get_first_match($this->dataSet, [$callback, 'isEvenStatic'])
        );
        $this->assertEquals(
            $expected,
            array_get_first_match(
                $this->dataSet,
                [__NAMESPACE__ . '\Stubs\CallbackStub', 'isEvenStatic']
            )
        );

        $data = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
            ],
        ];
        $this->assertEquals(
            [
                'twitter' => '@bobjones',
            ],
            array_get_first_match(
                $data,
                __NAMESPACE__ . '\Stubs\CallbackStub::isArray'
            )
        );
    }
}
