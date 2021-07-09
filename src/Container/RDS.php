<?php

namespace App\Container;

use Mix\Redis\Redis;

class RDS
{

    /**
     * @var Redis
     */
    static private $instance;

    /**
     * @return Redis
     */
    public static function instance(): Redis
    {
        if (!isset(self::$instance)) {
            $host = $_ENV['REDIS_HOST'];
            $port = $_ENV['REDIS_PORT'];
            $password = $_ENV['REDIS_PASSWORD'];
            $database = $_ENV['REDIS_DATABASE'];
            self::$instance = new Redis($host, $port, $password, $database);
        }
        return self::$instance;
    }

    public static function initCoroutine()
    {
        $maxOpen = 30;        // 最大开启连接数
        $maxIdle = 10;        // 最大闲置连接数
        $maxLifetime = 3600;  // 连接的最长生命周期
        $waitTimeout = 0.0;   // 从池获取连接等待的时间, 0为一直等待
        self::instance()->startPool($maxOpen, $maxIdle, $maxLifetime, $waitTimeout);
    }

}
