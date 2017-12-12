<?php

namespace Fulcrum\Tests\Unit\Extender\Arr;

use Fulcrum\Tests\Unit\UnitTestCase;

class ArrayPrependTest extends UnitTestCase
{
    public function testEmpty()
    {
        $emptyArray = [];

        $this->assertEquals([10], array_prepend($emptyArray, 10));
        $this->assertEquals([[]], array_prepend($emptyArray, []));

        $data = [
            'foo'   => 'bar',
            'apple' => 'orange',
        ];
        $this->assertEquals([$data], array_prepend($emptyArray, $data));
    }

    public function testPrepend()
    {
        $dataSet = [
            'names'  => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            'emails' => [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $twitterHandles = [
            'developer1' => '@foo',
            'developer2' => '@bar',
            'developer3' => '@baz',
        ];

        $expected = [
            [
                'developer1' => '@foo',
                'developer2' => '@bar',
                'developer3' => '@baz',
            ],
            'names'  => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            'emails' => [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $result = array_prepend($dataSet, $twitterHandles);
        $this->assertEquals($expected, $result);
        $expectedValue = 3;
        $this->assertCount($expectedValue, $result);
        $this->assertEquals($twitterHandles, $result[0]);
        $expectedValue = 0;
        $this->assertArrayHasKey($expectedValue, $result);
        $this->assertEquals('baz', $result['emails']['developer3']);
    }

    public function testPrependReindex()
    {
        $dataSet = [
            [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $twitterHandles = [
            'developer1' => '@foo',
            'developer2' => '@bar',
            'developer3' => '@baz',
        ];

        $expected = [
            [
                'developer1' => '@foo',
                'developer2' => '@bar',
                'developer3' => '@baz',
            ],
            [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $result = array_prepend($dataSet, $twitterHandles);
        $this->assertEquals($expected, $result);
        $expected = 3;
        $this->assertCount($expected, $result);
        $this->assertEquals($twitterHandles, $result[0]);
        $expected = 2;
        $this->assertArrayHasKey($expected, $result);
        $this->assertEquals([
            'developer1' => 'Tonya',
            'developer2' => 'Sally',
            'developer3' => 'Mike',
        ], $result[1]);
    }

    public function testKey()
    {
        $data     = ['foo', 'bar', 'apple', 'baseball', 'baz'];
        $expected = [
            0 => 'green',
            1 => 'foo',
            2 => 'bar',
            3 => 'apple',
            4 => 'baseball',
            5 => 'baz',
        ];
        $this->assertEquals($expected, array_prepend($data, 'green'));

        $dataSet = [
            'names'  => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            'emails' => [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $twitterHandles = [
            'developer1' => '@foo',
            'developer2' => '@bar',
            'developer3' => '@baz',
        ];

        $expected = [
            'twitter' => [
                'developer1' => '@foo',
                'developer2' => '@bar',
                'developer3' => '@baz',
            ],
            'names'   => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            'emails'  => [
                'developer1' => 'foo',
                'developer2' => 'bar',
                'developer3' => 'baz',
            ],
        ];

        $result = array_prepend($dataSet, $twitterHandles, 'twitter');
        $this->assertEquals($expected, $result);
        $expected = 3;
        $this->assertCount($expected, $result);
        $this->assertArrayHasKey('twitter', $result);
        $this->assertEquals($twitterHandles, $result['twitter']);
        $this->assertEquals('@bar', $result['twitter']['developer2']);
    }

    public function testIndexedKey()
    {
        $data = [
            'foo'   => 'bar',
            'apple' => 'orange',
        ];

        $expected = [
            15      => 'green',
            'foo'   => 'bar',
            'apple' => 'orange',
        ];

        $this->assertEquals($expected, array_prepend($data, 'green', 15));

        $data   = ['foo', 'bar', 'apple', 'baseball', 'baz'];
        $result = array_prepend($data, 'green', 152);

        $expected = [
            152 => 'green',
            0   => 'foo',
            1   => 'bar',
            2   => 'apple',
            3   => 'baseball',
            4   => 'baz',
        ];

        $this->assertEquals($expected, $result);
        $expected = 6;
        $this->assertCount($expected, $result);
        $this->assertEquals('apple', $result[2]);
    }
}
