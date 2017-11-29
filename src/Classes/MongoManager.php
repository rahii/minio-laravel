<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 11/18/17
 * Time: 4:22 PM
 */

namespace Rahii\MinioLaravel\Classes;


use MongoDB\Client;

class MongoManager
{
    protected $client;

    public function __construct()
    {
        $this->setClient();
    }

    public function setClient()
    {
        if (!env('MONGODB_USERNAME') && !env('MONGODB_PASSWORD')) {
            $this->client = new Client(config('minio.db')['mongo']['driver'] . '://' .
                config('minio.db')['mongo']['host'] . ':' .
                config('minio.db')['mongo']['port']
            );
        } else {
            $this->client = new Client(config('minio.db')['mongo']['driver'] . '://' .
                config('minio.db')['mongo']['host'] . ':' .
                config('minio.db')['mongo']['port']
                , ['authSource' => config('minio.db')['mongo']['database'],
                    'username' => config('minio.db')['mongo']['username'],
                    'password' => config('minio.db')['mongo']['password']]
            );
        }

    }

    public function insertVideo(Media $video)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'videos');
        $result = $collection->insertOne($video->__toArray());
        return $result ? true : false;
    }

    public function insertPicture(Media $picture)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->insertOne($picture->__toArray());
        return $result ? true : false;
    }
}