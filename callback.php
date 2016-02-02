<?php

require_once './conf.php';

$options[CURLOPT_CUSTOMREQUEST] = 'POST';
$options[CURLOPT_URL] = TOKEN_URL .'?'. http_build_query(array(
    'redirect_uri'  => REDIRECT,
    'client_id'     => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'scope'         => SCOPE,
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
));

$auth = curl_init();
curl_setopt_array($auth, $options);
$result = curl_exec($auth);
curl_close($auth);

$token = json_decode($result);

$options[CURLOPT_CUSTOMREQUEST] = 'GET';
$options[CURLOPT_URL] = API_URL .'/account/user?'. http_build_query(array(
    'access_token' => $token->access_token
));

$user = curl_init();
curl_setopt_array($user, $options);
$result = curl_exec($user);
curl_close($user);

$battleTag = json_decode($result);

header('Location: '. HUB .'/register.php?'. http_build_query(array(
    'token' => $token->access_token,
    'btag'  => $battleTag->battletag
)));
