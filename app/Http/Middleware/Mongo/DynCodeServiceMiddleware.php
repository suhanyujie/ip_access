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
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;

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
        if ($host) {

        }

        $response = Context::mustGet()->getResponse();
        return $response->withContent($host);
    }
}
