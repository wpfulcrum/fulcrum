<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\FilterArray;

use Fulcrum\Tests\Unit\UnitTestCase;

class TrimTest extends UnitTestCase
{
    public function testEmpty()
    {
        $emptyArray = [];
        $this->assertEquals([], array_trim($emptyArray));
        $this->assertEquals([], array_trim($emptyArray, true));

        $data = [''];
        $this->assertEquals([''], array_trim($data));
        $this->assertEquals([''], array_trim($data, true));

        $data = ['', false];
        $this->assertEquals(['', false], array_trim($data));
        $this->assertEquals(['', false], array_trim($data, true));
    }

    public function testAllStrings()
    {
        $dataSet  = [
            'name'    => '   Tonya ',
            'email'   => '  tonya@foo.com    ',
            'twitter' => '@hellofromtonya       ',
        ];
        $expected = [
            'name'    => 'Tonya',
            'email'   => 'tonya@foo.com',
            'twitter' => '@hellofromtonya',
        ];

        $this->assertEquals($expected, array_trim($dataSet));

        $dataSet  = [
            ' Brewers  ',
            '  Reds  ',
            '  Phillies ',
            ['Pacers', 'Bulls  ', 'Sonics  '],
        ];
        $expected = [
            'Brewers',
            'Reds',
            'Phillies',
            ['Pacers', 'Bulls  ', 'Sonics  '],
        ];
        $this->assertEquals($expected, array_trim($dataSet));
    }

    public function testMixedDataTypes()
    {
        $dataSet = [
            'user_id'    => 101,
            'name'       => '   Tonya ',
            'email'      => '  tonya@foo.com    ',
            'social'     => [
                'twitter' => '@hellofromtonya       ',
                'website' => 'https://foo.com   ',
            ],
            'has_access' => true,
        ];

        $expected = [
            'user_id'    => 101,
            'name'       => 'Tonya',
            'email'      => 'tonya@foo.com',
            'social'     => [
                'twitter' => '@hellofromtonya       ',
                'website' => 'https://foo.com   ',
            ],
            'has_access' => true,
        ];

        $this->assertEquals($expected, array_trim($dataSet));
    }

    public function testDeeplyAllStrings()
    {
        $dataSet  = [
            'name'    => '   Tonya ',
            'email'   => '  tonya@foo.com    ',
            'twitter' => '@hellofromtonya       ',
        ];
        $expected = [
            'name'    => 'Tonya',
            'email'   => 'tonya@foo.com',
            'twitter' => '@hellofromtonya',
        ];

        array_trim($dataSet, true);
        $this->assertEquals($expected, $dataSet);

        $dataSet  = [
            ' Brewers  ',
            '  Reds  ',
            '  Phillies ',
            ['Pacers', 'Bulls  ', 'Sonics  '],
        ];
        $expected = [
            'Brewers',
            'Reds',
            'Phillies',
            ['Pacers', 'Bulls', 'Sonics'],
        ];

        array_trim($dataSet, true);
        $this->assertEquals($expected, $dataSet);
    }

    public function testDeeplyMixedDataTypes()
    {
        $dataSet = [
            'user_id'    => 101,
            'name'       => '   Tonya ',
            'email'      => '  tonya@foo.com    ',
            'social'     => [
                'twitter' => '@hellofromtonya       ',
                'website' => 'https://foo.com   ',
            ],
            'has_access' => true,
        ];

        $expected = [
            'user_id'    => 101,
            'name'       => 'Tonya',
            'email'      => 'tonya@foo.com',
            'social'     => [
                'twitter' => '@hellofromtonya',
                'website' => 'https://foo.com',
            ],
            'has_access' => true,
        ];
        array_trim($dataSet, true);

        $this->assertEquals($expected, $dataSet);

        $dataSet = [
            'user_id'    => 101,
            'name'       => '   Tonya ',
            'email'      => '  <a:mailto="tonya@foo.com">tonya@foo.com   </a> ',
            'social'     => [
                'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
                'website' => '<a href="https://foo.com">https://foo.com   </a> ',
            ],
            'has_access' => true,
        ];

        $expected = [
            'user_id'    => 101,
            'name'       => 'Tonya',
            'email'      => '<a:mailto="tonya@foo.com">tonya@foo.com   </a>',
            'social'     => [
                'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>',
                'website' => '<a href="https://foo.com">https://foo.com   </a>',
            ],
            'has_access' => true,
        ];
        array_trim($dataSet, true);

        $this->assertEquals($expected, $dataSet);
    }
}
