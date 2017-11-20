<?php

$app->get('/', "MyWoWHub\Controller\Index::home")
    ->bind('home');

$app->get('/logout', "MyWoWHub\Controller\Index::logout")
    ->bind('logout');

$app->get('/callback', "MyWoWHub\Controller\Callback::register")
    ->bind('callback');

$app->get('/{realm}/guild/{guild}', "MyWoWHub\Controller\Guild::show")
    ->bind('guild');

$app->get('/{realm}/character/{character}', "MyWoWHub\Controller\Character::show")
    ->bind('character');
