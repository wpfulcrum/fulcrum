<?php

namespace Fulcrum\Tests\Unit\Foundation\ServiceProvider;

use Brain\Monkey\Functions;
use Fulcrum\Foundation\ServiceProvider\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorOkayToRegisterTest extends UnitTestCase
{
    protected static $defaultStructure = [
        'autoload' => false,
        'config'   => '',
    ];

    public function testShouldReturnTrue()
    {
        Functions\when('__')->justReturn('');
        $concreteConfig = [
            'autoload' => false,
            'config'   => [
                'foo' => true,
            ],
        ];

        $this->assertTrue(
            Validator::okayToRegister(
                'foo',
                $concreteConfig,
                self::$defaultStructure,
                __CLASS__
            )
        );
    }
}
