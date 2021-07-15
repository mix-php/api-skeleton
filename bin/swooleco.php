<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
define("APP_DEBUG", $_ENV['APP_DEBUG']);

Swoole\Coroutine\run(function () {
    $vega = Vega::new();
    $server = new Swoole\Coroutine\Http\Server('0.0.0.0', 9502, false, false);
    $init = function () {
        App\Container\DB::enableCoroutine();
        App\Container\RDS::enableCoroutine();
    };
    $server->handle('/', $vega->handler($init));

    foreach ([SIGHUP, SIGINT, SIGTERM] as $signal) {
        Swoole\Process::signal($signal, function () use ($server) {
            Logger::instance()->info('Shutdown swoole coroutine server');
            $server->shutdown();
        });
    }

    Logger::instance()->info('Start swoole coroutine server');
    $server->start();
});
