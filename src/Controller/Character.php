<?php

namespace MyWoWHub\Controller;

use Silex\Application;

class Character
{
    public function show(Application $app, $realm, $character)
    {
        $app['curl']->get(API_URL .'/wow/character/'. rawurlencode($realm) .'/'. rawurlencode($character), array(
            'apikey' => CLIENT_ID,
            'locale' => LOCALE,
            'fields' => 'items,guild'
        ));

        $characterInfo = json_decode(json_encode($app['curl']->response), true);
        $characterInfo['cover'] = preg_replace('/(avatar)/', 'main', $characterInfo['thumbnail']);

        return $app['twig']->render('Character/show.html.twig', array(
            'character' => $characterInfo
        ));
    }
}
