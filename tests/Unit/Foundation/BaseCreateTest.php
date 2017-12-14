<?php

namespace Fulcrum\Tests\Unit\Foundation;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Tests\Unit\Foundation\Stubs\FooBaseStub;
use Fulcrum\Tests\Unit\UnitTestCase;
use Mockery;

class BaseCreateTest extends UnitTestCase
{
    protected $fulcrumMock;

    protected function setUp()
    {
        parent::setUp();
        $this->fulcrumMock = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function testShouldCreate()
    {
        $config = ConfigFactory::create([
           'foo' => 'bar',
        ]);
        $stub = new FooBaseStub($config, $this->fulcrumMock);
        $this->assertInstanceOf(FooBaseStub::class, $stub);
        $this->assertEquals($config, $stub->get('config'));
    }

    public function testShouldReturnDefaultWhenPropertyDoesNotExist()
    {
        $config = ConfigFactory::create([
            'foo' => 'bar',
        ]);
        $stub = new FooBaseStub($config, $this->fulcrumMock);
        $this->assertNull($stub->get('donotexistsilly'));
        $this->assertFalse($stub->get('donotexistsilly', false));
    }

    public function testConfigHas()
    {
        $config = ConfigFactory::create([
            'foo' => 'bar',
        ]);
        $stub = new FooBaseStub($config, $this->fulcrumMock);
        $this->assertTrue($stub->configHas('foo'));
        $this->assertFalse($stub->configHas('bar'));
        $this->assertFalse($stub->configHas('foobar'));
    }

    public function testGetDefaultsFileWhenNoDefaultFileSpecified()
    {
        $config = ConfigFactory::create([
            'foo' => 'bar',
        ]);
        $stub = new FooBaseStub($config, $this->fulcrumMock);
        $this->assertSame('foo/foobasestub.php', $stub::getDefaultsFile('foo'));
    }
}
