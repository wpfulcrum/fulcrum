<?php

namespace Fulcrum\Tests\Integration;

use WP_UnitTestCase;

abstract class IntegrationTestCase extends WP_UnitTestCase
{
    protected static $testPageId;
    protected static $testPostId;
    protected static $pageOnFront;
    protected static $pageForPosts;

    public static function wpSetUpBeforeClass($factory)
    {
        self::$testPageId = $factory->post->create([
            'post_type' => 'page',
        ]);
        self::$testPostId = $factory->post->create();

        self::$pageOnFront  = self::factory()->post->create([
            'post_type' => 'page',
        ]);
        self::$pageForPosts = self::factory()->post->create([
            'post_type' => 'page',
        ]);

        update_option('posts_per_page', 5);
    }

    public static function tearDownAfterClass()
    {
        update_option('show_on_front', 'posts');
        delete_option('page_on_front');
        delete_option('page_for_posts');
        parent::tearDownAfterClass();
    }
}
