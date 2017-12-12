<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class AddTest extends UnitTestCase
{
    protected $bob;
    protected $mary;
    protected $james;
    protected $developers = [];

    public function setUp()
    {
        parent::setUp();

        $this->bob  = [
            'user_id'   => 1,
            'name'      => 'Bob',
            'email'     => 'foo',
            'social'    => [
                'twitter' => '@bob',
            ],
            'languages' => [
                'fav' => 'PHP',
            ],
        ];
        $this->mary  = [
            'user_id'   => 201,
            'name'      => 'Mary',
            'email'     => 'bar',
            'social'    => [
                'twitter' => '@mary',
            ],
            'languages' => [
                'fav' => 'JavaScript',
            ],
        ];
        $this->james = [
            'user_id'   => 504,
            'name'      => 'Bennie',
            'email'     => 'baz',
            'social'    => [
                'twitter' => '@james',
            ],
            'languages' => [
                'fav' => 'Ruby',
            ],
        ];

        $this->developers = [
            'bob'  => $this->bob,
            'mary'  => $this->mary,
            'james' => $this->james,
        ];
    }

    public function testAddNoKeyGiven()
    {
        $this->assertEquals(
            $this->developers,
            DotArray::add($this->developers, null, false)
        );
        $this->assertEquals(
            $this->developers,
            DotArray::add($this->developers, null, null)
        );
        $this->assertEquals(
            $this->developers,
            DotArray::add($this->developers, null, true)
        );
        $this->assertEquals(
            $this->developers,
            DotArray::add($this->developers, null, [])
        );
    }

    public function testAddNoKeyGivenAPI()
    {
        $this->assertEquals($this->developers, array_add($this->developers, null, false));
        $this->assertEquals($this->developers, array_add($this->developers, null, null));
        $this->assertEquals($this->developers, array_add($this->developers, null, true));
        $this->assertEquals($this->developers, array_add($this->developers, null, []));
    }

    public function testSetKeyExists()
    {
        $this->assertEquals($this->bob, DotArray::add($this->bob, 'email', 'bob@foo.com'));
        $this->assertEquals($this->mary, DotArray::add($this->mary, 'name', 'Mary Jones'));
        $this->assertEquals($this->james, DotArray::add($this->james, 'name', $this->bob));
        $this->assertEquals($this->james, DotArray::add($this->james, 'user_id', 10));
    }

    public function testSetKeyExistsAPI()
    {
        $this->assertEquals($this->bob, array_add($this->bob, 'email', 'bob@foo.com'));
        $this->assertEquals($this->mary, array_add($this->mary, 'name', 'Mary Jones'));
        $this->assertEquals($this->james, array_add($this->james, 'name', $this->bob));
        $this->assertEquals($this->james, array_add($this->james, 'user_id', 10));
    }

    public function testAddNewElement()
    {
        $expected        = $this->bob;
        $expected['foo'] = 'foobar';

        $this->assertEquals($expected, DotArray::set($this->bob, 'foo', 'foobar'));
        $this->assertEquals($expected, DotArray::add($expected, 'user_id', 10));

        $expected['city'] = 'Two Rivers';
        $this->assertEquals($expected, DotArray::add($expected, 'city', 'Two Rivers'));

        $this->assertNotEquals($expected, $this->bob);
    }

    public function testAddNewElementAPI()
    {
        $expected        = $this->bob;
        $expected['foo'] = 'foobar';

        $this->assertEquals($expected, array_set($this->bob, 'foo', 'foobar'));
        $this->assertEquals($expected, array_add($expected, 'user_id', 10));

        $expected['city'] = 'Two Rivers';
        $this->assertEquals(
            $expected,
            array_add($expected, 'city', 'Two Rivers')
        );

        $this->assertNotEquals($expected, $this->bob);
    }

    public function testAddNewElementDotNotation()
    {
        $expected                      = $this->bob;
        $expected['social']['website'] = 'https://developer.mozilla.org';

        $this->assertEquals(
            $expected,
            DotArray::add($this->bob, 'social.website', 'https://developer.mozilla.org')
        );
        $this->assertNotEquals($expected, $this->bob);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals(
            $expected,
            DotArray::add($expected, 'languages.leastFav', 'Assembler')
        );

        $this->assertNotEquals($expected, $this->bob);
    }

    public function testAddNewElementDotNotationAPI()
    {
        $expected                      = $this->bob;
        $expected['social']['website'] = 'https://developer.mozilla.org';

        $this->assertEquals(
            $expected,
            array_add($this->bob, 'social.website', 'https://developer.mozilla.org')
        );
        $this->assertNotEquals($expected, $this->bob);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals(
            $expected,
            array_add($expected, 'languages.leastFav', 'Assembler')
        );

        $this->assertNotEquals($expected, $this->bob);
    }

    public function testAddNewElementDotNotationArray()
    {
        $expected                      = $this->bob;
        $expected['social']['website'] = 'https://developer.mozilla.org';

        $this->assertEquals(
            $expected,
            DotArray::add($this->bob, ['social', 'website'], 'https://developer.mozilla.org')
        );
        $this->assertNotEquals($expected, $this->bob);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals(
            $expected,
            DotArray::add($expected, ['languages', 'leastFav'], 'Assembler')
        );

        $this->assertNotEquals($expected, $this->bob);
    }

    public function testAddNewElementDotNotationArrayAPI()
    {
        $expected                      = $this->bob;
        $expected['social']['website'] = 'https://developer.mozilla.org';

        $this->assertEquals(
            $expected,
            array_add($this->bob, ['social', 'website'], 'https://developer.mozilla.org')
        );
        $this->assertNotEquals($expected, $this->bob);

        $expected['languages']['leastFav'] = 'Assembler';
        $this->assertEquals(
            $expected,
            array_add($expected, ['languages', 'leastFav'], 'Assembler')
        );

        $this->assertNotEquals($expected, $this->bob);
    }
}
