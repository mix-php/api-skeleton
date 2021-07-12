<?php

use App\Controller\Auth;
use App\Controller\Hello;
use App\Controller\Users;

return function (Mix\Vega\Engine $vega) {
    $vega->handleCall('/hello', [new Hello(), 'index'])->methods('GET');
    $vega->handleCall('/users/{id}', [new Users(), 'index'])->methods('GET');
    $vega->handleCall('/auth', [new Auth(), 'index'])->methods('GET');
};
