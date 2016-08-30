<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ErrorHandler::register();
ExceptionHandler::register();

$curl = new Curl\Curl();
$curl->setOpt(CURLOPT_CONNECTTIMEOUT,   10);
$curl->setOpt(CURLOPT_RETURNTRANSFER,   true);
$curl->setOpt(CURLOPT_HEADER,           false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER,   false);
$app['curl'] = $curl;

$app['debug'] = true;
$app['locale'] = LOCALE;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(Cocur\Slugify\Slugify::create()));
    $twig->addExtension(new Twig_Extension_Debug());
    return $twig;
});
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\VarDumperServiceProvider());
