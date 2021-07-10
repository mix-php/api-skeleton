<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Container\Logger;
use App\Router\Vega;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
define("APP_DEBUG", $_ENV['APP_DEBUG']);

$vega = Vega::new();
$http = new Workerman\Worker("http://0.0.0.0:2345");
$http->onMessage = $vega->handler();
$http->count = 4;
Logger::instance()->info('Start workerman server');
Workerman\Worker::runAll();
