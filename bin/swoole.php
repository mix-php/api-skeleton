<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Router\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$vega = Vega::new();

$http = new Swoole\Http\Server('0.0.0.0', 9501);
$http->on('Request', $vega->handler());
Logger::instance()->info('Start swoole server');
$http->start();
