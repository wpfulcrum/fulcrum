<?php

namespace Fulcrum\Tests\Integration\Extender\WP;

use Fulcrum\Extender\WP\FrontPageDisplays;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class FrontPageDisplaysTest extends IntegrationTestCase
{
    public static function tearDownAfterClass()
    {
        FrontPageDisplays::clearOptionsCache();
        parent::tearDownAfterClass();
    }

    public function testShouldReturnStaticPostsPageId()
    {
        $this->assertEquals(0, FrontPageDisplays::getStaticPostsPageID());

        update_option('page_for_posts', self::$pageForPosts);

        $this->assertEquals(self::$pageForPosts, FrontPageDisplays::getStaticPostsPageID());
    }

    public function testShouldReturnStaticFrontPageId()
    {
        $this->assertEquals(0, FrontPageDisplays::getStaticFrontPageID());

        update_option('page_on_front', self::$pageOnFront);

        $this->assertEquals(self::$pageOnFront, FrontPageDisplays::getStaticFrontPageID());
    }

    public function testShouldReturnFalseWhenYourLatestPostsNotSet()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);

        $this->assertFalse(FrontPageDisplays::isSetToYourLatestPosts());
    }

    public function testShouldReturnTrueWhenYourLatestPostsIsSet()
    {
        $this->assertTrue(FrontPageDisplays::isSetToYourLatestPosts());

        $this->go_to('/');
        $this->assertQueryTrue('is_home', 'is_posts_page', 'is_front_page');
        $this->assertTrue(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(0, get_queried_object_id());

        update_option('page_on_front', self::$pageOnFront);

        $this->go_to('/');
        $this->assertTrue(is_home());
        $this->assertTrue(is_front_page());
    }

    public function testShouldReturnFalseWhenNotSetToStaticPostsPage()
    {
        $this->assertFalse(FrontPageDisplays::isSetToStaticPostsPage());

        update_option('show_on_front', 'page');
        $this->assertFalse(FrontPageDisplays::isSetToStaticPostsPage());

        update_option('page_on_front', self::$pageOnFront);
        $this->assertFalse(FrontPageDisplays::isSetToStaticPostsPage());
        $this->go_to('/');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
    }

    public function testShouldReturnTrueWhenSetToStaticPostsPage()
    {
        update_option('show_on_front', 'page');
        $this->go_to('/');
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertEquals(0, get_queried_object_id());

        update_option('page_for_posts', self::$pageForPosts);
        $this->assertTrue(FrontPageDisplays::isSetToStaticPostsPage());
        $this->go_to('/');
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertEquals(0, get_queried_object_id());

        update_option('page_on_front', 0);
        $this->assertTrue(FrontPageDisplays::isSetToStaticPostsPage());
        $this->go_to('/');
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertEquals(0, get_queried_object_id());
    }

    public function testShouldReturnFalseWhenNotSetToStaticFrontPage()
    {
        $this->assertFalse(FrontPageDisplays::isSetToStaticFrontPage());

        update_option('show_on_front', 'page');
        $this->assertFalse(FrontPageDisplays::isSetToStaticFrontPage());

        update_option('page_for_posts', self::$pageForPosts);
        $this->assertFalse(FrontPageDisplays::isSetToStaticFrontPage());
    }

    public function testShouldReturnTrueWhenSetToStaticFrontPage()
    {
        update_option('show_on_front', 'page');

        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);
        $this->go_to('/');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, get_queried_object_id());

        FrontPageDisplays::clearOptionsCache();

        delete_option('page_for_posts');
        $this->assertTrue(FrontPageDisplays::isSetToStaticFrontPage());
        $this->go_to('/');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, get_queried_object_id());

        update_option('page_for_posts', 0);
        $this->assertTrue(FrontPageDisplays::isSetToStaticFrontPage());
        $this->go_to('/');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, get_queried_object_id());
    }

    public function testShouldReturnTrueWhenBothStaticsAreSet()
    {
        update_option('show_on_front', 'page');
        update_option('page_on_front', self::$pageOnFront);
        update_option('page_for_posts', self::$pageForPosts);

        $this->assertTrue(FrontPageDisplays::isSetForBothStatics());

        $this->go_to('/');
        $this->assertQueryTrue('is_front_page', 'is_page', 'is_singular');
        $this->assertFalse(is_home());
        $this->assertTrue(is_front_page());
        $this->assertEquals(self::$pageOnFront, get_queried_object_id());

        $this->go_to(get_permalink(self::$pageForPosts));
        $this->assertQueryTrue('is_home', 'is_posts_page');
        $this->assertTrue(is_home());
        $this->assertFalse(is_front_page());
        $this->assertEquals(self::$pageForPosts, get_queried_object_id());
    }
}
