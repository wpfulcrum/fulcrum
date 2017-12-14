<?php

namespace Fulcrum\Tests\Integration\Custom\PostType;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\PostType\LabelsBuilder;
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
                        'pluralName'   => 'Books',
                        'singularName' => 'Book',
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
                    'name'          => 'Books',
                    'singular_name' => 'Book',
                ],
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertArrayHasKey('name', $labels);
        $this->assertArrayHasKey('singular_name', $labels);
        $this->assertFalse(array_key_exists('add_new', $labels));
        $this->assertFalse(array_key_exists('menu_name', $labels));
        $this->assertEquals('Books', $labels['name']);
        $this->assertEquals('Book', $labels['singular_name']);
        $this->assertEquals($config->labels, $labels);
    }

    public function testShouldReturnDefaultLabels()
    {
        $config = ConfigFactory::create(
            [
                'useBuilder'   => true,
                'pluralName'   => 'Books',
                'singularName' => 'Book',
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertEquals('Books', $labels['name']);
        $this->assertEquals('Book', $labels['singular_name']);
        $this->assertEquals('Add New', $labels['add_new']);
        $this->assertEquals('Books', $labels['menu_name']);
        $this->assertEquals('Edit Book', $labels['edit_item']);
        $this->assertEquals('No books found in Trash', $labels['not_found_in_trash']);
    }

    public function testShouldOverrideDefaultsWithConfigLabels()
    {
        $config = ConfigFactory::create(
            [
                'useBuilder'   => true,
                'pluralName'   => 'Books',
                'singularName' => 'Book',
                'labels'       => [
                    'name'      => 'My Books',
                    'menu_name' => 'My Cool Books',
                ],
            ]
        );
        $labels = (new LabelsBuilder($config))->build();

        $this->assertEquals('My Books', $labels['name']);
        $this->assertEquals('Add New', $labels['add_new']);
        $this->assertEquals('My Cool Books', $labels['menu_name']);
        $this->assertEquals('Book', $labels['singular_name']);
        $this->assertEquals('Edit Book', $labels['edit_item']);
        $this->assertEquals('No books found in Trash', $labels['not_found_in_trash']);
    }
}
