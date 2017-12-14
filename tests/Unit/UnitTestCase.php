<?php

namespace Fulcrum\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    /**
     * Prepares the test environment before each test.
     */
    protected function setUp()
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Cleans up the test environment after each test.
     */
    protected function tearDown()
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
