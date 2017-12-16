<?php

namespace Fulcrum\Tests\Integration\Custom\Shortcode;

use Fulcrum\Custom\Shortcode\Shortcode;
use Fulcrum\Custom\Shortcode\ShortcodeProvider;
use Fulcrum\Tests\Integration\Custom\Shortcode\Stubs\Foo;
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
        $provider  = new ShortcodeProvider($this->fulcrumMock);
        $fooConfig = Foo::$concreteConfig;

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concrete = $provider->getConcrete($fooConfig, 'shortcode.fooStub')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $shortcode = $provider->register($fooConfig, 'shortcode.fooStub');
        $this->assertInstanceOf(Foo::class, $shortcode);
        $this->assertEquals('stubbed foo', $shortcode->render());

        $this->assertTrue(shortcode_exists('fooStub'));
    }
}
