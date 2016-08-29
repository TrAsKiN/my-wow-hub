<?php

$app->get('/', "MyWoWHub\Controller\Index::home")
    ->requireHttp()
    ->bind('home');

$app->get('/logout', "MyWoWHub\Controller\Index::logout")
    ->requireHttp()
    ->bind('logout');

$app->get('/callback', "MyWoWHub\Controller\Callback::register")
    ->requireHttps()
    ->bind('callback');
