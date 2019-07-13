<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/12
 * Time: 18:14
 */

namespace App\Http\Middleware\Mongo;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * Class DynCodeServiceMiddleware
 * @package App\Http\Middleware\Mongo
 * @Bean()
 */
class DynCodeServiceMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $host = $request->getUri()->getHost();
        $ipStr = env('ALLOW_HOST_FOR_API_WHITE_LIST', 'localhost');
        $allowIpArr = explode(',', $ipStr);
        if (!in_array($host, $allowIpArr)) {
            $response = Context::mustGet()->getResponse();
            $result = json_encode([
                'status' => -1,
                'msg'    => 'forbidden',
                'data'=>$host,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            return $response->withStatus(403)->withContent($result);
        }

        return $handler->handle($request);
    }
}
