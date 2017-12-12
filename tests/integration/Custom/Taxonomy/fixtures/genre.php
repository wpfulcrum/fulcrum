<?php

$config = [
    'autoload'     => true,
    'taxonomyName' => 'genre',
    'config'       => [
        'taxonomyConfig' => [],
        'labelsConfig'   => [],
    ],
];

$config['config']['taxonomyConfig'] = [
    'objectType' => ['book'],
    'args'       => [
        'description'       => 'My Really Cool Book Genres',
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
    ],
];

/**
 * Labels Builder - Configuration Parameters
 */
$config['config']['labelsConfig'] = [
    'useBuilder'   => false,
    'pluralName'   => 'Genres',
    'singularName' => 'Genre',
    'labels'       => [
        'name'              => _x('Really Cool Genres', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('This Cool Genre', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Genres', 'textdomain'),
        'all_items'         => __('All Really Cool Genres', 'textdomain'),
        'parent_item'       => __('Parent Genre', 'textdomain'),
        'parent_item_colon' => __('Parent Genre:', 'textdomain'),
        'edit_item'         => __('Edit Genre', 'textdomain'),
        'update_item'       => __('Update Genre', 'textdomain'),
        'add_new_item'      => __('Add New Genre', 'textdomain'),
        'new_item_name'     => __('New Genre Name', 'textdomain'),
        'menu_name'         => __('Amazing Genres', 'textdomain'),
    ],
];

return $config;
