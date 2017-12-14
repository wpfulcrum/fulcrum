<?php

namespace Fulcrum\Tests\Integration\Custom\Template;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\LabelsBuilder;
use Fulcrum\Custom\PostType\PostType;
use Fulcrum\Custom\PostType\SupportedFeatures;
use Fulcrum\Custom\Template\TemplateLoader;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class TemplateLoaderTest extends IntegrationTestCase
{
    protected static $config;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$config = require __DIR__ . '/fixtures/book-genre.php';
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        // CYA to make sure we cleanup before we leave these tests.
        unregister_post_type('book');
        unregister_taxonomy('genre');
    }

    public function setUp()
    {
        parent::setUp();

        $this->set_permalink_structure('/%postname%/');
        delete_option('rewrite_rules');
        register_post_type('book', [
            'has_archive' => true,
            'public'      => true,
        ]);

        new TemplateLoader(ConfigFactory::create(self::$config['config']));
    }

    public function tearDown()
    {
        parent::tearDown();
        unregister_post_type('book');
    }

    public function testShouldLoadPluginSingleTemplate()
    {
        $bookId = self::factory()->post->create(['post_type' => 'book']);

        $this->go_to(get_permalink($bookId));

        $this->assertTrue(is_single());
        $this->assertEquals(
            __DIR__ . '/fixtures/templates/single-book.php',
            apply_filters('template_include', 'single.php')
        );
    }

    public function testShouldLoadPluginArchiveTemplate()
    {
        self::factory()->post->create(['post_type' => 'book']);

        $this->go_to('/book/');

        $this->assertTrue(is_post_type_archive('book'));
        $this->assertEquals(
            __DIR__ . '/fixtures/templates/archive-book.php',
            apply_filters('template_include', 'archive.php')
        );
    }

    public function testShouldLoadPluginTaxTemplate()
    {
        $bookId = self::factory()->post->create(['post_type' => 'book']);

        // Now register the taxonomy and term.
        register_taxonomy('genre', ['book']);
        $termId = self::factory()->term->create([
            'name'     => 'Mystery',
            'slug'     => 'mystery',
            'taxonomy' => 'genre',
        ]);
        wp_set_object_terms($bookId, $termId, 'genre');

        $this->go_to(get_term_link($termId, 'genre'));


        // Let's test.
        $this->assertTrue(is_tax('genre'));

        $this->assertEquals(
            __DIR__ . '/fixtures/templates/taxonomy-genre.php',
            apply_filters('template_include', 'archive.php')
        );
        unregister_taxonomy('genre');
    }
}
