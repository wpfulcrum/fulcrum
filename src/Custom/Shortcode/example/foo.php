<?php

return [
    /**
     * Defines whether the shortcode should autoload when registering with Fulcrum.
     *
     * Default is true.
     */
    'autoload'  => true,

    /**
     * If you'd like to build your own shortcode class, specify the class name here, such as:
     *
     * Fulcrum\Custom\Shortcode\Shortcode
     *
     * By default, it will use the built-in Shortcode class.
     */
    'classname' => '',

    'config'    => [
        /**
         * Specify the name of the shortcode.  This is the "tag" which is used in the content as: [foo].
         */
        'shortcode' => 'foo',

        /**
         * Set this parameter to `true` when no view file is needed for this shortcode.
         */
        'noView' => false,

        /**
         * Specify the absolute path to this shortcode's view file.
         * The view file contains the HTML that is built for this shortcode.
         */
        'view'      => __DIR__ . '/views/foo.php',

        /**
         * Specify the default attributes for this shortcode.
         */
        'defaults'  => [
            'class' => 'foobar',
        ],

        /**
         * When set to `true`, the incoming content is processed through {@see do_shortcode()}.
         */
        'doShortcodeWithinContent' => false,
    ],
];
