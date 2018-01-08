<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => 'Minio Laravel',

    'version' => '1.0.0',

    'minioStorage' => [
        'minio_driver' => 's3',
        'endpoint' => env('MINIO_ENDPOINT', 'http://minio:9000'),
        'domain' => env('', 'http://minio:9000'),
        'use_path_style_endpoint' => true,
        'key' => env('AWS_KEY', ''),
        'secret' => env('AWS_SECRET', ''),
        'region' => env('AWS_BUCKET', 'ap-south-1'),
        'bucket' => env('AWS_BUCKET', ''),
    ],

    'db' => [
        'mongo' => [
            'driver' => 'mongodb',
            'host' => env('MONGODB_HOST', 'mongo'),
            'port' => env('MONGODB_PORT', 27017),
            'username' => env('MONGODB_USERNAME', ''),
            'password' => env('MONGODB_PASSWORD', ''),
            'database' => env('MONGODB_DATABASE', 'storage'),
            'options' => array(
                'db' => env('MONGODB_AUTHDATABASE', '') //Sets the auth DB
            ),
        ],
    ],

    'pic_version' => [
        'dimension' => [
            '100x100' => ['width' => 100, 'height' => 100],
            '200x200' => ['width' => 200, 'height' => 200],
            '300x300' => ['width' => 300, 'height' => 300],
            '350x350' => ['width' => 350, 'height' => 350],
            '400x400' => ['width' => 400, 'height' => 400],
            '450x450' => ['width' => 450, 'height' => 450],
            '90x90' => ['width' => 90, 'height' => 90],
            '100x' => ['height' => 100],
            'x200' => ['width' => 200]]
    ]

];
