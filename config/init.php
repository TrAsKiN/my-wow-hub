<?php

require __DIR__ . '/conf.php';

session_start();

use \Curl\Curl;

$curl = new Curl();
$curl->setOpt(CURLOPT_CONNECTTIMEOUT,   10);
$curl->setOpt(CURLOPT_RETURNTRANSFER,   true);
$curl->setOpt(CURLOPT_HEADER,           false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER,   false);

$url = AUTHORIZE_URL .'?'. http_build_query(array(
    'redirect_uri'  => REDIRECT,
    'client_id'     => CLIENT_ID,
    'scope'         => SCOPE,
    'response_type' => 'code',
    'locale'        => LOCALE
));
