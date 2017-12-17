<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelRemoveTest extends UnitTestCase
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

    public function testShouldNotFailWhenDoesNotExist()
    {
        $model = new Model($this->data);

        $this->assertEquals($this->data, $model->getAll());
        $model->remove('doesnotexist');
        $this->assertEquals($this->data, $model->getAll());

        $this->assertEquals($this->data['foo'], $model->get('foo'));
        $model->remove('foo.doesnotexist');
        $this->assertEquals($this->data['foo'], $model->get('foo'));
    }

    public function testShouldRemove()
    {
        $model = new Model($this->data);

        $this->assertTrue($model->has('foo.platform'));
        $model->remove('foo.platform');
        $this->assertFalse($model->has('foo.platform'));

        $this->assertTrue($model->has('foo'));
        $model->remove('foo');
        $this->assertFalse($model->has('foo'));

        $this->assertTrue($model->has('bar.baz'));
        $model->remove('bar.baz');
        $this->assertFalse($model->has('bar.baz'));

        $this->assertEquals([
            'bar'   => [],
            'array' => [
                'aaa',
                'bbb',
            ],
        ], $model->getAll());
    }
}
