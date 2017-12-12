<?php

namespace Fulcrum\Tests\Integration\Custom\Shortcode;

use Fulcrum\Custom\Shortcode\Shortcode;
use Fulcrum\Custom\Shortcode\ShortcodeProvider;
use Fulcrum\Tests\Integration\Custom\Shortcode\Stubs\FooShortcode;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class ProviderTest extends IntegrationTestCase
{
    protected $fulcrumMock;

    public function setUp()
    {
        parent::setUp();

        $this->fulcrumMock = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function testShouldRegisterWhenNoClassnameProvided()
    {
        $provider = new ShortcodeProvider($this->fulcrumMock);

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concreteConfig = [
            'autoload' => true,
            'config'   => [
                'shortcode' => 'foo',
                'view'      => __DIR__ . '/views/foo.php',
                'defaults'  => [
                    'class' => 'foobar',
                ],
            ],
        ];
        $concrete       = $provider->getConcrete($concreteConfig, 'shortcode.foo')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $shortcode = $provider->register($concreteConfig, 'shortcode.foo');
        $this->assertInstanceOf(Shortcode::class, $shortcode);

        $this->assertTrue(shortcode_exists('foo'));
    }

    public function testShouldRegisterWhenEmptyClassname()
    {
        $provider = new ShortcodeProvider($this->fulcrumMock);

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concreteConfig = [
            'autoload'  => true,
            'classname' => '',
            'config'    => [
                'shortcode' => 'foo',
                'view'      => __DIR__ . '/views/foo.php',
                'defaults'  => [
                    'class' => 'foobar',
                ],
            ],
        ];
        $concrete       = $provider->getConcrete($concreteConfig, 'shortcode.foo')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $shortcode = $provider->register($concreteConfig, 'shortcode.foo');
        $this->assertInstanceOf(Shortcode::class, $shortcode);

        $this->assertTrue(shortcode_exists('foo'));
    }

    public function testShouldInvokeClassname()
    {
        $provider = new ShortcodeProvider($this->fulcrumMock);

        include_once __DIR__ . '/Stubs/FooShortcode.php';

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concrete = $provider->getConcrete(FooShortcode::$concreteConfig, 'shortcode.fooStub')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $shortcode = $provider->register(FooShortcode::$concreteConfig, 'shortcode.fooStub');
        $this->assertInstanceOf(FooShortcode::class, $shortcode);

        $this->assertTrue(shortcode_exists('fooStub'));
    }
}
