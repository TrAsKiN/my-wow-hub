<?php

namespace MyWoWHub\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class Callback
{
    public function register(Request $request, Application $app) {
        $app['curl']->post(TOKEN_URL, array(
            'redirect_uri'  => REDIRECT,
            'client_id'     => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'scope'         => SCOPE,
            'grant_type'    => 'authorization_code',
            'code'          => $request->query->get('code')
        ), true);
        $token = $app['curl']->response;

        $app['curl']->get(API_URL .'/account/user', array(
            'access_token' => $token->access_token
        ), true);
        $battleTag = $app['curl']->response;

        $response = $app->redirect($app['url_generator']->generate('home'));
        $response->headers->setCookie(new Cookie('access_token', $token->access_token, new \Datetime('+30 days')));
        $response->headers->setCookie(new Cookie('battle_tag', $battleTag->battletag, new \Datetime('+30 days')));

        return $response;
    }
}
