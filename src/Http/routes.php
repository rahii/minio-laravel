<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 12/16/17
 * Time: 3:14 PM
 */

use Illuminate\Support\Facades\Route;

Route::get('picture/get/{id}/{version}', 'Rahii\MinioLaravel\Http\VersionController@getVersionedPicure');