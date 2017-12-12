<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\FilterArray;

use Fulcrum\Tests\Unit\UnitTestCase;

class StripTagsTest extends UnitTestCase
{
    public function testEmpty()
    {
        $emptyArray = [];
        $this->assertEquals([], array_strip_tags($emptyArray));
        $this->assertEquals([], array_strip_tags($emptyArray, true));

        $data = [''];
        $this->assertEquals([''], array_strip_tags($data));
        $this->assertEquals([''], array_strip_tags($data, true));

        $data = ['', false];
        $this->assertEquals(['', false], array_strip_tags($data));
        $this->assertEquals(['', false], array_strip_tags($data, true));
    }

    public function testAllStrings()
    {
        $dataSet  = [
            'name'    => '   Tonya ',
            'email'   => '  tonya@foo.com    ',
            'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
        ];
        $expected = [
            'name'    => 'Tonya',
            'email'   => 'tonya@foo.com',
            'twitter' => '@hellofromtonya',
        ];

        $this->assertEquals($expected, array_strip_tags($dataSet));

        $dataSet  = [
            ' <p>Brewers</p>  ',
            '  <strong>Reds</strong>  ',
            '<em>  Phillies</em> ',
            ['Pacers', 'Bulls  ', '<span class="team-name">Sonics</span>  '],
        ];
        $expected = [
            'Brewers',
            'Reds',
            'Phillies',
            ['Pacers', 'Bulls  ', '<span class="team-name">Sonics</span>  '],
        ];
        $this->assertEquals($expected, array_strip_tags($dataSet));
    }

    public function testMixedDataTypes()
    {
        $dataSet = [
            'userId'    => 101,
            'name'      => '   Tonya ',
            'email'     => '  <a:mailto="tonya@foo.com">tonya@foo.com   </a> ',
            'social'    => [
                'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
                'website' => '<a href="https://foo.com">https://foo.com   </a> ',
            ],
            'hasAccess' => true,
        ];

        $expected = [
            'userId'    => 101,
            'name'      => 'Tonya',
            'email'     => 'tonya@foo.com',
            'social'    => [
                'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
                'website' => '<a href="https://foo.com">https://foo.com   </a> ',
            ],
            'hasAccess' => true,
        ];

        $this->assertEquals($expected, array_strip_tags($dataSet));
    }

    public function testDeeplyAllStrings()
    {
        $dataSet  = [
            'name'    => '   Tonya ',
            'email'   => '  tonya@foo.com    ',
            'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
        ];
        $expected = [
            'name'    => 'Tonya',
            'email'   => 'tonya@foo.com',
            'twitter' => '@hellofromtonya',
        ];

        $this->assertEquals($expected, array_strip_tags($dataSet, true));

        $dataSet  = [
            ' <p>Brewers</p>  ',
            '  <strong>Reds</strong>  ',
            '<em>  Phillies</em> ',
            ['Pacers', 'Bulls  ', '<span class="team-name">Sonics</span>  '],
        ];
        $expected = [
            'Brewers',
            'Reds',
            'Phillies',
            ['Pacers', 'Bulls', 'Sonics'],
        ];

        array_strip_tags($dataSet, true);
        $this->assertEquals($expected, $dataSet);
    }

    public function testDeeplyMixedDataTypes()
    {
        $dataSet = [
            'userId'    => 101,
            'name'      => '   Tonya ',
            'email'     => '  <a:mailto="tonya@foo.com">tonya@foo.com   </a> ',
            'social'    => [
                'twitter' => '<a href="https://twitter.com/hellofromtonya">@hellofromtonya  </a>       ',
                'website' => '<a href="https://foo.com">https://foo.com   </a> ',
            ],
            'hasAccess' => true,
        ];

        $expected = [
            'userId'    => 101,
            'name'      => 'Tonya',
            'email'     => 'tonya@foo.com',
            'social'    => [
                'twitter' => '@hellofromtonya',
                'website' => 'https://foo.com',
            ],
            'hasAccess' => true,
        ];
        array_strip_tags($dataSet, true);

        $this->assertEquals($expected, $dataSet);
    }
}
