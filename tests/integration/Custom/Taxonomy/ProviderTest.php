<?php

namespace Fulcrum\Tests\Integration\Custom\Taxonomy;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Taxonomy\LabelsBuilder;
use Fulcrum\Custom\Taxonomy\Taxonomy;
use Fulcrum\Custom\Taxonomy\TaxonomyProvider;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class ProviderTest extends IntegrationTestCase
{
    protected $concreteConfig;
    protected $fulcrumMock;

    public function setUp()
    {
        parent::setUp();

        $this->concreteConfig = require __DIR__.'/fixtures/genre.php';
        $this->fulcrumMock    = Mockery::mock('Fulcrum\FulcrumContract');
        register_post_type('book');
    }

    public function tearDown()
    {
        parent::tearDown();
        unregister_post_type('book');
        unregister_taxonomy('genre');
    }

    public function testShouldRegisterConcrete()
    {
        $provider = new TaxonomyProvider($this->fulcrumMock);

        // Mock Fulcrum's registerConcrete, which would store it in the Container and return the instance.
        $concrete = $provider->getConcrete($this->concreteConfig, 'tax.genre')['concrete'];
        $this->fulcrumMock
            ->shouldReceive('registerConcrete')
            ->andReturn($concrete());

        // Time to register.
        $taxonomy = $provider->register($this->concreteConfig, 'tax.genre');
        $this->assertInstanceOf(Taxonomy::class, $taxonomy);
        $this->assertNull($taxonomy->register());

        // Yup, it was registered.
        $this->assertTrue(taxonomy_exists('genre'));

        $genre = get_taxonomy('genre');

        // Test arguments.
        $this->assertEquals('genre', $genre->name);
        $this->assertEquals('My Really Cool Book Genres', $genre->description);
        $this->assertEquals(['book'], $genre->object_type);

        // Test custom labels.
        $this->assertEquals('Really Cool Genres', $genre->labels->name);
        $this->assertEquals('This Cool Genre', $genre->labels->singular_name);
        $this->assertEquals('All Really Cool Genres', $genre->labels->all_items);
        $this->assertEquals('Parent Genre', $genre->labels->parent_item);
        $this->assertEquals('Amazing Genres', $genre->labels->menu_name);
    }
}
