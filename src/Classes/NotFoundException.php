<?php
/**
 * Created by PhpStorm.
 * User: mahshid
 * Date: 12/23/17
 * Time: 4:19 PM
 */

namespace Rahii\MinioLaravel\Classes;

use Throwable;

class NotFoundException  extends \RuntimeException
{
    protected $instance;

    function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}



