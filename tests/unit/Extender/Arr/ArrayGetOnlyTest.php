<?php

namespace Fulcrum\Tests\Unit\Extender\Arr;

use Fulcrum\Tests\Unit\UnitTestCase;

class ArrayGetOnlyTest extends UnitTestCase
{
    public function testNoMatches()
    {
        $dataSet = [
            'user_id'    => 102,
            'name'       => 'Sally',
            'email'      => 'sally@foo.com',
            'has_access' => false,
        ];
        $this->assertEquals([], array_get_only($dataSet, ['twitter']));
        $this->assertEquals([], array_get_only($dataSet, 'twitter'));
    }

    public function testOnly()
    {
        $dataSet = [
            'user_id'    => 102,
            'name'       => 'Sally',
            'email'      => 'sally@foo.com',
            'has_access' => false,
            'social'     => [
                'twitter' => '@sally',
            ],
        ];
        $this->assertEquals(['name' => 'Sally'], array_get_only($dataSet, 'name'));
        $this->assertEquals(['email' => 'sally@foo.com'], array_get_only($dataSet, 'email'));
        $this->assertEquals([
            'social' => [
                'twitter' => '@sally',
            ],
        ], array_get_only($dataSet, ['social']));
    }

    public function testMultipleKeys()
    {
        $dataSet = [
            'user_id'   => 1,
            'name'      => 'Tonya',
            'email'     => 'foo',
            'social'    => [
                'twitter' => '@tonya',
            ],
            'languages' => [
                'fav' => 'PHP',
            ],
        ];

        $expected = [
            'name'  => 'Tonya',
            'email' => 'foo',
        ];
        $this->assertEquals($expected, array_get_only($dataSet, ['name', 'email']));
        $this->assertEquals($expected, array_get_only($dataSet, ['email', 'name']));

        $expected = [
            'user_id' => 1,
            'social'  => [
                'twitter' => '@tonya',
            ],
        ];
        $this->assertEquals($expected, array_get_only($dataSet, ['user_id', 'social']));
        $this->assertEquals($expected, array_get_only($dataSet, ['social', 'user_id']));
    }
}
