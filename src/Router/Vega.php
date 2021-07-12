<?php

namespace App\Router;

use App\Container\Logger;
use App\Controller\Auth;
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

        // 500
        $vega->use(function (Context $ctx) {
            try {
                $ctx->next();
            } catch (\Throwable $ex) {
                Logger::instance()->error(sprintf('%s %s:%d', $ex->getMessage(), $ex->getFile(), $ex->getLine()));
                $ctx->string(500, 'Internal Server Error');
                $ctx->abort();
            }
        });

        // debug
        if (APP_DEBUG) {
            $vega->use(function (Context $ctx) {
                Logger::instance()->debug(sprintf(
                    '%s|%s|%s|%s',
                    $ctx->request->getMethod(),
                    $ctx->uri(),
                    $ctx->response->getStatusCode(),
                    $ctx->remoteIP()
                ));
                $ctx->next();
            });
        }

        // routes
        $vega->handleCall('/hello', [new Hello(), 'index'])->methods('GET');
        $vega->handleCall('/users/{id}', [new Users(), 'index'])->methods('GET');
        $vega->handleCall('/auth', [new Auth(), 'index'])->methods('GET');

        return $vega;
    }

}
