<?php

$config = [
    'autoload' => true,
    'postType' => 'book',
    'config'   => [
        'postTypeConfig' => [],
        'labelsConfig'   => [],
        'supportsConfig' => [],
        'columnsConfig'  => [],
    ],
];

/**
 * Custom Post Type - Configuration Parameters
 */
$config['config']['postTypeConfig'] = [

    /**
     * Arguments Configuration Parameters
     *
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#arguments for more details.
     *
     * Don't configure the label, labels, or supports here.  Those are handled separately below.
     */
    'args' => [
        'description'  => 'Books - example custom post type',
//        'label'        => '', <-This isn't needed.
//        'labels'       => [], <- don't configure here as they are configured below.
        'public'       => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-book', // @link https://developer.wordpress.org/resource/dashicons
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
    'pluralName'   => 'Books',
    'singularName' => 'Book',

    /***************************************************************************************************
     * Specify the labels you want here.
     *
     * When not using the automatic builder (i.e. when 'useBuilder' is set to `false`), then you specify
     * all the custom labels here.
     *
     * If you are using the builder, any labels you specify here will overwrite what the builder generates.
     *
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#labels for more details.
     **************************************************************************************************/
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

/**
 * Post Type's Supported Features Builder - Configuration Parameters
 */
$config['config']['supportsConfig'] = [

    /***************************************************************************************************
     * When you want only these specific supports, configure them here.
     *
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#supports for more details.
     **************************************************************************************************/
    'supports'           => [
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'comments',
    ],

    /***************************************************************************************************
     * Want all or some of the supports that other plugins/theme add? Use this option instead.
     *
     * For example, let's say you want your custom post type to use the features that Yoast SEO, Genesis,
     * or Beans add.  This option uses the "post" post type as its base to grab all of them. Cool, right?!
     *
     * Configure the post type support features you want to include (set to `true`) or exclude (set to `false`).
     * You can add new ones too.  Then the builder handles it for you.
     **************************************************************************************************/
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
];

/**
 * Columns Handler - Configuration Parameters
 */
$config['config']['columnsConfig'] = [
    'columnsFilter' => [],
    'columnsData'   => [],
];

return $config;
