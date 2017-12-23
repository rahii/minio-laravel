<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 11/18/17
 * Time: 5:11 PM
 */

namespace Rahii\MinioLaravel\Classes;


use JsonSerializable;

class Media implements JsonSerializable
{
    protected $name;
    protected $mimetype;
    protected $size;
    protected $height, $width;
    protected $path;
    protected $hashId;
    protected $created_at;
    protected $bucket;
    protected $user_id;
    protected $uri;
    protected $originalName;
    protected $expiration_date;

    public function __construct($hashId, $mimetype)
    {
        $this->hashId = $hashId;
        $this->mimetype = $mimetype;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $originalName
     * @return Media
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }


    /**
     * @return mixed
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHashId()
    {
        return $this->hashId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @param mixed $bucket
     * @return Media
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     * @return Media
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param mixed $expiration_date
     */
    public function setExpirationDate($expiration_date)
    {
        $this->expiration_date = $expiration_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * @param mixed $height
     * @return Media
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $width
     * @return Media
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'hashId' => $this->getHashId(),
            'name' => $this->getName(),
            'original name' => $this->getOriginalName(),
            'user' => $this->getUserId(),
            'mimetype' => $this->getMimetype(),
            'size' => $this->getSize(),
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'path' => $this->getPath(),
            'uri' => $this->getUri(),
            'created_at' => $this->getCreatedAt(),
        ];
    }

    /**
     * return the original media as an array
     *
     * @return array
     */
    public function __toArray()
    {
        return [
            '_id' => $this->getHashId(),
            'originalName' => $this->getOriginalName(),
            'user' => $this->getUserId(),
            'bucket' => $this->getBucket(),
            'created_at' => $this->getCreatedAt(),
            'original' => [
                'name' => $this->getName(),
                'mimetype' => $this->getMimetype(),
                'size' => $this->getSize(),
                'height' => $this->getHeight(),
                'width' => $this->getWidth(),
                'path' => $this->getPath(),
                'uri' => $this->getUri(),
                'created_at' => $this->getCreatedAt(),
            ]
        ];
    }


    /**
     * return a version of media as array
     *
     * @return array
     */
    public function version_toArray()
    {
        return [
            'name' => $this->getName(),
            'mimetype' => $this->getMimetype(),
            'size' => $this->getSize(),
            'height' => $this->getHeight(),
            'width' =>$this->getWidth(),
            'path' => $this->getPath(),
            'uri' => $this->getUri(),
            'created_at' => $this->getCreatedAt(),
            'expiratione_date' => $this->getExpirationDate()
        ];
    }



}