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
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpFoundation\File\File;


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

    /**
     * set the s3 adapter bucket
     *
     * @return string
     */
    public function setBucket()
    {
        $bucket = date('Y') . '-' . date('m');
        if (!$this->adapter->getClient()->doesBucketExist($bucket)) {
            $this->adapter->getClient()->createBucket(["Bucket" => $bucket]);
            $this->adapter->getClient()->putBucketPolicy([
                'Bucket' => $bucket,
                'Policy' => '{"Version":"2012-10-17",
           "Statement":[{"Action":["s3:GetObject"],
           "Effect":"Allow","Principal":{"AWS":["*"]},
           "Resource":["arn:aws:s3:::' . $bucket . '/*"],"Sid":""}]}',
            ]);
        }
        $this->adapter->setBucket($bucket);
        return $bucket;

    }

    /**
     * store video into minio storage
     *
     * @param File $videoFile
     * @param $user
     * @return Media
     */
    public function storeVideo(File $videoFile, $user)
    {
        $bucket = $this->setBucket();
        $id = new ObjectID();
        $config = new Config();
        $config->set('mimetype', $videoFile->getMimeType());
        $config->set('type', 'video');
        $record = $this->adapter->write(date('d') . '/video/' . ($id->__toString()) . '.' . str_after($videoFile->getMimeType(), '/'),
            file_get_contents($videoFile), $config);
        $video = (new Media($id, $videoFile->getMimeType()))
            ->setName($videoFile->getFilename())
            ->setSize($videoFile->getSize())
            ->setPath($record['path'])
            ->setCreatedAt(Carbon::now()->toDateTimeString())
            ->setUser($user)
            ->setUri(config('minio.minioStorage')['domain'] . '/' . $bucket . '/' . $record['path'])
            ->setBucket($bucket);
        $this->manager->insertVideo($video);
        return $video;
    }

    /**
     * store picture into minio storage
     *
     * @param File $pictureFile
     * @param $user
     * @return Media
     */
    public function storePicture(File $pictureFile, $user)
    {
        $bucket = $this->setBucket();
        $id = new ObjectID();
        $config = new Config();
        $config->set('mimetype', $pictureFile->getMimeType());
        $config->set('type', 'image');
        $record = $this->adapter->write(date('d') . '/picture/' . ($id->__toString()) . '.' . str_after($pictureFile->getMimeType(), '/'),
            file_get_contents($pictureFile), $config);
        $picture = (new Media($id, $pictureFile->getMimeType()))
            ->setName($pictureFile->getFilename())
            ->setSize($pictureFile->getSize())
            ->setPath($record['path'])
            ->setCreatedAt(Carbon::now()->toDateTimeString())
            ->setUser($user)
            ->setUri(config('minio.minioStorage')['domain'] . '/' . $bucket . '/' . $record['path'])
            ->setBucket($bucket);
        $this->manager->insertPicture($picture);
        return $picture;
    }

}