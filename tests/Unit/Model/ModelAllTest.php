<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelAllTest extends UnitTestCase
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

    public function testShouldReturnEmptyArray()
    {
        $model = new Model();
        $this->assertEmpty($model->getAll());
        $this->assertEquals([], $model->getAll());
    }

    public function testShouldReturnAllItems()
    {
        $model     = new Model($this->data);
        $dataItems = $model->getAll();
        $this->assertEquals($this->data, $dataItems);

        $this->assertArrayHasKey('foo', $dataItems);
        $this->assertArrayHasKey('bar', $dataItems);
        $this->assertArrayHasKey('array', $dataItems);
        $this->assertFalse(isset($dataItems['doesnotexist']));
    }
}
