<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 11/18/17
 * Time: 4:22 PM
 */

namespace Rahii\MinioLaravel\Classes;


use MongoDB\BSON\ObjectID;
use MongoDB\Client;

class MongoManager
{
    protected $client;

    public function __construct()
    {
        $this->setClient();
    }

    /**
     *set the mongo client
     *
     */
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

    /**
     * insert video data into mongo
     *
     * @param Media $video
     * @return bool
     */
    public function insertVideo(Media $video)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'videos');
        $result = $collection->insertOne($video->__toArray());
        return $result ? true : false;
    }

    /**
     * insert picture data into mongo
     *
     * @param Media $picture
     * @return bool
     */
    public function insertPicture(Media $picture)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->insertOne($picture->__toArray());
        return $result ? true : false;
    }

    /**
     * insert versioned picture into mongo to the same record of the original
     *
     * @param Media $picture
     * @param $version
     * @return bool
     */
    public function insertVersionedPicture(Media $picture, $version)
    {

        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->updateOne(['_id' => new ObjectID($picture->getHashId())],
            ['$set' => [$version => $picture->version_toArray()]]);
        /*TODO: determine expiration*/
        return $result ? true : false;
    }

    /**
     * get a picture info from mongo by the $id
     *
     * @param $id
     * @param string $mimetype
     * @return Media
     */
    public function getPictureById($id, $mimetype = 'image/jpeg')
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->findOne(['_id' => new ObjectID($id)]);
        if (!$result) {
            /*TODO: exception*/
        }
        $picture = new Media($id, $mimetype);
        $picture->setBucket($result['bucket'])
            ->setUri($result['original']['uri'])
            ->setPath($result['original']['path'])
            ->setSize($result['original']['size'])
            ->setDimension($result['original']['dimension']);
        return $picture;
    }
}