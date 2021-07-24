<?php
require __DIR__ . '/../vendor/autoload.php';

/**
 * PHP-FPM 模式专用
 */

use App\Vega;
use Dotenv\Dotenv;

Dotenv::createUnsafeImmutable(__DIR__ . '/../', '.env')->load();
define("APP_DEBUG", env('APP_DEBUG'));

App\Error::register();

Vega::new()->run();
