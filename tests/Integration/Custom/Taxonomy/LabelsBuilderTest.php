<?php

namespace Fulcrum\Tests\Integration\Custom\Taxonomy;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Taxonomy\LabelsBuilder;
use Fulcrum\Tests\Integration\IntegrationTestCase;

class LabelsBuilderTest extends IntegrationTestCase
{
    public function testShouldCreate()
    {
        $this->assertInstanceOf(
            LabelsBuilder::class,
            new LabelsBuilder(
                ConfigFactory::create(
                    [
                        'useBuilder'   => false,
                        'pluralName'   => 'Genres',
                        'singularName' => 'Genre',
                        'labels'       => [],
                    ]
                )
            )
        );
    }

    public function testShouldReturnLabelsWhenBuilderDisabled()
    {
        $config = ConfigFactory::create(
            [
                'useBuilder' => false,
                'labels'     => [
                    'name'          => 'Genres',
                    'singular_name' => 'Genre',
                ],
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertArrayHasKey('name', $labels);
        $this->assertArrayHasKey('singular_name', $labels);
        $this->assertFalse(array_key_exists('add_new', $labels));
        $this->assertFalse(array_key_exists('menu_name', $labels));
        $this->assertEquals('Genres', $labels['name']);
        $this->assertEquals('Genre', $labels['singular_name']);
        $this->assertEquals($config->labels, $labels);
    }

    public function testShouldReturnDefaultLabels()
    {
        $config = ConfigFactory::create(
            [
                'useBuilder'   => true,
                'pluralName'   => 'Genres',
                'singularName' => 'Genre',
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertEquals('Genres', $labels['name']);
        $this->assertEquals('Genre', $labels['singular_name']);
        $this->assertEquals('Search Genres', $labels['search_items']);
        $this->assertEquals('Genres', $labels['menu_name']);
        $this->assertEquals('Edit Genre', $labels['edit_item']);
        $this->assertEquals('No genre found', $labels['not_found']);
    }

    public function testShouldOverrideDefaultsWithConfigLabels()
    {
        $config = ConfigFactory::create(
            [
                'useBuilder'   => true,
                'pluralName'   => 'Genres',
                'singularName' => 'Genre',
                'labels'       => [
                    'name'          => 'My Genres',
                    'singular_name' => 'My Mystery',
                    'menu_name'     => 'My Mysteries',
                ],
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertEquals('My Genres', $labels['name']);
        $this->assertEquals('My Mystery', $labels['singular_name']);
        $this->assertEquals('Search Genres', $labels['search_items']);
        $this->assertEquals('My Mysteries', $labels['menu_name']);
        $this->assertEquals('Edit Genre', $labels['edit_item']);
        $this->assertEquals('No genre found', $labels['not_found']);
    }
}
