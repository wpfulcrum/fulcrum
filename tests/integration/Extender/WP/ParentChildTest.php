<?php

namespace Fulcrum\Tests\Integration\Extender\WP;

use Fulcrum\Extender\WP\ParentChild;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class ParentChildTest extends IntegrationTestCase
{
    public function testShouldReturnFalseWhenNotAChildPost()
    {
        $this->assertFalse(ParentChild::isChildPost(self::$testPostId));
        $this->assertFalse(is_child_post(self::$testPostId));
        $postId = self::factory()->post->create(['post_title' => 'hello-world']);
        $this->assertFalse(ParentChild::isChildPost($postId));
        $this->assertFalse(is_child_post($postId));
    }

    public function testShouldReturnTrueWhenChildPost()
    {
        $postId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertTrue(ParentChild::isChildPost($postId));
        $this->assertTrue(is_child_post($postId));

        $postId = self::factory()->post->create([
            'post_title'  => 'grandchild-post',
            'post_parent' => $postId,
        ]);
        $this->assertTrue(ParentChild::isChildPost($postId));
        $this->assertTrue(is_child_post($postId));
    }

    public function testShouldReturnFalseWhenNotParentPost()
    {
        $postId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertFalse(ParentChild::isParentPost($postId));
        $this->assertFalse(is_parent_post($postId));

        $postId = self::factory()->post->create([
            'post_title'  => 'grandchild-post',
            'post_parent' => $postId,
        ]);
        $this->assertFalse(ParentChild::isParentPost($postId));
        $this->assertFalse(is_parent_post($postId));
    }

    public function testShouldReturnTrueWhenParentPost()
    {
        $this->assertTrue(ParentChild::isParentPost(self::$testPostId));
        $this->assertTrue(is_parent_post(self::$testPostId));

        $postId = self::factory()->post->create(['post_title' => 'hello-world']);
        $this->assertTrue(ParentChild::isParentPost($postId));
        $this->assertTrue(is_parent_post($postId));

        $postId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertTrue(ParentChild::isParentPost(self::$testPostId));
        $this->assertTrue(is_parent_post(self::$testPostId));
        $this->assertFalse(ParentChild::isParentPost($postId));
        $this->assertFalse(is_parent_post($postId));

        $postId = self::factory()->post->create([
            'post_title'  => 'grandchild-post',
            'post_parent' => $postId,
        ]);
        $this->assertTrue(ParentChild::isParentPost(self::$testPostId));
        $this->assertTrue(is_parent_post(self::$testPostId));
        $this->assertFalse(ParentChild::isParentPost($postId));
        $this->assertFalse(is_parent_post($postId));
    }

    public function testShouldReturnNumberOfChildren()
    {
        $this->assertEquals(0, ParentChild::getNumberOfPostChildren(self::$testPostId));
        $this->assertEquals(0, get_number_of_children_for_post(self::$testPostId));

        $postId = self::factory()->post->create(['post_title' => 'hello-world']);
        $this->assertEquals(0, ParentChild::getNumberOfPostChildren($postId));
        $this->assertEquals(0, get_number_of_children_for_post($postId));

        $childId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertEquals(1, ParentChild::getNumberOfPostChildren(self::$testPostId));
        $this->assertEquals(1, get_number_of_children_for_post(self::$testPostId));
        $this->assertEquals(0, ParentChild::getNumberOfPostChildren($childId));
        $this->assertEquals(0, get_number_of_children_for_post($childId));

        $grandchildId = self::factory()->post->create([
            'post_title'  => 'grandchild-post',
            'post_parent' => $childId,
        ]);
        $this->assertEquals(1, ParentChild::getNumberOfPostChildren($childId));
        $this->assertEquals(1, get_number_of_children_for_post($childId));
        $this->assertEquals(0, ParentChild::getNumberOfPostChildren($grandchildId));
        $this->assertEquals(0, get_number_of_children_for_post($grandchildId));
    }

    public function testShouldReturnFalseWhenNoChildren()
    {
        $this->assertFalse(ParentChild::postHasChildren(self::$testPostId));
        $this->assertFalse(post_has_children(self::$testPostId));

        $postId = self::factory()->post->create(['post_title' => 'hello-world']);
        $this->assertFalse(ParentChild::postHasChildren($postId));
        $this->assertFalse(post_has_children($postId));

        $postId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertFalse(ParentChild::postHasChildren($postId));
        $this->assertFalse(post_has_children($postId));
    }

    public function testShouldReturnTrueWhenHasChildren()
    {
        $childId = self::factory()->post->create([
            'post_title'  => 'child-post',
            'post_parent' => self::$testPostId,
        ]);
        $this->assertTrue(ParentChild::postHasChildren(self::$testPostId));
        $this->assertTrue(post_has_children(self::$testPostId));

        $grandchildId = self::factory()->post->create([
            'post_title'  => 'grandchild-post',
            'post_parent' => $childId,
        ]);
        $this->assertTrue(ParentChild::postHasChildren($childId));
        $this->assertTrue(post_has_children($childId));
        $this->assertFalse(ParentChild::postHasChildren($grandchildId));
        $this->assertFalse(post_has_children($grandchildId));
    }

    public function testShouldReturnNextParentPost()
    {
        $postOne = self::factory()->post->create_and_get([
            'post_title' => 'First',
            'post_date'  => '2017-01-01 12:00:00',
        ]);

        $postTwo = self::factory()->post->create_and_get([
            'post_title' => 'Second',
            'post_date'  => '2017-02-01 12:00:00',
        ]);

        $postThree = self::factory()->post->create_and_get([
            'post_title'  => 'Third',
            'post_parent' => $postOne->ID,
            'post_date'   => '2017-03-01 12:00:00',
        ]);

        $postFour = self::factory()->post->create_and_get([
            'post_title'  => 'Fourth',
            'post_parent' => $postTwo->ID,
            'post_date'   => '2017-04-01 12:00:00',
        ]);

        $postFive = self::factory()->post->create_and_get([
            'post_title' => 'Fifth',
            'post_date'  => '2017-05-01 12:00:00',
        ]);

        $this->go_to(get_permalink($postOne->ID));
        $this->assertEquals($postTwo, ParentChild::getNextParentPost());
        // calling it again returns the same answer.
        $this->assertEquals($postTwo, get_next_parent_post());
        $this->assertNotEquals($postFive, ParentChild::getNextParentPost());

        $this->go_to(get_permalink($postTwo->ID));
        $this->assertNotEquals($postThree, ParentChild::getNextParentPost());
        $this->assertNotEquals($postFour, ParentChild::getNextParentPost());
        $this->assertEquals($postFive, ParentChild::getNextParentPost());
        // calling it again returns the same answer.
        $this->assertEquals($postFive, get_next_parent_post());

        // This one is a child post.  The next "parent" is $postFive.
        $this->go_to(get_permalink($postThree->ID));
        $this->assertEquals($postFive, ParentChild::getNextParentPost());
        $this->assertEquals($postFive, get_next_parent_post());
        $this->assertNotEquals($postFour, ParentChild::getNextParentPost());
        $this->assertNotEquals($postTwo, ParentChild::getNextParentPost());
    }

    public function testShouldReturnPreviousParentPost()
    {
        $postOne = self::factory()->post->create_and_get([
            'post_title' => 'First',
            'post_date'  => '2017-01-01 12:00:00',
        ]);

        $postTwo = self::factory()->post->create_and_get([
            'post_title' => 'Second',
            'post_date'  => '2017-02-01 12:00:00',
        ]);

        $postThree = self::factory()->post->create_and_get([
            'post_title'  => 'Third',
            'post_parent' => $postOne->ID,
            'post_date'   => '2017-03-01 12:00:00',
        ]);

        $postFour = self::factory()->post->create_and_get([
            'post_title'  => 'Fourth',
            'post_parent' => $postTwo->ID,
            'post_date'   => '2017-04-01 12:00:00',
        ]);

        $postFive = self::factory()->post->create_and_get([
            'post_title' => 'Fifth',
            'post_date'  => '2017-05-01 12:00:00',
        ]);

        $this->go_to(get_permalink($postOne->ID));
        $this->assertEmpty(ParentChild::getPreviousParentPost());
        $this->assertEmpty(get_previous_parent_post());
        $this->assertNotEquals($postTwo, get_previous_parent_post());

        $this->go_to(get_permalink($postTwo->ID));
        $this->assertNotEquals($postThree, ParentChild::getPreviousParentPost());
        $this->assertNotEquals($postFour, get_previous_parent_post());
        $this->assertNotEquals($postFive, get_previous_parent_post());
        $this->assertEquals($postOne, ParentChild::getPreviousParentPost());
        $this->assertEquals($postOne, get_previous_parent_post());

        // This one is a child post.  The previous parent is $postTwo.
        $this->go_to(get_permalink($postThree->ID));
        $this->assertEquals($postTwo, get_previous_parent_post());
        $this->assertNotEquals($postFour, ParentChild::getNextParentPost());
        $this->assertNotEquals($postOne, ParentChild::getNextParentPost());

        // This one is a child post.  The previous parent is $postTwo.
        $this->go_to(get_permalink($postFour->ID));
        $this->assertEquals($postTwo, get_previous_parent_post());
        $this->assertNotEquals($postThree, ParentChild::getNextParentPost());
        $this->assertNotEquals($postOne, ParentChild::getNextParentPost());

        $this->go_to(get_permalink($postFive->ID));
        $this->assertEquals($postTwo, get_previous_parent_post());
        $this->assertNotEquals($postThree, ParentChild::getNextParentPost());
        $this->assertNotEquals($postFour, ParentChild::getNextParentPost());
    }

    public function testShouldReturnGetTheID()
    {
        $this->go_to(get_permalink(static::$testPostId));
        $this->assertEquals(static::$testPostId, ParentChild::extractPostId(static::$testPostId));
    }
}
