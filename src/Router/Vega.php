<?php

namespace App\Router;

use App\Controller\Hello;
use App\Controller\Users;
use Mix\Vega\Context;
use Mix\Vega\Engine;

class Vega
{

    /**
     * @return Engine
     */
    public static function new(): Engine
    {
        $vega = new Engine();

        $vega->use(function (Context $ctx) {
            try {
                $ctx->next();
            } catch (\Throwable $ex) {
                $ctx->string(500, 'Internal Server Error');
                $ctx->abort();
            }
        });

        $vega->handleC('/hello', [new Hello(), 'index'])->methods('GET');
        $vega->handleC('/users/{id}', [new Users(), 'index'])->methods('GET');

        return $vega;
    }

}
