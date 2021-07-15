<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Router\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
define("APP_DEBUG", $_ENV['APP_DEBUG']);

$vega = Vega::new();
$http = new Swoole\Http\Server('0.0.0.0', 9501);
$http->on('Request', $vega->handler());
$http->set([
    'worker_num' => 4,
]);
Logger::instance()->info('Start swoole server');
$http->start();
