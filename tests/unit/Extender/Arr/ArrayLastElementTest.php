<?php

namespace Fulcrum\Tests\Unit\Extender\Arr;

use Fulcrum\Tests\Unit\UnitTestCase;

class ArrayLastElementTest extends UnitTestCase
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

    public function testGetLast()
    {
        $data = [
            'user_id'    => 104,
            'name'       => 'Bob Jones',
            'has_access' => false,
        ];
        $this->assertFalse(array_get_last_element($data));

        $data = ['Tonya', 'Sally', 'Bob', 'Nate'];
        $this->assertEquals('Nate', array_get_last_element($data));
        $this->assertNotEquals('Tonya', array_get_last_element($data));

        $data     = [4, 3, 892, 122];
        $expected = 122;
        $this->assertEquals($expected, array_get_last_element($data));
        $expected = 4;
        $this->assertNotEquals($expected, array_get_last_element($data));

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
            'user_id'    => 104,
            'name'       => 'Bob Jones',
            'has_access' => false,
        ];
        $this->assertEquals($expected, array_get_last_element($data));
        $results  = array_get_last_element($data);
        $expected = 3;
        $this->assertCount($expected, $results);
        $this->assertEquals('Bob Jones', $results['name']);
        $this->assertFalse($results['has_access']);
    }
}
