<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelSetTest extends UnitTestCase
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

    public function testShouldSetWhenNotInModel()
    {
        $model = new Model();

        $this->assertFalse($model->has('foo'));
        $model->set('foo', 'fooItem');
        $this->assertTrue($model->has('foo'));
        $this->assertEquals('fooItem', $model->get('foo'));

        // set deeply
        $this->assertFalse($model->has('bar.baz'));
        $model->set('bar.baz', 72);
        $this->assertTrue($model->has('bar.baz'));
        $this->assertEquals(72, $model->get('bar.baz'));
    }

    public function testShouldChangesValue()
    {
        $model = new Model($this->data);

        $this->assertTrue($model->has('foo.platform'));
        $this->assertEquals('WordPress', $model->get('foo.platform'));
        $model->set('foo.platform', 'Fulcrum');
        $this->assertEquals('Fulcrum', $model->get('foo.platform'));

        $data = 'Overwriting the array';
        $model->set('foo', $data);
        $this->assertEquals($data, $model->get('foo'));
    }
}
