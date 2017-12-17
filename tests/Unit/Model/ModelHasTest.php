<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelHasTest extends UnitTestCase
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

    public function testShouldReturnFalse()
    {
        $model = new Model();
        $this->assertFalse($model->has('foo'));
        $this->assertFalse($model->has('foo.bar'));

        $model = new Model($this->data);
        $this->assertFalse($model->has('doesnotexist'));
    }

    public function testShouldReturnTrueWhenHasItem()
    {
        $model = new Model($this->data);
        $this->assertTrue($model->has('foo'));
        $this->assertTrue($model->has('foo.platform'));
        $this->assertTrue($model->has('bar'));
        $this->assertTrue($model->has('bar.baz.who'));
        $this->assertTrue($model->has('bar.baz.someNumber'));
    }
}
