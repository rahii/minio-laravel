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
            throw new NotFoundException('picture not found');
        }
        $picture = new Media($id, $mimetype);
        $picture->setBucket($result['bucket'])
            ->setOriginalExists($result['original_exists'])
            ->setUri($result['original']['uri'])
            ->setPath($result['original']['path'])
            ->setSize($result['original']['size'])
            ->setHeight($result['original']['height'])
            ->setWidth($result['original']['width']);
        return $picture;
    }


    /**
     * check if a version of picture already exists
     *
     * @param $id
     * @param $version
     * @return null
     */
    public function versionedPictureExists($id, $version)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->findOne(['_id' => new ObjectID($id)]);
        if (!array_has($result, $version)) {
            return null;
        }
        return $result[$version]['uri'];
    }

    /**
     * get a picture info from mongo by id and version
     *
     * @param $id
     * @param $version
     * @param string $mimetype
     * @return null|Media
     */
    public function getPictureByVersion($id, $version, $mimetype = 'image/jpeg')
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->findOne(['_id' => new ObjectID($id)]);
        if (!array_has($result, $version)) {
            return null;
        }
        $picture = new Media($id, $mimetype);
        $picture->setBucket($result['bucket'])
            ->setUri($result[$version]['uri'])
            ->setPath($result[$version]['path'])
            ->setSize($result[$version]['size'])
            ->setHeight($result[$version]['height'])
            ->setWidth($result[$version]['width']);
        return $picture;
    }

    /**
     * set original_exists false when the original picture file is removed
     *
     * @param $id
     * @return bool
     */
    public function setOriginalRemoved($id)
    {
        $collection = $this->client->selectCollection(config('minio.db')['mongo']['database'], 'pictures');
        $result = $collection->updateOne(['_id' => new ObjectID($id)],
            ['$set' => ['original_exists' => false]]);
        return $result ? true : false;
    }
}