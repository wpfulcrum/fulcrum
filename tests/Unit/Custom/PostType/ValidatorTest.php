<?php

namespace Fulcrum\Tests\Unit\Custom\PostType;

use Brain\Monkey\Functions;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorTest extends UnitTestCase
{
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = ConfigFactory::create(__DIR__.'/fixtures/foo.php');
    }

    public function testShouldReturnTrueWhenValid()
    {
        $this->assertTrue(Validator::isValid('foo', $this->config));
    }

    public function testShouldThrowErrorForNoPostTypeName()
    {
        $errorMessage = 'For Custom Post Type Configuration, the Post type cannot be empty.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::isValid('', $this->config);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }
}
