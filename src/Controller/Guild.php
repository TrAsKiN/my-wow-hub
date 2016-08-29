<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/init.php';
require __DIR__ . '/config/twig.php';

if (!empty($_GET['name']) || !empty($_GET['realm'])) {
    $guildRealm = $_GET['realm'];
    $guildName  = $_GET['name'];
} else {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('guildError.html.twig', array(
        'battleTag' => $_COOKIE['battle_tag']
    ));
    exit;
}

$curl->get(API_URL .'/wow/guild/'. rawurlencode($guildRealm) .'/'. rawurlencode($guildName), array(
    'apikey' => CLIENT_ID,
    'locale' => LOCALE,
    'fields' => 'members'
));

$guildInfo = json_decode(json_encode($curl->response), true);
foreach ($guildInfo['members'] as $key => $value) {
    $guildInfo['members'][$key]['character']['cover'] = preg_replace('/(avatar)/', 'profilemain', $value['character']['thumbnail']);
}

$name = [];
$level = [];
$rank = [];
foreach ($guildInfo['members'] as $key => $row) {
    $name[$key]     = $row['character']['name'];
    $level[$key]    = $row['character']['level'];
    $rank[$key]     = $row['rank'];
}

array_multisort($rank, SORT_ASC, $level, SORT_DESC, $name, SORT_ASC, $guildInfo['members']);

echo $twig->render('guild.html.twig', array(
    'members'   => $guildInfo['members'],
    'guild'     => $guildInfo,
    'battleTag' => $_COOKIE['battle_tag'],
    'guilds'    => $_SESSION['guilds']
));
