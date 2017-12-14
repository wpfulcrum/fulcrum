<?php

namespace Fulcrum\Tests\Unit\Foundation\Utility;

use Fulcrum\Tests\Unit\Foundation\Stubs\ArrayAccessStub;
use Fulcrum\Tests\Unit\Foundation\Stubs\FooStub;
use Fulcrum\Tests\Unit\UnitTestCase;

class HelpersTest extends UnitTestCase
{
    public function testShouldGetClassBasename()
    {
        $this->assertEquals('Baz', get_class_basename('Foo\Bar\Baz'));
        $this->assertEquals('Baz', get_class_basename('Baz'));
    }

    public function testShouldDoDataGet()
    {
        $object       = (object) [
            'users' => [
                'name' => ['Joe', 'TESTER'],
            ],
        ];
        $array        = [
            (object) [
                'users' => [
                    (object) ['name' => 'Joe'],
                ],
            ],
        ];
        $array_access = new ArrayAccessStub([
            'price' => 56,
            'user'  => new ArrayAccessStub([
                'name' => 'Mike',
            ]),
        ]);

        $this->assertEquals('Joe', get_data_item($object, 'users.name.0'));
        $this->assertEquals('Joe', get_data_item($array, '0.users.0.name'));
        $this->assertNull(get_data_item($array, '0.users.3'));
        $this->assertEquals('Not found', get_data_item($array, '0.users.3', 'Not found'));
        $this->assertEquals('Not found', get_data_item($array, '0.users.3', function () {
            return 'Not found';
        }));
        $this->assertEquals(56, get_data_item($array_access, 'price'));
        $this->assertEquals('Mike', get_data_item($array_access, 'user.name'));
        $this->assertEquals('void', get_data_item($array_access, 'foo', 'void'));
        $this->assertEquals('void', get_data_item($array_access, 'user.foo', 'void'));
        $this->assertNull(get_data_item($array_access, 'foo'));
        $this->assertNull(get_data_item($array_access, 'user.foo'));
    }

    public function testShouldReturnNullWhenGettingClassInfo()
    {
        $this->assertNull(get_calling_class_info('not-an-object'));
    }

    public function testShouldReturnClassInfo()
    {
        $object = new \stdClass();

        $this->assertInstanceOf(
            \ReflectionClass::class,
            get_calling_class_info($object)
        );
        $this->assertSame(
            'stdClass',
            get_calling_class_info($object)->getName()
        );

        $object = new FooStub();
        $this->assertSame(
            'Fulcrum\Tests\Unit\Foundation\Stubs\FooStub',
            get_calling_class_info($object)->getName()
        );
    }

    public function testShouldReturnNullWhenGettingCallingClassDir()
    {
        $this->assertNull(get_calling_class_directory('not-an-object'));
    }

    public function testShouldReturnDirWhenGettingCallingClass()
    {
        $this->assertSame(
            FULCRUM_TESTS_DIR . DIRECTORY_SEPARATOR . 'Foundation' . DIRECTORY_SEPARATOR . 'Stubs',
            get_calling_class_directory(new FooStub())
        );
    }

    public function testShouldGetValue()
    {
        $this->assertEquals('foo', get_default_value('foo'));
        $this->assertEquals('foo', get_default_value(function () {
            return 'foo';
        }));
    }

    public function testShouldGetObject()
    {
        $class              = new \stdClass();
        $class->name        = new \stdClass();
        $class->name->first = 'Tonya';

        $this->assertEquals('Tonya', get_object_item($class, 'name.first'));
    }

    public function testWithReturnsValue()
    {
        $this->assertEquals(10, with(10));
        $this->assertEquals('foo', with('foo'));
        $object = new \stdClass();
        $this->assertEquals($object, with($object));
    }

    public function testWithReturnsValueThroughCallback()
    {
        $this->assertEquals(10, with(5, function ($value) {
            return $value + 5;
        }));

        $this->assertEquals(8, with(2, function ($value) {
            return $value ** 3;
        }));

        $this->assertEquals('foobar', with('foo', function ($value) {
            return $value . 'bar';
        }));
    }
}
