<?php

namespace Rahii\MinioLaravel\Classes;

use Illuminate\Support\Facades\Config;

class ClassExample {

    public static function get($data = [])
    {
        echo Config::get('minio.name');
    }

}