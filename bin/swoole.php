<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
define("APP_DEBUG", $_ENV['APP_DEBUG']);

$vega = Vega::new();
$http = new Swoole\Http\Server('0.0.0.0', 9501);
$init = function () {
    App\Container\DB::enableCoroutine();
    App\Container\RDS::enableCoroutine();
};
$http->on('Request', $vega->handler($init));
$http->set([
    'enable_coroutine' => true,
    'worker_num' => 4,
]);
Logger::instance()->info('Start swoole server');
$http->start();
