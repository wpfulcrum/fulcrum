<?php

namespace Fulcrum\Tests\Integration\Custom\Taxonomy;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Taxonomy\LabelsBuilder;
use Fulcrum\Custom\Taxonomy\PostType;
use Fulcrum\Custom\Taxonomy\Taxonomy;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class TaxonomyTest extends IntegrationTestCase
{
    protected static $taxonomyConfig = [
        'objectType' => ['book'],
        'args'       => [
            'description'       => 'Book Genres',
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
        ],
    ];
    protected $config;
    protected $labelsMock;

    public function setUp()
    {
        parent::setUp();

        $this->config     = ConfigFactory::create(self::$taxonomyConfig);
        $this->labelsMock = Mockery::mock('Fulcrum\Custom\Taxonomy\Contract\LabelsBuilderContract');
        register_post_type('book');
    }

    public function tearDown()
    {
        parent::tearDown();
        unregister_post_type('book');
        unregister_taxonomy('genre');
    }

    public function testShouldRegister()
    {
        $this->labelsMock->shouldReceive('build')->andReturn(
            [
                'name'          => 'Genres',
                'singular_name' => 'Genre',
            ]
        );

        $taxonomy = new Taxonomy('genre', $this->config, $this->labelsMock);
        $this->assertNull($taxonomy->register());

        $this->assertTrue(taxonomy_exists('genre'));

        $genre = get_taxonomy('genre');
        $this->assertEquals('genre', $genre->name);
        $this->assertEquals('Genres', $genre->labels->name);
        $this->assertEquals('Genre', $genre->labels->singular_name);
        $this->assertEquals('Book Genres', $genre->description);
        $this->assertEquals(['book'], $genre->object_type);

        $this->assertEquals(['genre'], get_object_taxonomies('book', 'names'));
    }

    public function testShouldRegisterWithIntegratedLabels()
    {
        $taxonomy = new Taxonomy(
            'genre',
            $this->config,
            new LabelsBuilder(
                ConfigFactory::create(
                    [
                        'useBuilder'   => true,
                        'pluralName'   => 'Cool Genres',
                        'singularName' => 'Awesome Genre',
                    ]
                )
            )
        );
        $this->assertNull($taxonomy->register());

        $this->assertTrue(taxonomy_exists('genre'));

        $genre = get_taxonomy('genre');

        // Test arguments.
        $this->assertEquals('genre', $genre->name);
        $this->assertEquals('Book Genres', $genre->description);
        $this->assertEquals(['book'], $genre->object_type);

        // Test custom labels.
        $this->assertEquals('Cool Genres', $genre->labels->name);
        $this->assertEquals('Awesome Genre', $genre->labels->singular_name);
        $this->assertEquals('Cool Genres', $genre->labels->menu_name);
    }
}
