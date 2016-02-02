<?php

require_once './vendor/autoload.php';
require_once './conf.php';

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
    'debug' => true
));
$twig->addExtension(new Twig_Extension_Debug());

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

$character = curl_init();
curl_setopt_array($character, $options);
$result = curl_exec($character);
curl_close($character);

$characterInfo = json_decode($result, true);
$characterInfo['cover'] = preg_replace('/(avatar)/', 'profilemain', $characterInfo['thumbnail']);

echo $twig->render('characterInfo.html.twig', array(
    'character' => $characterInfo,
    'battleTag' => $_COOKIE['battle_tag'],
    'guilds'    => $_SESSION['guilds']
));
