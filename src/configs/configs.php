<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'renderer' => [
            'template_path' => __DIR__ . '/../view',
        ],
        'database' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'invoiceproject',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'token_expiry' => 43200 
    ],
];