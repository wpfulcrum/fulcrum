<?php

$config = [
    'autoload' => true,
    'postType' => 'book',
    'config'   => [
        'postTypeArgs'   => [],
        'labelsConfig'   => [],
        'supportsConfig' => [],
        'columnsConfig'  => [],
    ],
];

$config['config']['postTypeArgs'] = [
    'description'  => 'Books - example custom post type',
    'public'       => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'has_archive'  => true,
    'menu_icon'    => 'dashicons-book',
];

/**
 * Labels Builder - Configuration Parameters
 */
$config['config']['labelsConfig'] = [
    'useBuilder'   => true,
    'pluralName'   => 'Books',
    'singularName' => 'Book',

    'labels'       => [
        'name'               => _x('Books', 'post type general name', 'your-plugin-textdomain'),
        'singular_name'      => _x('Book', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name'          => _x('Books', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar'     => _x('Book', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new'            => _x('Add New', 'book', 'your-plugin-textdomain'),
        'add_new_item'       => __('Add New Book', 'your-plugin-textdomain'),
        'new_item'           => __('New Book', 'your-plugin-textdomain'),
        'edit_item'          => __('Edit Book', 'your-plugin-textdomain'),
        'view_item'          => __('View Book', 'your-plugin-textdomain'),
        'all_items'          => __('All Books', 'your-plugin-textdomain'),
        'search_items'       => __('Search Books', 'your-plugin-textdomain'),
        'parent_item_colon'  => __('Parent Books:', 'your-plugin-textdomain'),
        'not_found'          => __('No books found.', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('No books found in Trash.', 'your-plugin-textdomain'),
    ],
];

$config['config']['supportsConfig'] = [
//    'supports'           => [],
    'additionalSupports' => [
        'title'           => true,
        'editor'          => true,
        'author'          => false,
        'thumbnail'       => false,
        'excerpt'         => false,
        'trackbacks'      => false,
        'custom-fields'   => false,
        'comments'        => false,
        'revisions'       => false,
        'page-attributes' => false,
        'post-formats'    => false,
        'foo'             => true,
        'bar'             => true,
    ],
];

$config['config']['columnsConfig'] = [
    'columnsFilter' => [],
    'columnsData'   => [],
];

return $config;
