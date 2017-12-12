<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\DotArray;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Arr\DotArray;

class ForgetTest extends UnitTestCase
{
    public function testRemove()
    {
        $dataSet = [
            'names' => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
        ];

        DotArray::remove($dataSet, 'names.developer3');
        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer1']));
        $this->assertArrayHasKey('developer2', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer2']));
        $this->assertFalse(isset($dataSet['names']['developer3']));
        $this->assertArraySubset(['names' => ['developer1' => 'Tonya']], $dataSet);
        $this->assertArraySubset(['names' => ['developer2' => 'Sally']], $dataSet);
    }

    public function testRemoveArrayKeys()
    {
        $dataSet = [
            'names' => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
        ];

        DotArray::remove($dataSet, ['names.developer2', 'names.developer3']);
        $expectedCount = 1;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer1']));

        $this->assertFalse(isset($dataSet['names']['developer2']));
        $this->assertFalse(isset($dataSet['names']['developer3']));
        $this->assertEquals(['names' => ['developer1' => 'Tonya']], $dataSet);
    }

    public function testRemoveAPI()
    {
        $dataSet = [
            'names' => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
        ];

        array_remove($dataSet, 'names.developer3');
        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer1']));
        $this->assertArrayHasKey('developer2', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer2']));
        $this->assertFalse(isset($dataSet['names']['developer3']));
        $this->assertArraySubset(['names' => ['developer1' => 'Tonya']], $dataSet);
        $this->assertArraySubset(['names' => ['developer2' => 'Sally']], $dataSet);
    }

    public function testRemoveArrayKeysAPI()
    {
        $dataSet = [
            'names' => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
        ];

        array_remove($dataSet, ['names.developer2', 'names.developer3']);
        $expectedCount = 1;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertTrue(isset($dataSet['names']['developer1']));

        $this->assertFalse(isset($dataSet['names']['developer2']));
        $this->assertFalse(isset($dataSet['names']['developer3']));
        $this->assertEquals(['names' => ['developer1' => 'Tonya']], $dataSet);
    }

    public function testRemoveDeeper()
    {
        $dataSet  = [
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
        $expected = [
            'names'  => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
                'developer3' => 'Mike',
            ],
            'emails' => [
                'developer1' => 'foo',
                'developer2' => 'bar',
            ],
        ];

        DotArray::remove($dataSet, 'emails.developer3');
        $expectedCount = 3;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertArrayHasKey('developer2', $dataSet['names']);
        $this->assertArrayHasKey('developer3', $dataSet['names']);
        $this->assertArraySubset([
            'developer1' => 'Tonya',
            'developer2' => 'Sally',
            'developer3' => 'Mike',
        ], $dataSet['names']);

        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['emails']);
        $this->assertArrayHasKey('developer1', $dataSet['emails']);
        $this->assertArrayHasKey('developer2', $dataSet['emails']);
        $this->assertFalse(isset($dataSet['emails']['developer3']));

        $this->assertArraySubset([
            'developer1' => 'foo',
            'developer2' => 'bar',
        ], $dataSet['emails']);

        $this->assertEquals($expected, $dataSet);
    }

    public function testRemoveDeeperAPI()
    {
        $dataSet  = [
            'names'  => [
                'dev1' => 'Tonya',
                'dev2' => 'Sally',
                'dev3' => 'Mike',
            ],
            'emails' => [
                'dev1' => 'foo',
                'dev2' => 'bar',
                'dev3' => 'baz',
            ],
        ];
        $expected = [
            'names'  => [
                'dev1' => 'Tonya',
                'dev2' => 'Sally',
                'dev3' => 'Mike',
            ],
            'emails' => [
                'dev1' => 'foo',
                'dev2' => 'bar',
            ],
        ];

        array_remove($dataSet, 'emails.dev3');

        $expectedCount = 3;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('dev1', $dataSet['names']);
        $this->assertArrayHasKey('dev2', $dataSet['names']);
        $this->assertArrayHasKey('dev3', $dataSet['names']);
        $this->assertArraySubset([
            'dev1' => 'Tonya',
            'dev2' => 'Sally',
            'dev3' => 'Mike',
        ], $dataSet['names']);

        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['emails']);
        $this->assertArrayHasKey('dev1', $dataSet['emails']);
        $this->assertArrayHasKey('dev2', $dataSet['emails']);
        $this->assertFalse(isset($dataSet['emails']['dev3']));

        $this->assertArraySubset([
            'dev1' => 'foo',
            'dev2' => 'bar',
        ], $dataSet['emails']);

        $this->assertEquals($expected, $dataSet);
    }

    public function testRemoveDeeperArrayOfKeys()
    {
        $dataSet  = [
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
        $expected = [
            'names'  => [
                'developer1' => 'Tonya',
                'developer2' => 'Sally',
            ],
            'emails' => [
                'developer1' => 'foo',
                'developer2' => 'bar',
            ],
        ];

        DotArray::remove($dataSet, ['names.developer3', 'emails.developer3']);

        $this->assertEquals($expected, $dataSet);

        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['names']);
        $this->assertArrayHasKey('developer1', $dataSet['names']);
        $this->assertArrayHasKey('developer2', $dataSet['names']);
        $this->assertFalse(array_key_exists('developer3', $dataSet['names']));
        $this->assertArraySubset([
            'developer1' => 'Tonya',
            'developer2' => 'Sally',
        ], $dataSet['names']);

        $expectedCount = 2;
        $this->assertCount($expectedCount, $dataSet['emails']);
        $this->assertArrayHasKey('developer1', $dataSet['emails']);
        $this->assertArrayHasKey('developer2', $dataSet['emails']);
        $this->assertFalse(array_key_exists('developer3', $dataSet['emails']));

        $this->assertArraySubset([
            'developer1' => 'foo',
            'developer2' => 'bar',
        ], $dataSet['emails']);
    }
}
