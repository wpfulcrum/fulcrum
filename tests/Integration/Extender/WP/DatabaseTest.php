<?php

namespace Fulcrum\Tests\Integration\Extender\WP;

use Fulcrum\Extender\WP\Database;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class DatabaseTest extends IntegrationTestCase
{
    public function testShouldGive404WithoutHardFlush()
    {
        $this->assertEmpty(get_option('rewrite_rules'));

        // Now set the permalinks.
        $this->set_permalink_structure('/%postname%/');
        $this->assertNotEmpty(get_option('rewrite_rules'));

        // Let's register a post type but without flushing the rewrite rules.
        register_post_type('foo', ['public' => true]);
        $postId = self::factory()->post->create(['post_type' => 'foo']);

        // Test that going to this new post type gives a 404.
        $this->go_to(get_permalink($postId));
        $this->assertQueryTrue('is_404');

        _unregister_post_type('foo');
    }

    public function testShouldRedirectWithHardFlush()
    {
        register_post_type('foo', ['public' => true]);
        $postId = self::factory()->post->create(['post_type' => 'foo']);

        $this->assertNull(do_harder_rewrite_rules_flush());

        $this->go_to(get_permalink($postId));
        $this->assertQueryTrue('is_single', 'is_singular');

        _unregister_post_type('foo');
    }

    public function testShouldNotGetOptionAfterUpdate()
    {
        $this->assertEquals(5, get_option('posts_per_page'));
        $this->assertFalse(get_option('foo', false));

        // Let's insert foo directly into the db.
        global $wpdb;
        $optionId = $wpdb->insert(
            $wpdb->options,
            [
                'option_name'  => 'foo',
                'option_value' => 'foobar',
                'autoload'     => 'yes',
            ],
            ['%s', '%s', '%s']
        );

        $this->assertGreaterThan(0, $optionId);

        // Because of the cache and that we wrote directly to the database,
        // it's not updated in memory with get_option().
        $this->assertFalse(get_option('foo', false));

        delete_option('foo');
    }

    public function testShouldBypassCacheAndGetOption()
    {
        $this->assertEquals(5, Database::doHardGetOption('posts_per_page'));
        $this->assertFalse(do_hard_get_option('foo', false));

        global $wpdb;
        $optionId = $wpdb->insert(
            $wpdb->options,
            [
                'option_name'  => 'foo',
                'option_value' => 'foobar',
                'autoload'     => 'yes',
            ],
            ['%s', '%s', '%s']
        );

        $this->assertGreaterThan(0, $optionId);

        // With the hard get, it's goes directly to the database to grab the value.
        $this->assertEquals('foobar', do_hard_get_option('foo'));

        delete_option('foo');
    }
}
