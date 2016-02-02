<?php

setcookie(
    'access_token',
    $_GET['token'],
    time() + 60 * 60 * 24 * 30
);
setcookie(
    'battle_tag',
    $_GET['btag'],
    time() + 60 * 60 * 24 * 30
);

header('Location: ./');
