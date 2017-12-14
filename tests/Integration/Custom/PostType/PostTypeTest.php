<?php

namespace Fulcrum\Tests\Integration\Custom\PostType;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\LabelsBuilder;
use Fulcrum\Custom\PostType\PostType;
use Fulcrum\Custom\PostType\SupportedFeatures;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class PostTypeTest extends IntegrationTestCase
{
    protected static $postTypeConfig = [
        'description'  => 'Books - example custom post type',
        'public'       => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-book',
    ];
    protected $config;
    protected $columnsMock;
    protected $labelsMock;
    protected $supportsMock;

    public function setUp()
    {
        parent::setUp();

        $this->config       = ConfigFactory::create(self::$postTypeConfig);
        $this->columnsMock  = Mockery::mock('Fulcrum\Custom\PostType\Contract\ColumnsContract');
        $this->labelsMock   = Mockery::mock('Fulcrum\Custom\PostType\Contract\LabelsBuilderContract');
        $this->supportsMock = Mockery::mock('Fulcrum\Custom\PostType\Contract\SupportedFeaturesContract');
    }

    public function tearDown()
    {
        parent::tearDown();
        unregister_post_type('book');
    }

    public function testShouldRegister()
    {
        $this->columnsMock->shouldReceive('init');
        $this->labelsMock->shouldReceive('build')->andReturn(
            [
                'name'          => 'Books',
                'singular_name' => 'Book',
            ]
        );
        $this->labelsMock->shouldReceive('init');
        $this->supportsMock->shouldReceive('build')->andReturn(['title', 'editor']);

        $postType = new PostType('book', $this->config, $this->columnsMock, $this->supportsMock, $this->labelsMock);
        $postType->register();

        $this->assertTrue(post_type_exists('book'));
        $book = get_post_type_object('book');

        $this->assertInstanceOf('WP_Post_Type', $book);
        $this->assertEquals('book', $book->name);
        $this->assertEquals('Add New Post', $book->labels->add_new_item);
        $this->assertEquals('Books - example custom post type', $book->description);
    }

    public function testShouldIncludeTaxononiesWhenArray()
    {
        $this->columnsMock->shouldReceive('init');
        $this->labelsMock->shouldReceive('build')->andReturn(
            [
                'name'          => 'Books',
                'singular_name' => 'Book',
            ]
        );
        $this->labelsMock->shouldReceive('init');
        $this->supportsMock->shouldReceive('build')->andReturn(['title', 'editor']);

        self::$postTypeConfig['taxonomies'] = ['category', 'post_tag'];

        $postType = new PostType(
            'book',
            ConfigFactory::create(self::$postTypeConfig),
            $this->columnsMock,
            $this->supportsMock,
            $this->labelsMock
        );
        $postType->register();

        $this->assertTrue(post_type_exists('book'));
        $book = get_post_type_object('book');
        $this->assertEquals(['category', 'post_tag'], $book->taxonomies);
        $this->assertEquals(['category', 'post_tag'], get_object_taxonomies('book', 'names'));
    }

    public function testShouldRegisterWithIntegratedSupports()
    {
        $this->columnsMock->shouldReceive('init');
        $this->labelsMock->shouldReceive('build')->andReturn(
            [
                'name'          => 'Foos',
                'singular_name' => 'Foo',
            ]
        );
        $this->labelsMock->shouldReceive('init');

        $postType = new PostType(
            'book',
            $this->config,
            $this->columnsMock,
            new SupportedFeatures(ConfigFactory::create([
                'supports' => ['title', 'editor', 'foo'],
            ])),
            $this->labelsMock
        );
        $postType->register();

        $this->assertTrue(post_type_exists('book'));
        $book     = get_post_type_object('book');
        $supports = array_keys(get_all_post_type_supports('book'));

        $this->assertEquals('book', $book->name);
        $this->assertEquals('Add New Post', $book->labels->add_new_item);
        $this->assertEquals('Books - example custom post type', $book->description);
        $this->assertContains('title', $supports);
        $this->assertContains('foo', $supports);
        $this->assertContains('editor', $supports);
        $this->assertNotContains('author', $supports);
        $this->assertNotContains('thumbnail', $supports);
        $this->assertNotContains('excerpt', $supports);
        $this->assertEquals(['title', 'editor', 'foo'], $supports);
    }

    public function testShouldRegisterWithIntegratedLabels()
    {
        $this->columnsMock->shouldReceive('init');
        $this->supportsMock->shouldReceive('build')->andReturn(['title', 'editor']);

        $postType = new PostType(
            'book',
            $this->config,
            $this->columnsMock,
            $this->supportsMock,
            new LabelsBuilder(
                ConfigFactory::create(
                    [
                        'useBuilder'   => true,
                        'pluralName'   => 'Books',
                        'singularName' => 'Book',
                    ]
                )
            )
        );
        $postType->register();

        $this->assertTrue(post_type_exists('book'));
        $book = get_post_type_object('book');

        $this->assertEquals('book', $book->name);
        $this->assertEquals('Books', $book->labels->name);
        $this->assertEquals('Book', $book->labels->singular_name);
        $this->assertEquals('Add New Book', $book->labels->add_new_item);
        $this->assertEquals('No books found in Trash', $book->labels->not_found_in_trash);
    }

    public function testShouldRegisterWithLabelsAndSupports()
    {
        $this->columnsMock->shouldReceive('init');

        $postType = new PostType(
            'book',
            $this->config,
            $this->columnsMock,
            new SupportedFeatures(ConfigFactory::create([
                'supports' => ['title', 'editor', 'foo'],
            ])),
            new LabelsBuilder(
                ConfigFactory::create(
                    [
                        'useBuilder'   => true,
                        'pluralName'   => 'Books',
                        'singularName' => 'Book',
                    ]
                )
            )
        );
        $postType->register();

        $this->assertTrue(post_type_exists('book'));
        $book     = get_post_type_object('book');
        $supports = array_keys(get_all_post_type_supports('book'));

        // Test arguments.
        $this->assertEquals('book', $book->name);
        $this->assertEquals('Books - example custom post type', $book->description);

        // Test custom labels.
        $this->assertEquals('Books', $book->labels->name);
        $this->assertEquals('Book', $book->labels->singular_name);
        $this->assertEquals('Add New Book', $book->labels->add_new_item);
        $this->assertEquals('No books found in Trash', $book->labels->not_found_in_trash);

        // Test supports.
        $this->assertContains('title', $supports);
        $this->assertContains('foo', $supports);
        $this->assertContains('editor', $supports);
        $this->assertNotContains('author', $supports);
        $this->assertNotContains('thumbnail', $supports);
        $this->assertNotContains('excerpt', $supports);
        $this->assertEquals(['title', 'editor', 'foo'], $supports);
    }
}
