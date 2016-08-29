<?php

namespace MyWoWHub\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Index
{
    public function home(Request $request, Application $app) {
        if ($request->cookies->has('access_token')) {
            $app['curl']->get(API_URL .'/wow/user/characters', array(
                'access_token'  => $request->cookies->get('access_token'),
                'locale'        => LOCALE
            ), true);

            $characters = json_decode(json_encode($app['curl']->response), true);
            $name = [];
            $level = [];
            $guilds = [];
            foreach ($characters['characters'] as $key => $value) {
                $characters['characters'][$key]['cover'] = preg_replace('/(avatar)/', 'profilemain', $value['thumbnail']);
                $name[$key]     = $value['name'];
                $level[$key]    = $value['level'];
                if (isset($value['guild'])) {
                    if (!array_key_exists($value['guild'], $guilds)) {
                        $guilds[$value['guild']] = $value['guildRealm'];
                    }
                }
            }

            $app['session']->set('guilds', $guilds);

            array_multisort($level, SORT_DESC, $name, SORT_ASC, $characters['characters']);

            return $app['twig']->render('listCharacters.html.twig', array(
                'characters'    => $characters['characters']
            ));
        } else {
            return $app['twig']->render('index.html.twig', array(
                'url' => AUTHORIZE_URL .'?'. http_build_query(array(
                    'redirect_uri'  => REDIRECT,
                    'client_id'     => CLIENT_ID,
                    'scope'         => SCOPE,
                    'response_type' => 'code',
                    'locale'        => LOCALE
                ))
            ));
        }
    }

    public function logout(Request $request, Application $app) {
        $request->cookies->remove('access_token');
        $request->cookies->remove('battle_tag');

        return $app->redirect($app['url_generator']->generate('home'));
    }
}
