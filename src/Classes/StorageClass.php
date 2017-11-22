<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 11/18/17
 * Time: 4:30 PM
 */

namespace Rahii\MinioLaravel\Classes;

use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use MongoDB\BSON\ObjectID;


class StorageClass
{
    protected $adapter;
    protected $manager;

    public function __construct()
    {
        $this->adapter = new AwsS3Adapter(new S3Client([
            'use_path_style_endpoint' => config('minio.minioStorage')['use_path_style_endpoint'],
            'version' => 'latest',
            'region' => config('minio.minioStorage')['region'],
            'endpoint' => config('minio.minioStorage')['endpoint'],
            'credentials' => [
                'key' => config('minio.minioStorage')['key'],
                'secret' => config('minio.minioStorage')['secret'],
            ],

        ]), '', '', []);

        $this->manager = new MongoManager();
    }

    public function setBucket(){
        $bucket = date('Y') . '-' . date('m');
        if (!$this->adapter->getClient()->doesBucketExist($bucket)) {
            $this->adapter->getClient()->createBucket(["Bucket" => $bucket]);
        }
        $this->adapter->setBucket($bucket);
        return $bucket;
    }

    public function storeVideo(UploadedFile $videoFile, $user)
    {
        $bucket = $this->setBucket();
        $id = new ObjectID();
        $config = new Config();
        $config->set('mimetype', $videoFile->getMimeType());
        $config->set('type', 'video');
        $record = $this->adapter->write(date('d') . '/video/' . ($id->__toString()) . '.' . str_after($videoFile->getMimeType(), '/'),
            file_get_contents($videoFile), $config);
        $video = (new Media($id, $videoFile->getMimeType()))
            ->setName($videoFile->getClientOriginalName())
            ->setSize($videoFile->getSize())
            ->setPath($record['path'])
            ->setCreatedAt(Carbon::now()->toDateTimeString())
            ->setUser($user)
            ->setBucket($bucket);
        $this->manager->insertVideo($video);
        return $video;
    }

    public function storePicture(UploadedFile $pictureFile, $user)
    {
        $bucket = $this->setBucket();
        $id = new ObjectID();
        $config = new Config();
        $config->set('mimetype', $pictureFile->getMimeType());
        $config->set('type', 'image');
        $record = $this->adapter->write(date('d') . '/picture/' . ($id->__toString()) . '.' . str_after($pictureFile->getMimeType(), '/'),
            file_get_contents($pictureFile), $config);
        $picture = (new Media($id, $pictureFile->getMimeType()))
            ->setName($pictureFile->getClientOriginalName())
            ->setSize($pictureFile->getSize())
            ->setPath($record['path'])
            ->setCreatedAt(Carbon::now()->toDateTimeString())
            ->setUser($user)
            ->setBucket($bucket);
        $this->manager->insertPicture($picture);
        return $picture;
    }

}