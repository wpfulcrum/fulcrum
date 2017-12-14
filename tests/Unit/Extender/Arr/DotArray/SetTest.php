<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class SetTest extends UnitTestCase
{
    protected $tom;
    protected $sarah;
    protected $charlie;
    protected $developers = [];

    public function setUp()
    {
        parent::setUp();
        $this->tom     = [
            'user_id'   => 1,
            'name'      => 'Tom',
            'email'     => 'foo',
            'social'    => [
                'twitter' => '@tom',
            ],
            'languages' => [
                'fav' => 'PHP',
            ],
        ];
        $this->sarah   = [
            'user_id'   => 201,
            'name'      => 'Sarah',
            'email'     => 'bar',
            'social'    => [
                'twitter' => '@sarah',
            ],
            'languages' => [
                'fav' => 'JavaScript',
            ],
        ];
        $this->charlie = [
            'user_id'   => 504,
            'name'      => 'Charlie',
            'email'     => 'baz',
            'social'    => [
                'twitter' => '@charlie',
            ],
            'languages' => [
                'fav' => 'Python',
            ],
        ];

        $this->developers = [
            'tom'     => $this->tom,
            'sarah'   => $this->sarah,
            'charlie' => $this->charlie,
        ];
    }

    public function testSetNoKeyGiven()
    {
        $this->assertFalse(DotArray::set($this->indexArray, null, false));
        $this->assertNull(DotArray::set($this->indexArray, null, null));
        $this->assertTrue(DotArray::set($this->indexArray, null, true));
        $this->assertEquals([], DotArray::set($this->indexArray, null, []));

        $this->assertFalse(array_set($this->indexArray, null, false));
        $this->assertNull(array_set($this->indexArray, null, null));
        $this->assertTrue(array_set($this->indexArray, null, true));
        $this->assertEquals([], array_set($this->indexArray, null, []));
    }

    public function testSetReplaceValue()
    {
        $expected          = $this->tom;
        $expected['email'] = 'tom@foo.com';
        $this->assertEquals($expected, DotArray::set($this->tom, 'email', 'tom@foo.com'));

        $expected         = $this->sarah;
        $expected['name'] = 'Sarah Jones';
        $this->assertEquals($expected, DotArray::set($this->sarah, 'name', 'Sarah Jones'));
    }

    public function testSetReplaceValueAPI()
    {
        $expected          = $this->tom;
        $expected['email'] = 'tom@foo.com';

        $this->assertEquals($expected, array_set($this->tom, 'email', 'tom@foo.com'));

        $expected         = $this->sarah;
        $expected['name'] = 'Sarah Jones';
        $this->assertEquals($expected, array_set($this->sarah, 'name', 'Sarah Jones'));
    }

    public function testSetReplaceValueDotNotation()
    {
        $expected                      = $this->tom;
        $expected['social']['twitter'] = '@tommy';

        $this->assertEquals([
            'twitter' => '@tommy',
        ], DotArray::set($this->tom, 'social.twitter', '@tommy'));
        $this->assertEquals($expected, $this->tom);

        $expected['languages']['fav'] = 'Python';
        $this->assertEquals([
            'fav' => 'Python',
        ], DotArray::set($this->tom, 'languages.fav', 'Python'));

        $this->assertEquals($expected, $this->tom);
    }

    public function testSetReplaceValueDotNotationAPI()
    {
        $expected                      = $this->tom;
        $expected['social']['twitter'] = '@tommy';

        $this->assertEquals(
            [
                'twitter' => '@tommy',
            ],
            array_set($this->tom, 'social.twitter', '@tommy')
        );
        $this->assertEquals($expected, $this->tom);

        $expected['languages']['fav'] = 'Python';
        $this->assertEquals(
            [
                'fav' => 'Python',
            ],
            array_set($this->tom, 'languages.fav', 'Python')
        );

        $this->assertEquals($expected, $this->tom);
    }

    public function testSetNoElement()
    {
        $expected        = $this->tom;
        $expected['foo'] = 'foobar';

        $this->assertEquals($expected, DotArray::set($this->tom, 'foo', 'foobar'));

        $expected['social']['website'] = 'https://google.com';

        $this->assertEquals([
            'twitter' => '@tom',
            'website' => 'https://google.com',
        ], DotArray::set($this->tom, 'social.website', 'https://google.com'));
        $this->assertEquals($expected, $this->tom);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals([
            'fav'      => 'PHP',
            'leastFav' => 'Assembler',
        ], DotArray::set($this->tom, 'languages.leastFav', 'Assembler'));

        $this->assertEquals($expected, $this->tom);
    }

    public function testSetNoElementAPI()
    {
        $expected        = $this->tom;
        $expected['foo'] = 'foobar';

        $this->assertEquals($expected, array_set($this->tom, 'foo', 'foobar'));

        $expected['social']['website'] = 'https://google.com';

        $this->assertEquals([
            'twitter' => '@tom',
            'website' => 'https://google.com',
        ], array_set($this->tom, 'social.website', 'https://google.com'));
        $this->assertEquals($expected, $this->tom);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals([
            'fav'      => 'PHP',
            'leastFav' => 'Assembler',
        ], array_set($this->tom, 'languages.leastFav', 'Assembler'));

        $this->assertEquals($expected, $this->tom);
    }
}
