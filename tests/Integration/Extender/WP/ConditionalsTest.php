<?php

namespace Fulcrum\Tests\Integration\Extender\WP;

use Fulcrum\Extender\WP\Conditionals;
use Fulcrum\Extender\WP\FrontPageDisplays;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class ConditionalsTest extends IntegrationTestCase
{
    public function testShouldReturnFalseWhenNotPostsPage()
    {
        $this->go_to(get_permalink(self::$testPostId));
        $this->assertFalse(Conditionals::isPostsPage());
        $this->assertFalse(is_posts_page());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertFalse(Conditionals::isPostsPage());
        $this->assertFalse(is_posts_page());

        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);

        $this->assertFalse(Conditionals::isPostsPage());
        $this->assertFalse(is_posts_page());

        $this->go_to('/');
        $this->assertFalse(Conditionals::isPostsPage());
        $this->assertFalse(is_posts_page());

        $this->go_to(home_url());
        $this->assertFalse(Conditionals::isPostsPage());
        $this->assertFalse(is_posts_page());
    }

    public function testShouldReturnTrueWhenPostsPage()
    {
        $this->go_to('/');
        $this->assertTrue(Conditionals::isPostsPage());
        $this->assertTrue(is_posts_page());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isPostsPage());
        $this->assertTrue(is_posts_page());

        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);

        $this->go_to(get_permalink(self::$pageForPosts));
        $this->assertQueryTrue('is_home', 'is_posts_page');
        $this->assertTrue(Conditionals::isPostsPage());
        $this->assertTrue(is_posts_page());
    }

    public function testShouldReturnFalseWhenNotOnStaticFrontPage()
    {
        $this->go_to('/');
        $this->assertFalse(Conditionals::isStaticFrontPage());
        $this->assertFalse(is_static_front_page());

        $this->go_to(home_url());
        $this->assertFalse(Conditionals::isStaticFrontPage());
        $this->assertFalse(is_static_front_page());

        $this->go_to(get_permalink(self::$testPostId));
        $this->assertFalse(Conditionals::isStaticFrontPage());
        $this->assertFalse(is_static_front_page());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertFalse(Conditionals::isStaticFrontPage());
        $this->assertFalse(is_static_front_page());

        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);

        $this->assertFalse(Conditionals::isStaticFrontPage());
        $this->assertFalse(is_static_front_page());
    }

    public function testShouldReturnTrueWhenStaticFrontPage()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);

        $this->go_to('/');
        $this->assertQueryTrue('is_front_page', 'is_page', 'is_singular');
        $this->assertTrue(Conditionals::isStaticFrontPage());
        $this->assertTrue(is_static_front_page());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isStaticFrontPage());
        $this->assertTrue(is_static_front_page());

        $this->go_to(get_permalink(self::$pageOnFront));
        $this->assertTrue(Conditionals::isStaticFrontPage());
        $this->assertTrue(is_static_front_page());
    }

    public function testShouldReturnFalseWhenSetToDefaultButNotAtRoot()
    {
        $this->go_to(get_permalink(self::$pageOnFront));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$pageOnFront, (int) get_queried_object_id());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$testPageId, (int) get_queried_object_id());
    }

    public function testShouldReturnTrueWhenSetToDefaultAndAtRoot()
    {
        $this->go_to('/');
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertQueryTrue('is_home', 'is_posts_page', 'is_front_page');
        $this->assertTrue(is_home());
        $this->assertTrue(is_front_page());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertQueryTrue('is_home', 'is_posts_page', 'is_front_page');
        $this->assertTrue(is_home());
        $this->assertTrue(is_front_page());
    }

    public function testShouldReturnFalseWhenSetToStaticFPButNotAtRoot()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        FrontPageDisplays::clearOptionsCache();

        $this->go_to(get_permalink(self::$pageForPosts));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$pageForPosts, (int) get_queried_object_id());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$testPageId, (int) get_queried_object_id());
    }

    public function testShouldReturnTrueWhenSetToStaticFrontPageAndAtRoot()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        FrontPageDisplays::clearOptionsCache();

        $this->go_to('/');
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertSame(self::$pageOnFront, (int) get_queried_object_id());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertSame(self::$pageOnFront, (int) get_queried_object_id());
    }

    public function testShouldReturnFalseWhenSetToStaticPostsPageButNotAtRoot()
    {
        update_option('show_on_front', 'page');
        update_option('page_for_posts', self::$pageForPosts);
        FrontPageDisplays::clearOptionsCache();

        $this->go_to(get_permalink(self::$pageOnFront));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$pageOnFront, (int) get_queried_object_id());

        $this->go_to(get_permalink(self::$testPageId));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertFalse(is_home());
        $this->assertFalse(is_front_page());
        $this->assertSame(self::$testPageId, (int) get_queried_object_id());
    }

    public function testShouldReturnTrueWhenSetToStaticPostsPageAndAtRoot()
    {
        update_option('show_on_front', 'page');
        update_option('page_for_posts', self::$pageForPosts);
        FrontPageDisplays::clearOptionsCache();

        $this->go_to('/');
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());

        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertNotSame(self::$pageForPosts, (int) get_queried_object_id());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertNotSame(self::$pageForPosts, (int) get_queried_object_id());
    }

    public function testShouldReturnTrueWhenBothStaticsSetAndAtRoot()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);
        FrontPageDisplays::clearOptionsCache();

        $this->go_to('/');
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertQueryTrue('is_front_page', 'is_page', 'is_singular');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, (int) get_queried_object_id());

        $this->go_to(home_url());
        $this->assertTrue(Conditionals::isRootPage());
        $this->assertTrue(is_root_web_page());
        $this->assertQueryTrue('is_front_page', 'is_page', 'is_singular');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, (int) get_queried_object_id());

        $this->go_to(get_permalink(self::$pageForPosts));
        $this->assertFalse(Conditionals::isRootPage());
        $this->assertFalse(is_root_web_page());
        $this->assertQueryTrue('is_home', 'is_posts_page');
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertEquals(self::$pageForPosts, (int) get_queried_object_id());
    }
}
