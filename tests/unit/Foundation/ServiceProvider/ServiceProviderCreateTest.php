<?php

namespace Fulcrum\Tests\Unit\Foundation\ServiceProvider;

use Brain\Monkey\Functions;
use Fulcrum\Config\Exception\InvalidFileException;
use Fulcrum\Tests\Unit\Foundation\Stubs\BadProviderStub;
use Fulcrum\Tests\Unit\Foundation\Stubs\FooProviderStub;
use Fulcrum\Tests\Unit\UnitTestCase;
use Mockery;

class ServiceProviderCreateTest extends UnitTestCase
{
    protected $fulcrumMock;

    protected function setUp()
    {
        parent::setUp();
        $this->fulcrumMock = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function testShouldCreate()
    {
        Functions\when('__')->justReturn('');
        $stub = new FooProviderStub($this->fulcrumMock);
        $this->assertInstanceOf(FooProviderStub::class, $stub);
    }

    public function testShouldThrowErrorWhenNotReadable()
    {
        Functions\when('__')->justReturn('The specified configuration file is not readable');

        try {
            new BadProviderStub($this->fulcrumMock);
        } catch (InvalidFileException $exception) {
            $errorMessage = 'The specified configuration file is not readable: ' . BadProviderStub::getDefaultsPath();
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }

    public function testShouldOverrideConcreteDefaultStructure()
    {
        Functions\when('__')->justReturn('');
        $stub = new FooProviderStub($this->fulcrumMock);

        $this->assertEquals([
            'autoload' => false,
            'config'   => '',
            'foobar'   => [
                'bar' => 'baz',
            ],
        ], $stub->defaultStructure);
    }

    public function testShouldLoadDefault()
    {
        Functions\when('__')->justReturn('');
        $stub = new FooProviderStub($this->fulcrumMock);
        $path = dirname(__DIR__) . '/Stubs/fixtures/foo-defaults.php';

        $this->assertEquals($path, $stub->defaultsLocation);
        $this->assertEquals([
            'foo'       => [],
            'bar'       => [],
            'baz'       => [],
            'isEnabled' => false,
        ], $stub->defaults);
    }
}
