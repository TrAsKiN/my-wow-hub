<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/init.php';

$curl->post(TOKEN_URL, array(
    'redirect_uri'  => REDIRECT,
    'client_id'     => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'scope'         => SCOPE,
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
));
$token = $curl->response;

$curl->get(API_URL .'/account/user', array(
    'access_token' => $token->access_token
));
$battleTag = $curl->response;

header('Location: '. HUB .'/register.php?'. http_build_query(array(
    'token' => $token->access_token,
    'btag'  => $battleTag->battletag
)));
