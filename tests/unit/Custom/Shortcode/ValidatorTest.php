<?php

namespace Fulcrum\Tests\Unit\Custom\Shortcode;

use Brain\Monkey\Functions;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Shortcode\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorTest extends UnitTestCase
{
    public function testShouldReturnTrueWhenValid()
    {
        $this->assertTrue(Validator::isValid(ConfigFactory::create([
            'shortcode' => 'foo',
            'view'      => __DIR__ . '/views/foo.php',
            'defaults'  => [
                'class' => 'foobar',
            ],
        ])));

        $this->assertTrue(Validator::isValid(ConfigFactory::create([
            'shortcode' => 'foo',
            'noView'    => true,
            'defaults'  => [
                'class' => 'foobar',
            ],
        ])));

        $this->assertTrue(Validator::isValid(ConfigFactory::create([
            'shortcode' => 'foo',
            'noView'    => true,
            'view'      => '',
            'defaults'  => [
                'class' => 'foobar',
            ],
        ])));
    }

    public function testShouldThrowErrorForNoShortcode()
    {
        $errorMessage = 'Invalid shortcode configuration. The "shortcode" parameter is required.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::isValid(ConfigFactory::create([
                'view'     => __DIR__ . '/views/foo.php',
                'defaults' => [
                    'class' => 'foobar',
                ],
            ]));
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }

        try {
            Validator::isValid(ConfigFactory::create([
                'shortcode' => '',
                'view'      => __DIR__ . '/views/foo.php',
                'defaults'  => [
                    'class' => 'foobar',
                ],
            ]));
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }

    public function testShouldThrowErrorForNoDefaults()
    {
        $errorMessage = 'Invalid shortcode configuration for %s.  The "defaults" parameter is required.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::isValid(ConfigFactory::create([
                'shortcode' => 'foo',
                'view'      => __DIR__ . '/views/foo.php',
            ]));
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame(
                'Invalid shortcode configuration for foo.  The "defaults" parameter is required.',
                $exception->getMessage()
            );
        }
    }

    public function testShouldThrowErrorForNonArrayDefaults()
    {
        Functions\when('__')->justReturn(
            'Invalid shortcode configuration for %s.  The "defaults" parameter must be an array.'
        );

        try {
            Validator::isValid(ConfigFactory::create([
                'shortcode' => 'foo',
                'view'      => __DIR__ . '/views/foo.php',
                'defaults'  => 'foobar',
            ]));
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame(
                'Invalid shortcode configuration for foo.  The "defaults" parameter must be an array.',
                $exception->getMessage()
            );
        }
    }

    public function testShouldThrowErrorWhenNoViewParameter()
    {
        $errorMessage = 'Invalid config for shortcode as a "view" parameter is required.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::isValid(ConfigFactory::create([
                'shortcode' => 'foo',
//                'view'      => __DIR__ . '/views/foo.php',
                'defaults'  => [
                    'class' => 'foobar',
                ],
            ]));
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenNonLoadableView()
    {
        Functions\when('__')->justReturn(
            'The specified view file is not readable. [View: %s]'
        );

        try {
            Validator::isValid(ConfigFactory::create([
                'shortcode' => 'foo',
                'view'      => 'view-does-not-exist.php',
                'defaults'  => [
                    'class' => 'foobar',
                ],
            ]));
        } catch (\RuntimeException $exception) {
            $this->assertSame(
                sprintf(
                    'The specified view file is not readable. [View: %s]',
                    print_r('view-does-not-exist.php', true)
                ),
                $exception->getMessage()
            );
        }
    }
}
