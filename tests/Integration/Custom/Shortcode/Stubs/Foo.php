<?php

namespace Fulcrum\Tests\Integration\Custom\Shortcode\Stubs;

use Fulcrum\Custom\Shortcode\Shortcode;

class Foo extends Shortcode
{
    public static $concreteConfig = [
        'autoload'  => true,
        'classname' => 'Fulcrum\Tests\Integration\Custom\Shortcode\Stubs\Foo',
        'config'    => [
            'shortcode' => 'fooStub',
            'noView'    => true,
            'defaults'  => [
                'class' => 'foobar',
            ],
        ],
    ];

    public function render()
    {
        return 'stubbed foo';
    }
}
