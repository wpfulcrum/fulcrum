<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\FilterArray;

use Fulcrum\Tests\Unit\UnitTestCase;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class ArrayFilterTest extends UnitTestCase
{
    protected $users;

    public function setUp()
    {
        $this->users = [
            101 => [
                'userId'    => 101,
                'name'      => 'Tonya',
                'email'     => 'tonya@foo.com',
                'hasAccess' => true,
            ],
            102 => [
                'userId'    => 102,
                'name'      => 'Sally',
                'email'     => 'sally@foo.com',
                'hasAccess' => false,
            ],
            103 => [
                'userId'    => 103,
                'name'      => 'Rose',
                'hasAccess' => true,
            ],
            104 => [
                'userId'    => 104,
                'name'      => 'Bob Jones',
                'hasAccess' => false,
            ],
        ];
    }

    public function testNoMatches()
    {
        $dataSet = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5,
            'f' => 6,
            'g' => 7,
            'h' => 8,
        ];
        $this->assertEquals([], array_filter_with_keys(
            $dataSet,
            function (...$args) {
                $threshold = 10;
                return $args[1] > $threshold;
            }
        ));
    }

    public function testClosure()
    {
        $dataSet  = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5,
            'f' => 6,
            'g' => 7,
            'h' => 8,
        ];
        $expected = [
            'b' => 2,
            'd' => 4,
            'f' => 6,
            'h' => 8,
        ];
        $this->assertEquals($expected, array_filter_with_keys(
            $dataSet,
            function (...$args) {
                return $args[1] % 2 === 0;
            }
        ));

        $expected = $this->users;
        unset($expected[102], $expected[104]);
        $this->assertEquals($expected, array_filter_with_keys(
            $this->users,
            function (...$args) {
                if (!isset($args[1]['hasAccess'])) {
                    return false;
                }

                return $args[1]['hasAccess'];
            }
        ));

        foreach ($this->users as $user) {
            $expected = isset($user['email'])
                ? ['email' => $user['email']]
                : [];
            $this->assertEquals($expected, array_filter_with_keys(
                $user,
                function ($key) {
                    return $key == 'email';
                }
            ));
        }
    }

    public function testRemovingFalseys()
    {
        $dataSet  = [
            0 => 'foo',
            1 => false,
            2 => -1,
            3 => null,
            4 => '',
            5 => [],
            6 => 0,
            7 => '0',
            8 => [
                'foo',
                'bar',
            ],
        ];
        $expected = [
            0 => 'foo',
            2 => -1,
            8 => [
                'foo',
                'bar',
            ],
        ];

        $this->assertEquals($expected, array_filter_with_keys($dataSet));
    }

    public function testCallback()
    {
        $dataSet  = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5,
            'f' => 6,
            'g' => 7,
            'h' => 8,
        ];
        $expected = [
            'b' => 2,
            'd' => 4,
            'f' => 6,
            'h' => 8,
        ];
        $this->assertEquals($expected, array_filter_with_keys($dataSet, __NAMESPACE__ . '\isEvenNumber'));

        $expected = $this->users;
        unset($expected[102], $expected[104]);
        $this->assertEquals($expected, array_filter_with_keys($this->users, __NAMESPACE__ . '\hasAccessFilter'));

        foreach ($this->users as $user) {
            $expected = isset($user['email'])
                ? ['email' => $user['email']]
                : [];

            $this->assertEquals($expected, array_filter_with_keys($user, __NAMESPACE__ . '\hasEmailFilter'));
        }
    }
}

function hasAccessFilter(...$args)
{
    if (!isset($args[1]['hasAccess'])) {
        return false;
    }

    return $args[1]['hasAccess'];
}

function hasEmailFilter($key)
{
    return $key == 'email';
}

function isEvenNumber(...$args)
{
    return $args[1] % 2 === 0;
}
