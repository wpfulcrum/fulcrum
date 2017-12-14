<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class GetExceptTest extends UnitTestCase
{
    protected $data;

    public function setUp()
    {
        parent::setUp();

        $this->data = [
            'user_id'   => 504,
            'name'      => 'Bob Jones',
            'social'    => [
                'twitter' => '@bobjones',
                'website' => 'https://bobjones.com',
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
    }

    public function testNullKey()
    {
        $this->assertEquals($this->data, DotArray::getExcept($this->data, null));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, ''));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, false));
    }

    public function testInvalidKey()
    {
        $this->assertEquals($this->data, DotArray::getExcept($this->data, 'foo'));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, 'foo.bar'));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, ['foo', 'bar']));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, 'social.foo'));
        $this->assertEquals($this->data, DotArray::getExcept($this->data, ['social.foo', 'social.bar']));
    }

    public function testSingleExclude()
    {
        $expected = $this->data;
        unset($expected['social']['twitter']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, 'social.twitter'));
        $expected = $this->data;
        unset($expected['languages']['php']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, 'languages.php'));
        $expected = $this->data;
        unset($expected['languages']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, 'languages'));

        $expected = $this->data;
        unset($expected['social']['twitter']);
        $this->assertEquals($expected, array_get_except($this->data, 'social.twitter'));
        $expected = $this->data;
        unset($expected['languages']['ruby']);
        $this->assertEquals($expected, array_get_except($this->data, 'languages.ruby'));
        $expected = $this->data;
        unset($expected['social']);
        $this->assertEquals($expected, array_get_except($this->data, 'social'));
    }

    public function testMultipleExcludes()
    {
        $expected = $this->data;
        unset($expected['social']);
        unset($expected['languages']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, ['social', 'languages']));

        $expected = $this->data;
        unset($expected['social']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, ['social', 'languages.php.oop']));

        $expected = $this->data;
        unset($expected['languages']['ruby']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, ['languages.ruby', 'languages.php.oop']));

        $expected = $this->data;
        unset($expected['social']['website']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, DotArray::getExcept($this->data, ['social.website', 'languages.php.oop']));
    }

    public function testMultipleExcludesAPI()
    {
        $expected = $this->data;
        unset($expected['social']);
        unset($expected['languages']);
        $this->assertEquals($expected, array_get_except($this->data, ['social', 'languages']));

        $expected = $this->data;
        unset($expected['social']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, array_get_except($this->data, ['social', 'languages.php.oop']));

        $expected = $this->data;
        unset($expected['languages']['ruby']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, array_get_except($this->data, ['languages.ruby', 'languages.php.oop']));

        $expected = $this->data;
        unset($expected['social']['website']);
        unset($expected['languages']['php']['oop']);
        $this->assertEquals($expected, array_get_except($this->data, ['social.website', 'languages.php.oop']));
    }
}
