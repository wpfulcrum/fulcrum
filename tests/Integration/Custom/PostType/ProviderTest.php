<?php

namespace Fulcrum\Tests\Integration\Custom\PostType;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\LabelsBuilder;
use Fulcrum\Custom\PostType\PostType;
use Fulcrum\Custom\PostType\PostTypeProvider;
use Fulcrum\Custom\PostType\SupportedFeatures;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class ProviderTest extends IntegrationTestCase
{
    protected $concreteConfig;
    protected $fulcrumMock;

    public function setUp()
    {
        parent::setUp();

        $this->concreteConfig = require __DIR__.'/fixtures/book.php';
        $this->fulcrumMock    = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function tearDown()
    {
        parent::tearDown();
        unregister_post_type('book');
    }

    public function testShouldRegisterConcreteAndReturnPostType()
    {
        $provider = new PostTypeProvider($this->fulcrumMock);

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concrete = $provider->getConcrete($this->concreteConfig, 'cpt.books')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $postType = $provider->register($this->concreteConfig, 'cpt.books');
        $this->assertInstanceOf(PostType::class, $postType);
        $postType->register();

        // Test that the post type was registered with WordPress.
        $this->assertTrue(post_type_exists('book'));
        $book = get_post_type_object('book');

        // Test arguments.
        $this->assertInstanceOf('WP_Post_Type', $book);
        $this->assertEquals('book', $book->name);
        $this->assertEquals('Books - example custom post type', $book->description);

        // Test custom labels.
        $this->assertEquals('Books', $book->labels->name);
        $this->assertEquals('Book', $book->labels->singular_name);
        $this->assertEquals('Add New Book', $book->labels->add_new_item);
        $this->assertEquals('No books found in Trash.', $book->labels->not_found_in_trash);

        // Test supports.
        $supports = array_keys(get_all_post_type_supports('book'));
        $this->assertContains('title', $supports);
        $this->assertContains('foo', $supports);
        $this->assertContains('bar', $supports);
        $this->assertContains('editor', $supports);
        $this->assertNotContains('author', $supports);
        $this->assertNotContains('thumbnail', $supports);
        $this->assertNotContains('excerpt', $supports);
        $this->assertEquals(['title', 'editor', 'foo', 'bar'], $supports);
    }
}
