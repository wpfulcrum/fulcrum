<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class PluckTest extends UnitTestCase
{
    protected $indexedArray = [];
    protected $assocArray = [];

    public function setUp()
    {
        $tonya  = [
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
        $sally  = [
            'user_id'   => 201,
            'name'      => 'Sally',
            'email'     => 'bar',
            'social'    => [
                'twitter' => '@sally',
            ],
            'languages' => [
                'fav' => 'JavaScript',
            ],
        ];
        $bennie = [
            'user_id'   => 504,
            'name'      => 'Bennie',
            'email'     => 'baz',
            'social'    => [
                'twitter' => '@bennie',
            ],
            'languages' => [
                'fav' => 'Ruby',
            ],
        ];

        $this->indexedArray = [$tonya, $sally, $bennie];
        $this->assocArray   = [
            'tonya'  => $tonya,
            'sally'  => $sally,
            'bennie' => $bennie,
        ];
    }

    public function testPluckEmptyArray()
    {
        $this->assertEquals([], DotArray::pluck([], 'name'));
        $this->assertEquals([], array_pluck([], 'name'));

        $emptyArray = [];
        $this->assertEquals([], DotArray::pluck($emptyArray, 'name'));
        $this->assertEquals([], array_pluck($emptyArray, 'name'));
    }

    public function testPluckWhenValueNotInSubject()
    {
        $data = [
            [
                'name'  => 'Tonya',
                'email' => 'foo',
            ],
        ];

        $this->assertEquals([], DotArray::pluck($data, 'doesnotexist'));
        $this->assertEquals([], DotArray::pluck($data, 'foo', 'invalidKey'));

        $this->assertEquals([], array_pluck($data, 'doesnotexist'));
        $this->assertEquals([], array_pluck($data, 'foo', 'invalidKey'));
    }

    public function testPluckIndexedArrayByValue()
    {
        $expected = ['Tonya', 'Sally', 'Bennie'];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'name'));
        $this->assertEquals($expected, array_pluck($this->indexedArray, 'name'));

        $expected = ['foo', 'bar', 'baz'];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'email'));
        $this->assertEquals($expected, array_pluck($this->indexedArray, 'email'));

        $expected = [
            'Tonya'  => 'foo',
            'Sally'  => 'bar',
            'Bennie' => 'baz',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'email', 'name'));
        $this->assertEquals($expected, array_pluck($this->indexedArray, 'email', 'name'));
    }

    public function testPluckAssociativeArrayByValue()
    {
        $expected = ['Tonya', 'Sally', 'Bennie'];
        $this->assertEquals($expected, DotArray::pluck($this->assocArray, 'name'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'name'));

        $expected = ['foo', 'bar', 'baz'];
        $this->assertEquals($expected, DotArray::pluck($this->assocArray, 'email'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'email'));

        $expected = [
            'Tonya'  => 'foo',
            'Sally'  => 'bar',
            'Bennie' => 'baz',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->assocArray, 'email', 'name'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'email', 'name'));
    }

    public function testPluckIndexedArrayDotNotation()
    {
        $expected = [
            '@tonya',
            '@sally',
            '@bennie',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'social.twitter'));
        $this->assertEquals($expected, array_pluck($this->indexedArray, 'social.twitter'));
    }

    public function testPluckAssociativeArrayDotNotation()
    {
        $expected = [
            '@tonya',
            '@sally',
            '@bennie',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->assocArray, 'social.twitter'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'social.twitter'));
    }

    public function testPluckReKey()
    {
        $expected = [
            'Tonya'  => '@tonya',
            'Sally'  => '@sally',
            'Bennie' => '@bennie',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'social.twitter', 'name'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'social.twitter', 'name'));

        $expected = [
            'PHP'        => '@tonya',
            'JavaScript' => '@sally',
            'Ruby'       => '@bennie',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'social.twitter', 'languages.fav'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'social.twitter', 'languages.fav'));

        $expected = [
            'PHP'        => 'Tonya',
            'JavaScript' => 'Sally',
            'Ruby'       => 'Bennie',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'name', 'languages.fav'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'name', 'languages.fav'));

        $expected = [
            1   => 'PHP',
            201 => 'JavaScript',
            504 => 'Ruby',
        ];
        $this->assertEquals($expected, DotArray::pluck($this->indexedArray, 'languages.fav', 'user_id'));
        $this->assertEquals($expected, array_pluck($this->assocArray, 'languages.fav', 'user_id'));
    }

    public function testPluckAssociativeArrayDotNotationArray()
    {
        $expected = [
            '@tonya',
            '@sally',
            '@bennie',
        ];

        $this->assertEquals($expected, DotArray::pluck($this->assocArray, ['social', 'twitter']));
        $this->assertEquals($expected, array_pluck($this->assocArray, ['social', 'twitter']));
    }
}
