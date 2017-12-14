<?php

namespace Fulcrum\Tests\Unit\Config;

use Brain\Monkey\Functions;
use Fulcrum\Config\Exception\InvalidConfigException;
use Fulcrum\Config\Exception\InvalidFileException;
use Fulcrum\Config\Exception\InvalidSourceException;
use Fulcrum\Config\Validator;

require_once __DIR__ . '/ConfigTestCase.php';

class AValidatorTest extends ConfigTestCase
{
    public function testThrowsErrorWhenSourceIsInvalid()
    {
        $errorMessage = 'Invalid configuration source. Source must be an array of configuration parameters or a ' .
                        'string filesystem path to load the configuration parameters.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::mustBeStringOrArray(null);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': ', $exception->getMessage());
        }

        $source = new \stdClass;
        try {
            Validator::mustBeStringOrArray($source);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r($source, true),
                $exception->getMessage()
            );
        }

        try {
            Validator::mustBeStringOrArray(10);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': 10', $exception->getMessage());
        }
    }

    public function testThrowsErrorWhenDefaultsSourceIsInvalid()
    {
        $errorMessage = 'Invalid default configuration source. Source must be an array of default configuration ' .
                        'parameters or a string filesystem path to load the default configuration parameters.';
        Functions\when('__')->justReturn($errorMessage);

        $source = new \stdClass;
        try {
            Validator::mustBeStringOrArray($source, true);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r($source, true),
                $exception->getMessage()
            );
        }

        try {
            Validator::mustBeStringOrArray(10, true);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': 10', $exception->getMessage());
        }
    }

    public function testReturnsTrueWhenSourceIsValid()
    {
        $this->assertTrue(Validator::mustBeStringOrArray($this->testArray));
        $this->assertTrue(Validator::mustBeStringOrArray($this->defaults));

        $this->assertTrue(Validator::mustBeStringOrArray($this->testArrayPath));
        $this->assertTrue(Validator::mustBeStringOrArray($this->defaultsPath));
    }

    public function testThrowsErrorWhenNotAnArray()
    {
        $errorMessage = 'Invalid configuration. The configuration must an array.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::mustBeAnArray(require __DIR__ . '/fixtures/invalid-config.php');
        } catch (InvalidConfigException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r(new \stdClass(), true),
                $exception->getMessage()
            );
        }
    }

    public function testReturnsTrueWhenAnArray()
    {
        $this->assertTrue(Validator::mustBeAnArray($this->testArray));
        $this->assertTrue(Validator::mustBeAnArray($this->defaults));

        $this->assertTrue(Validator::mustBeAnArray(require $this->testArrayPath));
        $this->assertTrue(Validator::mustBeAnArray(require $this->defaultsPath));
    }

    public function testThrowsErrorWhenSourceIsEmpty()
    {
        $errorMessage = 'Empty configuration source error.  The configuration source cannot be empty.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::mustNotBeEmpty('');
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': ', $exception->getMessage());
        }
    }

    public function testReturnsTrueWhenSourceIsNotEmpty()
    {
        $this->assertTrue(Validator::mustNotBeEmpty($this->testArray));
        $this->assertTrue(Validator::mustNotBeEmpty($this->defaults));

        $this->assertTrue(Validator::mustNotBeEmpty($this->testArrayPath));
        $this->assertTrue(Validator::mustNotBeEmpty($this->defaultsPath));

        // These are not configurations, but they are also not empty.
        $this->assertTrue(Validator::mustNotBeEmpty('foo'));
        $this->assertTrue(Validator::mustNotBeEmpty(10));
        $this->assertTrue(Validator::mustNotBeEmpty(new \stdClass()));
    }

    public function testThrowsErrorWhenFileIsInvalid()
    {
        $file         = 'file-does-not-exist.php';
        $errorMessage = 'The specified configuration file is not readable';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::mustBeLoadable($file);
        } catch (InvalidFileException $exception) {
            $this->assertEquals($errorMessage . ': ' . $file, $exception->getMessage());
        }
    }

    public function testReturnsTrueWhenFileIsValid()
    {
        $this->assertTrue(Validator::mustBeLoadable($this->testArrayPath));
        $this->assertTrue(Validator::mustBeLoadable($this->defaultsPath));
        $this->assertTrue(Validator::mustBeLoadable(__FILE__));
    }
}
