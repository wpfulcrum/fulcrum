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
    protected static $bookId;
    protected static $term;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $config = require __DIR__ . '/fixtures/book-genre.php';
        new TemplateLoader(ConfigFactory::create($config['config']));

        set_current_screen('front');
        update_option('posts_per_page', 3);
    }

    public function setUp()
    {
        parent::setUp();

        $this->set_permalink_structure('/%postname%/');
    }

    public function testShouldLoadPluginSingleTemplate()
    {
        delete_option('rewrite_rules');
        register_post_type('book', ['public' => true]);

        $bookId = self::factory()->post->create(['post_type' => 'book']);

        $this->go_to(get_permalink($bookId));

        $this->assertEquals(
            __DIR__ . '/fixtures/templates/single-book.php',
            apply_filters('template_include', 'single.php')
        );

        unregister_post_type('book');
    }

    public function testShouldLoadPluginArchiveTemplate()
    {
        delete_option('rewrite_rules');
        register_post_type('book', [
            'has_archive' => true,
            'public'      => true,
        ]);

        self::factory()->post->create(['post_type' => 'book']);

        $this->go_to('/book/');

        $this->assertTrue(is_post_type_archive('book'));

        $this->assertEquals(
            __DIR__ . '/fixtures/templates/archive-book.php',
            apply_filters('template_include', 'archive.php')
        );

        unregister_post_type('book');
    }

    public function testShouldLoadPluginTaxTemplate()
    {
        delete_option('rewrite_rules');

        // Register the post type.
        register_post_type('book', [
            'has_archive' => true,
            'public'      => true,
        ]);
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

        // Clean-up
        unregister_post_type('book');
        unregister_taxonomy('genre');
    }
}
