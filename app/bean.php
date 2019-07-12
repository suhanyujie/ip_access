<?php

use App\Common\DbSelector;
use Swoft\Db\Database;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Redis\RedisDb;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\WebSocket\Server\WebSocketServer;

return [
    'logger'         => [
        'flushRequest' => true,
        'enable'       => true,
        'json'         => true,
    ],
    'httpServer'     => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        'on'       => [
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 3,
            'task_enable_coroutine' => true
        ]
    ],
    'httpDispatcher' => [
        // Add global http middleware
        'middlewares' => [
            \App\Http\Middleware\ControllerMiddleware::class,
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
        ],
    ],
    'db' => [
        'class'    => Database::class,
        'dsn'      => env('DB1_DSN'),
        'username' => env('DB1_USER'),
        'password' => env('DB1_PWD'),
        'prefix'   => env('DB1_PREFIX'),
//        'dbSelector' => bean(DbSelector::class)
    ],
    'db.pool'       => [
        'class'    => Pool::class,
        'database' => bean('db')
    ],
    'migrationManager' => [
        'migrationPath' => '@app/Migration',
    ],
    'redis'          => [
        'class'    => RedisDb::class,
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
    ],
    'user'           => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'      => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
    'rpcServer'      => [
        'class' => ServiceServer::class,
    ],
    'wsServer'       => [
        'class'   => WebSocketServer::class,
        'on'      => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
    'cliRouter'      => [
        // 'disabledGroups' => ['demo', 'test'],
    ]
];
