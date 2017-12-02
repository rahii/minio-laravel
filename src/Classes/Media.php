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
    protected $path;
    protected $hashId;
    protected $created_at;
    protected $bucket;
    protected $user;
    protected $uri;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
            'user' => $this->getUser(),
            'mimetype' => $this->getMimetype(),
            'size' => $this->getSize(),
            'path' => $this->getPath(),
            'uri' => $this->getUri(),
            'created_at' => $this->getCreatedAt(),
        ];
    }

    public function __toArray()
    {
        return [
            '_id' => $this->getHashId(),
            'name' => $this->getName(),
            'user' => $this->getUser(),
            "mimetype" => $this->getMimetype(),
            "size" => $this->getSize(),
            "path" => $this->getPath(),
            "uri" => $this->getUri(),
            "bucket" => $this->getBucket(),
            "created_at" => $this->getCreatedAt(),
        ];
    }

}