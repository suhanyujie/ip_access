<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/10
 * Time: 12:29
 */

namespace App\Http\Controller;

use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;

class BaseController
{
    public function __construct()
    {

    }

    public function json($data=[], $encodeType='')
    {
        if (!empty($encodeType)) {
            $encodeOption = $encodeType;
        } else {
            $encodeOption = JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE;
        }

        return Context::mustGet()
            ->getResponse()
            ->withContentType(ContentType::JSON)
            ->withContent(json_encode($data, $encodeOption));
    }

    public function html($html)
    {
        return Context::mustGet()
            ->getResponse()
            ->withContentType(ContentType::HTML)
            ->withContent($html);
    }
}
