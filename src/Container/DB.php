<?php

namespace App\Container;

use Mix\Database\Database;

class DB
{

    /**
     * @var Database
     */
    static private $instance;

    /**
     * @return Database
     */
    public static function instance(): Database
    {
        if (!isset(self::$instance)) {
            $dsn = $_ENV['DATABASE_DSN'];
            $username = $_ENV['DATABASE_USERNAME'];
            $password = $_ENV['DATABASE_PASSWORD'];
            self::$instance = new Database($dsn, $username, $password);
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
