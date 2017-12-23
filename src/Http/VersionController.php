<?php

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 12/16/17
 * Time: 3:26 PM
 */
namespace Rahii\MinioLaravel\Http;

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
     */
    public function getVersionedPicture(Request $request, StorageClass $storageClass){
        /*TODO: version detection*/
        $version = 'test';
        $org_picture = $storageClass->getManager()->getPicturebyId($request->get('id'));
        $manager = new ImageManager();
        $image = $manager->make($org_picture->getUri())->resize(400,null, function ($c) {
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
        $storageClass->storeVersionedPicture(new File('../../tempics/' . $name), $org_picture->getHashId(), $version, $org_picture->getBucket());
    }
}