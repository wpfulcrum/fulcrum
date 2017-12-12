<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class PullTest extends UnitTestCase
{
    protected $tonya;
    protected $sally;
    protected $bennie;
    protected $developers = [];

    public function setUp()
    {
        parent::setUp();

        $this->tonya  = [
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
        $this->sally  = [
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
        $this->bennie = [
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

        $this->developers = [
            'tonya'  => $this->tonya,
            'sally'  => $this->sally,
            'bennie' => $this->bennie,
        ];
    }

    public function testPullEmptyArray()
    {
        $emptyArray = [];
        $this->assertNull(DotArray::pull($emptyArray, 'name'));
        $this->assertNull(DotArray::pull($emptyArray, 'email'));

        $this->assertNull(array_pull($emptyArray, 'name'));
        $this->assertNull(array_pull($emptyArray, 'email'));
    }

    public function testPullInvalidKey()
    {
        $this->assertNull(DotArray::pull($this->tonya, 'invalid_key'));
        $this->assertNull(array_pull($this->tonya, 'invalid_key'));
    }

    public function testPullInvalidKeyDefaultReturned()
    {
        $this->assertEquals([], DotArray::pull($this->tonya, 'invalid_key', []));
        $this->assertEquals(false, DotArray::pull($this->tonya, 'invalid_key', false));
        $this->assertEquals('', DotArray::pull($this->tonya, 'social.invalid_key', ''));
        $this->assertEquals('foo', DotArray::pull($this->tonya, 'languages.invalid_key', 'foo'));

        $this->assertEquals([], array_pull($this->tonya, 'invalid_key', []));
        $this->assertEquals(false, array_pull($this->tonya, 'invalid_key', false));
        $this->assertEquals('', array_pull($this->tonya, 'social.invalid_key', ''));
        $this->assertEquals('foo', array_pull($this->tonya, 'languages.invalid_key', 'foo'));
    }

    public function testPull()
    {
        $expected = $this->sally;
        unset($expected['user_id']);
        $expectedNumber = 201;
        $this->assertEquals($expectedNumber, DotArray::pull($this->sally, 'user_id'));
        $this->assertArrayNotHasKey('user_id', $this->sally);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->sally;
        unset($expected['email']);
        $this->assertEquals('bar', DotArray::pull($this->sally, 'email'));
        $this->assertArrayNotHasKey('email', $this->sally);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->sally;
        unset($expected['social']);
        $this->assertEquals([
            'twitter' => '@sally',
        ], DotArray::pull($this->sally, 'social'));
        $this->assertArrayNotHasKey('social', $this->sally);
        $this->assertEquals($expected, $this->sally);
    }

    public function testPullAPI()
    {
        $expected = $this->sally;
        unset($expected['user_id']);
        $expectedNumber = 201;
        $this->assertEquals($expectedNumber, array_pull($this->sally, 'user_id'));
        $this->assertArrayNotHasKey('user_id', $this->sally);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->sally;
        unset($expected['email']);
        $this->assertEquals('bar', array_pull($this->sally, 'email'));
        $this->assertArrayNotHasKey('email', $this->sally);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->sally;
        unset($expected['social']);
        $this->assertEquals([
            'twitter' => '@sally',
        ], array_pull($this->sally, 'social'));
        $this->assertArrayNotHasKey('social', $this->sally);
        $this->assertEquals($expected, $this->sally);
    }

    public function testPullNested()
    {
        $expected = $this->sally;
        unset($expected['social']['twitter']);
        $this->assertEquals('@sally', DotArray::pull($this->sally, 'social.twitter'));
        $this->assertArrayNotHasKey('twitter', $this->sally['social']);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->bennie;
        unset($expected['languages']['fav']);
        $this->assertEquals('Ruby', DotArray::pull($this->bennie, 'languages.fav'));
        $this->assertArrayHasKey('languages', $this->bennie);
        $this->assertArrayNotHasKey('fav', $this->bennie['languages']);
        $this->assertEquals($expected, $this->bennie);

        // Let's go deeper.
        $expected = $this->developers;
        unset($expected['bennie']['email']);
        $this->assertEquals('baz', DotArray::pull($this->developers, 'bennie.email'));
        $this->assertArrayNotHasKey('email', $this->developers['bennie']);
        $this->assertEquals($expected, $this->developers);

        unset($expected['tonya']['social']['twitter']);
        $this->assertEquals('@tonya', DotArray::pull($this->developers, 'tonya.social.twitter'));
        $this->assertArrayHasKey('social', $this->developers['tonya']);
        $this->assertArrayNotHasKey('twitter', $this->developers['tonya']['social']);
        $this->assertEquals($expected, $this->developers);
    }

    public function testPullNestedAPI()
    {
        $expected = $this->sally;
        unset($expected['social']['twitter']);
        $this->assertEquals('@sally', array_pull($this->sally, 'social.twitter'));
        $this->assertArrayNotHasKey('twitter', $this->sally['social']);
        $this->assertEquals($expected, $this->sally);

        $expected = $this->bennie;
        unset($expected['languages']['fav']);
        $this->assertEquals('Ruby', array_pull($this->bennie, 'languages.fav'));
        $this->assertArrayHasKey('languages', $this->bennie);
        $this->assertArrayNotHasKey('fav', $this->bennie['languages']);
        $this->assertEquals($expected, $this->bennie);

        // Let's go deeper.
        $expected = $this->developers;
        unset($expected['bennie']['email']);
        $this->assertEquals('baz', array_pull($this->developers, 'bennie.email'));
        $this->assertArrayNotHasKey('email', $this->developers['bennie']);
        $this->assertEquals($expected, $this->developers);

        unset($expected['tonya']['social']['twitter']);
        $this->assertEquals('@tonya', array_pull($this->developers, 'tonya.social.twitter'));
        $this->assertArrayHasKey('social', $this->developers['tonya']);
        $this->assertArrayNotHasKey('twitter', $this->developers['tonya']['social']);
        $this->assertEquals($expected, $this->developers);
    }
}
