<?php

return [
    'autoload'     => true,
    'postTypeName' => 'foo',
    'config'       => [
        'pluralName'         => 'Foos',
        'singularName'       => 'Foo',
        'args'               => [
            'public'       => true,
            'hierarchical' => false,
            'show_in_rest' => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-video-alt2',
            'description'  => 'Foo - example custom post type',
        ],
        'labels'             => [
            'archive' => 'Foo',
        ],
        'additionalSupports' => [
            'author'          => false,
            'comments'        => false,
            'excerpt'         => false,
            'post-formats'    => false,
            'trackbacks'      => false,
            'custom-fields'   => false,
            'revisions'       => false,
            'page-attributes' => true,
        ],
    ],
];
