<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    __DIR__ . '/Doctrine/Example/Entity'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' => __DIR__ . '/data/cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => 'localhost',
                'dbname'   => 'example',
                'user'     => 'example',
                'password' => 'example',
                'driverOptions'	=> array('1002'=> "SET NAMES 'UTF8' COLLATE 'utf8_general_ci'")
            ]
        ],
    ],
];
