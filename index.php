<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/conf.php';
require __DIR__ . '/config/twig.php';

$authorized = isset($_COOKIE['access_token']);

if ($authorized) {
    $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
    $curl->get(API_URL .'/wow/user/characters', array(
        'access_token'  => $_COOKIE['access_token'],
        'locale'        => LOCALE
    )));
    $curl->exec();

    $characters = json_decode($curl->response->data, true);
    $name = [];
    $level = [];
    $guilds = [];
    foreach ($characters['characters'] as $key => $value) {
        $characters['characters'][$key]['cover'] = preg_replace('/(avatar)/', 'profilemain', $value['thumbnail']);
        $name[$key]     = $value['name'];
        $level[$key]    = $value['level'];
        if (!array_key_exists($value['guild'], $guilds)){
            $guilds[$value['guild']] = $value['guildRealm'];
        }
    }

    $_SESSION['guilds'] = $guilds;

    array_multisort($level, SORT_DESC, $name, SORT_ASC, $characters['characters']);

    echo $twig->render('listCharacters.html.twig', array(
        'characters'    => $characters['characters'],
        'battleTag'     => $_COOKIE['battle_tag'],
        'guilds'        => $_SESSION['guilds']
    ));
} else {
    echo $twig->render('index.html.twig', array(
        'url' => $url
    ));
}
