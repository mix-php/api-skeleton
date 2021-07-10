<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Container\DB;
use App\Container\RDS;
use App\Router\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
define("APP_DEBUG", $_ENV['APP_DEBUG']);

Swoole\Coroutine\run(function () {
    DB::initCoroutine();
    RDS::initCoroutine();

    $vega = Vega::new();
    $server = new Swoole\Coroutine\Http\Server('127.0.0.1', 9502, false, false);
    $server->handle('/', $vega->handler());

    foreach ([SIGHUP, SIGINT, SIGTERM] as $signal) {
        Swoole\Process::signal($signal, function () use ($server) {
            Logger::instance()->info('Shutdown swoole coroutine server');
            $server->shutdown();
        });
    }

    Logger::instance()->info('Start swoole coroutine server');
    $server->start();
});
