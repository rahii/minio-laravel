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
        'domain' => env('', 'http://localhost:9000'),
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
    ]

];
