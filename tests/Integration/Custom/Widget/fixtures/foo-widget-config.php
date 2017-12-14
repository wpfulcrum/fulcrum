<?php

return [
    'id_base'         => 'foo-widget',
    'name'            => 'Foo Widget',
    'widget_options'  => [
        'classname'   => 'foo-widget',
        'description' => 'Displays some cool stuff.',
    ],
    'control_options' => [
        'width'  => 400,
        'height' => 350,
    ],
    'defaults'        => [
        'class' => '',
    ],
    'views'           => [
        'widget' => __DIR__ . '/views/foo.php',
        'form'   => __DIR__ . '/views/foo-form.php',
    ],
];
