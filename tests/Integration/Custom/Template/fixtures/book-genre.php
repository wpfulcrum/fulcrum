<?php

return [
    'autoload' => true,
    'config'   => [
        'templateFolderPath' => __DIR__ . '/templates/',
        'postType'           => 'book',
        'useSingle'          => true,
        'useArchive'         => true,
        'useTax'             => true,
        'taxonomy'           => 'genre',
        'usePageTemplates'   => true,
    ],
];
