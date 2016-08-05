<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/conf.php';
require __DIR__ . '/config/twig.php';

use \Curl\Curl;

if (!empty($_GET['realm']) && !empty($_GET['name'])) {
    $realm  = $_GET['realm'];
    $name   = $_GET['name'];
} else {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('characterError.html.twig', array(
        'battleTag' => $_COOKIE['battle_tag']
    ));
    exit;
}

$options[CURLOPT_CUSTOMREQUEST] = 'GET';
$options[CURLOPT_URL] = API_URL .'/wow/character/'. rawurlencode($realm) .'/'. rawurlencode($name) .'?'. http_build_query(array(
    'apikey' => CLIENT_ID,
    'locale' => LOCALE,
    'fields' => 'items,guild'
));

$curl = new Curl();
$curl->get($options);
$result = $curl->response;
$curl->close();

$characterInfo = json_decode($result, true);
$characterInfo['cover'] = preg_replace('/(avatar)/', 'profilemain', $characterInfo['thumbnail']);

echo $twig->render('characterInfo.html.twig', array(
    'character' => $characterInfo,
    'battleTag' => $_COOKIE['battle_tag'],
    'guilds'    => $_SESSION['guilds'],
    'locale'    => LOCALE
));
