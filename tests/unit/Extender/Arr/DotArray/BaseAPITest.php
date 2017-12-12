<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Tests\Unit\Extender\Arr\Stubs\ArrayAccessStub;
use Fulcrum\Tests\Unit\Extender\Arr\Stubs\DeveloperStub;
use Fulcrum\Extender\Arr\DotArray;

class BaseAPITest extends UnitTestCase
{
    protected $dataArray;

    public function setUp()
    {
        parent::setUp();

        $this->dataArray = [
            'name'      => 'Bob Jones',
            'social'    => [
                'twitter' => '@bobjones',
            ],
            'languages' => [
                'php'        => true,
                'javascript' => true,
                'ruby'       => false,
            ],
        ];
    }

    public function testIsArrayAccessible()
    {
        $this->assertTrue(is_array_accessible($this->dataArray));
        $this->assertFalse(is_array_accessible($this->dataArray['name']));
        $this->assertTrue(is_array_accessible($this->dataArray['social']));
        $this->assertTrue(is_array_accessible($this->dataArray['languages']));
        $this->assertFalse(is_array_accessible($this->dataArray['languages']['php']));
    }

    public function testIsArrayAccessibleObject()
    {
        $developer = new DeveloperStub([
            'name'   => 'Bob Jones',
            'email'  => 'bob.jones@foo.com',
            'social' => [
                'twitter' => '@bobjones',
            ],
        ]);

        $this->assertFalse(is_array_accessible($developer));
        $this->assertTrue(is_array_accessible($developer->social));
    }

    public function testIsArrayAccessibleArrayAccess()
    {
        $object = new ArrayAccessStub();
        $this->assertTrue(is_array_accessible($object));
    }

    public function testHasKeyOrOffsetArray()
    {
        $this->assertFalse(array_exists($this->dataArray, 'title'));
        $this->assertTrue(array_exists($this->dataArray, 'name'));
        $this->assertFalse(array_exists($this->dataArray, 'social.twitter'));
    }

    public function testHasKeyOrOffsetArrayAccess()
    {
        $developer = new ArrayAccessStub([
            'name'      => 'Bob Jones',
            'email'     => 'bob.jones@foo.com',
            'social'    => [
                'twitter' => '@bobjones',
            ],
            'languages' => [
                'php'        => true,
                'javascript' => true,
                'ruby'       => false,
            ],
        ]);

        $this->assertFalse(array_exists($developer, 'title'));
        $this->assertTrue(array_exists($developer, 'name'));
        $this->assertTrue(array_exists($developer, 'social'));
        $this->assertTrue(array_exists($developer->social, 'twitter'));
        $this->assertFalse(array_exists($developer, 'social.twitter'));
    }

    public function testHas()
    {
        $data = [
            'names' => [
                'developer' => 'Tonya',
            ],
        ];

        $this->assertTrue(DotArray::has($data, 'names.developer'));
        $this->assertFalse(DotArray::has($data, 'foo.bar'));
        $this->assertFalse(DotArray::has($data, 'keydoesntexist'));
        $this->assertFalse(DotArray::has($data, 'names.foo'));
    }

    public function testHasAPI()
    {
        $data = [
            'names' => [
                'developer' => 'Tonya',
            ],
        ];

        $this->assertTrue(array_has($data, 'names.developer'));
        $this->assertFalse(array_has($data, 'foo.bar'));
        $this->assertFalse(array_has($data, 'keydoesntexist'));
        $this->assertFalse(array_has($data, 'names.foo'));
    }

    public function testHasArrayAccess()
    {
        $developer = new ArrayAccessStub([
            'name'      => 'Bob Jones',
            'email'     => 'bob.jones@foo.com',
            'social'    => [
                'twitter' => '@bobjones',
            ],
            'languages' => [
                'php'        => true,
                'javascript' => true,
                'ruby'       => false,
            ],
        ]);

        $this->assertTrue(DotArray::has($developer, 'name'));
        $this->assertFalse(DotArray::has($developer, 'social.foo'));
        $this->assertFalse(DotArray::has($developer, 'keydoesntexist'));
        $this->assertFalse(DotArray::has($developer, 'user_id'));
        $this->assertTrue(DotArray::has($developer, 'social.twitter'));
        $this->assertTrue(DotArray::has($developer, 'languages.php'));
    }
}
