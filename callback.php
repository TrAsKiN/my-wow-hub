<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/conf.php';

use \Curl\Curl;

$curl->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
$curl->setURL(TOKEN_URL, array(
    'redirect_uri'  => REDIRECT,
    'client_id'     => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'scope'         => SCOPE,
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
));

$curl->exec();
$token = json_decode($curl->response->data);

$curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
$curl->setURL(API_URL .'/account/user', array(
    'access_token' => $token->access_token
));

$curl->exec();
$battleTag = json_decode($curl->response->data);

header('Location: '. HUB .'/register.php?'. http_build_query(array(
    'token' => $token->access_token,
    'btag'  => $battleTag->battletag
)));
