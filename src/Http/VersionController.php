<?php

namespace Rahii\MinioLaravel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Rahii\MinioLaravel\Classes\NotFoundException;
use Rahii\MinioLaravel\Classes\StorageClass;
use Symfony\Component\HttpFoundation\File\File;

class VersionController extends Controller
{

    /**
     * get a versioned picture upon request
     *
     * @param Request $request
     * @param StorageClass $storageClass
     * @return \Illuminate\Http\JsonResponse
     */

    public function getVersionedPicture(Request $request, StorageClass $storageClass, ImageManager $manager)
    {
        /*TODO: version detection*/
        $version = 'shit';
        $versionUrl = $this->findVersionedPicture($request->get('id'), $version, $storageClass);
        if (!$versionUrl) {
            $org_picture = $this->findOriginalPicture($request->get('id'), $storageClass);
            $image = $manager->make($org_picture->getUri())->resize(100, null, function ($c) {
                $c->aspectRatio();
            });

            $image->setFileInfoFromPath($org_picture->getUri());
            $name = substr($org_picture->getHashId(), -6) . '-' . $version . '.' . ltrim($image->mime(), 'image/');
            /*TODO: save on disk('cdn') */
            if (!file_exists('../../tempics/')) {
                mkdir('../../tempics', 0777);
            }
            $image->save('../../tempics/' . $name);
            /*TODO: version resizing*/
            $picture = $storageClass->storeVersionedPicture(new File('../../tempics/' . $name), $org_picture->getHashId(), $version, $org_picture->getBucket());
            $versionUrl = $picture->getUri();
        }
        return response()->json([
            'status' => 'ok',
            'url' => $versionUrl
        ], '200');

    }

    /**
     * check if a versioned picture exists through minio and mongo
     *
     * @param $id
     * @param $version
     * @param StorageClass $storageClass
     * @return mixed|null
     */
    public function findVersionedPicture($id, $version, StorageClass $storageClass)
    {
        $versionedPicture = $storageClass->getManager()->getPictureByVersion($id, $version);
        if ($versionedPicture) {
            $file_exists = $storageClass->hasFile($versionedPicture->getBucket(), $versionedPicture->getPath());
            if ($file_exists) {
                return $versionedPicture->getUri();
            }
        }
        return null;
    }

    /**
     * chech if original picture exists through minio and mongo
     *
     * @param $id
     * @param StorageClass $storageClass
     * @return \Rahii\MinioLaravel\Classes\Media
     */
    public function findOriginalPicture($id, StorageClass $storageClass)
    {
        $org_picture = $storageClass->getManager()->getPicturebyId($id);
        if (!$org_picture->getOriginalExists()) {
            throw new NotFoundException('image not found');
        }
        $file_exists = $storageClass->hasFile($org_picture->getBucket(), $org_picture->getPath());
        if (!$file_exists) {
            $storageClass->getManager()->setOriginalRemoved($id);
            throw new NotFoundException('image not found');
        }
        return $org_picture;
    }


}