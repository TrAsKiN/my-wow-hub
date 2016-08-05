<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/conf.php';
require __DIR__ . '/config/twig.php';

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

$curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
$curl->setURL(API_URL .'/wow/character/'. rawurlencode($realm) .'/'. rawurlencode($name), array(
    'apikey' => CLIENT_ID,
    'locale' => LOCALE,
    'fields' => 'items,guild'
));
$curl->exec();

$characterInfo = json_decode($curl->response->data, true);
$characterInfo['cover'] = preg_replace('/(avatar)/', 'profilemain', $characterInfo['thumbnail']);

echo $twig->render('characterInfo.html.twig', array(
    'character' => $characterInfo,
    'battleTag' => $_COOKIE['battle_tag'],
    'guilds'    => $_SESSION['guilds'],
    'locale'    => LOCALE
));
