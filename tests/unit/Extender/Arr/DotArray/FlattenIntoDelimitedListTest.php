<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;

class FlattenIntoDelimitedListTest extends UnitTestCase
{
    public function testShouldReturnListForFlatArray()
    {
        $data = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
        ];

        $this->assertEquals('504,Bob Jones', array_flatten_into_delimited_list($data));
        $this->assertEquals('504 | Bob Jones', array_flatten_into_delimited_list($data, ' | '));

        $data = ['#foo', '#bar', '#baz'];
        $this->assertEquals('#foo,#bar,#baz', array_flatten_into_delimited_list($data));
        $this->assertEquals('#foo/#bar/#baz', array_flatten_into_delimited_list($data, '/'));

        $data = [false, true, true, 0, 1, null];
        $this->assertEquals(',1,1,0,1,', array_flatten_into_delimited_list($data));
        $this->assertEquals(' ; 1 ; 1 ; 0 ; 1 ; ', array_flatten_into_delimited_list($data, ' ; '));
    }

    public function testShouldDeeplyFlatten()
    {
        $data = [
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
        $this->assertEquals(
            '504,Bob Jones,@bobjones,foo,bar,baz',
            array_flatten_into_delimited_list($data)
        );
        $this->assertEquals(
            '504 %D% Bob Jones %D% @bobjones %D% foo %D% bar %D% baz',
            array_flatten_into_delimited_list($data, ' %D% ')
        );

        $data = [
            504,
            'Bob Jones',
            'Hello, my name is Bob.',
            [
                'twitter' => '@bobjones',
                'website' => [
                    'personal' => 'https://bobjones.com',
                    'business' => 'https://foo.com',
                ],
            ],
        ];
        $this->assertEquals(
            '504,Bob Jones,Hello, my name is Bob.,@bobjones,https://bobjones.com,https://foo.com',
            array_flatten_into_delimited_list($data)
        );
        $this->assertEquals(
            '504;Bob Jones;Hello, my name is Bob.;@bobjones;https://bobjones.com;https://foo.com',
            array_flatten_into_delimited_list($data, ';')
        );

        $data = [
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
        $this->assertEquals(
            '504,Bob Jones,@bobjones,https://bobjones.com,https://foo.com,1,,1,',
            array_flatten_into_delimited_list($data)
        );
    }
}
