<?php

namespace Fulcrum\Tests\Unit\Custom\Widget;

use Brain\Monkey\Functions;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Widget\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorTest extends UnitTestCase
{
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = require __DIR__ . '/fixtures/foo-widget-config.php';
    }

    public function testShouldReturnTrueWhenValid()
    {
        $config = ConfigFactory::create($this->config);
        $this->assertTrue(Validator::isValid($config));

        $config->set('control_options', []);
        $this->assertTrue(Validator::isValid($config));

        $config->set('defaults', []);
        $this->assertTrue(Validator::isValid($config));
    }

    public function testShouldThrowErrorForMissingRequiredParameters()
    {
        $errorMessage = 'Invalid widget configuration. The "%s" parameter is required.';
        Functions\when('__')->justReturn($errorMessage);

        $required = ['id_base', 'name', 'widget_options', 'views', 'control_options', 'defaults',
            'widget_options.classname', 'widget_options.description', 'views.widget', 'views.form'];
        foreach ($required as $parameter) {
            $config = ConfigFactory::create($this->config);
            try {
                $config->remove($parameter);
                Validator::isValid($config);
            } catch (\InvalidArgumentException $exception) {
                $this->assertSame(sprintf($errorMessage, $parameter), $exception->getMessage());
            }
        }
    }

    public function testShouldThrowErrorForEmptyRequiredParameters()
    {
        $errorMessage = 'Invalid widget configuration. The "%s" parameter is required and must be configured.';
        Functions\when('__')->justReturn($errorMessage);

        $required = ['id_base', 'name', 'widget_options', 'views',
            'widget_options.classname', 'widget_options.description', 'views.widget', 'views.form'];
        foreach ($required as $parameter) {
            $config = ConfigFactory::create($this->config);
            try {
                $config->set($parameter, '');
                Validator::isValid($config);
            } catch (\InvalidArgumentException $exception) {
                $this->assertSame(sprintf($errorMessage, $parameter), $exception->getMessage());
            }
        }
    }

    public function testShouldThrowErrorForNonArray()
    {
        $errorMessage = 'Invalid widget configuration. The "%s" parameter must be an array.';
        Functions\when('__')->justReturn($errorMessage);
        $required = ['widget_options', 'control_options', 'defaults', 'views'];
        foreach ($required as $parameter) {
            $config = $this->config;
            try {
                unset($config[$parameter]);
                Validator::isValid(ConfigFactory::create($config));
            } catch (\InvalidArgumentException $exception) {
                $this->assertSame(sprintf($errorMessage, $parameter), $exception->getMessage());
            }
        }
    }

    public function testShouldThrowErrorForNonLoadableView()
    {
        $errorMessage = 'The specified view file is not readable. [View: %s]';
        Functions\when('__')->justReturn($errorMessage);

        foreach (['views.widget', 'views.form'] as $parameter) {
            $config = ConfigFactory::create($this->config);
            $config->set($parameter, 'filedoesnotexists.php');

            try {
                Validator::isValid($config);
            } catch (\RuntimeException $exception) {
                $this->assertSame(
                    sprintf($errorMessage, print_r('filedoesnotexists.php', true)),
                    $exception->getMessage()
                );
            }
        }
    }
}
