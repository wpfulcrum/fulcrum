<?php

namespace Fulcrum\Tests\Integration\Extender\WP;

use Fulcrum\Tests\Integration\IntegrationTestCase;

class HelpersTest extends IntegrationTestCase
{
    public function testShouldReturnNullWhenNoPostTypeSupports()
    {
        $this->assertEmpty(get_all_supports_for_post_type('foo'));
    }

    public function testShouldReturnPostTypeSupports()
    {
        register_post_type('foo');
        $this->assertEquals(['title', 'editor'], get_all_supports_for_post_type('foo'));
        unregister_post_type('foo');
    }

    public function testShouldReturnNoCustomPostTypes()
    {
        $this->assertEmpty(get_all_custom_post_types());
    }

    public function testShouldReturnAllCustomPostTypes()
    {
        $cpts = [
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
        ];
        foreach ($cpts as $postName) {
            register_post_type($postName);
        }

        $this->assertEquals($cpts, get_all_custom_post_types());

        foreach ($cpts as $postName) {
            unregister_post_type($postName);
        }
    }

    public function testShouldReturnCurrentWebPageId()
    {
        $this->go_to('/');
        $this->assertEquals(0, get_current_web_page_id());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertEquals(self::$testPageId, get_current_web_page_id());

        $this->go_to(get_permalink(self::$testPostId));
        $this->assertEquals(self::$testPostId, get_current_web_page_id());
    }

    public function testShouldReturnPostsWebPageId()
    {
        $postId = self::factory()->post->create(['post_title' => 'hello-world']);
        $this->go_to(get_permalink($postId));
        $this->assertEquals($postId, get_current_web_page_id());
    }

    public function testShouldGetJoinedListOfTerms()
    {
        // Setup.
        register_post_type('extender_cpt');
        register_taxonomy('extender_tax', ['post', 'extender_cpt']);
        register_taxonomy('extender_footax', ['extender_cpt']);
        $bar    = $this->factory->term->create(['name' => 'Bar', 'taxonomy' => 'extender_tax']);
        $baz    = $this->factory->term->create(['name' => 'Baz', 'taxonomy' => 'extender_tax']);
        $foobar = $this->factory->term->create(['name' => 'Foobar', 'taxonomy' => 'extender_footax']);
        $postId = self::factory()->post->create(['post_type' => 'extender_cpt']);
        wp_set_post_terms($postId, [$bar, $baz], 'extender_tax');
        wp_set_post_terms($postId, [$foobar], 'extender_footax');

        // Let's test.
        $this->assertSame('Bar, Baz', get_joined_list_of_terms('extender_tax', $postId));
        $this->assertSame('Foobar', get_joined_list_of_terms('extender_footax', $postId));

        // Clean up.
        unregister_post_type('extender_cpt');
        unregister_taxonomy('extender_tax');
        unregister_taxonomy('extender_footax');
    }

    public function testShouldLimitTermsToPostTypeWhenNoArgsGiven()
    {
        // Setup.
        register_post_type('extender_cpt');
        register_taxonomy('extender_tax', ['post', 'extender_cpt']);
        register_taxonomy('extender_footax', ['extender_cpt']);
        $bar    = $this->factory->term->create(['name' => 'Bar', 'taxonomy' => 'extender_tax']);
        $baz    = $this->factory->term->create(['name' => 'Baz', 'taxonomy' => 'extender_tax']);
        $foobar = $this->factory->term->create(['name' => 'Foobar', 'taxonomy' => 'extender_footax']);
        $postId = self::factory()->post->create(['post_type' => 'extender_cpt']);
        wp_set_post_terms(self::$testPostId, [$bar], 'extender_tax');
        wp_set_post_terms($postId, [$baz], 'extender_tax');
        wp_set_post_terms($postId, [$foobar], 'extender_footax');

        // Let's test.
        $terms   = get_terms_by_post_type(['extender_cpt']);
        $termIds = wp_list_pluck($terms, 'term_id');
        $this->assertNotContains($bar, $termIds);
        $this->assertContains($baz, $termIds);
        $this->assertContains($foobar, $termIds);

        $terms   = get_terms_by_post_type('post');
        $termIds = wp_list_pluck($terms, 'term_id');
        $this->assertContains($bar, $termIds);
        $this->assertNotContains($baz, $termIds);
        $this->assertNotContains($foobar, $termIds);

        $terms   = get_terms_by_post_type(['post', 'extender_cpt']);
        $termIds = wp_list_pluck($terms, 'term_id');
        $this->assertContains($bar, $termIds);
        $this->assertContains($baz, $termIds);
        $this->assertContains($foobar, $termIds);

        // Clean up.
        unregister_post_type('extender_cpt');
        unregister_taxonomy('extender_tax');
        unregister_taxonomy('extender_footax');
    }

