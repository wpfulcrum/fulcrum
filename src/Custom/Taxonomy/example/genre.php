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

    /**
     * Arguments Configuration Parameters
     *
     * @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments for more details.
     *
     * Don't configure the label or labels here.  Those are handled separately below.
     */
    'args'       => [
        'description'       => 'Book Genres',
//        'label'        => '', <- This isn't needed.
//        'labels'       => [], <- don't configure here as they are configured below.
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

    /***************************************************************************************************
     * When Your Plugin Doesn't Need Internationalization:
     *
     * By default, the label builder automatically builds the labels for you using the plural and singular
     * names you configure below.
     **************************************************************************************************/
    'useBuilder'   => true, // set to false when you need internationalization.
    'pluralName'   => 'Genres',
    'singularName' => 'Genre',

    /***************************************************************************************************
     * Specify the labels you want here.
     *
     * When not using the automatic builder (i.e. when 'useBuilder' is set to `false`), then you specify
     * all the custom labels here.
     *
     * If you are using the builder, any labels you specify here will overwrite what the builder generates.
     *
     * @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments for more details.
     **************************************************************************************************/
    'labels'       => [
        'name'              => _x('Genres', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Genre', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Genres', 'textdomain'),
        'all_items'         => __('All Genres', 'textdomain'),
        'parent_item'       => __('Parent Genre', 'textdomain'),
        'parent_item_colon' => __('Parent Genre:', 'textdomain'),
        'edit_item'         => __('Edit Genre', 'textdomain'),
        'update_item'       => __('Update Genre', 'textdomain'),
        'add_new_item'      => __('Add New Genre', 'textdomain'),
        'new_item_name'     => __('New Genre Name', 'textdomain'),
        'menu_name'         => __('Genre', 'textdomain'),
    ],
];

return $config;
