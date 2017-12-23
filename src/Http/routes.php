<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 12/16/17
 * Time: 3:14 PM
 */

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Rahii\MinioLaravel\Http'], function()
{
    Route::get('picture/getVersion', ['uses' => 'VersionController@getVersionedPicture']);
});