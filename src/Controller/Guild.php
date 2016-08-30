<?php

namespace MyWoWHub\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Guild
{
    public function show(Request $request, Application $app, $realm, $guild) {
        $app['curl']->get(API_URL .'/wow/guild/'. rawurlencode($realm) .'/'. rawurlencode($guild), array(
            'apikey' => CLIENT_ID,
            'locale' => LOCALE,
            'fields' => 'members'
        ));

        $guildInfo = json_decode(json_encode($app['curl']->response), true);
        foreach ($guildInfo['members'] as $key => $value) {
            $guildInfo['members'][$key]['character']['cover'] = preg_replace('/(avatar)/', 'profilemain', $value['character']['thumbnail']);
        }

        $name = [];
        $level = [];
        $rank = [];
        foreach ($guildInfo['members'] as $key => $row) {
            $name[$key]     = $row['character']['name'];
            $level[$key]    = $row['character']['level'];
            $rank[$key]     = $row['rank'];
        }

        array_multisort($rank, SORT_ASC, $level, SORT_DESC, $name, SORT_ASC, $guildInfo['members']);

        return $app['twig']->render('Guild/show.html.twig', array(
            'guild' => $guildInfo
        ));
    }
}
