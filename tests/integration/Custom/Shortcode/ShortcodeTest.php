<?php

namespace Fulcrum\Tests\Integration\Custom\Shortcode;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Shortcode\Shortcode;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class ShortcodeTest extends IntegrationTestCase
{
    protected static $fooConfig = [
        'shortcode' => 'foo',
        'view'      => __DIR__ . '/views/foo.php',
        'defaults'  => [
            'class' => 'foobar',
        ],
    ];

    public function testShouldAddShortcode()
    {
        $shortcode = new Shortcode(ConfigFactory::create(self::$fooConfig));
        $this->assertInstanceOf('Fulcrum\Custom\Shortcode\Shortcode', $shortcode);

        $this->assertTrue(shortcode_exists('foo'));
    }

    public function testShouldRenderWhenDirectlyCalled()
    {
        $shortcode = new Shortcode(ConfigFactory::create(self::$fooConfig));

        $this->assertEquals(
            '<p class="foobar"></p>',
            $shortcode->renderCallback([])
        );

        $this->assertEquals(
            '<p class="foo-invoke">Directly invoking</p>',
            $shortcode->renderCallback(['class' => 'foo-invoke'], 'Directly invoking')
        );
    }

    public function testShouldRenderWithDoShortcode()
    {
        new Shortcode(ConfigFactory::create(self::$fooConfig));

        $this->assertEquals(
            '<p class="foobar"></p>',
            do_shortcode('[foo /]')
        );

        $this->assertEquals(
            '<p class="bar">Fulcrum is cool!</p>',
            do_shortcode('[foo class="bar"]Fulcrum is cool![/foo]')
        );
    }
}
