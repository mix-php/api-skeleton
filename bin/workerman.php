<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Router\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$vega = Vega::new();

$http = new Workerman\Worker("http://0.0.0.0:2345");
$http->onMessage = $vega->handler();
$http->count = 8;
Logger::instance()->info('Start workerman server');
Workerman\Worker::runAll();
