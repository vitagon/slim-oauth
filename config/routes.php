<?php

declare(strict_types=1);

use Slim\App;

return static function (App $app): void {
    $app->get('/', \App\Http\Action\HomeAction::class);
    $app->get('/profile', \App\Http\Action\ProfileAction::class);
    $app->get('/authorize', \App\Http\Action\AuthorizationAction::class);
};
