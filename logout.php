<?php

unset($_COOKIE['access_token']);
setcookie('access_token', null, time()-1);
unset($_COOKIE['battle_tag']);
setcookie('battle_tag', null, time()-1);

header('Location: ./');
