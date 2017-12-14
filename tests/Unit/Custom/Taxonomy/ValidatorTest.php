<?php

namespace Fulcrum\Tests\Unit\Custom\Taxonomy;

use Brain\Monkey\Functions;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Config\Exception\InvalidConfigException;
use Fulcrum\Custom\Taxonomy\Validator;
use Fulcrum\Tests\Unit\UnitTestCase;

class ValidatorTest extends UnitTestCase
{
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = [
            'objectType' => ['book'],
            'args'       => [
                'description'       => 'Book Genres',
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
            ],
        ];
    }

    public function testShouldReturnTrueWhenValid()
    {
        $this->assertTrue(Validator::run('genre', ConfigFactory::create($this->config)));
    }

    public function testShouldThrowErrorForNoTaxonomyName()
    {
        $errorMessage = 'For Custom Taxonomy Configuration, the taxonomy name cannot be empty.';
        Functions\when('__')->justReturn($errorMessage);

        try {
            Validator::run('', ConfigFactory::create($this->config));
        } catch (InvalidConfigException $exception) {
            $this->assertSame($errorMessage, $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenNoObjectType()
    {
        $errorMessage = 'The "objectType" must be configured as an array of post types in the [%s] '.
                        'taxonomy configuration.';
        Functions\when('__')->justReturn($errorMessage);

        unset($this->config['objectType']);

        try {
            Validator::run('genre', ConfigFactory::create($this->config));
        } catch (InvalidConfigException $exception) {
            $this->assertSame(sprintf($errorMessage, 'genre'), $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenInvalidObjectType()
    {
        $errorMessage = 'The "objectType" must be configured as an array of post types in the [%s] '.
                        'taxonomy configuration.';
        Functions\when('__')->justReturn($errorMessage);

        $this->config['objectType'] = 'genre';

        try {
            Validator::run('genre', ConfigFactory::create($this->config));
        } catch (InvalidConfigException $exception) {
            $this->assertSame(sprintf($errorMessage, 'genre'), $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenNoArgs()
    {
        $errorMessage = 'The "args" must be configured for [%s] taxonomy.';
        Functions\when('__')->justReturn($errorMessage);

        unset($this->config['args']);

        try {
            Validator::run('genre', ConfigFactory::create($this->config));
        } catch (InvalidConfigException $exception) {
            $this->assertSame(sprintf($errorMessage, 'genre'), $exception->getMessage());
        }
    }

    public function testShouldThrowErrorWhenInvalidArgs()
    {
        $errorMessage = 'The "args" must be configured for [%s] taxonomy.';
        Functions\when('__')->justReturn($errorMessage);

        $this->config['args'] = 'genre';

        try {
            Validator::run('genre', ConfigFactory::create($this->config));
        } catch (InvalidConfigException $exception) {
            $this->assertSame(sprintf($errorMessage, 'genre'), $exception->getMessage());
        }
    }
}
