<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class FlattenTest extends UnitTestCase
{
    public function testShouldNotFlattenAndReturnValues()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
        ];
        $expected = [504, 'Bob Jones'];

        $this->assertEquals($expected, DotArray::flatten($data));
        $this->assertEquals($expected, array_flatten($data));

        $data = ['#foo', '#bar', '#baz'];
        $this->assertEquals($data, DotArray::flatten($data));
        $this->assertEquals($data, array_flatten($data));
    }

    public function testShouldDeeplyFlatten()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
            ],
            'foo',
            [
                'bar',
                'baz',
            ],
        ];
        $expected = [504, 'Bob Jones', '@bobjones', 'foo', 'bar', 'baz'];

        $this->assertEquals($expected, DotArray::flatten($data));
        $this->assertEquals($expected, array_flatten($data));

        $data     = [
            504,
            'Bob Jones',
            [
                'twitter' => '@bobjones',
                'website' => [
                    'personal' => 'https://bobjones.com',
                    'business' => 'https://foo.com',
                ],
            ],
            'foo',
            [
                'bar',
                'baz',
            ],
        ];
        $expected = [
            504,
            'Bob Jones',
            '@bobjones',
            'https://bobjones.com',
            'https://foo.com',
            'foo',
            'bar',
            'baz',
        ];

        $this->assertEquals($expected, DotArray::flatten($data));
        $this->assertEquals($expected, array_flatten($data));

        $data     = [
            504,
            'Bob Jones',
            [
                'twitter' => '@bobjones',
                'website' => [
                    'personal' => 'https://bobjones.com',
                    'business' => 'https://foo.com',
                ],
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
        $expected = [
            504,
            'Bob Jones',
            '@bobjones',
            'https://bobjones.com',
            'https://foo.com',
            true,
            false,
            true,
            false,
        ];

        $this->assertEquals($expected, DotArray::flatten($data));
        $this->assertEquals($expected, array_flatten($data));
    }

    public function testShouldFlattenToDepth1()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
                'website' => [
                    'personal' => 'https://bobjones.com',
                    'business' => 'https://foo.com',
                ],
            ],
        ];
        $expected = [
            504,
            'Bob Jones',
            '@bobjones',
            [
                'personal' => 'https://bobjones.com',
                'business' => 'https://foo.com',
            ],
        ];
        $this->assertEquals($expected, DotArray::flatten($data, 1));
        $this->assertEquals($expected, array_flatten($data, 1));

        $data     = [['#foo', ['#bar', ['#baz']]], '#zap'];
        $expected = ['#foo', ['#bar', ['#baz']], '#zap'];
        $this->assertEquals($expected, DotArray::flatten($data, 1));
        $this->assertEquals($expected, array_flatten($data, 1));
    }

    public function testShouldFlattenToDepth2()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
                'website' => [
                    'personal' => [
                        'https://bobjones.com',
                        'http://example.com',
                    ],
                    'business' => 'https://foo.com',
                ],
            ],
        ];
        $expected = [
            504,
            'Bob Jones',
            '@bobjones',
            [
                'https://bobjones.com',
                'http://example.com',
            ],
            'https://foo.com',
        ];
        $this->assertEquals($expected, DotArray::flatten($data, 2));
        $this->assertEquals($expected, array_flatten($data, 2));

        $data     = [['#foo', ['#bar', ['#baz']]], '#zap'];
        $expected = ['#foo', '#bar', ['#baz'], '#zap'];
        $this->assertEquals($expected, DotArray::flatten($data, 2));
        $this->assertEquals($expected, array_flatten($data, 2));
    }
}
