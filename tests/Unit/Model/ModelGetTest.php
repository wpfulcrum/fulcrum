<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelGetTest extends UnitTestCase
{
    protected $data;

    /**
     * Prepares the test environment before each test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->data = require __DIR__ . '/fixtures/data.php';
    }

    public function testShouldReturnNull()
    {
        $model = new Model();
        $this->assertNull($model->get('foo'));
        $this->assertNull($model->get('foo.bar'));
    }

    public function testShouldReturnDefault()
    {
        $model = new Model();
        $this->assertEquals('foo', $model->get('foo', 'foo'));
        $this->assertEquals('foobar', $model->get('foo.bar', 'foobar'));

        $model = new Model($this->data);
        $this->assertEquals(14, $model->get('doesnotexist', 14));
    }

    public function testShouldReturnValue()
    {
        $model = new Model($this->data);
        $this->assertEquals('WordPress', $model->get('foo.platform'));
        $this->assertEquals('Beans', $model->get('foo.theme'));
        $this->assertNull($model->get('foo.site'));
        $this->assertEquals('Tonya', $model->get('bar.baz.who'));
        $this->assertEquals(300, $model->get('bar.baz.someNumber'));
    }

    public function testShouldReturnValuesForMany()
    {
        $model = new Model($this->data);

        $this->assertEquals([
            'foo.platform' => 'WordPress',
            'foo.theme'    => 'Beans',
        ], $model->get(['foo.platform' => '', 'foo.theme' => '']));

        $this->assertEquals([
            'foo.platform' => 'WordPress',
            'bar.baz'      => [
                'who'        => 'Tonya',
                'someNumber' => 300,
            ],
        ], $model->get(['foo.platform' => '', 'bar.baz' => '']));
    }
}
