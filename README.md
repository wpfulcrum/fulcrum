# Post Type Module

[![Build Status](https://travis-ci.org/wpfulcrum/post-type.svg?branch=develop)](https://travis-ci.org/wpfulcrum/post-type) 
[![Latest Stable Version](https://poser.pugx.org/wpfulcrum/post-type/v/stable)](https://packagist.org/packages/wpfulcrum/post-type) 
[![License](https://poser.pugx.org/wpfulcrum/post-type/license)](https://packagist.org/packages/wpfulcrum/post-type)

The Fulcrum Custom Post Type Module makes your job easier for adding custom post types to your project. Pass it a configuration and it handles the rest for you.

## Features

- Registration is handled for you.
- Label generation - handy when you do not need internationalization.
- Supported features builder - handy when you want the supports that other plugins and/or the theme adds.
- Stores in Fulcrum's Container - when added, automatically stores it in the Container for global usage.
- Column filtering, sorting, and configuration are all handled for you.

## Installation

The best way to use this component is through Composer:

```
composer require wpfulcrum/post-type
```

## Dependencies

This module requires:
 
- at least PHP 5.6
- WordPress 4.8+

## Configuring a Custom Post Type

This module, as with all Fulcrum modules, is configuration driven as part of the ModularConfig design pattern.  In your theme/plugin's configuration folder, you will want to create a configuration file.  Here is the basic structure of that file:

```
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

/**
 * Arguments Configuration Parameters
 *
 * @see https://codex.wordpress.org/Function_Reference/register_post_type#arguments for more details.
 *
 * Don't configure the label, labels, or supports here.  Those are handled separately below.
 */
$config['config']['postTypeArgs'] = [
    'description'  => 'Books - example custom post type',
    'public'       => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'has_archive'  => true,
    'menu_icon'    => 'dashicons-book', // @link https://developer.wordpress.org/resource/dashicons
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

```

## Making It Work

There are 2 ways to utilize this module:

1. With the full [Fulcrum plugin](https://github.com/wpfulcrum/fulcrum).
2. Or on its own without Fulcrum.

### With Fulcrum

In Fulcrum, your plugin is an Add-on.  In your plugin's configuration file, you will have a parameter for the `serviceProviders`, where you list each of the service providers you want to use.  In this case, you'll use the `provider.post_type`.  

For example, using our Book configuration above, this would be the configuration:

```
	'serviceProviders' => [

		/****************************
		 * Custom Post Types
		 ****************************/
		'book.post_type' => array(
			'provider' => 'provider.post_type', // this is the service provider to be used.
			'config'   => BOOK_PLUGIN_DIR . 'config/post-type/book.php', // path to the book post type's configuration file.
		),
	],
```

[Fulcrum's Add-On Module](https://github.com/wpfulcrum/addon) handles flushing the rewrites upon plugin activation and deactivation.  That saves you time.

### Without Fulcrum

Without Fulcrum, you'll need to instantiate each of the dependencies and `PostType`.  For example, you would do:

```
$config = require_once BOOK_PLUGIN_DIR . 'config/post-type/book.php';  // path to the book post type's configuration file.

$supportsConfig                 = $config['config']['supportsConfig'];
$supportsConfig['hierarchical'] = $config['config']['postTypeArgs']['hierarchical'];

$postType = new PostType(
    $config['postType'],
    ConfigFactory::create($config['config']['postTypeArgs']),
    new Columns($config['postType'], ConfigFactory::create($config['config']['columnsConfig'])),
    new SupportedFeatures(ConfigFactory::create($supportsConfig)),
    new LabelsBuilder(ConfigFactory::create($config['config']['labelsConfig']))
);
$postType->register();    
```

You will need to handle flushing the rewrites with your plugin's activation and deactivation.

## Contributing

All feedback, bug reports, and pull requests are welcome.