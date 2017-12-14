<?php

namespace Fulcrum\Tests\Unit\Config;

use Fulcrum\Tests\Unit\UnitTestCase;

abstract class ConfigTestCase extends UnitTestCase
{
    protected $isLoaded = false;
    protected $testArrayPath;
    protected $defaultsPath;
    protected $testArray;
    protected $defaults;

    /**
     * Prepares the test environment before each test.
     */
    protected function setUp()
    {
        parent::setUp();
        if (!$this->isLoaded) {
            $this->testArrayPath = __DIR__ . '/fixtures/test-array.php';
            $this->defaultsPath  = __DIR__ . '/fixtures/defaults.php';
            $this->testArray     = require $this->testArrayPath;
            $this->defaults      = require $this->defaultsPath;
            $this->isLoaded      = true;
        }
    }
}
