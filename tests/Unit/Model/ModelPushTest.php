<?php

namespace Fulcrum\Tests\Unit\Model;

use Fulcrum\Model\Model;
use Fulcrum\Tests\Unit\UnitTestCase;

class ModelPushTest extends UnitTestCase
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

    public function testShouldPushNewElements()
    {
        $model = new Model($this->data);

        $actual = $model->push('array', 'zyxw');
        $this->assertContains('zyxw', $actual);
        $this->assertContains('zyxw', $model->get('array'));
    }

    public function testShouldCreateNewElements()
    {
        $model = new Model($this->data);

        $actual = $model->push('oof', 'Hi there');
        $this->assertContains('Hi there', $actual);
        $this->assertContains('Hi there', $model->get('oof'));

        $actual = $model->push('foo', 'Element 0');
        $this->assertContains('Element 0', $actual);
        $this->assertContains('Element 0', $model->get('foo.0'));
        $expected   = $this->data['foo'];
        $expected[] = 'Element 0';
        $this->assertEquals($expected, $model->get('foo'));
    }
}
