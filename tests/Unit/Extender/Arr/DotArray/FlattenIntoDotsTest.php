<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class ZFlattenIntoDotsTest extends UnitTestCase
{
    public function testShouldNotChangeFlatArrays()
    {
        $data = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
        ];

        $this->assertEquals($data, DotArray::flattenIntoDots($data));
        $this->assertEquals($data, array_flatten_into_dots($data));
    }

    public function testShouldDeeplyFlatten()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
            ],
        ];
        $expected = [
            'user_id'        => 504,
            'name'           => 'Bob Jones',
            'social.twitter' => '@bobjones',
        ];

        $this->assertEquals($expected, DotArray::flattenIntoDots($data));
        $this->assertEquals($expected, array_flatten_into_dots($data));

        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
                'website' => 'https://bobjones.com',
            ],
        ];
        $expected = [
            'user_id'        => 504,
            'name'           => 'Bob Jones',
            'social.twitter' => '@bobjones',
            'social.website' => 'https://bobjones.com',
        ];

        $this->assertEquals($expected, DotArray::flattenIntoDots($data));
        $this->assertEquals($expected, array_flatten_into_dots($data));

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
            'user_id'                 => 504,
            'name'                    => 'Bob Jones',
            'social.twitter'          => '@bobjones',
            'social.website.personal' => 'https://bobjones.com',
            'social.website.business' => 'https://foo.com',
        ];

        $this->assertEquals($expected, DotArray::flattenIntoDots($data));
        $this->assertEquals($expected, array_flatten_into_dots($data));

        $data     = [
            'user_id'   => 504,
            'name'      => 'Bob Jones',
            'social'    => [
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
            'user_id'                  => 504,
            'name'                     => 'Bob Jones',
            'social.twitter'           => '@bobjones',
            'social.website.personal'  => 'https://bobjones.com',
            'social.website.business'  => 'https://foo.com',
            'languages.php.procedural' => true,
            'languages.php.oop'        => false,
            'languages.javascript'     => true,
            'languages.ruby'           => false,
        ];

        $this->assertEquals($expected, DotArray::flattenIntoDots($data));
        $this->assertEquals($expected, array_flatten_into_dots($data));
    }

    public function testShouldAddPrefix()
    {
        $data     = [
            'user_id' => 504,
            'name'    => 'Bob Jones',
            'social'  => [
                'twitter' => '@bobjones',
            ],
        ];
        $expected = [
            'fulcrum_user_id'        => 504,
            'fulcrum_name'           => 'Bob Jones',
            'fulcrum_social.twitter' => '@bobjones',
        ];

        $this->assertEquals($expected, DotArray::flattenIntoDots($data, 'fulcrum_'));
        $this->assertEquals($expected, array_flatten_into_dots($data, 'fulcrum_'));
    }
}
