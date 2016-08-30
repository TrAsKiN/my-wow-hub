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

$app->get('/{realm}/guild/{guild}', "MyWoWHub\Controller\Guild::show")
    ->requireHttp()
    ->bind('guild');

$app->get('/{realm}/character/{character}', "MyWoWHub\Controller\Character::show")
    ->requireHttp()
    ->bind('character');
