<?php

namespace Fulcrum\Tests\Unit\Config;

use Brain\Monkey\Functions;
use Fulcrum\Config\Config;
use Fulcrum\Config\Exception\InvalidConfigException;
use Fulcrum\Config\Exception\InvalidFileException;
use Fulcrum\Config\Exception\InvalidSourceException;

class ConfigCreateTest extends ConfigTestCase
{
    public function testCreateWhenGivenAnArray()
    {
        $config = new Config($this->testArray);
        $this->assertInstanceOf(Config::class, $config);

        $config = new Config($this->testArray, $this->defaults);
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testDefaultsAreOverwritten()
    {
        $config = new Config($this->testArray, $this->defaults);

        $this->assertEquals('WordPress', $config->foo['platform']);
        $this->assertEquals('Beans', $config->foo['theme']);
        $this->assertEquals('Tonya', $config->bar['baz']['who']);
    }

    public function testCreateWhenGivenFilePath()
    {
        $config = new Config($this->testArrayPath);
        $this->assertInstanceOf(Config::class, $config);

        $config = new Config($this->testArrayPath, $this->defaultsPath);
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testThrowsErrorWhenSourceIsInvalid()
    {
        $errorMessage = 'Invalid configuration source. Source must be an array of configuration parameters or a ' .
                        'string filesystem path to load the configuration parameters.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            new Config(null);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': ', $exception->getMessage());
        }

        $source = new \stdClass;
        try {
            new Config($source);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r($source, true),
                $exception->getMessage()
            );
        }

        try {
            new Config(10);
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
            new Config($this->testArrayPath, $source);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r($source, true),
                $exception->getMessage()
            );
        }

        try {
            new Config($this->testArrayPath, 10);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': 10', $exception->getMessage());
        }
    }

    public function testThrowsErrorWhenSourceIsEmpty()
    {
        $errorMessage = 'Empty configuration source error.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            new Config([]);
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': ' . print_r([], true), $exception->getMessage());
        }

        try {
            new Config('');
        } catch (InvalidSourceException $exception) {
            $this->assertEquals($errorMessage . ': ', $exception->getMessage());
        }
    }

    public function testThrowsErrorWhenFileIsInvalid()
    {
        $file         = 'file-does-not-exist.php';
        $errorMessage = 'The specified configuration file is not readable';
        Functions\when('__')->justReturn($errorMessage);

        try {
            new Config($file);
        } catch (InvalidFileException $exception) {
            $this->assertEquals($errorMessage . ': ' . $file, $exception->getMessage());
        }
    }

    public function testThrowsErrorWhenLoadedConfigIsInvalid()
    {
        $file         = __DIR__ . '/fixtures/invalid-config.php';
        $errorMessage = 'Invalid configuration. The configuration must an array.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            new Config($file);
        } catch (InvalidConfigException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r(new \stdClass(), true),
                $exception->getMessage()
            );
        }

        try {
            new Config($this->testArray, $file);
        } catch (InvalidConfigException $exception) {
            $this->assertEquals(
                $errorMessage . ': ' . print_r(new \stdClass(), true),
                $exception->getMessage()
            );
        }
    }
}
