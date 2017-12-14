<?php

namespace Fulcrum\Tests\Unit\Foundation\ServiceProvider;

use Brain\Monkey\Functions;
use Fulcrum\Foundation\ServiceProvider\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorIsUniqueIdValidTest extends UnitTestCase
{

    public function testShouldThrowErrorWhenEmpty()
    {
        Functions\when('__')->justReturn('For the service provider [%s], the container unique ID cannot be empty.');
        $errorMessage = 'For the service provider [' . __CLASS__ . '], the container unique ID cannot be empty.';

        try {
            Validator::isUniqueIdValid('', __CLASS__);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }

        try {
            Validator::isUniqueIdValid(null, __CLASS__);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }

        try {
            Validator::isUniqueIdValid(false, __CLASS__);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenNotString()
    {
        Functions\when('__')
            ->justReturn('For the service provider [%s], the container unique ID must be a string. %s given.');
        $errorMessage = 'For the service provider [' . __CLASS__ . '], the container unique ID must be a string. %s given.'; // @codingStandardsIgnoreLine - Generic.Files.LineLength.TooLong

        try {
            Validator::isUniqueIdValid(42, __CLASS__);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame(sprintf($errorMessage, 'integer'), $exception->getMessage());
        }

        try {
            Validator::isUniqueIdValid(['foo'], __CLASS__);
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame(sprintf($errorMessage, 'array'), $exception->getMessage());
        }
    }

    public function testShouldReturnTrueWhenValid()
    {
        $this->assertTrue(Validator::isUniqueIdValid('uniqueId', __CLASS__));
        $this->assertTrue(Validator::isUniqueIdValid('this.is.a.unique.id', __CLASS__));
        $this->assertTrue(Validator::isUniqueIdValid(__CLASS__, __CLASS__));
    }
}
