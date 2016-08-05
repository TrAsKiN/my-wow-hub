<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/conf.php';

use \Curl\Curl;

$options[CURLOPT_CUSTOMREQUEST] = 'POST';

$curl = new Curl();
$curl->get(TOKEN_URL .'?'. http_build_query(array(
    'redirect_uri'  => REDIRECT,
    'client_id'     => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'scope'         => SCOPE,
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
)), $options);
$result = $curl->response;
$curl->close();

$token = json_decode($result);

$options[CURLOPT_CUSTOMREQUEST] = 'GET';

$curl = new Curl();
$curl->get( API_URL .'/account/user?'. http_build_query(array(
    'access_token' => $token->access_token
)), $options);
$result = $curl->response;
$curl->close();

$battleTag = json_decode($result);

header('Location: '. HUB .'/register.php?'. http_build_query(array(
    'token' => $token->access_token,
    'btag'  => $battleTag->battletag
)));
