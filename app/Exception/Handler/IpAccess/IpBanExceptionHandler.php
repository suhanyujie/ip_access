<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/10
 * Time: 18:36
 */

namespace App\Exception\Handler\IpAccess;


use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;

class IpBanExceptionHandler extends \Exception
{
    public function __construct()
    {
        parent::__construct("", -1);
        $this->handle();
    }

    public function handle(): Response
    {
        // TODO: Implement handle() method.
        $result = [
            'status' => -1,
            'msg'    => "禁止",
        ];
        $json = json_encode($result, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return Context::mustGet()->getResponse()
            ->withStatus(200)
            ->withContentType(ContentType::JSON)
            ->withContent($json);
    }
}
