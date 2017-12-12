<?php

namespace Fulcrum\Tests\Unit\Extender\Arr;

use Fulcrum\Tests\Unit\UnitTestCase;

class ArrayFirstElementTest extends UnitTestCase
{
    public function testDefaultValue()
    {
        $emptyArray = [];

        $this->assertNull(array_get_first_element($emptyArray));
        $this->assertFalse(array_get_first_element($emptyArray, false));
        $this->assertTrue(array_get_first_element($emptyArray, true));
        $this->assertEmpty(array_get_first_element($emptyArray, ''));
        $this->assertEquals([], array_get_first_element($emptyArray, []));
    }

    public function testGetFirst()
    {
        $data = [
            'user_id'    => 104,
            'name'       => 'Bob Jones',
            'has_access' => false,
        ];
        $this->assertEquals(104, array_get_first_element($data));

        $data = ['Tonya', 'Sally', 'Bob', 'Nate'];
        $this->assertEquals('Tonya', array_get_first_element($data));
        $this->assertNotEquals('Sally', array_get_first_element($data));

        $data     = [4, 3, 892, 122];
        $expected = 4;
        $this->assertEquals($expected, array_get_first_element($data));
        $expected = 122;
        $this->assertNotEquals($expected, array_get_first_element($data));

        $data = [
            101 => [
                'user_id'    => 101,
                'name'       => 'Tonya',
                'email'      => 'tonya@foo.com',
                'has_access' => true,
            ],
            102 => [
                'user_id'    => 102,
                'name'       => 'Sally',
                'email'      => 'sally@foo.com',
                'has_access' => false,
            ],
            103 => [
                'user_id'    => 103,
                'name'       => 'Rose',
                'has_access' => true,
            ],
            104 => [
                'user_id'    => 104,
                'name'       => 'Bob Jones',
                'has_access' => false,
            ],
        ];

        $expected = [
            'user_id'    => 101,
            'name'       => 'Tonya',
            'email'      => 'tonya@foo.com',
            'has_access' => true,
        ];
        $this->assertEquals($expected, array_get_first_element($data));
        $results  = array_get_first_element($data);
        $expected = 4;
        $this->assertCount($expected, $results);
        $this->assertTrue($results['has_access']);
    }
}
