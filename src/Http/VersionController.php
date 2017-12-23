<?php

namespace Rahii\MinioLaravel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
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
        $versionUrl = $storageClass->getManager()->versionedPictureExists($request->get('id'), $version);
        if (!$versionUrl) {
            $org_picture = $storageClass->getManager()->getPicturebyId($request->get('id'));
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
}