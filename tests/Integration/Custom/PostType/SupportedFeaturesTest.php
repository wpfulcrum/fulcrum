<?php

namespace Fulcrum\Tests\Integration\Custom\PostType;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\PostType;
use Fulcrum\Custom\PostType\SupportedFeatures;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class SupportedFeaturesTest extends IntegrationTestCase
{
    public function testShouldCreate()
    {
        $this->assertInstanceOf(
            SupportedFeatures::class,
            new SupportedFeatures(
                ConfigFactory::create(
                    [
                        'supports' => ['title', 'editor', 'excerpt'],
                    ]
                )
            )
        );
    }

    public function testShouldBuildAndReturnSupports()
    {
        $config = [
            'supports' => ['title', 'editor', 'excerpt'],
        ];
        $this->assertEquals(
            $config['supports'],
            (new SupportedFeatures(ConfigFactory::create($config)))->build()
        );
    }

    public function testShouldAddPageAttributesWhenSupportsGiven()
    {
        $config           = [
            'hierarchical'       => true,
            'supports'           => ['title', 'editor', 'excerpt'],
            'additionalSupports' => [],
        ];
        $postTypeSupports = new SupportedFeatures(ConfigFactory::create($config));

        $this->assertEquals(['title', 'editor', 'excerpt', 'page-attributes'], $postTypeSupports->build());
    }

    public function testShouldReturnPostSupportsWhenNoSupportsGiven()
    {
        $config   = ConfigFactory::create(
            [
                'hierarchical'       => false,
                'additionalSupports' => [],
            ]
        );
        $supports = (new SupportedFeatures($config))->build();

        $this->assertContains('title', $supports);
        $this->assertEquals(array_keys(get_all_post_type_supports('post')), $supports);
    }

    public function testShouldBuildAdditionalSupports()
    {
        $config   = ConfigFactory::create(
            [
                'hierarchical'       => false,
                // All of the post type supported features + 2 extra.
                'additionalSupports' => [
                    'title'           => true,
                    'editor'          => true,
                    'author'          => false,
                    'thumbnail'       => true,
                    'excerpt'         => true,
                    'trackbacks'      => false,
                    'custom-fields'   => false,
                    'comments'        => false,
                    'revisions'       => false,
                    'page-attributes' => false,
                    'post-formats'    => false,
                    'foo'             => true,
                    'bar'             => true,
                ],
            ]
        );
        $supports = (new SupportedFeatures($config))->build();

        $this->assertContains('title', $supports);
        $this->assertContains('editor', $supports);
        $this->assertNotContains('author', $supports);
        $this->assertContains('thumbnail', $supports);
        $this->assertContains('excerpt', $supports);
        $this->assertNotContains('trackbacks', $supports);
        $this->assertNotContains('custom-fields', $supports);
        $this->assertNotContains('comments', $supports);
        $this->assertNotContains('revisions', $supports);
        $this->assertNotContains('page-attributes', $supports);
        $this->assertNotContains('post-formats', $supports);
        $this->assertContains('foo', $supports);
        $this->assertContains('bar', $supports);

        $config   = ConfigFactory::create(
            [
                'hierarchical'       => false,
                // Only a few of them.
                'additionalSupports' => [
                    'author'        => false,
                    'custom-fields' => false,
                    'comments'      => false,
                    'revisions'     => false,
                    'foo'           => true,
                    'bar'           => true,
                ],
            ]
        );
        $supports = (new SupportedFeatures($config))->build();

        $this->assertContains('title', $supports);
        $this->assertContains('editor', $supports);
        $this->assertNotContains('author', $supports);
        $this->assertContains('thumbnail', $supports);
        $this->assertContains('excerpt', $supports);
        $this->assertContains('trackbacks', $supports);
        $this->assertNotContains('custom-fields', $supports);
        $this->assertNotContains('comments', $supports);
        $this->assertNotContains('revisions', $supports);
        $this->assertNotContains('page-attributes', $supports);
        $this->assertContains('post-formats', $supports);
        $this->assertContains('foo', $supports);
        $this->assertContains('bar', $supports);
    }
}