    public function testGetTermsByPostTypeShouldOverrideArgs()
    {
        // Setup.
        register_post_type('extender_cpt');
        register_taxonomy('extender_tax', ['post', 'extender_cpt']);
        register_taxonomy('extender_footax', ['extender_cpt']);
        $bar    = $this->factory->term->create(['name' => 'Bar', 'taxonomy' => 'extender_tax']);
        $baz    = $this->factory->term->create(['name' => 'Baz', 'taxonomy' => 'extender_tax']);
        $foobar = $this->factory->term->create(['name' => 'Foobar', 'taxonomy' => 'extender_footax']);
        $postId = self::factory()->post->create(['post_type' => 'extender_cpt']);
        wp_set_post_terms(self::$testPostId, [$bar], 'extender_tax');
        wp_set_post_terms($postId, [$baz], 'extender_tax');
        wp_set_post_terms($postId, [$foobar], 'extender_footax');

        // Let's test.
        $termIds = get_terms_by_post_type(
            ['extender_cpt'],
            [
                'hide_empty' => false,
                'fields'     => 'ids',
            ]
        );
        $this->assertNotContains($bar, $termIds);
        $this->assertContains($baz, $termIds);
        $this->assertContains($foobar, $termIds);

        $termIds = get_terms_by_post_type(
            'post',
            [
                'post_type'  => 'post',
                'hide_empty' => false,
                'fields'     => 'ids',
            ]
        );
        $this->assertContains($bar, $termIds);
        $this->assertNotContains($baz, $termIds);
        $this->assertNotContains($foobar, $termIds);

        $termIds = get_terms_by_post_type(
            ['post', 'extender_cpt'],
            [
                'hide_empty' => false,
                'fields'     => 'ids',
            ]
        );
        $this->assertContains($bar, $termIds);
        $this->assertContains($baz, $termIds);
        $this->assertContains($foobar, $termIds);

        // Clean up.
        unregister_post_type('extender_cpt');
        unregister_taxonomy('extender_tax');
        unregister_taxonomy('extender_footax');
    }

    public function testShouldReturnPathRelativeToHomeUrl()
    {
        $this->assertSame(trailingslashit(get_home_url()), get_url_relative_to_home_url());
        $this->assertSame(get_home_url(null, 'foo'), get_url_relative_to_home_url('foo'));
    }

    public function testShouldReturnPostIdWhenNotInAdmin()
    {
        $this->assertEquals(0, get_post_id_when_in_backend());
        $this->assertEquals(14, get_post_id_when_in_backend(14));
        $this->assertEquals('not an integer', get_post_id_when_in_backend('not an integer'));
    }

    public function testShouldReturnPostIdWhenInAdminButNotFound()
    {
        // Setup.
        set_current_screen('edit.php');

        $this->assertEquals(0, get_post_id_when_in_backend());
        $this->assertEquals(14, get_post_id_when_in_backend(14));
        $this->assertEquals('not an integer', get_post_id_when_in_backend('not an integer'));

        // Clean up.
        set_current_screen('front');
    }

    public function testShouldGetPostIdWhenInBackend()
    {
        // Setup.
        set_current_screen('edit.php');

        $_REQUEST['post_ID'] = 10;
        $this->assertEquals(10, get_post_id_when_in_backend());
        unset($_REQUEST['post_ID']);
        $this->assertEquals(0, get_post_id_when_in_backend());

        $_REQUEST['post_id'] = 47;
        $this->assertEquals(47, get_post_id_when_in_backend());
        unset($_REQUEST['post_id']);
        $this->assertEquals(0, get_post_id_when_in_backend());

        $_REQUEST['post'] = 831;
        $this->assertEquals(831, get_post_id_when_in_backend());
        unset($_REQUEST['post']);
        $this->assertEquals(0, get_post_id_when_in_backend());

        $_REQUEST['post'] = '11';
        $this->assertEquals(11, get_post_id_when_in_backend());
        unset($_REQUEST['post']);
        $this->assertEquals(0, get_post_id_when_in_backend());

        // Clean up.
        set_current_screen('front');
    }

    public function testShouldReturnGetTheIdWhenLessZero()
    {
        $this->go_to(get_permalink(self::$testPostId));

        $this->assertEquals(self::$testPostId, get_the_ID());
        $this->assertEquals(self::$testPostId, get_post_id());
        $this->assertEquals(self::$testPostId, get_post_id(-10));
        $this->assertEquals(self::$testPostId, get_post_id('not an integer'));
    }

    public function testShouldReturnPostIdWhenInBackend()
    {
        // Setup.
        set_current_screen('edit.php');

        $_REQUEST['post_ID'] = 10;
        $this->assertEquals(10, get_post_id());
        unset($_REQUEST['post_ID']);
        $this->assertEquals(0, get_post_id());

        // Clean up.
        set_current_screen('front');
    }
}
